<?
/* Подключение к серверу MySQL */ 
$server = "localhost";//СЕРВЕР MYSQL
$user_db = "root";//ЮЗЕР MYSQL
$pass_db = "";//ПАРОЛЬ MYSQL
$base_db = "userlistdb";//ИМЯ_БАЗЫ MYSQL
$mysqli = new mysqli($server, $user_db, $pass_db, $base_db);
if(mysqli_connect_errno()){
	printf("Сервер базы данных не доступен. Код ошибки: %s\n", mysqli_connect_error());
	exit;
}
?>