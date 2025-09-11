<?php
$basePath = realpath(__DIR__ . '/../../');

// Proteção e includes
require_once $basePath . '/src/lib/auth.php';
require_auth();
require_once $basePath . '/config/database.php';
require_once $basePath . '/src/models/Company.php';

// Inicialização
$db = Database::getInstance();
$company = new Company($db);

$action = $_GET['action'] ?? '';

// Roteamento de ações
switch ($action) {
    case 'create':
        handlePostRequest(function() use ($company) {
            $company->nome = $_POST['nome'];
            $company->url_site = $_POST['url_site'];
            $company->url_logo = $_POST['url_logo'];

            if ($company->create()) {
                $_SESSION['message'] = 'Empresa criada com sucesso!';
            } else {
                $_SESSION['message'] = 'Erro ao criar a empresa.';
            }
            redirectToList();
        });
        break;

    case 'update':
        handlePostRequest(function() use ($company) {
            $company->id = $_POST['id'];
            $company->nome = $_POST['nome'];
            $company->url_site = $_POST['url_site'];
            $company->url_logo = $_POST['url_logo'];

            if ($company->update()) {
                $_SESSION['message'] = 'Empresa atualizada com sucesso!';
            } else {
                $_SESSION['message'] = 'Erro ao atualizar a empresa.';
            }
            redirectToList();
        });
        break;

    case 'delete':
        if (isset($_GET['id'])) {
            $company->id = $_GET['id'];
            if ($company->delete()) {
                $_SESSION['message'] = 'Empresa excluída com sucesso!';
            } else {
                $_SESSION['message'] = 'Erro ao excluir a empresa.';
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
 * Redireciona o usuário para a página de listagem de empresas.
 */
function redirectToList() {
    header('Location: ../views/admin/empresas/index.php');
    exit;
}
