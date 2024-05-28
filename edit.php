<?php
$mysqli = new mysqli("localhost", "root", "", "file_manager");

if ($mysqli->connect_error) {
    die("Connection failed: " . $mysqli->connect_error);
}

if (isset($_GET['id'])) {
    $id = $_GET['id'];
    $result = $mysqli->query("SELECT * FROM files WHERE id = $id");
    $file = $result->fetch_assoc();
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $id = $_POST['id'];
    $description = $mysqli->real_escape_string($_POST['description']);

    $stmt = $mysqli->prepare("UPDATE files SET description = ? WHERE id = ?");
    $stmt->bind_param("si", $description, $id);
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
    <title>Edit File</title>
    <link rel="stylesheet" href="css/styles.css">
</head>
<body>
    <h1>Edit File Description</h1>
    <form action="edit.php" method="post">
        <input type="hidden" name="id" value="<?= $file['id'] ?>">
        <label for="description">Description:</label>
        <textarea name="description" id="description" required><?= htmlspecialchars($file['description']) ?></textarea>
        <br>
        <button type="submit">Save</button>
    </form>
    <style>
        
    </style>
</body>
</html>
