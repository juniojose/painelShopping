<?php
// Inicia a sessão em todas as páginas que utilizam o controller
if (session_status() == PHP_SESSION_NONE) {
    session_start();
}

require_once __DIR__ . '/../../config/config.php';

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
        header('Location: ../views/admin/login.php');
        exit;
}

function handleLogin() {
    // Verifica se o método da requisição é POST
    if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
        header('Location: ../views/admin/login.php');
        exit;
    }

    $email = $_POST['email'] ?? '';
    $senha = $_POST['senha'] ?? '';

    // --- LÓGICA DE AUTENTICAÇÃO (SIMULADA) ---
    // Na Fase 4, isso será substituído por uma consulta ao banco de dados usando o UserModel.
    $usuario_valido = ('admin@example.com' === $email);
    $senha_valida = ('password123' === $senha); // Em um caso real, usaríamos password_verify()

    if ($usuario_valido && $senha_valida) {
        // Login bem-sucedido: armazena informações na sessão
        $_SESSION['user_id'] = 1; // ID do usuário (simulado)
        $_SESSION['user_nome'] = 'Administrador';

        // Redireciona para o painel de controle
        header('Location: ../views/admin/dashboard.php');
        exit;
    } else {
        // Login falhou: define mensagem de erro e redireciona de volta
        $_SESSION['error_message'] = 'E-mail ou senha inválidos.';
        header('Location: ../views/admin/login.php');
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
    header('Location: ../views/admin/login.php');
    exit;
}
