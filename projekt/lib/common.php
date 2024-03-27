<?php
ob_start();

function head($title) {
	echo "<head>";
	echo "\t<meta charset=\"UTF-8\">";
	echo "\t<meta name=\"viewport\" content=\"width=device-width, initial-scale=1.0\">";
	echo "\t<link rel=\"stylesheet\" href=\"lib/main.css.php?scheme=frappe\">";
	echo "<link rel=\"icon\" href=\"./img/icon.svg\">";
	echo "\t<title>$title - FreeForms</title>";
	echo "</head>";
}
?>