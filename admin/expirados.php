<?php

//LEMBRAR DE JOGAR NA HOSTINGER PARA SER AUTO



// Define o fuso horário corretamente
date_default_timezone_set('America/Sao_Paulo');

// Caminho para o arquivo JSON
$arquivo = 'shows.json';

// Verifica se o arquivo existe
if (!file_exists($arquivo)) {
    exit("Arquivo 'shows.json' não encontrado.");
}

// Lê o conteúdo atual dos shows
$shows = json_decode(file_get_contents($arquivo), true);

// Data e hora atual
$agora = new DateTime();

// Lista para armazenar os shows que ainda são válidos
$showsAtuais = [];

foreach ($shows as $show) {
    // Data do show no formato YYYY-MM-DD
    $dataShow = $show['data'];

    // Cria um objeto DateTime para o dia seguinte às 03:00 da manhã
    $dataLimite = DateTime::createFromFormat('Y-m-d H:i', $dataShow . ' 03:00');
    $dataLimite->modify('+1 day'); // Soma 1 dia à data do show

    // Se o show ainda não expirou, mantém na lista
    if ($agora < $dataLimite) {
        $showsAtuais[] = $show;
    }
}

// Salva apenas os shows válidos de volta no JSON
file_put_contents($arquivo, json_encode($showsAtuais, JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE));
