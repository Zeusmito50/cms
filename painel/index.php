<?php
if (file_exists("./inc/init.php")){
    require_once "./inc/init.php";
} else {
    die("error");
}
require_once "./inc/security.php";

$data = isset($_SESSION["data"]) ? $_SESSION["data"] : unserialize($_COOKIE["data"]);
if (!$data){
    header("Location: login.php");
}

$url = PROJECT_URL;

$categories = array();
$okay = false;

if ($data){
	if ($data["privLevel"] > 1){
	    $sql_category = "select * from category order by name ASC";
		$sql_check = "select * from users where user = '" . $data['user'] . "' and pass = '" . $data['senha'] . "' and privLevel > 1";
	
	    $db = mysqli_connect(SERVIDOR, USUARIO, SENHA, BANCO) or die(mysqli_error($db));
		mysqli_set_charset($db, "utf8mb4");
	        
		$check = mysqli_query($db, $sql_check) or die(mysqli_error($db));
			
		if (mysqli_num_rows($check) > 0){
			$okay = true;
			$cat = mysqli_query($db, $sql_category) or die(mysqli_error($db));
	        foreach ($cat as $_rowc) {
	        	$categories[$_rowc["id"]] = $_rowc["name"];
            }
		}
    }
}

if ($okay){
	$main_content = "";
	$title_page = "Página inicial";
	if (isset($_GET["p"])){
		ob_start();
		if ($_GET["p"] == "newpost"){
			$title_page = "Nova postagem";
			require_once("./requires/newpost.php");
		}
		$main_content = ob_get_contents();
		ob_end_clean();
	}
}

function get_category($_id, $cats) {
	foreach ($cats as $_row => $_value) {
		if ($_row == $_id) {
			return $_value;
		}
	}
}
?>
<!DOCTYPE html>
<html lang="pt">
<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1">
	<title>Painel - CMS</title>
	<link rel="shortcut icon" href="<?php echo PROJECT_URL ?>/images/favicon.png" type="image/x-icon" />

    <link href="https://cdn.jsdelivr.net/npm/froala-editor@latest/css/froala_editor.pkgd.min.css" rel="stylesheet" type="text/css" /> 

    <style media="all">
	<?php require "./inc/css/css.css"; ?>
	</style>
</head>
<body>
	<header class="header">
		<div class="container">
		    <div class="header-fixed">
			    <span onclick="window.location='./'">Painel</span>
				<div style="float: right;">
				    <a href="sair.php">Sair</a>
				</div>
			</div>
		</div>
	</header>
	<div class="column">
        <nav class="sidebar">
			<ul>
				<li>
					<a href="./">Página inicial</a>
				</li>
				<li>
					<a href="./?p=newpost">Nova postagem</a>
				</li>
				<li>
					<a href="#">Postagens</a>
				</li>
				<li>
					<a href="#">Categorias</a>
				</li>
				<li>
					<a href="#">Tags</a>
				</li>
			</ul>
		</nav>
	</div>
	<div class="row">
        <div class="content">
        	<div class="title">
			    <span><?php echo $title_page; ?></span>
		    </div>
		    <div id="main-content">
		        <?php echo $main_content; ?>
		    </div>
        </div>
	</div>
<script src="./js/jquery-2.2.4.js"></script>
<script type="text/javascript" src="https://cdn.jsdelivr.net/npm/froala-editor@latest/js/froala_editor.pkgd.min.js"></script>
<script>
    var editor = new FroalaEditor('#editor', {
    	imageUpload: true,
    	imageUploadURL: "./assets/upload_image.php",
    	imageMaxSize: 5 * 1024 * 1024,
    	imageDefaultWidth: 250,
        imageAllowedTypes: ['jpeg', 'jpg', 'png'],

        placeholderText: "Digite aqui o texto..."
    });
    $(window).on("load", function(){
        $("#fr-logo").hide();
    });

    $("#newpost").submit(function(e) {
    	e.preventDefault();
        var text = $(".fr-view").html();
        var ok = false;
        if ($(".fr-code-view")[0]) {
        	alert("feche o código para continuar");
        } else {
        	if (strip_tags(text).length < 1){
        		alert("Digite algo para publicar");
        	} else {
                ok = true;
        	}
        }
        if (ok){
        	var $form = $(this),
        	    post_url = $form.find("input[name='post_url']").val();
        	    title = $form.find("input[name='title']").val(),
        	    content = $form.find("textarea").val(),
        	    url = $form.attr("action");

        	$.ajax({
		    	method: "POST",
		    	url: url,
		    	data: {
                    post_url: post_url,
		    		title: title,
		    		content: content
		    	}
		    })
		    .done(function(msg){
		    	alert(msg);
		    });
        }
    });

    function strip_tags(str){
    	str = str.toString();
        return str.replace(/<\/?[^>]+>/gi, '').replace(/&nbsp;/g, '').replace(/\s/g, '');
    }
</script>
</body>
</html>

