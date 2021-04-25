<?php
include("includes/config.php");
include("includes/classes/Account.php");
include("includes/classes/Constants.php");
$account = new Account($con);

include("includes/handlers/register-handler.php");
include("includes/handlers/login-handler.php");

function getInputValue($name) {
	if (isset($_POST[$name])) {
		echo $_POST[$name];
	}
}

?>

<!DOCTYPE html>
<html>
<head>
	<title>Welcome to Ctrl-P MP3</title>
	<link rel="stylesheet" type="text/css" href="assets/css/register.css">
	<script src="https://ajax.aspnetcdn.com/ajax/jQuery/jquery-3.4.1.min.js"></script>
	<script src="assets/js/register.js"></script>
</head>
<body>
	<?php
	if (isset($_POST['registerButton'])) {
		echo '<script>
				$(document).ready(function() {
					$("#registerForm").show();
					$("#loginForm").hide();
				});
			</script>';
	}
	else {
		echo '<script>
			$(document).ready(function() {
				$("#registerForm").hide();
				$("#loginForm").show();
			});
			</script>';
		}
	?>

	<div id="background">
		<div id="loginContainer">
			<div id="inputContainer">
				<form id="loginForm" action="register.php" method="POST">
					<h2>Login to your account</h2>
					<p>
						<?php echo $account->getError(Constants::$loginFailed) ?>
						<label for="loginUsername">Username: </label>
						<input id="loginUsername" type="text" name="loginUsername" placeholder="My Username" value="<?php getInputValue('loginUsername'); ?>" required="">
					</p>
					<p>
						<label for="loginPassword">Password: </label>
						<input id="loginPassword" type="password" name="loginPassword" placeholder="******" required="">
					</p>
					<button type="submit" name="loginButton">Login</button>


					<div class="hasAccountText">
						<span id="hideLogin">Don't have an account yet?</span>
					</div>
				</form>

				<form id="registerForm" action="register.php" method="POST">
					<h2>Create your account</h2>
					<p>
						<?php echo $account->getError(Constants::$usernameCharacters) ?>
						<?php echo $account->getError(Constants::$usernameTaken) ?>
						<label for="username">Username: </label>
						<input id="username" type="text" name="username" placeholder="jdoemusic" value="<?php getInputValue('username'); ?>" required="">
					</p>
					<p>
						<?php echo $account->getError(Constants::$emailsDoNotMatch) ?>
						<?php echo $account->getError(Constants::$emailInvalid) ?>
						<?php echo $account->getError(Constants::$emailTaken) ?>
						<label for="email">Email: </label>
						<input id="email" type="email" name="email" placeholder="janedoe8@gmail.com" value="<?php getInputValue('email'); ?>" required="">
					</p>
					<p>
						<label for="email2">Confirm Email: </label>
						<input id="email2" type="email" name="email2" placeholder="janedoe8@gmail.com" value="<?php getInputValue('email2'); ?>" required="">
					</p>
					<p>
						<?php echo $account->getError(Constants::$passwordsDoNotMatch) ?>
						<?php echo $account->getError(Constants::$passwordCharacters) ?>
						<label for="password">Password: </label>
						<input id="password" type="password" name="password" placeholder="******" required="">
					</p>
					<p>
						<label for="password2">Confirm Password: </label>
						<input id="password2" type="password" name="password2" placeholder="******" required="">
					</p>
					<button type="submit" name="registerButton">Register</button>

					<div class="hasAccountText">
						<span id="hideRegister">Need to login?</span>
					</div>
				</form>
			</div>

			<div id="loginText">
				<h1>Ctrl-P MP3</h1>
				<h2>The music player with fuzzy song search</h2>
				<ul>
					<li>Listen to music on your desktop or laptop</li>
					<li>Upload your music to a server and listen anywhere</li>
					<li>View other great projects at <a href="http://www.davesprojects.net">davesprojects.net</a></li>
				</ul>
			</div>

		</div>
	</div>
</body>
</html>