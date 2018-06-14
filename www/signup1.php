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
		<title>Signup</title>
		
		<!-- ADD_PLUGIN -->
		<script src="//cdnjs.cloudflare.com/ajax/libs/jquery/3.3.1/jquery.min.js"></script>
		<!-- ADD_PLUGIN -->
	</head>
	<body>
		<main>
			<div class="login">
				<div class="login__inner">
					<div class="login__title">
						<a href="" class="">Login</a> 
						<a href="" class="login__title--active">Signup</a> <!-- active class -->
					</div>
					<!-- REG_FORM -->
					<form action="reg.php" method="post" onSubmit="return checkConfirm();">
						<div class="login__content">
							<div class="login__label">Username</div>
							<input type="text" class="login__input" name="login" placeholder="Username" required pattern="^[a-z0-9-_\.]{5,20}$" min="5" maxlength="20" title="ЛОГИН может состоять от 5(пяти) до 20(двадцати) символов из: англ.символов(нижний регистр), цифр и знаков - (точка), (тире), (нижнее_подчеркивание)" autocomplete="off">
							<div class="login__label">Email</div>
							<input type="email" class="login__input" name="email" placeholder="Email" required>
							<div class="login__label">Password</div>
							<input type="password" class="login__input" name="password" placeholder="Password" required pattern="(?=(.*[0-9]))(?=.*[a-zа-я])(?=(.*[A-ZА-Я]))(?=(.*)).{6,20}" title="Пароль может состоять от 6(шести) до 20(двадцати) символов, обязательно наличие минимум одной цифры, одной заглавной буквы, одной маленькой буквы">
							<div class="login__label">Repeat Password</div>
							<input type="password" class="login__input" name="password2" placeholder="Repeat Password" required pattern="(?=(.*[0-9]))(?=.*[a-zа-я])(?=(.*[A-ZА-Я]))(?=(.*)).{6,20}" title="Пароль может состоять от 6(шести) до 20(двадцати) символов, обязательно наличие минимум одной цифры, одной заглавной буквы, одной маленькой буквы">
							<div class="login__check">
								<input id="checkbox" type="checkbox" name="terms" value="1">
								<label for="checkbox"></label>
								<span>I agree to the <a href="">Terms and Conditions</a></span>
							</div>
							<!-- google captcha -->
							<input type="submit" class="btn btn--login" id="registration" value="Registration">
						</div>
					</form>
					<!-- REG_FORM -->
				</div>
			</div>
		</main>
		<!-- ADD_SCRIPT -->
		<script>
		function checkConfirm(){
			$result = registrationCorrect();
			if !($result){
				window.alert('Вы ввели говно!')
				return false
			}
			else {
				window.alert('Вы the best!')
				return true
			}
		}	
		/*$(document).ready(function(){
			
		})*/
		</script>
		<!-- ADD_SCRIPT -->
		<script src="js/js.js"></script>
	</body>
</html>