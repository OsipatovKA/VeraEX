<?php
/*
	ini_set('display_errors',1);
	error_reporting('E_ALL');
	
	//SETTINGS//
	$server_bot = 'smtp.timeweb.ru';
	$password_bot = '1q2w3e4r5';
	$mail_bot = 'no-reply@sign.tw1.ru';
	$name_bot = 'NO_REPLY';
	$title = 'Подтверждение регистрации на VeraExchange';
	$mess = 'Для того что-бы продолжить регистрацию, перейдите по ссылке:';
	$mess1 = 'Если эту регистрацию, делали не ВЫ. Удалите письмо.';
	//SETTINGS//
*/	
	include_once('db.php');

	$login = htmlspecialchars($_POST['login']);
	$password = htmlspecialchars($_POST['password']);
	$mail = htmlspecialchars($_POST['email']);
	//$salt = mt_rand(100, 999);
	$tm = time();
	$password = md5($password);
	$querylogin=$mysqli->query("SELECT * FROM `user` WHERE login='".$login."'");
	$numrowlogin = $querylogin->num_rows;
	
	$queryemail=$mysqli->query("SELECT * FROM `user` WHERE mail='".$mail."'");
	$numrowemail = $queryemail->num_rows;
	
	if ($numrowlogin==0){
		if ($numrowemail==0){
			if($mysqli->query("INSERT INTO `user` (`login`, `password`, `mail`, `status`, `last_act`, `reg_date`) VALUES ('$login', '$password', '$mail', 'false', $tm, $tm)")){
				include ("login-done.php");
			}
			else {
				$message = "Failed to insert data information!";
				echo ($message);
			}
		}	
		else {
			$message = "That Email already exists! Please try another one!";
			echo ($message);
		}
	}
	else {
		$message = "That username already exists! Please try another one!";
		echo ($message);
	}
	
?>