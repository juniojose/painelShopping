<?php
$basePath = realpath(__DIR__ . '/../../');

// Proteção e includes
require_once $basePath . '/src/lib/auth.php';
require_auth();
require_once $basePath . '/config/database.php';
require_once $basePath . '/src/models/Banner.php';
require_once $basePath . '/src/lib/helpers.php'; // Inclui o novo helper

// Inicialização
$db = Database::getInstance();
$banner = new Banner($db);
$redirect_url = BASE_URL . '/src/views/admin/banners/index.php';

$action = $_GET['action'] ?? '';

// Roteamento de ações
switch ($action) {
    case 'create':
        handlePostRequest(function() use ($banner) {
            $banner->nome = $_POST['nome'];
            $banner->url_link = $_POST['url_link'];
            $banner->url_imagem_banner = $_POST['url_imagem_banner'];

            $_SESSION['message'] = $banner->create() 
                ? 'Banner criado com sucesso!' 
                : 'Erro ao criar o banner.';
        }, $redirect_url);
        break;

    case 'update':
        handlePostRequest(function() use ($banner) {
            $banner->id = $_POST['id'];
            $banner->nome = $_POST['nome'];
            $banner->url_link = $_POST['url_link'];
            $banner->url_imagem_banner = $_POST['url_imagem_banner'];

            $_SESSION['message'] = $banner->update() 
                ? 'Banner atualizado com sucesso!' 
                : 'Erro ao atualizar o banner.';
        }, $redirect_url);
        break;

    case 'delete':
        if (isset($_GET['id'])) {
            $banner->id = $_GET['id'];
            $_SESSION['message'] = $banner->delete() 
                ? 'Banner excluído com sucesso!' 
                : 'Erro ao excluir o banner.';
        }
        redirect($redirect_url);
        break;

    default:
        redirect($redirect_url);
        break;
}
