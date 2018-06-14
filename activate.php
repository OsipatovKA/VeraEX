<?
	session_start();
	ini_set('display_errors',1);/////////////////////////////DEBUG///////////////////////////////////
	error_reporting('E_ALL');////////////////////////////////DEBUG///////////////////////////////////
	include_once('db.php');//////////////////////////////////DATE BASE///////////////////////////////
	include_once('inc.class.php');///////////////////////////CLASS///////////////////////////////////
	/***********************************************************************************************/

	/*объявление переменных*/
	foreach($_GET as $key=>$name)
	{
		${$key} = $name;
	}
	print_r($_GET);
	
	$valid_sql = $mysqli->query("SELECT * FROM `validation` WHERE `valid` = '$valid'");	
	$valid_row = $valid_sql->fetch_assoc();
	$id_user = $valid_row['id_user'];
	//echo $id_user;
	$validation = $mysqli->query("UPDATE `user` SET `status` = 'true' WHERE `id` = '$id_user'");
	$delete = $mysqli->query("DELETE FROM `validation` WHERE `id_user` = '$id_user'");
	echo 'Вы подтвердили свой емейл...';
?>