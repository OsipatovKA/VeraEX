<?
session_start();
include_once('db.php');
include_once('inc.class.php');

echo 'ИД:'.$_SESSION['id'].'/// ВАЛИД.КОД: '.$_SESSION['token'];
?>