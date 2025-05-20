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
    // Exclui imagem antiga
    if (!empty($show['imagem']) && file_exists($show['imagem'])) {
        unlink($show['imagem']);
    }

    unset($shows[$id]);
    $shows = array_values($shows); // Reindexar
    file_put_contents($arquivo, json_encode($shows, JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE));
    header('Location: modificar.php');
    exit();
}

// ✅ Se for edição
if ($_SERVER['REQUEST_METHOD'] === 'POST' && !isset($_POST['excluir'])) {
    $imagemAtual = $show['imagem'];

    // Se foi enviado um novo arquivo
    if (isset($_FILES['imagem']) && $_FILES['imagem']['error'] === UPLOAD_ERR_OK) {
        $pastaUpload = 'uploads/';
        if (!is_dir($pastaUpload)) {
            mkdir($pastaUpload, 0755, true);
        }

        // Excluir imagem antiga
        if (!empty($imagemAtual) && file_exists($imagemAtual)) {
            unlink($imagemAtual);
        }

        $extensao = pathinfo($_FILES['imagem']['name'], PATHINFO_EXTENSION);
        $novaImagem = $pastaUpload . uniqid('show_') . '.' . $extensao;
        move_uploaded_file($_FILES['imagem']['tmp_name'], $novaImagem);
    } else {
        $novaImagem = $imagemAtual; // Mantém a antiga se não enviou nova
    }

    $shows[$id] = [
        "nome" => $_POST['nome'],
        "nome_fantasia" => !empty($_POST['nome_fantasia']) ? $_POST['nome_fantasia'] : "",
        "data" => $_POST['data'],
        "horario_inicio" => $_POST['horario_inicio'],
        "descricao" => $_POST['descricao'],
        "preco" => $_POST['preco'],
        "imagem" => $novaImagem, // caminho completo
        "url_compra" => $_POST['url_compra'],
        "melhores" => isset($_POST['melhores']) ? true : false
    ];

    file_put_contents($arquivo, json_encode($shows, JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE));
    header('Location: modificar.php');
    exit();
}
?>

<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <title>Editar Show</title>
    <link rel="stylesheet" href="style.css">
</head>
<body>
    <div class="background-image"></div>
    <div class="container">
        <h2>Editar Show</h2>

        <form method="POST" enctype="multipart/form-data" class="edit-form">
            <label for="nome">Nome:</label><br>
            <input name="nome" id="nome" value="<?= htmlspecialchars($show['nome']) ?>" required><br><br>

            <label for="nome_fantasia">Nome Fantasia (opcional):</label><br>
            <input name="nome_fantasia" id="nome_fantasia" value="<?= htmlspecialchars($show['nome_fantasia'] ?? '') ?>"><br><br>

            <label for="data">Data:</label><br>
            <input type="date" name="data" id="data" value="<?= htmlspecialchars($show['data']) ?>" required><br><br>

            <label for="horario_inicio">Horário de Início:</label><br>
            <input type="time" name="horario_inicio" id="horario_inicio" value="<?= htmlspecialchars($show['horario_inicio']) ?>" required><br><br>

            <label for="descricao">Descrição:</label><br>
            <input name="descricao" id="descricao" value="<?= htmlspecialchars($show['descricao']) ?>"><br><br>

            <label for="preco">Preço:</label><br>
            <input name="preco" id="preco" value="<?= htmlspecialchars($show['preco']) ?>" required><br><br>

            <label for="imagem">Imagem do Show:</label><br>
            <?php if (!empty($show['imagem'])): ?>
                <img src="<?= htmlspecialchars($show['imagem']) ?>" alt="Imagem atual" width="100"><br>
            <?php endif; ?>
            <input type="file" name="imagem" id="imagem" accept="image/*"><br><br>

            <label for="url_compra">URL da Compra:</label><br>
            <input type="text" name="url_compra" id="url_compra" value="<?= htmlspecialchars($show['url_compra'] ?? '') ?>"><br><br>

            <label><input type="checkbox" name="melhores" <?= isset($show['melhores']) && $show['melhores'] ? 'checked' : '' ?>> Melhores Shows</label><br><br>

            <button type="submit">Salvar Alterações</button>
        </form>

        <form method="POST" onsubmit="return confirm('Tem certeza que deseja excluir este show?');" class="delete-form">
            <input type="hidden" name="excluir" value="1">
            <button type="submit" class="delete-button">Excluir Show</button>
        </form>

        <a href="modificar.php" class="cancel-link">Cancelar</a>
    </div>
</body>
</html>
