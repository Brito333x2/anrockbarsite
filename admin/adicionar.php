<?php
session_start();
if (!isset($_SESSION['logado'])) {
    header('Location: index.php');
    exit();
}

$mensagem = "";
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $novoShow = [
        "nome" => $_POST['nome'],
        "data" => $_POST['data'],
        "horario" => $_POST['horario'],
        "descricao" => $_POST['descricao'],
        "preco" => $_POST['preco'],
        "imagem" => $_POST['imagem'],
        "geral" => isset($_POST['geral']) ? true : false
    ];

    $arquivo = 'shows.json';
    $shows = file_exists($arquivo) ? json_decode(file_get_contents($arquivo), true) : [];
    $shows[] = $novoShow;
    file_put_contents($arquivo, json_encode($shows, JSON_PRETTY_PRINT));
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

<h2>Adicionar Novo Show</h2>

<?php if ($mensagem): ?>
    <p><?= $mensagem ?></p>
    <a href="painel.php">Voltar ao Painel</a>
<?php else: ?>
    <form method="POST">
        <input name="nome" placeholder="Nome do Show" required><br>
        <input name="data" placeholder="Data" required><br>
        <input name="horario" placeholder="Horário" required><br>
        <input name="descricao" placeholder="Descrição"><br>
        <input name="preco" placeholder="Preço" required><br>
        <input name="imagem" placeholder="URL da imagem"><br>
        <label><input type="checkbox" name="geral"> Evento Geral</label><br>
        <button type="submit">Adicionar</button>
    </form>
    <a href="painel.php">Cancelar</a>
<?php endif; ?>

</body>
</html>
