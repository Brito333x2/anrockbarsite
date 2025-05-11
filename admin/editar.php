<?php 
session_start();
if (!isset($_SESSION['logado'])) {
    header('Location: index.php');
    exit();
}

$arquivo = 'shows.json';
$shows = file_exists($arquivo) ? json_decode(file_get_contents($arquivo), true) : [];

if (!isset($_GET['id']) || !isset($shows[$_GET['id']])) {
    echo "Show não encontrado.";
    exit();
}

$id = $_GET['id'];
$show = $shows[$id];

// ✅ Se for pedido de exclusão
if (isset($_POST['excluir'])) {
    unset($shows[$id]);
    $shows = array_values($shows); // Reindexar
    file_put_contents($arquivo, json_encode($shows, JSON_PRETTY_PRINT));
    header('Location: modificar.php');
    exit();
}

// ✅ Se for edição
if ($_SERVER['REQUEST_METHOD'] === 'POST' && !isset($_POST['excluir'])) {
    $shows[$id] = [
        "nome" => $_POST['nome'],
        "data" => $_POST['data'],
        "horario" => $_POST['horario'],
        "descricao" => $_POST['descricao'],
        "preco" => $_POST['preco'],
        "imagem" => $_POST['imagem'],
        "geral" => isset($_POST['geral']) ? true : false
    ];

    file_put_contents($arquivo, json_encode($shows, JSON_PRETTY_PRINT));
    header('Location: modificar.php');
    exit();
}
?>

<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <title>Editar Show</title>
    <link rel="stylesheet" href="style.css"> <!-- CSS externo -->
</head>
<body>
    <div class="container">
        <h2>Editar Show</h2>
        <form method="POST" class="edit-form">
            <input name="nome" placeholder="Nome do Show" value="<?= htmlspecialchars($show['nome']) ?>" required><br>
            <input name="data" placeholder="Data" value="<?= htmlspecialchars($show['data']) ?>" required><br>
            <input name="horario" placeholder="Horário" value="<?= htmlspecialchars($show['horario']) ?>" required><br>
            <input name="descricao" placeholder="Descrição" value="<?= htmlspecialchars($show['descricao']) ?>"><br>
            <input name="preco" placeholder="Preço" value="<?= htmlspecialchars($show['preco']) ?>" required><br>
            <input name="imagem" placeholder="URL da imagem" value="<?= htmlspecialchars($show['imagem']) ?>"><br>
            <label><input type="checkbox" name="geral" <?= $show['geral'] ? 'checked' : '' ?>> Evento Geral</label><br>
            <button type="submit">Salvar Alterações</button>
        </form>

        <!-- 🔴 Botão de exclusão -->
        <form method="POST" onsubmit="return confirm('Tem certeza que deseja excluir este show?');" class="delete-form">
            <input type="hidden" name="excluir" value="1">
            <button type="submit" class="delete-button">Excluir Show</button>
        </form>

        <a href="modificar.php" class="cancel-link">Cancelar</a>
    </div>
</body>
</html>
