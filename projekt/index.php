<?php require_once "lib/common.php"; ?>
<!DOCTYPE html>
<html lang="en">
<?php htmlHead("Hlavní stránka"); ?>
	<body>
<?php 
	if ($isLoggedIn) {
		htmlHeader(["login", "register"]);
	} else {
		htmlHeader(["forms", "userdata", "logout"]);
	}
?>
		<main>
			<h1>Vítejte ve FreeForms</h1>
			<p>FOSS alternativa pro Microsoft Forms a Google Forms.</p>
<?php
	if (!isset($isLoggedIn)) {
		echo htmlTags(3, [array(
			"element" => "p",
			"content" => "Pro použití se přihlašte."
		)]);
	} else {
		echo htmlTags(3, [array(
			"element" => "p",
			"content" => array(
				array(
					"element" => "a"
				)
			)
		)]);
	}
?>
		</main>
<?php htmlFooter(); ?>
	</body>
</html>