<?php
require_once "lib/common.php";

if (count($_POST) != 0) {
	$username = $_POST["username"];
	$firstName = $_POST["firstName"];
	$lastName = $_POST["lastName"];
	$email = $_POST["email"];
	$pass1 = $_POST["pass1"];
	$pass2 = $_POST["pass2"];

	$isPost = true;

	$db = load_db($userdb_location);
} else {
	$username = "";
	$firstName = "";
	$lastName = "";
	$email = "";
	$pass1 = "";
	$pass2 = "";

	$isPost = false;
}

$error = false;
?>
<!DOCTYPE html>
<html lang="en">
<?php htmlHead("Registrace"); ?>
	<body>
<?php htmlHeader(["forms", "register"]); ?>
		<main>
			<h1>Registrace</h1>
			<form method="post" action="#">
				<table>
					<tr>
						<?php
						if ($isPost) {
							if ($username === "") {
								$error = true;

								$error_cur = "Uživatelské jméno nesmí být prázdné.";
							} elseif (!preg_match('/^\w+$/u', $username)) {
								$error = true;

								$error_cur = "Uživatelské jméno se musí skládat jen z velkých písmen, malých písmen a čísel.";
							}
						}

						echo "<td>Uživatelské jméno</td>";
						echo "<td><input type=\"text\" id=\"username\" name=\"username\" " . (!isset($error_cur) ? "value=\"$username\"" : "") . "></td>";
						if (isset($error_cur)) {
							echo "<td class=\"error\">$error_cur</td>";
						}
						?>
					</tr>
					<tr>
						<?php
						unset($error_cur);
						
						if ($isPost) {
							if ($firstName === "") {
								$error = true;

								$error_cur = "Jméno nesmí být prázdné.";
							} elseif (!preg_match('/^[[:alpha:]]+$/u', $firstName)) {
								$error = true;

								$error_cur = "Jméno se musí skládat jen z velkých a malých písmen.";
							}
						}

						echo "<td>Jméno</td>";
						echo "<td><input type=\"text\" name=\"firstName\" " . (!isset($error_cur) ? "value=\"$firstName\"" : "") . "></td>";
						if (isset($error_cur)) {
							echo "<td class=\"error\">$error_cur</td>";
						}
						?>
					</tr>
					<tr>
						<?php
						unset($error_cur);
						
						if ($isPost) {
							if ($lastName === "") {
								$error = true;

								$error_cur = "Příjmení nesmí být prázdné.";
							} elseif (!preg_match('/[[:alpha:]]+/u', $lastName)) {
								$error = true;

								$error_cur = "Příjmení se musí skládat jen z velkých a malých písmen.";
							}
						}

						echo "<td>Příjmení</td>";
						echo "<td><input type=\"text\" name=\"lastName\" " . (!isset($error_cur) ? "value=\"$lastName\"" : "") . "></td>";
						if (isset($error_cur)) {
							echo "<td class=\"error\">$error_cur</td>";
						}
						?>
					</tr>
					<tr>
						<?php
						unset($error_cur);
						
						if ($isPost) {
							if ($email === "") {
								$error = true;

								$error_cur = "E-mail nesmí být prázdný.";
							} elseif (!preg_match('/(?:[a-z0-9!#$%&\'*+\/=?^_`{|}~-]+(?:\.[a-z0-9!#$%&\'*+\/=?^_`{|}~-]+)*|"(?:[\x01-\x08\x0b\x0c\x0e-\x1f\x21\x23-\x5b\x5d-\x7f]|\\[\x01-\x09\x0b\x0c\x0e-\x7f])*")@(?:(?:[a-z0-9](?:[a-z0-9-]*[a-z0-9])?\.)+[a-z0-9](?:[a-z0-9-]*[a-z0-9])?|\[(?:(?:(2(5[0-5]|[0-4][0-9])|1[0-9][0-9]|[1-9]?[0-9]))\.){3}(?:(2(5[0-5]|[0-4][0-9])|1[0-9][0-9]|[1-9]?[0-9])|[a-z0-9-]*[a-z0-9]:(?:[\x01-\x08\x0b\x0c\x0e-\x1f\x21-\x5a\x53-\x7f]|\\[\x01-\x09\x0b\x0c\x0e-\x7f])+)\])/u', $email)) {
								$error = true;

								$error_cur = "E-mail není platný.";
							}
						}

						echo "<td>E-mail</td>";
						echo "<td><input type=\"email\" name=\"email\" " . (!isset($error_cur) ? "value=\"$email\"" : "") . "></td>";
						if (isset($error_cur)) {
							echo "<td class=\"error\">$error_cur</td>";
						}
						?>
					</tr>
					<tr>
						<?php
						unset($error_cur);
						
						if ($isPost) {
							if ($pass1 === "") {
								$error = true;

								$error_cur = "Heslo nesmí být prázdné.";
							} elseif (!preg_match('/^[[:alnum:]]{5,}$/u', $pass1)) {
								$error = true;

								$error_cur = "Heslo se musí skládat z velkých písmen, malých písmen a čísel a musí být alespoň 5 znaků dlouhé.";
							}
						}

						echo "<td>Heslo</td>";
						echo "<td><input type=\"password\" autocomplete=\"new-password\" name=\"pass1\" " . (!isset($error_cur) ? "value=\"$pass1\"" : "") . "></td>";
						if (isset($error_cur)) {
							echo "<td class=\"error\">$error_cur</td>";
						}
						?>
					</tr>
					<tr>
						<?php
						unset($error_cur);

						if ($isPost) {
							if ($pass1 != $pass2) {
								$error = true;

								$error_cur = "Hesla se musí shodovat.";
							} elseif ($pass2 === "") {
								$error = true;

								$error_cur = "Heslo nesmí být prázdné.";
							} elseif (!preg_match('/^\w{5,}$/u', $pass1)) {
								$error = true;

								$error_cur = "Heslo se musí skládat z velkých písmen, malých písmen a čísel a musí být alespoň 5 znaků dlouhé.";
							}
						}

						echo "<td>Heslo znovu</td>";
						echo "<td><input type=\"password\" autocomplete=\"new-password\" name=\"pass2\" " . (!isset($error_cur) ? "value=\"$pass2\"" : "") . "></td>";
						if (isset($error_cur)) {
							echo "<td class=\"error\">$error_cur</td>";
						}
						?>
					</tr>
					<tr>
						<td><input type="submit" value="Zaregistrovat"></td>
						<?php
						if ($isPost && !$error) {
							$newElem = array(
								"username" => $username,
								"firstName" => $firstName,
								"lastName" => $lastName,
								"email" => $email,
								"pass" => $pass1,
								"scheme" => $defaultScheme,
								"isAdmin" => "false"
							);

							foreach ($db as $elem) {
								if (($elem["username"] ?? "") === $newElem["username"]) {
									$error_cur = "Uživatel již existuje.";
									$error = true;
									break;
								} elseif (($elem["email"] ?? "") === $newElem["email"]) {
									$error_cur = "Tento e-mail byl již použit.";
									$error = true;
									break;
								}
							}

							if (!$error) {
								array_push($db, $newElem);

								save_db($userdb_location, $db);
								$_SESSION["username"] = $username;

								header('Location: ./');
							} else {
								echo "<td class=\"error\">$error_cur</td>";
							}
						}
						?>
					</tr>
				</table>
			</form>
		</main>
<?php htmlFooter(); ?>
	</body>
</html>