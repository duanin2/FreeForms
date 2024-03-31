<?php
require_once "lib/common.php";

$contentDB = load_db($contentdb_location);

$id = $_POST["id"] ?? ($_GET["id"] ?? "");

if ($id !== "") {
	foreach ($contentDB as $key => $content) {
		if ($content["id"] === $id) {
			unset($contentDB[$key]);
			break;
		}
	}

	save_db($contentdb_location, $contentDB);

	header("Location: /fileList.php");
}
?>