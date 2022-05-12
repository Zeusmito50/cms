<?php
namespace inc;

if (file_exists("inc/config.php")){
    require_once "inc/config.php";
} else {
    die("Erro: nao foi possível encontrar o arquivo config.php");
}	

class php {	
	public $ip;
	public $db;
	
	public function __construct() {
		if ($_SERVER["HTTP_USER_AGENT"] == "") {
			die("Parece que você está tentando fazer uma conexão que nosso servidor não permite, tente novamente mais tarde.");
			return;
		}
		
		if (!empty($_SERVER['HTTP_CLIENT_IP'])) {
			$ip = $_SERVER['HTTP_CLIENT_IP'];
		} else if (!empty($_SERVER['HTTP_X_FORWARDED_FOR'])) {
			$ip = $_SERVER['HTTP_X_FORWARDED_FOR'];
		} else {
			$ip = $_SERVER['REMOTE_ADDR'];
		}
		$this->ip = $ip;

		if ($this->db == null){
			mysqli_report(MYSQLI_REPORT_ERROR | MYSQLI_REPORT_STRICT);
			$this->db = new \mysqli(SERVIDOR, USUARIO, SENHA, BANCO) or die(mysqli_error($this->db));
		}

		foreach ($_GET as $_key => $_value) {
			$_GET[$_key] = $this->_escape_str($_value);
		}

		foreach ($_POST as $_key => $_value) {
			$_POST[$_key] = $this->_escape_str($_value);
		}
		
		foreach ($_COOKIE as $_key => $_value) {
			$_COOKIE[$_key] = $this->_escape_str($_value);
		}
		
		foreach ($_REQUEST as $_key => $_value) {
			$_REQUEST[$_key] = $this->_escape_str($_value);
		}
	}
	
	function _contains_word($str, $word) {
		return !!preg_match('#\\b' . preg_quote($word, '#') . '\\b#i', $str);
	}
	
	public function _escape_str($string) {
		return addslashes(strip_tags(trim($string)));
	}

	public function _display_php_errors() {
		ini_set("display_errors", 1);
		ini_set("display_startup_erros", 1);
		error_reporting(E_ALL);
	}
}

?>