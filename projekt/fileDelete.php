<?php
require_once "lib/common.php";

$id = $_POST["id"] ?? ($_GET["id"] ?? "");

if ($id !== "") {
	unset($db["content"][$id]);

	redirect("./fileList.php");
}
?>