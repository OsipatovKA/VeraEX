<?php

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
	
	include_once('db.php');
	include_once('inc.class.php');//Различные классы и функции для упрощения работы
	
	$SETTINGS = new SETTINGS();//////////////////////////////START_SETTINGS_CLASS////////////////////
	$FORM = new FORM();//////////////////////////////////////START_FORM_CLASS////////////////////////
	$AUTH = new AUTH();//////////////////////////////////////START_AUTH_CLASS////////////////////////
	
// Присваивание переменных из предыдущей формы
	$login = htmlspecialchars($_POST['login']);
	$password = htmlspecialchars($_POST['password']);
	$mail = htmlspecialchars($_POST['email']);
	$tm = time();
	$password = md5($password);
//Закончил присваивать 

//Вычисление количества записей  в БД с принятыми Логином и Емэйлом	
	$querylogin=$mysqli->query("SELECT * FROM `user` WHERE login='".$login."'");
	$numrowlogin = $querylogin->num_rows;
	
	$queryemail=$mysqli->query("SELECT * FROM `user` WHERE mail='".$mail."'");
	$numrowemail = $queryemail->num_rows;
//Закончил вычислять	

//Всё для боты отправки сообщ
	$mail_class = new EMAIL($email_bot); //Создаём экземпляр класса
	$mail_class->setFromName($name_bot); //Устанавливаем имя в обратном адресеc
	$validation = md5($tm);
	$messageEmail = $mess.' <a href="http://sign.tw1.ru/activate.php?valid='.$validation.'">ссылка</a> '.$mess1;
	$mailSMTP = new SMTP_EMAIL($server_bot, $mail_bot, $password_bot, $name_bot, 25); // создаем экземпляр класса
	$headers = "MIME-Version: 1.0\r\n";
	$headers .= "Content-type: text/html; charset=utf-8\r\n"; // кодировка письма
	$headers .= "From: ".$name_bot." <".$mail_bot." >\r\n"; // от кого письмо

//Проверки на уникальность и добавление в БД
	if ($numrowlogin==0){
		if ($numrowemail==0){
			if($mysqli->query("INSERT INTO `user` (`login`, `password`, `mail`, `status`, `last_act`, `reg_date`) VALUES ('$login', '$password', '$mail', 'false', $tm, $tm)")){
				$resultEmail = $mailSMTP->send($mail, $title, $messageEmail, $headers); // отправляем письмо					
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
		$message = "That Login already exists! Please try another one!";
		echo ($message);
	}
	if($resultEmail){
		include ("login-done.php");
	}
	else{
		echo ('Хуй чо отправилось');
	}
?>