<?php

if (file_exists("../inc/config.php")){
    require_once "../inc/config.php";
} else {
    die("Erro: Arquivo config.php nao localizado");
}   

if ($_SERVER["REQUEST_METHOD"] == "POST"){
	$post_url = $_POST["post_url"];
    $title = $_POST["title"];
    $content = $_POST["content"];

    $db = mysqli_connect(SERVIDOR, USUARIO, SENHA, BANCO) or die(mysqli_error($db));
    mysqli_set_charset($db, "utf8mb4");

    $query_insert = "INSERT INTO posts (link, title, content) VALUES ('$post_url', '$title', '$content')";
    if (mysqli_query($db, $query_insert)){
        echo "Publicado!";
    } else {
        echo "Erro ao publicar: " . mysqli_error($db);
    }
}

?>