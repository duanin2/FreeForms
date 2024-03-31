<?php require_once "lib/common.php"; ?>
<!DOCTYPE html>
<html lang="en">
<?php htmlHead("O uživateli"); ?>
	<body>
<?php 
if ($isLoggedIn) {
	htmlHeader(["userdata", "login", "register"]);
} else {
	htmlHeader(["userdata", "logout"]);
	header("Location: /");
}
?>
		<main>
			<h1>O uživateli</h1>
			<?php
			$userDB = load_db($userdb_location);

			foreach ($userDB as $loopKey => $loopUser) {
				if ($loopUser["username"] = $_SESSION["username"]) {
					$user = $loopUser;
					$key = $loopKey;
					break;
				}
			}

			if (isset($_POST["scheme"])) {
				$newScheme = $_POST["scheme"];

				if ($newScheme === "latte" || $newScheme === "frappe" || $newScheme === "macchiato" || $newScheme === "mocha") {
					if ($user["scheme"] != $newScheme) {
						$userDB[$key]["scheme"] = $newScheme;
						save_db($userdb_location, $userDB);
						$_SESSION["scheme"] = $newScheme;
					}

					header("Location: .");
				}
			}
			?>
			<form action="#" method="post">
				<table>
					<tr>
						<td>Uživatelské jméno</td>
						<td><?php echo $user["username"]; ?></td>
					</tr>
					<tr>
						<td>Jméno</td>
						<td><?php echo $user["firstName"]; ?></td>
					</tr>
					<tr>
						<td>Příjmení</td>
						<td><?php echo $user["lastName"]; ?></td>
					</tr>
					<tr>
						<td>E-mail</td>
						<td><?php echo $user["email"]; ?></td>
					</tr>
					<tr>
						<td>Barevné téma</td>
						<td>
							<select name="scheme" id="scheme">
								<option value="latte" <?php if ($user["scheme"] === "latte") echo "selected"; ?>>Catppuccin Latte</option>
								<option value="frappe" <?php if ($user["scheme"] === "frappe") echo "selected"; ?>>Catppuccin Frappe</option>
								<option value="macchiato" <?php if ($user["scheme"] === "macchiato") echo "selected"; ?>>Catppuccin Macchiato</option>
								<option value="mocha" <?php if ($user["scheme"] === "mocha") echo "selected"; ?>>Catppuccin Mocha</option>
							</select>
						</td>
					</tr>
				</table>
				<input type="submit" value="Změnit">
			</form>
		</main>
<?php htmlFooter(); ?>
	</body>
</html>