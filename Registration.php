<?php
	$login = htmlspecialchars($_POST['login']);
	echo "$login";
	
	/*ini_set('display_errors',1);
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
	
	include_once('db.php');
	
	window.alert('Начинаем передачу!')
	
	$login = htmlspecialchars($_POST['login']);
	$password = $_POST['password'];
	$mail = htmlspecialchars($_POST['mail']);
	//$salt = mt_rand(100, 999);
	$tm = time();
	$password = md5($password);
	
	if (mysql_query("INSERT INTO users (login,password,mail,last_act,reg_date) VALUES ('".$login."','".$password."','".$mail."','".$tm."','".$tm."')")) //пишем данные в БД и авторизовываем пользователя
	{
		include ("login-done.php");
	}*/
?>