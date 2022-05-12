<?php
session_start();
if (file_exists("./inc/init.php")){
	require_once "./inc/init.php";
} else {
	die("error");
}

if (isset($_SESSION["data"])) {
    header("Location: index.php");
}

function clear($string){
	$var = trim($string);
	$var = addslashes($var);
	return $var;
}

function redirect($redir){
	die("<script>window.location.href = '$redir';</script>");
}

$msg = "";

if (getenv("REQUEST_METHOD") == "POST"){
	$user = isset($_POST["usuario"]) ? clear($_POST["usuario"]) : "";
	$senha = isset($_POST["senha"]) ? clear($_POST["senha"]) : "";
	$senha256 = getPasswordHash($senha);
	
	$user_minusculo = strtolower($user);
	$userfinal = ucfirst($user_minusculo);
	if (!preg_match('/^[A-Za-z0-9-]+$/', $userfinal)){
		$msg = "Nome de usuário inválido. Não use caracteres nem espaços em seu nome de usuário.";
	} else {
	    $sql = "select * from users where user = '$userfinal' and pass = '$senha256'";
	    $db = mysqli_connect(SERVIDOR, USUARIO, SENHA, BANCO) or die(mysqli_error($db));
	
	    $r = mysqli_query($db, $sql) or die(mysqli_error($db));
	    if (mysqli_num_rows($r) > 0){
	    	$r = mysqli_query($db, "select * from users where user = '$userfinal' and pass = '$senha256'") or die(mysqli_error($db));
	    	$result = mysqli_fetch_array($r);
			$priv = $result["privLevel"];
	    	if ($priv > 0){
			    $data = array();
			    $data["user"] = $userfinal;
			    $data["senha"] = getPasswordHash($senha);
				$data["privLevel"] = $priv;
			    $_SESSION["data"] = $data;
			    //if (isset($_POST["cookie"])){
			    //	setcookie("data", serialize($data), time()+60*60*24*365);
			    //}
				redirect("index.php");
		    	//echo "<script>window.location.href = 'index.php';</script>";
		    } else {
		    	$msg = "Você não tem permissão para fazer login.";
		    }
	    } else {
	    	$msg = "Usuário ou senha incorreto.";
	    }
	}
}
?>
<!DOCTYPE html>
<html lang="pt">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
	<title>Painel - Login</title>
	<link rel="stylesheet" type="text/css" href="./inc/css/css.css" media="screen" />
</head>
<body>
    <div class="painelArea">
	    <div class="pArea">
		    <div class="loginBox">
			    <div class="loginBox-header">
				    <h3>
				        <b>Login</b>
				    </h3>
				</div>
			    <div class="loginBox-body">
				    <form id="subpost" method="POST">
					    <label>
						    <input type="text" class="input-box" name="usuario" id="usuario" placeholder="Usuário" required>
							<input type="password" class="input-box" name="senha" id="senha" placeholder="Senha" required>
						</label>
						<?php if (isset($msg)){ ?>
						<span style="color:#ff0000"><?php echo $msg ?></span>
						<?php } ?>
						<button class="sButtom preto" type="submit">Entrar</button>
						<center>
						    <small>Não possui uma conta? <a href="register.php">Registre-se</a></small>
						</center>
					</form>
				</div>
			</div>
		</div>
	</div>
</body>
</html>