<?
	session_start();
	ini_set('display_errors',1);/////////////////////////////DEBUG///////////////////////////////////
	error_reporting('E_ALL');////////////////////////////////DEBUG///////////////////////////////////
	include_once('db.php');//////////////////////////////////DATE BASE///////////////////////////////
	include_once('inc.class.php');///////////////////////////CLASS///////////////////////////////////
	/***********************************************************************************************/
	$FORM = new FORM();//////////////////////////////////////СТАРТ FORM/////////////////////////////
	$TOKEN = new TOKEN();////////////////////////////////////СТАРТ TOKEN////////////////////////////
	/***********************************************************************************************/

	/*объявление переменных*/
	foreach($_POST as $key=>$name){
		${$key} = $name;
	}
	print_r($_POST);
echo FORM::hash_pass($password);
if($FORM->reg($login, 'empty')){
	if($FORM->reg($login, 'preg_match_login')){
		if($FORM::reg($password, 'empty')){
			$password = $FORM->hash_pass($password);
			$sql_email = $mysqli->query("SELECT * FROM `user` WHERE `login` = '$login' AND `password` = '$password'")->fetch_assoc();
			if($FORM->reg($sql_email['id'], 'empty')){
				$_SESSION['id'] = $sql_email['id'];
				$_SESSION['token'] = $TOKEN->generator($sql_email['id']);	
			}else{exit('Не правильно введен Email или Пароль или такой не зарегестрирован.');}
		}else{exit("Вы не ввели Пароль.");}
	}else{exit("Вы ввели Email не правильно.");}
}else{exit("Вы не ввели Email.");}
?> 