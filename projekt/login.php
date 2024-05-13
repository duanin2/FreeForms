<?php
require_once "lib/common.php";

$username = $_POST["username"] ?? "";
$pass = $_POST["pass"] ?? "";

$elem = array();

$isPost = count($_POST) != 0;

$error = false;
?>
<!DOCTYPE html>
<html lang="en">
<?php htmlHead("Přihlášení"); ?>
	<body>
<?php htmlHeader(["forms", "login"]); ?>
		<main>
			<h1>Přihlášení</h1>
			<form method="post" action="#">
				<table>
					<tr>
					<?php
					if ($isPost) {
						if ($username === "") {
							$error = true;
							$error_cur = "Uživatelské jméno nesmí být prázdné.";
						} else {
                            if (isset($db["users"][$username])) {
                                $elem = $db["users"][$username];

                                $error = false;
                                unset($error_cur);
                            } else {
                                $error = true;
                                $error_cur = "Uživatel neexistuje.";
                            }
						}
					}

					echo "<td>Uživatelské jméno</td>";
					echo "<td><input type=\"text\" id=\"username\" name=\"username\" " . (!isset($error_cur) ? "value=\"$username\"" : "") . "></td>";
					if (isset($error_cur)) {
						echo "<td style=\"color: red;\">$error_cur</td>";
					}
					?>
					</tr>
					<tr>
					<?php
					unset($error_cur);
					
					if ($isPost) {
						if ($pass === "") {
							$error = true;
							$error_cur = "Heslo nesmí být prázdné.";
						} elseif ($elem != array() && ($elem["pass"] ?? "") != $pass) {
							$error = true;
							$error_cur = "Nesprávné heslo.";
						}
					}

					echo "<td>Heslo</td>";
					echo "<td><input type=\"password\" id=\"pass\" name=\"pass\" " . (!isset($error) ? "value=\"$pass\"" : "") . "></td>";
					if (isset($error_cur)) {
						echo "<td style=\"color: red;\">$error_cur</td>";
					}
					?>
					</tr>
					<tr>
						<td><input type="submit" value="Přihlásit se"></td>
						<?php
						if ($isPost && !$error) {
							$_SESSION["username"] = $username;

						    redirect('./');
						}
						?>
					</tr>
				</table>
			</form>
		</main>
<?php htmlFooter(); ?>
	</body>
</html>
