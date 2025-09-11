<?php
// Inicia a sessão para uso futuro (ex: área administrativa)
if (session_status() == PHP_SESSION_NONE) {
    session_start();
}

// Inclui o arquivo de configuração
require_once __DIR__ . '/../config/config.php';
?>
<!DOCTYPE html>
<html lang="pt-br">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Painel Shopping</title>
    
    <!-- Bootstrap CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-QWTKZyjpPEjISv5WaRU9OFeRpok6YctnYmDr5pNlyT2bRjXh0JMhjY6hW+ALEwIH" crossorigin="anonymous">
    
    <!-- Ícones do Bootstrap -->
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css">

    <style>
        /* Estilos personalizados */
        body {
            display: flex;
            flex-direction: column;
            min-height: 100vh;
        }
        main {
            flex: 1;
        }
        .iframe-container {
            position: relative;
            overflow: hidden;
            width: 100%;
            padding-top: 56.25%; /* Proporção 16:9 */
        }
        .iframe-container iframe {
            position: absolute;
            top: 0;
            left: 0;
            bottom: 0;
            right: 0;
            width: 100%;
            height: 100%;
            border: 0;
        }
    </style>
</head>
<body>

<header style="background-color: <?= htmlspecialchars(THEME_CONFIG['header_cor_fundo']); ?>; color: <?= htmlspecialchars(THEME_CONFIG['header_cor_letra']); ?>;">
    <div class="container">
        <div class="d-flex justify-content-between align-items-center py-3">
            <a href="<?= BASE_URL ?>" class="d-flex align-items-center text-decoration-none" style="color: inherit;">
                <img src="<?= htmlspecialchars(THEME_CONFIG['header_logo_url']); ?>" alt="Logo" height="50">
            </a>
            <a href="https://wa.me/5511999999999" target="_blank" class="btn btn-success">
                <i class="bi bi-whatsapp"></i> Contato
            </a>
        </div>
    </div>
</header>

<main>
