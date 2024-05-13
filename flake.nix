{
  description = "PHP web development shell";
  inputs.nixpkgs.url = "github:NixOS/nixpkgs/nixpkgs-unstable";
  inputs.flake-utils.url = "github:numtide/flake-utils";

  outputs = { nixpkgs, flake-utils, ... }: flake-utils.lib.eachDefaultSystem (system: let
    pkgs = nixpkgs.legacyPackages.${system};
    projectRoot = "/home/duanin2/dev/FreeForms/projekt";

    host = "127.0.0.1";
    port = 8080;

    phpConfig = pkgs.writeText "php.ini" '''';

    runTest = pkgs.writeShellScriptBin "runTest" ''
    #!${pkgs.bash}

    php -S ${host}:${builtins.toString port} -t ${projectRoot} &
    phpPID=$!

    echo "Press ENTER to exit..."
    read exit

    kill $phpPID &> /dev/null
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
