<?php require_once "lib/common.php"; ?>
<!DOCTYPE html>
<html lang="en">
<?php htmlHead("Formuláře"); ?>
	<body>
<?php 
	if ($isLoggedIn) {
		htmlHeader(["forms", "login", "register"]);
	} else {
		htmlHeader(["forms", "userdata", "logout"]);
	}
?>
		<main>
			<?php
				$contentDB = load_db($contentdb_location);
				$username = $_SESSION["username"] ?? "";

				$userContent = [];
				$sharedContent = [];

				if ($isLoggedIn) {
					foreach ($contentDB as $content) {
						if (($content["username"] ?? "") == $username) {
							array_push($userContent, $content);
						} elseif (array_search($username, $content["sharedWith"] ?? [])) {
							array_push($sharedContent, $content);
						}
					}
				} else {
					header("Location: /");
				}

				function formTagsDefine() {
					return array(
						"element" => "table",
						"content" => array(
							array(
								"element" => "tr",
								"content" => array(
									array(
										"element" => "th",
										"content" => "Jméno"
									),
									array(
										"element" => "th",
										"content" => "Datum vytvoření"
									),
									array(
										"element" => "th",
										"content" => "Upravit"
									),
									array(
										"element" => "th",
										"content" => "Smazat"
									)
								)
							)
						)
					);
				}
				function formTags(&$tags, &$fullContent) {
					foreach ($fullContent as $content) {
						array_push($tags["content"], array(
							"element" => "tr",
							"content" => array(
								array(
									"element" => "td",
									"content" => $content["name"]
								),
								array(
									"element" => "td",
									"content" => date("j. M. Y, H:i:s", $content["creationDate"])
								),
								array(
									"element" => "td",
									"content" => array(
										array(
											"element" => "a",
											"params" => array(
												"href" => "/fileEdit.php?id=" . $content["id"]
											),
											"content" => "Upravit"
										)
									)
								),
								array(
									"element" => "td",
									"content" => array(
										array(
											"element" => "a",
											"params" => array(
												"href" => "/fileDelete.php?id=" . $content["id"]
											),
											"content" => "Smazat"
										)
									)
								)
							)
						));
					}
				}

				$userTags = formTagsDefine();
				formTags($userTags, $userContent);

				$sharedTags = formTagsDefine();
				formTags($sharedTags, $sharedContent);
			?>
			<h1>Formuláře</h1>
			<a href="/fileEdit.php">Vytvořit nový formulář</a>
<?php
	if ($sharedTags !== formTagsDefine()) {
		echo htmlTags(3, array(
			array(
				"element" => "h2",
				"content" => "S vámi sdílené formuláře"
			),
			$sharedTags
		));
	}
	if ($userTags !== formTagsDefine()) {
		echo htmlTags(3, array(
			array(
				"element" => "h2",
				"content" => "Vaše formuláře"
			),
			$userTags
		));
	}

	if ($sharedTags === formTagsDefine() && $userTags === formTagsDefine()) {
		echo htmlTags(3, array(
			array(
				"element" => "h2",
				"content" => "Nemáte žádné formuláře."
			)
		));
	}
?>
		</main>
<?php htmlFooter(); ?>
	</body>
</html>