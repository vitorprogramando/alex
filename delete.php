<?php
$mysqli = new mysqli("localhost", "root", "", "file_manager");

if ($mysqli->connect_error) {
    die("Connection failed: " . $mysqli->connect_error);
}

if (isset($_GET['id'])) {
    $id = $_GET['id'];
    $stmt = $mysqli->prepare("SELECT filename FROM files WHERE id = ?");
    $stmt->bind_param("i", $id);
    $stmt->execute();
    $stmt->store_result();
    
    if ($stmt->num_rows > 0) {
        $stmt->bind_result($filename);
        $stmt->fetch();
        
        // Exclua o registro do banco de dados
        $deleteStmt = $mysqli->prepare("DELETE FROM files WHERE id = ?");
        $deleteStmt->bind_param("i", $id);
        $deleteStmt->execute();

        // Verifique se o arquivo foi excluído do banco de dados
        if ($deleteStmt->affected_rows > 0) {
            // Defina o caminho completo do arquivo
            $uploadDir = __DIR__ . "/uploads/";
            $filenameEncoded = rawurlencode($filename);
            $filePath = $uploadDir . $filenameEncoded;

            // Verifique se o arquivo existe antes de tentar excluí-lo
            if (file_exists($filePath)) {
                // Exclua o arquivo do sistema de arquivos
                if (unlink($filePath)) {
                    $referer = $_SERVER['HTTP_REFERER'];
                    header("Location: $referer");
                    exit();
                } else {
                    die("Erro ao excluir o arquivo.");
                }
            } else {
                // Se o arquivo não existe, redirecione de volta para a página anterior
                $referer = $_SERVER['HTTP_REFERER'];
                header("Location: $referer");
                exit();
            }
        } else {
            die("Erro ao excluir o registro do banco de dados.");
        }
    } else {
        die("Arquivo não encontrado.");
    }
    
    $stmt->close();
    $deleteStmt->close();
}

$mysqli->close();
?>
