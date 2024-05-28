<?php
$mysqli = new mysqli("localhost", "root", "", "file_manager");

if ($mysqli->connect_error) {
    die("Connection failed: " . $mysqli->connect_error);
}

$search = $_GET['search'] ?? '';
$sql = "SELECT * FROM files WHERE filename LIKE ? OR description LIKE ? ORDER BY uploaded_at DESC";
$stmt = $mysqli->prepare($sql);
$searchTerm = '%' . $search . '%';
$stmt->bind_param("ss", $searchTerm, $searchTerm);
$stmt->execute();
$result = $stmt->get_result();
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Documentos</title>
    <link rel="stylesheet" href="css/styles.css">
</head>
<body>
    <h1>Documentos</h1>
    <form action="index.php" method="get">
        <input type="text" name="search" value="<?= htmlspecialchars($search) ?>" placeholder="Pesquisar Documentos...">
        <button type="submit">Pesquisar</button>
        <a href="index.php"><button type="button">Limpar Filtro</button></a>
        <br>
        <br>
    </form>
    <a href="upload.php"><button type="button">Adicionar novo Documento</button></a>
    <br>
    <br>
    <table>
        <tr>
            <th>ID</th>
            <th>Documento</th>
            <th>Descrição</th>
            <th>Ações</th>
        </tr>
        <?php while ($row = $result->fetch_assoc()): ?>
            <tr>
                <td><?= htmlspecialchars($row['id']) ?></td>
                <td><?= htmlspecialchars($row['filename']) ?></td>
                <td><?= htmlspecialchars($row['description']) ?></td>
                <td>
                    <a href="view.php?id=<?= $row['id'] ?>"><button>Visualizar</button></a>
                    <a href="edit.php?id=<?= $row['id'] ?>"><button>Editar</button></a>
                    <a href="delete.php?id=<?= $row['id'] ?>"><button>Excluir</button></a>
                    <a href="download.php?id=<?= $row['id'] ?>"><button>Baixar</button></a>
                </td>
            </tr>
        <?php endwhile; ?>
    </table>
    <style>
        
    </style>
</body>
</html>
<?php
$stmt->close();
$mysqli->close();
?>
