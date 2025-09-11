<?php 
// Define o caminho base para facilitar a inclusão de arquivos
$basePath = realpath(__DIR__ . '/../../../');
require_once $basePath . '/templates/header.php'; 
?>

<div class="container mt-5">
    <div class="row justify-content-center">
        <div class="col-md-6 col-lg-4">
            <div class="card">
                <div class="card-header text-center">
                    <h3>Painel Administrativo</h3>
                </div>
                <div class="card-body">
                    <form action="<?= BASE_URL ?>/src/controllers/AuthController.php?action=login" method="POST">
                        <?php 
                        // Exibe mensagens de erro, se houver
                        if (isset($_SESSION['error_message'])) {
                            echo '<div class="alert alert-danger">' . htmlspecialchars($_SESSION['error_message']) . '</div>';
                            unset($_SESSION['error_message']); // Limpa a mensagem após exibir
                        }
                        ?>
                        <div class="mb-3">
                            <label for="email" class="form-label">E-mail</label>
                            <input type="email" class="form-control" id="email" name="email" required>
                        </div>
                        <div class="mb-3">
                            <label for="senha" class="form-label">Senha</label>
                            <input type="password" class="form-control" id="senha" name="senha" required>
                        </div>
                        <div class="d-grid">
                            <button type="submit" class="btn btn-primary">Entrar</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>

<?php require_once $basePath . '/templates/footer.php'; ?>
