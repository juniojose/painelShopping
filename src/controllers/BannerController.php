<?php
$basePath = realpath(__DIR__ . '/../../');

// Proteção e includes
require_once $basePath . '/src/lib/auth.php';
require_auth();
require_once $basePath . '/config/database.php';
require_once $basePath . '/src/models/Banner.php';

// Inicialização
$db = Database::getInstance();
$banner = new Banner($db);

$action = $_GET['action'] ?? '';

// Roteamento de ações
switch ($action) {
    case 'create':
        handlePostRequest(function() use ($banner) {
            $banner->nome = $_POST['nome'];
            $banner->url_link = $_POST['url_link'];
            $banner->url_imagem_banner = $_POST['url_imagem_banner'];

            if ($banner->create()) {
                $_SESSION['message'] = 'Banner criado com sucesso!';
            } else {
                $_SESSION['message'] = 'Erro ao criar o banner.';
            }
            redirectToList();
        });
        break;

    case 'update':
        handlePostRequest(function() use ($banner) {
            $banner->id = $_POST['id'];
            $banner->nome = $_POST['nome'];
            $banner->url_link = $_POST['url_link'];
            $banner->url_imagem_banner = $_POST['url_imagem_banner'];

            if ($banner->update()) {
                $_SESSION['message'] = 'Banner atualizado com sucesso!';
            } else {
                $_SESSION['message'] = 'Erro ao atualizar o banner.';
            }
            redirectToList();
        });
        break;

    case 'delete':
        if (isset($_GET['id'])) {
            $banner->id = $_GET['id'];
            if ($banner->delete()) {
                $_SESSION['message'] = 'Banner excluído com sucesso!';
            } else {
                $_SESSION['message'] = 'Erro ao excluir o banner.';
            }
        }
        redirectToList();
        break;

    default:
        redirectToList();
        break;
}

/**
 * Garante que a requisição seja do tipo POST antes de executar uma função.
 * @param callable $callback A função a ser executada.
 */
function handlePostRequest(callable $callback) {
    if ($_SERVER['REQUEST_METHOD'] === 'POST') {
        $callback();
    } else {
        redirectToList();
    }
}

/**
 * Redireciona o usuário para a página de listagem de banners.
 */
function redirectToList() {
    header('Location: ../views/admin/banners/index.php');
    exit;
}
