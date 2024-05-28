<?php
$mysqli = new mysqli("localhost", "root", "", "file_manager");

if ($mysqli->connect_error) {
    die("Connection failed: " . $mysqli->connect_error);
}

if (isset($_GET['id'])) {
    $id = $_GET['id'];
    $stmt = $mysqli->prepare("SELECT filename, filedata FROM files WHERE id = ?");
    $stmt->bind_param("i", $id);
    $stmt->execute();
    $stmt->store_result();
    $stmt->bind_result($filename, $filedata);
    $stmt->fetch();
    
    if ($stmt->num_rows > 0) {
        header('Content-Description: File Transfer');
        header('Content-Type: application/pdf');
        header('Content-Disposition: attachment; filename="'.basename($filename).'"');
        header('Content-Length: ' . strlen($filedata));
        echo $filedata;
        exit;
    } else {
        die("Arquivo não encontrado.");
    }
    
    $stmt->close();
}

$mysqli->close();
?>
