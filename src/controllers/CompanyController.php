<?php
$basePath = realpath(__DIR__ . '/../../');

// Proteção e includes
require_once $basePath . '/src/lib/auth.php';
require_auth();
require_once $basePath . '/config/database.php';
require_once $basePath . '/src/models/Company.php';
require_once $basePath . '/src/lib/helpers.php'; // Inclui o novo helper

// Inicialização
$db = Database::getInstance();
$company = new Company($db);
$redirect_url = '../views/admin/empresas/index.php';

$action = $_GET['action'] ?? '';

// Roteamento de ações
switch ($action) {
    case 'create':
        handlePostRequest(function() use ($company) {
            $company->nome = $_POST['nome'];
            $company->url_site = $_POST['url_site'];
            $company->url_logo = $_POST['url_logo'];

            $_SESSION['message'] = $company->create() 
                ? 'Empresa criada com sucesso!' 
                : 'Erro ao criar a empresa.';
        }, $redirect_url);
        break;

    case 'update':
        handlePostRequest(function() use ($company) {
            $company->id = $_POST['id'];
            $company->nome = $_POST['nome'];
            $company->url_site = $_POST['url_site'];
            $company->url_logo = $_POST['url_logo'];

            $_SESSION['message'] = $company->update() 
                ? 'Empresa atualizada com sucesso!' 
                : 'Erro ao atualizar a empresa.';
        }, $redirect_url);
        break;

    case 'delete':
        if (isset($_GET['id'])) {
            $company->id = $_GET['id'];
            $_SESSION['message'] = $company->delete() 
                ? 'Empresa excluída com sucesso!' 
                : 'Erro ao excluir a empresa.';
        }
        redirect($redirect_url);
        break;

    default:
        redirect($redirect_url);
        break;
}
