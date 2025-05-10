<?php
$jsonFile = 'shows.json';
$shows = json_decode(file_get_contents($jsonFile), true);

// Remover item
if (isset($_GET['remove'])) {
    $index = (int)$_GET['remove'];
    if (isset($shows[$index])) {
        array_splice($shows, $index, 1);
        file_put_contents($jsonFile, json_encode($shows, JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE));
        header("Location: admin.php");
        exit;
    }
}

// Adicionar item
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $novoShow = [
        "nome" => $_POST['nome'],
        "data" => $_POST['data'],
        "horario" => $_POST['horario'],
        "descricao" => $_POST['descricao'],
        "preco" => $_POST['preco'],
        "imagem" => $_POST['imagem']
    ];

    $shows[] = $novoShow;
    file_put_contents($jsonFile, json_encode($shows, JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE));
    header("Location: admin.php");
    exit;
}
?>

<!DOCTYPE html>
<html lang="pt-br">
<head>
    <meta charset="UTF-8">
    <title>Administração de Próximos shows</title>
    <link rel="stylesheet" href="admin.css">

</head>
<body>
    <h1>Painel de Administração de Próximos Shows</h1>

    <?php foreach ($shows as $index => $show): ?>
        <div class="show">
            <strong><?= htmlspecialchars($show['nome']) ?></strong><br>
            Data: <?= htmlspecialchars($show['data']) ?><br>
            Horário: <?= htmlspecialchars($show['horario']) ?><br>
            Descrição: <?= htmlspecialchars($show['descricao']) ?><br>
            Preço: <?= htmlspecialchars($show['preco']) ?><br>
            <img src="<?= htmlspecialchars($show['imagem']) ?>" alt="Imagem" width="100"><br>
            <a href="?remove=<?= $index ?>" class="remove" onclick="return confirm('Deseja remover este show?')">Remover</a>
        </div>
    <?php endforeach; ?>

    <h2>Adicionar Novo Show</h2>
    <form method="post">
        <input type="text" name="nome" placeholder="Nome do Show" required>
        <input type="text" name="data" placeholder="Data (ex: 10 de Junho)" required>
        <input type="text" name="horario" placeholder="Horário (ex: 20:00)" required>
        <textarea name="descricao" placeholder="Descrição" required></textarea>
        <input type="text" name="preco" placeholder="Preço (ex: R$ 30,00)" required>
        <input type="text" name="imagem" placeholder="URL da Imagem" required>
        <button type="submit">Adicionar Show</button>
    </form>
</body>
</html>
