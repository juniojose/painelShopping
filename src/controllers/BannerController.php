<?php
$basePath = realpath(__DIR__ . '/../../');

// Includes
require_once $basePath . '/src/lib/auth.php';
require_auth();
require_once $basePath . '/config/database.php';
require_once $basePath . '/src/models/Banner.php';
require_once $basePath . '/src/lib/helpers.php';

// Inicialização
$db = Database::getInstance();
$banner = new Banner($db);
$redirect_url = '?page=banners';

$action = $_GET['action'] ?? '';

// Roteamento de ações
switch ($action) {
    case 'create':
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $image_path = handle_file_upload($_FILES['imagem'] ?? null, 'banners');

            if ($image_path === null && (!isset($_FILES['imagem']) || $_FILES['imagem']['error'] !== UPLOAD_ERR_NO_FILE)) {
                $_SESSION['message'] = 'Erro: O upload de uma imagem de banner válida é obrigatório e falhou.';
                redirect('?page=banners-form');
                break;
            }

            $banner->nome = $_POST['nome'];
            $banner->url_link = $_POST['url_link'];
            $banner->url_imagem_banner = $image_path;

            $_SESSION['message'] = $banner->create()
                ? 'Banner criado com sucesso!'
                : 'Erro ao criar o banner.';
            redirect($redirect_url);
        }
        break;

    case 'update':
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $current_image = $_POST['current_image'] ?? null;
            $image_path = handle_file_upload($_FILES['imagem'] ?? null, 'banners', $current_image);

            if ($image_path === null && (!isset($_FILES['imagem']) || $_FILES['imagem']['error'] !== UPLOAD_ERR_NO_FILE)) {
                $_SESSION['message'] = 'Erro: O arquivo de imagem enviado não é válido ou falhou ao salvar.';
                redirect('?page=banners-form&id=' . $_POST['id']);
                break;
            }

            $banner->id = $_POST['id'];
            $banner->nome = $_POST['nome'];
            $banner->url_link = $_POST['url_link'];
            $banner->url_imagem_banner = $image_path;

            $_SESSION['message'] = $banner->update()
                ? 'Banner atualizado com sucesso!'
                : 'Erro ao atualizar o banner.';
            redirect($redirect_url);
        }
        break;

    case 'delete':
        if (isset($_GET['id'])) {
            // Busca o banner para obter o caminho da imagem antes de deletar
            if ($banner->findById($_GET['id'])) {
                $image_file = $basePath . '/public' . $banner->url_imagem_banner;
                if ($banner->url_imagem_banner && file_exists($image_file)) {
                    unlink($image_file);
                }
            }

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