<?php
session_start();
if (!isset($_SESSION['logado'])) {
    header('Location: index.php');
    exit();
}

$arquivo = 'shows.json';
$shows = file_exists($arquivo) ? json_decode(file_get_contents($arquivo), true) : [];
?>

<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <title>Modificar Shows</title>
    <link rel="stylesheet" href="style.css"> <!-- CSS externo -->
</head>
<body>
    <div class="container">
        <h2>Modificar Shows</h2>
        <ul class="show-list">
            <?php foreach ($shows as $index => $show): ?>
                <li class="show-item">
                    <?= htmlspecialchars($show['nome']) ?> - <?= $show['data'] ?>
                    <a href="editar.php?id=<?= $index ?>" class="edit-link">Editar</a>
                </li>
            <?php endforeach; ?>
        </ul>
        <a href="painel.php" class="back-link">Voltar</a>
    </div>
</body>
</html>
