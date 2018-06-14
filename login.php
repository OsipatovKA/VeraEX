<!DOCTYPE html>
	<html lang="en">
	<head>
		<meta charset="utf-8">
		<meta http-equiv="x-ua-compatible" content="ie=edge">
		<meta name="keywords" content="Some, SEO, Tags">
		<meta name="description" content="A description of the page">
		<meta name="viewport" content="width=device-width, initial-scale=1">
		<link rel="stylesheet" href="css/style.css">
		<link rel="stylesheet" href="css/mobile.css">
		<link rel="icon" href="img/icons/favicon.png">
		<link href="https://fonts.googleapis.com/css?family=Roboto:300,400,400i,500,700&amp;subset=latin-ext" rel="stylesheet">
		<title>Login</title>
	</head>
	<body>
		<main>
			<div class="login">
				<div class="login__inner">
					<div class="login__title">
						<a href="" class="login__title--active">Login</a> <!-- active class -->
						<a href="" class="">Signup</a>
					</div>
					<form action="auth.php" method="post">
						<div class="login__content">
							<div class="login__label">Username</div>
							<input type="text" class="login__input" name="login">
							<div class="login__label">Password</div>
							<input type="password" class="login__input" name="password">
							<div class="login__check">
								<input id="checkbox" type="checkbox" name="remember" value="1">
								<label for="checkbox"></label>
								<span>Remember me on this computer</span>
							</div>
							<!-- google captcha -->
							<input class="btn btn--login" type="submit" value="Login">
							<div class="login__text">Forgot your password?</div>
							<a href="" class="login__text2">Please use password recovery.</a>
						</div>
					</form>
				</div>
			</div>
		</main>
		<script src="js/js.js"></script>
	</body>
</html>