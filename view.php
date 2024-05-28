<?php
$mysqli = new mysqli("localhost", "root", "", "file_manager");

if ($mysqli->connect_error) {
    die("Connection failed: " . $mysqli->connect_error);
}

if (isset($_GET['id'])) {
    $id = $_GET['id'];
    $result = $mysqli->query("SELECT * FROM files WHERE id = $id");
    $file = $result->fetch_assoc();
} else {
    die("Arquivo não encontrado.");
}

$mysqli->close();
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Visualização do Documento</title>
    <link rel="stylesheet" href="css/styles.css">
</head>
<body>
    <h1>Visualização de Documento</h1>
    <p><strong>Documento:</strong> <?= htmlspecialchars($file['filename']) ?></p>
    <p><strong>Descrição:</strong> <?= htmlspecialchars($file['description']) ?></p>
    <p><strong>Código:</strong> <?= htmlspecialchars($file['uploaded_at']) ?></p>
    <a href="index.php"><input type="button" value="Voltar"></a>
</body>
</html>
