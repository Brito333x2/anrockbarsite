<?php
session_start();
if (!isset($_SESSION['logado'])) {
    header('Location: index.php');
    exit();
}

$arquivo = 'shows.json';
$shows = file_exists($arquivo) ? json_decode(file_get_contents($arquivo), true) : [];

$filtro_mes = null;
if (isset($_GET['mes']) && $_GET['mes'] !== '') {
    $filtro_mes = $_GET['mes']; // formato esperado: YYYY-MM

    // Filtra os shows que estão no mesmo mês e ano do filtro
    $shows = array_filter($shows, function($show) use ($filtro_mes) {
        // pega os 7 primeiros caracteres da data do show (YYYY-MM)
        return substr($show['data'], 0, 7) === $filtro_mes;
    });
}
?>

<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <title>Modificar Shows</title>
    <link rel="stylesheet" href="style.css"> <!-- CSS externo -->
</head>
<body>
    <div class="background-image"></div>
    <div class="container">
        <h2>Modificar Shows</h2>
        
        <!-- Formulário de filtro por mês -->
        <form method="GET" action="" style="margin-bottom: 30px; text-align: center;">
            <label for="mes">Filtrar por mês: </label>
            <input type="month" id="mes" name="mes" value="<?= htmlspecialchars($filtro_mes ?? '') ?>">
            <button type="submit" style="padding: 8px 15px; background-color: #ff4444; color: white; border: none; border-radius: 6px; cursor: pointer;">Filtrar</button>
            <a href="modificar.php" style="margin-left: 15px; color: #ff4444; text-decoration: none; font-weight: 600;">Limpar filtro</a>
        </form>

        <ul class="show-list">
            <?php if (count($shows) > 0): ?>
                <?php foreach ($shows as $index => $show): ?>
                    <li class="show-item">
                        <span><?= htmlspecialchars($show['nome']) ?></span>
                        <?= htmlspecialchars($show['data']) ?>
                        <a href="editar.php?id=<?= $index ?>" class="edit-link">Editar</a>
                    </li>
                <?php endforeach; ?>
            <?php else: ?>
                <p style="color: #ff4444; text-align:center;">Nenhum show encontrado para este mês.</p>
            <?php endif; ?>
        </ul>
        <a href="painel.php" class="back-link">Voltar</a>
    </div>

    <style>
        /* seu CSS permanece o mesmo */
        /* Container dos shows */
        .show-list {
          list-style: none;
          padding: 0;
          margin: 0 auto;
          display: grid;
          grid-template-columns: repeat(auto-fit, minmax(250px, 1fr));
          gap: 25px;
          max-width: 700px; /* seguindo container max */
        }

        /* Cada show como card */
        .show-item {
          background: #1e1e1e;
          border-radius: 12px;
          box-shadow: 0 8px 25px rgba(0, 0, 0, 0.8);
          padding: 25px 20px;
          color: #f5f5f5;
          font-size: 1.3rem;
          font-weight: 600;
          display: flex;
          flex-direction: column;
          justify-content: space-between;
          text-align: center;
          transition: background-color 0.3s ease, box-shadow 0.3s ease;
          cursor: default;
        }

        .show-item:hover {
          background-color: #2a2a2a;
          box-shadow: 0 12px 30px rgba(255, 68, 68, 0.6);
        }

        /* Nome e data do show */
        .show-item span {
          display: block;
          margin-bottom: 18px;
          font-weight: 700;
          color: #ff4444;
          text-shadow: 1px 1px 3px #000;
        }

        /* Link editar */
        .edit-link {
          background: #ff4444;
          color: #fff;
          text-decoration: none;
          padding: 14px 24px;
          border-radius: 10px;
          font-weight: 700;
          font-size: 1.2rem;
          text-transform: uppercase;
          box-shadow: 0 6px 18px rgba(255, 68, 68, 0.7);
          transition: background 0.3s ease, box-shadow 0.3s ease;
          align-self: center;
        }

        .edit-link:hover {
          background: #e22a2a;
          box-shadow: 0 8px 22px rgba(226, 42, 42, 0.9);
        }

        /* Link voltar */
        .back-link {
          display: inline-block;
          margin: 40px auto 0;
          background: #ff4444;
          color: #fff;
          padding: 18px 32px;
          font-size: 1.4rem;
          border-radius: 10px;
          font-weight: 700;
          text-decoration: none;
          box-shadow: 0 6px 18px rgba(255, 68, 68, 0.7);
          transition: background 0.3s ease, box-shadow 0.3s ease;
          text-align: center;
        }

        .back-link:hover {
          background: #e22a2a;
          box-shadow: 0 8px 22px rgba(226, 42, 42, 0.9);
        }
    </style>
</body>
</html>
