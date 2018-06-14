<?
class SETTINGS{
	function ROOT(){
		return 'https://'.$_SERVER['HTTP_HOST'].'';
	}
	
	function ROOT1(){
		return 'https://'.$_SERVER['HTTP_HOST'].'/dashboard';
	}
	
	function REFRESH($site, $time=0){
		return '<meta http-equiv="refresh" content="'.$time.';URL='.$site.'">';
	}
	
	function TITLE($name, $name2="«BikeAuto» - Магазин Авто запчастей"){
		return '<script>document.title = "'.$name.' - '.$name2.'";</script>';
	}
}

class INDEX{
	function request_uri($level, $url){
		$request = explode('/', substr($_SERVER['REQUEST_URI'], 1));
		if($url!='UTM_TAG'){
			if($request[$level]==$url){
				return true;
			}else{
				return false;
			}
		}else{
			$array_get=array(0=>'utm_medium', 'utm_source', 'utm_campaign', 'utm_term', 'utm_content');//костыль на UTM теги
			$count=0;//костыль
			for($i=0;$i<count($array_get);$i++){
				if($_GET[$array_get[$i]]!=''){$count++;}
			}
			if($count>0){
				return true;
			}else{
				return false;
			}
		}
	}
}

class FORM{
	function reg($var, $type){
		global $mysqli;
		switch($type){
			case 'empty':
				if($var!=''){
					return true;
				}else{
					return false;
				}
			break;
			case 'preg_match_email':
				if(preg_match("/^([a-z0-9_\.-]+)@([a-z0-9_\.-]+)\.([a-z\.]{2,6})$/", $var)){
					return true;
				}else{
					return false;
				}
			break;
			case 'preg_match_login':
				if(preg_match("/^[a-z0-9\-\_\.]{5,20}$/", $var)){
					return true;
				}else{
					return false;
				}
			break;
			case 'preg_match_phone':
				if(preg_match("/^[0-9]{10,10}$/", $var)){
					return true;
				}else{
					return false;
				}
			break;
			case 'preg_match_password':
				if(preg_match("/(?=(.*[0-9]))(?=.*[a-zа-я])(?=(.*[A-ZА-Я]))(?=(.*)).{6,20}/", $var)){
					return true;
				}else{
					return false;
				}
			break;
			case 'repeat_email':
				$sql_email = $mysqli->query("SELECT `id` FROM `user` WHERE `mail` = '$var' LIMIT 1");
				$row_email = $sql_email->fetch_row();
				if($row_email[0]==''){
					return true;
				}else{
					return false;
				}
			break;
			case 'repeat_login':
				$sql_login = $mysqli->query("SELECT `id` FROM `user` WHERE `login` = '$var' LIMIT 1");
				$row_login = $sql_login->fetch_row();
				if($row_login[0]==''){
					return true;
				}else{
					return false;
				}
			break;
			case 'repeat_phone':
				$sql_phone = $mysqli->query("SELECT `id` FROM `auth` WHERE `tel` = '$var' LIMIT 1");
				$row_phone = $sql_phone->fetch_row();
				if($row_phone[0]==''){
					return true;
				}else{
					return false;
				}
			break;
		}
	}
	
	function hash_pass($pass){
		return md5(crypt(md5(sha1(md5($pass).'SALT1').'SALT2'),'SALT3'));
	}
	
	function gen_pass($max){
		$chars="qwertyuiopasdfghjklzxcvbnm1234567890"; 
		$max=10; 
		$size=strlen($chars)-1; 
		$password=''; 
		while($max--){
			$password.=$chars[rand(0,$size)];
		}
		return $password;
	}
	
	function validation($id, $valid, $type=''){
		global $mysqli;
		if($type==''){
			if($mysqli->query("INSERT INTO `validation` (`id`, `status`, `id_user`,`valid`)
								VALUES(NULL, 'false', '$id', '$valid')")){
				return true;
			}else{
				return false;
			}
		}else if($type=='update'){
			if($mysqli->query("UPDATE `validation` SET `status` = 'true' WHERE `id_user`='$id'")){
				return true;
			}else{
				return false;
			}
		}
		
	}
	
	function format_tel($tel){
		$t = '+7('.substr($tel,0,3).')'.substr($tel,3,3).'-'.substr($tel,6,2).'-'.substr($tel,8,2);
		return $t;
	}
}

class EMAIL{
	private $from;
	private $from_name = "";
	private $type = "text/html";
	private $encoding = "utf-8";
	private $notify = false;

	/* Конструктор принимающий обратный e-mail адрес */
	public function __construct($from) {
		$this->from = $from;
	}
	
	/* Изменение обратного e-mail адреса */
	public function setFrom($from) {
		$this->from = $from;
	}
	
	/* Изменение имени в обратном адресе */
	public function setFromName($from_name) {
		$this->from_name = $from_name;
	}
	
	/* Изменение типа содержимого письма */
	public function setType($type) {
		$this->type = $type;
	}
	
	/* Нужно ли запрашивать подтверждение письма */
	public function setNotify($notify) {
		$this->notify = $notify;
	}
	
	/* Изменение кодировки письма */
	public function setEncoding($encoding) {
		$this->encoding = $encoding;
	}
	
	/* Метод отправки письма */
	public function send($to, $subject, $message) {
		$from = "=?utf-8?B?".base64_encode($this->from_name)."?="." <".$this->from.">"; // Кодируем обратный адрес (во избежание проблем с кодировкой)
		$headers = "From: ".$from."\r\nReply-To: ".$from."\r\nContent-type: ".$this->type."; charset=".$this->encoding."\r\n";//Заголовки
		if ($this->notify){
			$headers .= "Disposition-Notification-To: ".$this->from."\r\n";
		}//Добавляем запрос подтверждения получения письма, если требуется
		$subject = "=?utf-8?B?".base64_encode($subject)."?="; // Кодируем тему(во избежание проблем с кодировкой)
		
		return mail($to, $subject, $message, $headers); // Отправляем письмо и возвращаем результат
	}
}

class EMAIL_SMTP{
	private $debug = false;
	
	private $auth_serv;
	private $auth_mail;
	private $auth_pass;
	private $auth_port = 2525;
	
	private $from;
	private $from_name = "";
	private $notify = false;
	private $charset = 'utf-8';
	
	public function __construct($server, $mail, $password, $debug=false){
		$this->auth_serv = $server;
		$this->auth_mail = $mail;
		$this->auth_pass = $password;
		$this->debug = $debug;
	}
	
	/* Порт отправления письма */
	public function setPort($port = 2525){
		$this->auth_port = $port;
	}
	
	/* Кодировка письма */
	public function setCharset($charset = 'utf-8'){
		$this->charset = $charset;
	}
	
	/* Изменение обратного e-mail адреса */
	public function setFrom($from) {
		$this->from = $from;
	}
	
	/* Изменение имени в обратном адресе */
	public function setFromName($from_name) {
		$this->from_name = $from_name;
	}
	
	/* Нужно ли запрашивать подтверждение письма */
	public function setNotify($notify) {
		$this->notify = $notify;
	}
	
	/* Формирование и отправка письма */
	public function send($to, $title, $message){
		/*$s = "Subject: =?".$this->charset."?B?".base64_encode($title)."?=\r\n";
		$s.= "From: =?".$this->charset."?B?".base64_encode($this->auth_mail).'?=\r\n';
		$s.= "Reply-to: =?".$this->charset."?B?".base64_encode($this->auth_mail).'?=\r\n';
		$s.= "To: =?".$this->charset."?B?".base64_encode($to)."?=\r\n";
		$s.= "MIME-Version: 1.0\r\n";
		$s.= "Content-Type: text/html; charset=".$this->charset."\r\n";
		$s.= "Content-Transfer-Encoding: 8bit\r\n";
		$s.= "X-Priority: 3\r\n\r\n";
		$s.= "=?".$this->charset."?B?".base64_encode($message)."?=\r\n";*/
		if(!$socket = @fsockopen("smtp.timeweb.ru",2525,$errno1,$errstr1)){
			if ($this->debug) echo $errno1."<br>".$errstr1;
			return false;
		}
		fputs($socket, "EHLO smtp.timeweb.ru\r\n");
		
		return fgets($socket, 256). __LINE__;
		exit;
		/*if(!server_parse($socket, "250", __LINE__)){
			if ($this->debug) echo '<p>Не могу отправить HELO!</p>';
			fclose($socket);
			return false;
		}*/
		fputs($socket, "AUTH LOGIN\r\n");
		return fgets($socket, 128). __LINE__;
		/*if(!server_parse($socket, "334", __LINE__)){
			if ($this->debug) echo '<p>Не могу найти ответ на запрос авторизаци.</p>';
			fclose($socket);
			return false;
		}*/
		fputs($socket, base64_encode($this->auth_mail) . "\r\n");
		return fgets($socket, 128). __LINE__;
		/*if(!server_parse($socket, "334", __LINE__)){
			if ($this->debug) echo '<p>Логин авторизации не был принят сервером!</p>';
			fclose($socket);
			return false;
		}*/
		fputs($socket, base64_encode($this->auth_pass) . "\r\n");
		return fgets($socket, 128). __LINE__;
		/*if(!server_parse($socket, "235", __LINE__)){
			if ($this->debug) echo '<p>Пароль не был принят сервером как верный! Ошибка авторизации!</p>';
			fclose($socket);
			return false;
		}*/
		fputs($socket, "MAIL FROM: <".$this->auth_mail.">\r\n");
		return fgets($socket, 128). __LINE__;
		/*if (!server_parse($socket, "250", __LINE__)){
			if($this->debug) echo '<p>Не могу отправить комманду MAIL FROM: </p>';
			fclose($socket);
			return false;
		}*/
		fputs($socket, "RCPT TO: <" .$to. ">\r\n");
		return fgets($socket, 128). __LINE__;
		/*if (!server_parse($socket, "250", __LINE__)){
			if($this->debug) echo '<p>Не могу отправить комманду RCPT TO: </p>';
			fclose($socket);
			return false;
		}*/
		fputs($socket, "DATA\r\n");
		return fgets($socket, 128). __LINE__;
		/*if (!server_parse($socket, "354", __LINE__)){
			if($this->debug) echo '<p>Не могу отправить комманду DATA</p>';
			fclose($socket);
			return false;
		}*/
		fputs($socket, $message."\r\n.\r\n");
		return fgets($socket, 128). __LINE__;
		/*if(!server_parse($socket, "250", __LINE__)){
			if($this->debug) echo '<p>Не смог отправить тело письма. Письмо не было отправленно!</p>';
			fclose($socket);
			return false;
		}*/
		fputs($socket, "QUIT\r\n");
		return fgets($socket, 128). __LINE__;
		fclose($socket);
		//return true;
	}
}

class SMTP_EMAIL {
	/**
	*
	* @var string $smtp_username - логин
	* @var string $smtp_password - пароль
	* @var string $smtp_host - хост
	* @var string $smtp_from - от кого
	* @var integer $smtp_port - порт
	* @var string $smtp_charset - кодировка
	*
	*/
	public $smtp_username;
	public $smtp_password;
	public $smtp_host;
	public $smtp_from;
	public $smtp_port;
	public $smtp_charset;
	
	public function __construct($smtp_host, $smtp_username, $smtp_password, $smtp_from, $smtp_port = 2525, $smtp_charset = "utf-8") {
		$this->smtp_username = $smtp_username;
		$this->smtp_password = $smtp_password;
		$this->smtp_host = $smtp_host;
		$this->smtp_from = $smtp_from;
		$this->smtp_port = $smtp_port;
		$this->smtp_charset = $smtp_charset;
	}
	/**
	* Отправка письма
	*
	* @param string $mailTo - получатель письма
	* @param string $subject - тема письма
	* @param string $message - тело письма
	* @param string $headers - заголовки письма
	*
	* @return bool|string В случаи отправки вернет true, иначе текст ошибки *
	*/
	function send($mailTo, $subject, $message, $headers) {
		$contentMail = "Date: " . date("D, d M Y H:i:s") . " UT\r\n";
		$contentMail .= 'Subject: =?' . $this->smtp_charset . '?B?' . base64_encode($subject) . "=?=\r\n";
		$contentMail .= $headers . "\r\n";
		$contentMail .= $message . "\r\n";
		try {
			if(!$socket = @fsockopen($this->smtp_host, $this->smtp_port, $errorNumber, $errorDescription, 30)){
				throw new Exception($errorNumber.".".$errorDescription);
			}
			if (!$this->_parseServer($socket, "220")){
				throw new Exception('Connection error');
			}
			fputs($socket, "EHLO " . $this->smtp_host . "\r\n");
			if (!$this->_parseServer($socket, "250")) {
				fclose($socket);
				throw new Exception('Error of command sending: HELO');
			}
			fputs($socket, "AUTH LOGIN\r\n");
			if (!$this->_parseServer($socket, "334")) {
				fclose($socket);
				throw new Exception('Autorization error1');
			}
			fputs($socket, base64_encode($this->smtp_username) . "\r\n");
			if (!$this->_parseServer($socket, "334")) {
				fclose($socket);
				throw new Exception('Autorization error2');
			}
			fputs($socket, base64_encode($this->smtp_password) . "\r\n");
			if (!$this->_parseServer($socket, "235")) {
				fclose($socket);
				throw new Exception('Autorization error3');
			}
			fputs($socket, "MAIL FROM: ".$this->smtp_username."\r\n");
			if (!$this->_parseServer($socket, "250")) {
				fclose($socket);
				throw new Exception('Error of command sending: MAIL FROM');
			}
			fputs($socket, "RCPT TO: " . $mailTo . "\r\n");
			if (!$this->_parseServer($socket, "250")) {
				fclose($socket);
				throw new Exception('Error of command sending: RCPT TO');
			}
			fputs($socket, "DATA\r\n");
			if (!$this->_parseServer($socket, "354")) {
				fclose($socket);
				throw new Exception('Error of command sending: DATA');
			}
			fputs($socket, $contentMail."\r\n.\r\n");
			if (!$this->_parseServer($socket, "250")) {
				fclose($socket);
				throw new Exception("E-mail didn't sent");
			}
			fputs($socket, "QUIT\r\n");
			fclose($socket);
		} catch (Exception $e) {
			return $e->getMessage();
		}
		return true;
	}
	private function _parseServer($socket, $response) {
		while (@substr($responseServer, 3, 1) != ' ') {
			if (!($responseServer = fgets($socket, 256))) {
				return false;
			}
		}
			if (!(substr($responseServer, 0, 3) == $response)) {
				return false;
			}
			return true;
	}
}

class TOKEN extends SETTINGS{
	function generator($id){
			  if(stristr($_SERVER['HTTP_USER_AGENT'], 'Firefox')){$browser = 'firefox';
		}else if(stristr($_SERVER['HTTP_USER_AGENT'], 'Chrome')){$browser = 'chrome';
		}else if(stristr($_SERVER['HTTP_USER_AGENT'], 'Safari')){$browser = 'safari';
		}else if(stristr($_SERVER['HTTP_USER_AGENT'], 'Opera')){$browser = 'opera';
		}else if(stristr($_SERVER['HTTP_USER_AGENT'], 'MSIE 6.0')){$browser = 'ie6';
		}else if(stristr($_SERVER['HTTP_USER_AGENT'], 'MSIE 7.0')){$browser = 'ie7';
		}else if(stristr($_SERVER['HTTP_USER_AGENT'], 'MSIE 8.0')){$browser = 'ie8';
		}else{
			$browser='qwerty';
		}
		$seckret_key[0]='AMvM2L08VDdmdIbuPfOmgn66lPETTgpb';//СЕКРЕТНЫЙ КЛЮЧ 1
		$seckret_key[1]='Belko.CMS';//СЕКРЕТНЫЙ КЛЮЧ 2
		$hash = strtoupper(md5(md5(md5(md5($id).md5($browser.$seckret_key[0])).md5($seckret_key[1]))));//32символа
		return $hash;
	}

	function verify($token, $cookie_token){
		if($token!=''){
			if($cookie_token!=''){
				if($token==$cookie_token){
					return;
				}else{return $this->REFRESH($this->ROOT().'/logout.php');}
			}else{return;}
		}else{return;}
	}
	
	function captcha($captcha, $session){
			if($captcha == $session){
				$captcha = '';
				$session = '';
				return 'ok';
			}else if($captcha == ''){
				return 'error not available';//каптча отсутствует
			}else{
				return 'error not correct';//каптча не введена
			}
	}

}

class AUTH{
	function add($login, $email, $password, $timestamp){
		global $mysqli;
		$salt = '';
		$sql = $mysqli->query("INSERT INTO `user` (`id`, `login`, `password`, `salt`, `mail_reg`, `mail`, `last_act`, reg_date`) VALUES (NULL, '$login', '$password', '$salt', '$email', '$email', '$timestamp', '$timestamp')");
		$id = $mysqli->insert_id;
		return $id;
	}
	
	function INFO($name){
		global $mysqli;
		$id_user = $this->session('id');
		if($id_user!=''){
			$sql = $mysqli->query("SELECT * FROM `auth` WHERE `id` = $id_user")->fetch_assoc();
			return $sql[$name];
		}else{
			return false;
		}
	}
	
	function USER_INFO($name){
		global $mysqli;
		$id_user = $this->session('id');
		if($id_user!=''){
			$sql = $mysqli->query("SELECT * FROM `user` WHERE `id` = $id_user")->fetch_assoc();
			return $sql[$name];
		}else{
			return false;
		}
	}
	
	function cookie($name, $val=0){
		if($val==0){
			if(!empty($_COOKIE[$name])){
				return $_COOKIE[$name];
			}else{
				return '';
			}
		}else if($val=='empty'){
			if($_COOKIE[$name] != ''){
				return true;
			}else{
				return false;
			}
		}
	}
	
	function session($name, $val=0){
		if($val==0){
			if(!empty($_SESSION[$name])){
				return $_SESSION[$name];
			}else{
				return '';
			}
		}else if($val=='empty'){
			if($_SESSION[$name] != ''){
				return true;
			}else{
				return false;
			}
		}
	}
}

/***************/
function object_to_array($data)
{
    if (is_array($data) || is_object($data))
    {
        $result = array();
        foreach ($data as $key => $value)
        {
            $result[$key] = object_to_array($value);
        }
        return $result;
    }
    return $data;
}
/***************/

/***************/
function str_text_explode($text){
	$t = explode(",",$text);
	for($i=0;$i<count($t);$i++){
		$s[$i] = strpbrk($t[$i], "-");
		if($s[$i]!=''){
			$t2[$i] = explode("-",$t[$i]);
			for($q=$t2[$i][0];$q<$t2[$i][1]+1;$q++){
				$ext[$i].='"'.$q.'",';
				//echo $q.'<br>';
			}
			$ext[$i] = substr($ext[$i],0,-1);
			//echo $t[$i].'<br>';
		}else{
			$t2[$i] = $t[$i];
			$ext[$i] = '"'.$t[$i].'"';
			//echo $t[$i].'<br>';
		}
	}
	return $ext;
}
function toArray($data){
    if(is_array($data) || is_object($data)){
        $result = array();
        foreach($data as $key => $value){
            $result[$key] = toArray($value);
        }
        return $result;
    }
    return $data;
}
function toObject(array $ar){
  return json_decode(json_encode($ar));
}
/***************/

function registrationCorrect() {
	if ($_POST['login'] == "") return false; //не пусто ли поле логина 	
	if ($_POST['password'] == "") return false; //не пусто ли поле пароля
	if ($_POST['password2'] == "") return false; //не пусто ли поле подтверждения пароля
	if ($_POST['email'] == "") return false; //не пусто ли поле e-mail
	if ($_POST['terms'] != True) return false; //приняты ли правила
	if (!preg_match('/^([a-z0-9])(\w|[.]|-|_)+([a-z0-9])@([a-z0-9])([a-z0-9.-]*)([a-z0-9])([.]{1})([a-z]{2,4})$/is', $_POST['mail'])) return false; //соответствует ли поле e-mail регулярному выражению
	if (!preg_match('/^([a-zA-Z0-9])(\w|-|_)+([a-z0-9])$/is', $_POST['login'])) return false; // соответствует ли логин регулярному выражению
	if (strlen($_POST['password']) < 5) return false; //не меньше ли 5 символов длина пароля
 	if ($_POST['password'] != $_POST['password2']) return false; //равен ли пароль его подтверждению
	$login = $_POST['login'];
	$rez = mysql_query("SELECT * FROM users WHERE login=$login");
	if (@mysql_num_rows($rez) != 0) return false; // проверка на существование в БД такого же логина
	return true; //если выполнение функции дошло до этого места, возвращаем true }
}
?>