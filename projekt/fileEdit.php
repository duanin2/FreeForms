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
	<body onload="redraw()">
<?php 
	if ($isLoggedIn) {
		htmlHeader(["login", "register"]);
	} else {
		htmlHeader(["forms", "userdata", "logout"]);
	}
?>
		<main>
			<?php
			$contentDB = load_db($contentdb_location);

			$contentId = $_POST["id"] ?? ($_GET["id"] ?? "");
			if (isset($_POST["save"])) {
				if ($contentId === "") {
					$curTime = time();
					$contentId = md5(($_SESSION["username"] ?? "") . $curTime);

					array_push($contentDB, array(
						"name" => $_POST["name"],
						"id" => $contentId,
						"creationDate" => $curTime,
						"username" => $_SESSION["username"],
						"sharedWith" => ""
					));
				} else {
					foreach ($contentDB as $key => $content) {
						if ($content["id"] === $contentId) {
							$contentDB[$key] = array(
								"name" => $_POST["name"],
								"id" => $contentId,
								"creationDate" => $contentDB[$key]["creationDate"],
								"username" => $_SESSION["username"],
								"sharedWith" => ""
							);
							break;
						}
					}
				}
				save_db($contentdb_location, $contentDB);

				header("Location: /fileEdit.php?id=$contentId");
			}

			$content = [];
			$contentKey = "";
			foreach ($contentDB as $loopKey => $loopContent) {
				if (($loopContent["id"] ?? "") === $contentId) {
					$content = $loopContent;
					$contentKey = $loopKey;
					break;
				}
			}
			?>

			<form action="#" method="post">
				<table>
					<tr>
						<td><input type="text" name="name" id="name" value="<?php if ($contentId !== "") echo $content["name"]; ?>"></td>
						<td><input type="submit" name="save" value="Uložit"></td>
						<td><input type="button" value="Nová otázka" onclick="newNode()"></td>
						<td><input type="text" name="nodeName" id="selected" hidden></td>
						<td><input type="button" value="Přejmenovat" onclick="renameNode()" id="selectedRename" hidden></td>
						<td><select name="options" id="options" onchange="selectOption()" hidden></select></td>
						<td><input type="text" name="option" id="option" hidden></td>
						<td><input type="button" value="Přidat/Přejmenovat Možnost" id="renameOption" onclick="globalThis.renameOption()" hidden></td>
					</tr>
				</table>
			</form>
			<canvas id="p5jsCanvas"></canvas>
		</main>
<?php htmlFooter(); ?>
	</body>
</html>