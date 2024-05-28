<?php
$mysqli = new mysqli("localhost", "root", "", "file_manager");

if ($mysqli->connect_error) {
    die("Connection failed: " . $mysqli->connect_error);
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $description = $mysqli->real_escape_string($_POST['description']);
    $file = $_FILES['file'];

    if ($file['error'] !== UPLOAD_ERR_OK) {
        die("Erro no upload do arquivo.");
    }

    $filename = basename($file['name']);
    $filedata = file_get_contents($file['tmp_name']);

    $stmt = $mysqli->prepare("INSERT INTO files (filename, description, filedata) VALUES (?, ?, ?)");
    $stmt->bind_param("sss", $filename, $description, $filedata);

    if ($stmt->execute()) {
        header("Location: index.php");
        exit();
    } else {
        die("Erro na execução da consulta SQL: " . $stmt->error);
    }
    
    $stmt->close();
}

$mysqli->close();
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Cadastro de Documentos</title>
    <link rel="stylesheet" href="css/styles.css">
</head>
<body>
    <h1>Cadastrar Documentos</h1>
    <form action="upload.php" method="post" enctype="multipart/form-data">
        <label for="file">Arquivo:</label>
        <br>
        <input type="file" name="file" id="file" accept="application/pdf" required>
        <br>
        <br>
        <label for="description">Descrição:</label>
        <br>
        <textarea name="description" id="description" required></textarea>
        <br>
        <br>
        <button type="submit">Salvar</button>
    </form>
    <br>
    <a href="index.php"><button type="button">Visualizar Documentos</button></a>
    <a href="index.php"><button type="button">Voltar para o inicio</button></a>
    <style>
        
    </style>
</body>
</html>
