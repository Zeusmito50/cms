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
	$email = isset($_POST["email"]) ? clear($_POST["email"]) : "";
	$user = isset($_POST["usuario"]) ? clear($_POST["usuario"]) : "";
	$senha = isset($_POST["senha"]) ? clear($_POST["senha"]) : "";
	$senha2 = isset($_POST["senha2"]) ? clear($_POST["senha2"]) : "";
	$senha256 = getPasswordHash($senha);
	$priv = 1;
	
	$emailfinal = strtolower($email);
	$user_minusculo = strtolower($user);
	$userfinal = ucfirst($user_minusculo);
	
	if (!filter_var($email, FILTER_VALIDATE_EMAIL)){
		$msg = "$email não é um endereço de e-mail válido.";
	} else if (!preg_match('/^[A-Za-z0-9-]+$/', $userfinal)){
		$msg = "Nome de usuário inválido. Não use caracteres nem espaços em seu nome de usuário.";
    } else if ($senha2 != $senha){
	    $msg = "As duas senhas devem ser idênticas.";
	} else {
	    $sql_user = "select * from users where user = '$userfinal'";
		$sql_email = "select * from users where email = '$emailfinal'";
		$sql_insert = "insert into users (user, pass, email, privLevel) values ('$userfinal', '$senha256', '$emailfinal',  '$priv')";
	    $db = mysqli_connect(SERVIDOR, USUARIO, SENHA, BANCO) or die(mysqli_error($db));
		
		$check_user = mysqli_query($db, $sql_user) or die(mysqli_error($db));
		$check_email = mysqli_query($db, $sql_email) or die(mysqli_error($db));
	    
		if (mysqli_num_rows($check_email) > 0){
			$msg = "Este e-mail já foi registrado.";
		} else if (mysqli_num_rows($check_user) > 0){
			$msg = "Este nome de usuário já foi registrado.";
		} else {
			if (mysqli_query($db, $sql_insert) or die(mysqli_error($db))){
	    		$r = mysqli_query($db, "select * from users where user = '$userfinal' and pass = '$senha256'") or die(mysqli_error($db));
	    		$result = mysqli_fetch_array($r);
				$priv = $result["privLevel"];
	    		if ($priv > 0){
				    $data = array();
			 	    $data["user"] = $userfinal;
			  	    $data["senha"] = getPasswordHash($senha);
					$data["privLevel"] = $priv;
			  	    $_SESSION["data"] = $data;
			   	  //  if (isset($_POST["cookie"])){
			   		// 	setcookie("data", serialize($data), time()+60*60*24*365);
			       // }
		    	    redirect("index.php");
		        } else {
		            $msg = "Você não tem permissão para fazer login.";
		        }
			}
		}
	}
}
?>
<!DOCTYPE html>
<html lang="pt">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
	<title>Painel - Registro</title>
	<link rel="stylesheet" type="text/css" href="./inc/css/css.css" media="screen" />
</head>
<body>
    <div class="painelArea">
	    <div class="pArea">
		    <div class="loginBox">
			    <div class="loginBox-header">
				    <h3>
				        <b>Registre-se</b>
				    </h3>
				</div>
			    <div class="loginBox-body">
				    <form id="subpost" method="POST">
					    <label>
							<input type="text" class="input-box" name="email" id="email" placeholder="E-Mail Address" required>
							<input type="text" class="input-box" name="usuario" id="usuario" placeholder="Usuário" required>
							<input type="password" class="input-box" name="senha" id="senha" placeholder="Senha" required>
							<input type="password" class="input-box" name="senha2" id="senha2" placeholder="Digite a senha novamente" required>
						</label>
						<?php if (isset($msg)){ ?>
						<span style="color:#ff0000"><? echo $msg ?></span>
						<?php } ?>
						<button class="sButtom preto" type="submit">Registrar</button>
						<center>
						    <small>Já tem uma conta? <a href="login.php">Login</a></small>
						</center>
					</form>
				</div>
			</div>
		</div>
	</div>
</body>
</html>