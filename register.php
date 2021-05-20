<?php
include("includes/config.php");
include("includes/classes/Account.php");
include("includes/classes/Constants.php");
$account = new Account($con);

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
	<link rel="icon" href="assets/images/controlp_sq_jpg.jpg">
</head>
<body>

	<div id="background">
		<div id="loginContainer">
			<div id="inputContainer">
				<form id="loginForm" action="register.php" method="POST">
					<h2>Login to your account</h2>
					<p>
						<?php echo $account->getError(Constants::$loginFailed) ?>
						<?php echo $account->getError(Constants::$doNotHaveAcc) ?>
						<label for="loginUsername">Username: </label>
						<input id="loginUsername" type="text" name="loginUsername" placeholder="My Username" value="<?php getInputValue('loginUsername'); ?>" required="">
					</p>
					<p>
						<label for="loginPassword">Password: </label>
						<input id="loginPassword" type="password" name="loginPassword" placeholder="******" required="">
					</p>
					<button type="submit" name="loginButton">Login</button>
				</form>
			</div>

			<div id="loginText">
				<h1>Ctrl-P MP3</h1>
				<h2>The music player with fuzzy song search</h2>
				<ul>
					<li>Listen to music on your desktop or laptop</li>
					<li>Upload your music to a server and listen anywhere</li>
					<li>Get other great projects at <a href="http://www.davesprojects.net">davesprojects.net</a></li>
				</ul>
			</div>

		</div>
	</div>
</body>
</html>