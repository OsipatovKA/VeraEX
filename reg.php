<?php
	//session_start();/////////////////////////////////////////SESSION_START///////////////////////////
	ini_set('display_errors',1);/////////////////////////////DEBUG///////////////////////////////////
	error_reporting('E_ALL');////////////////////////////////DEBUG///////////////////////////////////

	//SETTINGS//
	$server_bot = 'smtp.timeweb.ru';
	$password_bot = '1q2w3e4r5';
	$mail_bot = 'no-reply@sign.tw1.ru';
	$name_bot = 'NO_REPLY';
	$title = 'Подтверждение регистрации на VeraExchange';
	$mess = 'Для того что-бы продолжить регистрацию, перейдите по ссылке:';
	$mess1 = 'Если эту регистрацию, делали не ВЫ. Удалите письмо.';
	//SETTINGS//


	include_once('db.php');//////////////////////////////////SETTINGS_DATA_BASE//////////////////////
	include_once('inc.class.php');///////////////////////////ADD_CLASS_EASY_JOB//////////////////////
	$SETTINGS = new SETTINGS();//////////////////////////////START_SETTINGS_CLASS////////////////////
	$FORM = new FORM();//////////////////////////////////////START_FORM_CLASS////////////////////////
	$AUTH = new AUTH();//////////////////////////////////////START_AUTH_CLASS////////////////////////
	/*Быстрое объявление переменных*/
	foreach($_POST as $key=>$name){
		${$key} = $name;
	}
	/*АЛГОРИТМ: $_POST['name'] = $name //значение name становится названием переменной */

	/*echo '<pre>';//////////////
	print_r($_POST);///DEBUG///
	echo $login.'<br>';
	echo $email.'<br>';
	echo $password.'<br>';
	echo $password2.'<br>';
	echo '</pre>';/////////////*/

if($FORM->reg($login, 'empty')){//проверка LOGIN на пустоту
	if($FORM->reg($login, 'preg_match_login')){//проверка LOGIN на символы
		if($FORM->reg($login, 'repeat_login')){//проверка LOGIN на повторение в базе
			if($FORM->reg($email, 'empty')){//проверка EMAIL на пустоту
				if($FORM->reg($email, 'preg_match_email')){//проверка EMAIL на символы
					if($FORM->reg($email, 'repeat_email')){//проверка EMAIL на повторение в базе
						if($FORM->reg($password, 'empty')){//проверка PASSWORD на пустоту
							if($FORM->reg($password2, 'empty')){//проверка PASSWORD2 на пустоту
								if($FORM->reg($password, 'preg_match_password')){//проверка PASSWORD на символы
									if($password==$password2){//проверка PASSWORD и PASSWORD2 на совпадение
										$password = $FORM->hash_pass($password);
										$timestamp = time();
										$validation = md5($timestamp);
										$mail_class = new EMAIL($email_bot); //Создаём экземпляр класса
										$mail_class->setFromName($name_bot); //Устанавливаем имя в обратном адресе
										$message = $mess.' <a href="http://sign.tw1.ru/activate.php?valid='.$validation.'">ссылка</a> '.$mess1;
										$mailSMTP = new SMTP_EMAIL($server_bot, $mail_bot, $password_bot, $name_bot, 25); // создаем экземпляр класса
										$headers= "MIME-Version: 1.0\r\n";
										$headers .= "Content-type: text/html; charset=utf-8\r\n"; // кодировка письма
										$headers .= "From: ".$name_bot." <".$mail_bot." >\r\n"; // от кого письмо
										
										if($sql = $mysqli->query("INSERT INTO `user` (`id`, `login`, `password`, `mail`, `status`, `last_act`, `reg_date`) VALUES (NULL, '$login', '$password', '$email', 'false', $timestamp, $timestamp)")){
											$id = $mysqli->insert_id;
											if($FORM->validation($id, $validation)){
												$result = $mailSMTP->send($email, $title, $message, $headers); // отправляем письмо
											}
										}
										
										if($result === true){
											//echo 'Письмо с подтверждением было отправлено на ваш E-mail: '.$email.'<br>В течении нескольких секунды вы будете перенаправлены на главную страницу.';
											echo $SETTINGS->REFRESH($SETTINGS->ROOT().'/login-done.php',0);//отправить юзера через 0 сек по ссылке
										}else{
											echo "Письмо не отправлено. Напишите в тех.поддержку. Ошибка: " . $result;
										}
									}else{exit("Пароли не совпадают");}
								}else{exit("Пароль может состоять от 6(шести) до 20(двадцати) символов, обязательно наличие минимум одной цифры, одной заглавной буквы, одной маленькой буквы");}
							}else{exit("Вы не повторили Пароль");}
						}else{exit("Вы не ввели Пароль");}
					}else{exit("Ваш Email уже зарегестрирован, попробуйте другой или восстановите старый аккаунт");}
				}else{exit("Не правильно введен E-mail");}
			}else{exit('Вы не ввели E-mail');}
		}else{exit("Ваш ЛОГИН уже зарегестрирован, попробуйте другой или восстановите старый аккаунт");}
	}else{exit("ЛОГИН может состоять от 5(пяти) до 20(двадцати) символов из: англ.символов(нижний регистр), цифр и знаков - (точка), (тире), (нижнее_подчеркивание)");}
}else{exit("Вы не ввели ЛОГИН");}


?>