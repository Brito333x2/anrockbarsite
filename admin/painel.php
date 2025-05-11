<?php
session_start();
if (!isset($_SESSION['logado'])) {
    header('Location: index.php');
    exit();
}
?>
<!DOCTYPE html>
<html lang="pt-br">
<head>
    <meta charset="UTF-8">
    <title>Painel Admin</title>
    <link rel="stylesheet" href="style.css">
</head>
<body>
    <h2>Painel de Administração</h2>
    <p>Bem-vindo, admin!</p>
    <a href="adicionar.php"><button>Adicionar Show</button></a>
    <a href="modificar.php"><button>Modificar Show</button></a>
    <br><br>
    <a href="logout.php">Sair</a>
</body>
</html>
