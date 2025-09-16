<?php
// Garante que a sessão seja iniciada em todas as páginas, antes de qualquer output.
if (session_status() == PHP_SESSION_NONE) {
    session_start();
}

// Inclui o arquivo de configuração base
require_once __DIR__ . '/../config/config.php';

// Carrega as configurações do tema do banco de dados
require_once __DIR__ . '/../config/database.php';
require_once __DIR__ . '/../src/models/Setting.php';
$db = Database::getInstance();
$settingModel = new Setting($db);
$themeSettings = $settingModel->getAllSettings();

// Prepara variáveis para OpenGraph com fallback
$ogTitle = $themeSettings['og_title'] ?? 'Painel Shopping'; // Fallback para o nome do site
$ogDescription = $themeSettings['og_description'] ?? 'Navegue por nossas lojas e aproveite as melhores ofertas.'; // Fallback genérico

// Lógica de fallback para a imagem
$ogImage = !empty($themeSettings['og_image']) ? $themeSettings['og_image'] : ($themeSettings['header_logo_url'] ?? '');
if (!empty($ogImage)) {
    // Garante que a URL da imagem seja absoluta
    $ogImage = rtrim(BASE_URL, '/') . '/' . ltrim($ogImage, '/');
}

// URL canônica da página atual
$ogUrl = rtrim(BASE_URL, '/') . $_SERVER['REQUEST_URI'];
?>
<!DOCTYPE html>
<html lang="pt-br">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Painel Shopping</title>

    <!-- OpenGraph Meta Tags -->
    <meta property="og:title" content="<?= htmlspecialchars($ogTitle) ?>" />
    <meta property="og:description" content="<?= htmlspecialchars($ogDescription) ?>" />
    <meta property="og:type" content="website" />
    <meta property="og:url" content="<?= htmlspecialchars($ogUrl) ?>" />
    <?php if (!empty($ogImage)): ?>
    <meta property="og:image" content="<?= htmlspecialchars($ogImage) ?>" />
    <?php endif; ?>
    
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
    </style>
</head>
<body>

<header style="background-color: <?= htmlspecialchars($themeSettings['header_cor_fundo']); ?>; color: <?= htmlspecialchars($themeSettings['header_cor_letra']); ?>;">
    <nav class="navbar navbar-expand-lg navbar-dark" style="background-color: inherit;">
        <div class="container">
            <!-- Logo -->
            <a class="navbar-brand" href="<?= BASE_URL ?>" id="home-logo-link" style="color: inherit;">
                <img src="<?= htmlspecialchars(rtrim(BASE_URL, '/') . '/' . ltrim($themeSettings['header_logo_url'], '/')) ?>" alt="Logo" height="50">
            </a>

            <!-- Botão Sanduíche (Toggler) -->
            <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNavContent" aria-controls="navbarNavContent" aria-expanded="false" aria-label="Toggle navigation">
                <span class="navbar-toggler-icon"></span>
            </button>

            <!-- Conteúdo Colapsável -->
            <div class="collapse navbar-collapse" id="navbarNavContent">
                <div class="d-flex flex-column flex-lg-row align-items-center ms-auto mt-3 mt-lg-0">
                    <!-- Formulário de Busca -->
                    <form action="<?= BASE_URL ?>" method="GET" class="d-flex me-lg-3 mb-3 mb-lg-0" role="search">
                        <input class="form-control me-2" type="search" name="search" placeholder="Buscar empresa..." aria-label="Buscar">
                        <button class="btn btn-primary" type="submit">Buscar</button>
                    </form>
                    <!-- Botão Contato -->
                    <a href="https://wa.me/556198343743" target="_blank" class="btn btn-success">
                        <i class="bi bi-whatsapp"></i> Contato
                    </a>
                </div>
            </div>
        </div>
    </nav>
</header>

<?php
// Se estiver na área administrativa E o usuário estiver logado, carrega o menu de navegação do admin
if (isset($is_admin_area) && $is_admin_area && isset($_SESSION['user_id'])) {
    require_once __DIR__ . '/admin_nav.php';
}
?>

<main>
