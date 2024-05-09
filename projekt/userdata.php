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
			$userdata = 
			foreach ($db["users"] as $loopKey => $loopUser) {
				if ($loopKey = $_SESSION["username"]) {
					$userdata = $loopUser;
					$username = $loopKey;
					break;
				}
			}

			if (isset($_POST["scheme"])) {
				$newScheme = $_POST["scheme"];

				if ($newScheme === "latte" || $newScheme === "frappe" || $newScheme === "macchiato" || $newScheme === "mocha") {
					if ($userdata["scheme"] != $newScheme) {
						$db["users"][$username]["scheme"] = $newScheme;
						$_SESSION["scheme"] = $newScheme;
					}

					redirect(".");
				}
			}
			?>
			<form action="#" method="post">
				<table>
					<tr>
						<td>Uživatelské jméno</td>
						<td><?php echo $username; ?></td>
					</tr>
					<tr>
						<td>Jméno</td>
						<td><?php echo $userdata["firstName"]; ?></td>
					</tr>
					<tr>
						<td>Příjmení</td>
						<td><?php echo $userdata["lastName"]; ?></td>
					</tr>
					<tr>
						<td>E-mail</td>
						<td><?php echo $userdata["email"]; ?></td>
					</tr>
					<tr>
						<td>Barevné téma</td>
						<td>
							<select name="scheme" id="scheme">
								<option value="latte" <?php if ($userdata["scheme"] === "latte") echo "selected"; ?>>Catppuccin Latte</option>
								<option value="frappe" <?php if ($userdata["scheme"] === "frappe") echo "selected"; ?>>Catppuccin Frappe</option>
								<option value="macchiato" <?php if ($userdata["scheme"] === "macchiato") echo "selected"; ?>>Catppuccin Macchiato</option>
								<option value="mocha" <?php if ($userdata["scheme"] === "mocha") echo "selected"; ?>>Catppuccin Mocha</option>
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