<?php
session_start();
if (!isset($_SESSION['logado'])) {
    header('Location: index.php');
    exit();
}

$mensagem = "";
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $nomeImagem = "";

    if (isset($_FILES['imagem']) && $_FILES['imagem']['error'] === UPLOAD_ERR_OK) {
        $pastaUpload = 'uploads/';
        if (!is_dir($pastaUpload)) {
            mkdir($pastaUpload, 0755, true);
        }

        $extensao = pathinfo($_FILES['imagem']['name'], PATHINFO_EXTENSION);
        $nomeImagem = uniqid('show_') . '.' . $extensao;
        move_uploaded_file($_FILES['imagem']['tmp_name'], $pastaUpload . $nomeImagem);
    }

    $novoShow = [
        "nome" => $_POST['nome'],
        "nome_fantasia" => !empty($_POST['nome_fantasia']) ? $_POST['nome_fantasia'] : "", // campo opcional
        "data" => $_POST['data'],
        "horario_inicio" => $_POST['horario_inicio'],
        "descricao" => $_POST['descricao'],
        "preco" => $_POST['preco'],
        "imagem" => $pastaUpload . $nomeImagem, // caminho completo da imagem
        "url_compra" => $_POST['url_compra'],
        "melhores" => isset($_POST['melhores']) ? true : false
    ];

    $arquivo = 'shows.json';
    $shows = file_exists($arquivo) ? json_decode(file_get_contents($arquivo), true) : [];
    $shows[] = $novoShow;
    file_put_contents($arquivo, json_encode($shows, JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE));

    $mensagem = "Show adicionado com sucesso!";
}
?>

<!DOCTYPE html>
<html lang="pt-br">
<head>
    <meta charset="UTF-8">
    <title>Adicionar Show</title>
    <link rel="stylesheet" href="style.css">
</head>
<body>
    <div class="background-image"></div>
    <div class="container">
        <h2>Adicionar Novo Show</h2>

        <?php if ($mensagem): ?>
            <p><?= htmlspecialchars($mensagem) ?></p>
            <a href="painel.php">Voltar ao Painel</a>
        <?php else: ?>
            <form method="POST" enctype="multipart/form-data">
                <label for="nome">Nome do Show:</label><br>
                <input type="text" id="nome" name="nome" placeholder="Nome do Show" required><br><br>

                <label for="nome_fantasia">Nome Fantasia (opcional):</label><br>
                <input type="text" id="nome_fantasia" name="nome_fantasia" placeholder="Nome Fantasia"><br><br>

                <label for="data">Data:</label><br>
                <input type="date" id="data" name="data" required><br><br>

                <label for="horario_inicio">Horário de Início:</label><br>
                <input type="time" id="horario_inicio" name="horario_inicio" required><br><br>

                <label for="descricao">Descrição:</label><br>
                <input type="text" id="descricao" name="descricao" placeholder="Descrição do Show"><br><br>

                <label for="preco">Preço:</label><br>
                <input type="text" id="preco" name="preco" placeholder="Ex: 20.00" required><br><br>

                <label for="imagem">Imagem do Show:</label><br>
                <input type="file" id="imagem" name="imagem" accept="image/*" required><br><br>

                <label for="url_compra">URL da Compra:</label><br>
                <input type="text" id="url_compra" name="url_compra" placeholder="https://..."><br><br>

                <label><input type="checkbox" name="melhores"> Melhores Shows</label><br><br>

                <button type="submit">Adicionar</button>
            </form>
            <br>
            <a href="painel.php">Cancelar</a>
        <?php endif; ?>
    </div>
</body>
</html>
