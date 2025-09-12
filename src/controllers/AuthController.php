<?php
// Inicia a sessão em todas as páginas que utilizam o controller
if (session_status() == PHP_SESSION_NONE) {
    session_start();
}

// Includes necessários para o controller
require_once __DIR__ . '/../../config/config.php';
require_once __DIR__ . '/../../config/database.php';
require_once __DIR__ . '/../models/User.php';

// Roteamento de ações
$action = $_GET['action'] ?? 'login';

switch ($action) {
    case 'login':
        handleLogin();
        break;
    case 'logout':
        handleLogout();
        break;
    default:
        // Redireciona para a página de login se a ação for inválida
        header('Location: ' . BASE_URL . '/admin/');
        exit;
}

function handleLogin() {
    // Verifica se o método da requisição é POST
    if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
        header('Location: ' . BASE_URL . '/admin/');
        exit;
    }

    $email = $_POST['email'] ?? '';
    $senha = $_POST['senha'] ?? '';

    // Conecta ao banco e instancia o model
    $db = Database::getInstance();
    $user = new User($db);

    // Busca o usuário pelo e-mail
    $userData = $user->findByEmail($email);

    // Verifica se o usuário existe e se a senha está correta
    if ($userData && password_verify($senha, $userData['senha'])) {
        // Login bem-sucedido: armazena informações na sessão
        $_SESSION['user_id'] = $userData['id'];
        $_SESSION['user_nome'] = $userData['nome'];

        // Redireciona para o painel de controle
        header('Location: ?page=dashboard');
        exit;
    } else {
        // Login falhou: define mensagem de erro e redireciona de volta
        $_SESSION['error_message'] = 'E-mail ou senha inválidos.';
        header('Location: ' . BASE_URL . '/admin/');
        exit;
    }
}

function handleLogout() {
    // Limpa todas as variáveis de sessão
    $_SESSION = [];

    // Destrói a sessão
    if (ini_get("session.use_cookies")) {
        $params = session_get_cookie_params();
        setcookie(session_name(), '', time() - 42000,
            $params["path"], $params["domain"],
            $params["secure"], $params["httponly"]
        );
    }
    session_destroy();

    // Redireciona para a página de login
    header('Location: .');
    exit;
}
