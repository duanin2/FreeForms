<?php
ob_start();
session_start();

function exceptions_error_handler($severity, $message, $filename, $lineno) {
	throw new ErrorException($message, 0, $severity, $filename, $lineno);
}

set_error_handler('exceptions_error_handler');

$userdb_location = "users.csv";
$contentdb_location = "content.csv";
$content_location = "content/";
$accessCountPath = "accesses";

require_once "db.php";

function htmlTag(string $element, string $content = "", array $params = [], string $endOffsetString = "") : string {
	$paramsString = "";

	foreach ($params as $param => $value) {
		$paramsString .= "$param=\"$value\" ";
	}

	if ($content === "") {
		if (array_search($element, [
			"div",
			"a",
			"p",
			"script"
		]) === false) {
			return "<$element $paramsString/>";
		}
	}
	return "<$element $paramsString>$content$endOffsetString</$element>";
}

function htmlTags(int $offset = 0, array $content = []) {
	$offsetString = "";
	for ($i = 0; $i < $offset; $i++) {
		$offsetString .= "\t";
	}

	$result = "";
	foreach ($content as $tag) {
		if (gettype($tag) === "string") {
			$element = "";
			$params = [];
			$content = $tag;
		} else {
			$element = $tag["element"] ?? "";
			$params = $tag["params"] ?? [];
			$content = $tag["content"] ?? "";
		}

		if ($element === "") {
			if (gettype($content) !== "string") {
				throw new InvalidArgumentException("Elementless \$content must be of type string.");
			}
			$result .= "$offsetString$content\n";
		} else {
			if (gettype($content) === "array") {
				$result .= $offsetString . htmlTag($element, "\n" . htmlTags($offset + 1, $content), $params, $offsetString) . "\n";
			} elseif (gettype($content) === "string") {
				$result .= $offsetString . htmlTag($element, $content, $params) . "\n";
			}
			if ($element === "img") {
				echo htmlspecialchars($result);
			}
		}
	}

	return $result;
}

$defaultScheme = "latte";

try {
	$accesses = (int) file_get_contents($accessCountPath);
} catch(e) {
	$accesses = 0;
	file_put_contents($accessCountPath, $accesses);
}
if (isset($_SESSION["accessed"]) != true) {
	$accesses += 1;
	file_put_contents($accessCountPath, $accesses);
	$_SESSION["accessed"] = true;
}

$isLoggedIn = isset($_SESSION["username"]) && $_SESSION["username"] != null;
if (isset($_SESSION["isAdmin"])) {
	$isAdmin = filter_var($_SESSION["isAdmin"] ?? false, FILTER_VALIDATE_BOOLEAN);
} elseif ($isLoggedIn) {
	foreach (load_db($userdb_location) as $user) {
		if ($user["username"] === $_SESSION["username"]) {
			$isAdmin = filter_var($user["isAdmin"] ?? false, FILTER_VALIDATE_BOOLEAN);
			$_SESSION["isAdmin"] = $user["isAdmin"] ?? "false";
			break;
		}
	}
} else {
	$isAdmin = false;
}

if (isset($_GET["logout"])) {
	unset($_SESSION["username"]);
	unset($_SESSION["scheme"]);
	unset($_SESSION["isAdmin"]);

	header("Location: ./");
}

$buttons = array(
	"register" => array(
		"href" => "./register.php",
		"content" => "Zaregistrovat se"
	),
	"login" => array(
		"href" => "./login.php",
		"content" => "Přihlásit se"
	),
	"logout" => array(
		"href" => "./?logout",
		"content" => "Odhlásit se"
	),
	"userdata" => array(
		"href" => "./userdata.php",
		"content" => $_SESSION["username"] ?? ""
	),
	"forms" => array(
		"href" => "./fileList.php",
		"content" => "Formuláře"
	)
);

function htmlHead(string $title, array $extraTags = []) {
	global
		$userdb_location,
		$defaultScheme,
		$isLoggedIn;

	if (isset($_SESSION["scheme"])) {
		$scheme = $_SESSION["scheme"];
	} elseif ($isLoggedIn) {
		foreach (load_db($userdb_location) as $user) {
			if (($user["username"] ?? "") == $_SESSION["username"]) {
				$scheme = $user["scheme"] ?? $defaultScheme;
				$_SESSION["scheme"] = $scheme;
			}
		}
	} else {
		$scheme = $defaultScheme;
	}

	$finalTags = array(
		"element" => "head",
		"content" => array(
			array(
				"element" => "script",
				"content" => "/*    
				@licstart  The following is the entire license notice for the 
				JavaScript code in this page.

				Copyright (C) 2014  Loic J. Duros

				The JavaScript code in this page is free software: you can
				redistribute it and/or modify it under the terms of the GNU
				General Public License (GNU GPL) as published by the Free Software
				Foundation, either version 3 of the License, or (at your option)
				any later version.  The code is distributed WITHOUT ANY WARRANTY;
				without even the implied warranty of MERCHANTABILITY or FITNESS
				FOR A PARTICULAR PURPOSE.  See the GNU GPL for more details.

				As additional permission under GNU GPL version 3 section 7, you
				may distribute non-source (e.g., minimized or compacted) forms of
				that code without the copy of the GNU GPL normally required by
				section 4, provided you include this license notice and a URL
				through which recipients can access the Corresponding Source.   


				@licend  The above is the entire license notice
				for the JavaScript code in this page.
				*/"
			),
			array(
				"element" => "script",
				"content" => "
				function documentHeight() {
					return Math.max(
						document.documentElement[\"clientHeight\"],
						document.body[\"offsetHeight\"],
						document.documentElement[\"offsetHeight\"]
					);
				}
				function documentWidth() {
					return Math.max(
						document.documentElement[\"clientWidth\"],
						document.body[\"offsetWidth\"],
						document.documentElement[\"offsetWidth\"]
					);
				}
				"
			),
			array(
				"element" => "script",
				"params" => array(
					"src" => "https://cdn.jsdelivr.net/npm/p5@1.9.2/lib/p5.js"
				)
			),
			array(
				"element" => "meta",
				"params" => array(
					"charset" => "UTF-8"
				)
			),
			array(
				"element" => "meta",
				"params" => array(
					"name" => "viewport",
					"content" => "width=device-width, initial-scale=1.0"
				)
			),
			array(
				"element" => "link",
				"params" => array(
					"rel" => "stylesheet",
					"href" => "./lib/main.css.php?scheme=$scheme"
				)
			),
			array(
				"element" => "link",
				"params" => array(
					"rel" => "icon",
					"href" => "./img/icon.svg"
				)
			),
			array(
				"element" => "title",
				"content" => "$title - FreeForms"
			)
		)
	);

	foreach ($extraTags as $extraTag) {
		array_push($finalTags["content"], $extraTag);
	}

	echo htmlTags(1, [$finalTags]);
}
function htmlHeader($excludedButtons = []) {
	global $buttons;
	if (gettype($excludedButtons) === "string") {
		$excludedButtons = [ $excludedButtons ];
	}

	$buttonTags = array();
	foreach ($buttons as $name => $button) {
		if (array_search($name, $excludedButtons) !== false) {
			continue;
		}

		array_push($buttonTags, array(
			"element" => "a",
			"params" => array(
				"href" => $button["href"]
			),
			"content" => $button["content"]
		));
	}

	echo htmlTags(2, [array(
		"element" => "header",
		"content" => array(
			array(
				"element" => "div",
				"params" => array(
					"class" => "logo"
				),
				"content" => array(
					array(
						"element" => "a",
						"params" => array(
							"href" => "./"
						),
						"content" => array(
							array(
								"element" => "img",
								"params" => array(
									"src" => "./img/icon.svg",
									"alt" => "FreeForms logo"
								)
							)
						)
					)
				)
			),
			array(
				"element" => "div",
				"params" => array(
					"class" => "buttons"
				),
				"content" => $buttonTags
			)
		)
	)]);
}
function htmlFooter() {
	global $accesses;

	$visit = $accesses == 1 ? "Navštívil" : ($accesses > 1 && $accesses < 5 ? "Navštívily" : "Navštívilo");
	$people = $accesses == 1 ? "člověk" : ($accesses > 1 && $accesses < 5 ? "lidi" : "lidí");

	$year = date("Y");

	echo htmlTags(2, [array(
		"element" => "footer",
		"content" => array(
			array(
				"element" => "div",
				"params" => array(
					"class" => "copyright"
				),
				"content" => array(
					"&copy; 2024 – $year",
					array(
						"element" => "a",
						"params" => array(
							"href" => "https://github.com/duanin2"
						),
						"content" => "Dušan Till."
					),
					array(
						"element" => "a",
						"params" => array(
							"href" => "https://www.gnu.org/licenses/agpl-3.0.en.html#license-text"
						),
						"content" => array(
							array(
								"element" => "img",
								"params" => array(
									"src" => "https://www.gnu.org/graphics/agplv3-with-text-100x42.png",
									"alt" => "GNU AGPL logo"
								)
							)
						)
					)
				)
			),
			array(
				"element" => "div",
				"params" => array(
					"class" => "accessCount"
				),
				"content" => "$visit nás $accesses $people."
			)
		)
	)]);

	function contentId() {
		return md5(($_SESSION["username"] ?? "") . time());
	}
}
?>