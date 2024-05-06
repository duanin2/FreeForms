<?php
require_once "lib/common.php";

$id = $_POST["id"] ?? ($_GET["id"] ?? "");

if ($id !== "") {
	foreach ($db["content"] as $key => $content) {
		if ($content["id"] === $id) {
			unset($db["content"][$key]);
			break;
		}
	}

	redirect("./fileList.php");
}
?>