{
  description = "PHP web development shell";
  inputs.nixpkgs.url = "github:NixOS/nixpkgs/nixpkgs-unstable";
  inputs.flake-utils.url = "github:numtide/flake-utils";

  outputs = { nixpkgs, flake-utils, ... }: flake-utils.lib.eachDefaultSystem (system: let
    pkgs = nixpkgs.legacyPackages.${system};
    projectRoot = "/home/duanin2/dev/FreeForms/projekt";

    phpCGIAddress = "127.0.0.1";
    phpCGIPort = 9000;

    nginxConfig = pkgs.writeText "nginx-php-dev.conf" ''
    daemon off;
    error_log /dev/stdout info;
    pid /dev/null;
    events {}
    http {
      access_log /dev/stdout;
      server {
        listen   8080;
        root ${projectRoot};
        
        index index.php index.html;
        charset utf-8;

        location / {
          root ${projectRoot};
          try_files $uri $uri/ /index.php?$args;
        }

        location ~ \.php$ {
          root ${projectRoot};
          #NOTE: You should have "cgi.fix_pathinfo = 0;" in php.ini
          fastcgi_split_path_info ^(.+?\.php)(/.*)$;
          if (!-f $document_root$fastcgi_script_name) {
              return 404;
          }

          # Mitigate https://httpoxy.org/ vulnerabilities
          fastcgi_param HTTP_PROXY "";

          fastcgi_pass ${phpCGIAddress}:${toString phpCGIPort};
          fastcgi_index index.php;

          include ${pkgs.nginx}/conf/fastcgi_params;
          include ${pkgs.nginx}/conf/fastcgi.conf;
        }

        location ~* \.css$ {
          types {
            text/css css;
          }
        }

        location ~* \.svg$ {
          types {
            image/svg+xml svg;
          }
        }
      }
    }
    '';
    
    phpConfig = pkgs.writeText "php.ini" ''
    cgi.fix_pathinfo = 0;
    '';

    runTest = pkgs.writeShellScriptBin "runTest" ''
    #!${pkgs.bash}

    php-cgi -c ${phpConfig} -b "${phpCGIAddress}:${toString phpCGIPort}" &
    phpPID=$!

    nginx -c ${nginxConfig} &
    nginxPID=$!

    echo "Press ENTER to exit..."
    read exit

    kill $phpPID &> /dev/null
    kill $nginxPID &> /dev/null
    '';
  in {
    devShells.default = pkgs.mkShell {
      packages = with pkgs; [
        (php.buildEnv {
          
        })
        nginx

        runTest
      ];
    };
  });
}