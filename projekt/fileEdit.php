<?php require_once "lib/common.php"; ?>
<!DOCTYPE html>
<html lang="en">
<?php
htmlHead("Editor formuláře", array(
	array(
		"element" => "script",
		"params" => array(
			"src" => "./lib/sketch.js"
		)
	)
));
?>
	<body>
<?php 
	if ($isLoggedIn) {
		htmlHeader(["login", "register"]);
	} else {
		htmlHeader(["forms", "userdata", "logout"]);
	}
?>
		<main>
			<?php
			$contentId = $_POST["id"] ?? ($_GET["id"] ?? "");
			if (isset($_POST["save"])) {
				if ($contentId === "") {
					$curTime = time();
					$contentId = md5(($_SESSION["username"] ?? "") . $curTime);

					$db["content"][$contentId] = array(
						"name" => $_POST["name"],
						"creationDate" => $curTime,
						"username" => $_SESSION["username"],
						"sharedWith" => ""
					);
				} else {
					foreach ($db["content"] as $key => $content) {
						if ($content["id"] === $contentId) {
							$db["content"][$key] = array(
								"name" => $_POST["name"],
								"id" => $contentId,
								"creationDate" => $db["content"][$key]["creationDate"],
								"username" => $_SESSION["username"],
								"sharedWith" => ""
							);
							break;
						}
					}
				}

				redirect("./fileEdit.php?id=$contentId");
			}

			$content = [];
			$contentKey = "";
			foreach ($db["content"] as $loopKey => $loopContent) {
				if (($loopContent["id"] ?? "") === $contentId) {
					$content = $loopContent;
					$contentKey = $loopKey;
					break;
				}
			}
			?>
			<canvas id="p5jsCanvas"></canvas>
		</main>
<?php htmlFooter(); ?>
	</body>
</html>