<?php
$basePath = realpath(__DIR__ . '/../../../../');

// Proteção e includes
require_once $basePath . '/src/lib/auth.php';
require_auth();
require_once $basePath . '/config/database.php';
require_once $basePath . '/src/models/Banner.php';

// Inicialização de variáveis
$db = Database::getInstance();
$banner = new Banner($db);
$pageTitle = 'Adicionar Novo Banner';
$action = 'create';

// Modo de edição: se um ID for passado, busca os dados do banner
if (isset($_GET['id'])) {
    if ($banner->findById($_GET['id'])) {
        $pageTitle = 'Editar Banner';
        $action = 'update';
    } else {
        $_SESSION['message'] = 'Erro: Banner não encontrado.';
        header('Location: ?page=banners');
        exit;
    }
}

// Inclui o cabeçalho
require_once $basePath . '/templates/header.php';
?>

<div class="container mt-5">
    <div class="row justify-content-center">
        <div class="col-md-8">
            <div class="card">
                <div class="card-header">
                    <h3><?= $pageTitle ?></h3>
                </div>
                <div class="card-body">
                    <form action="?controller=banner&action=<?= $action ?>" method="POST">
                        <?php if ($action === 'update'): ?>
                            <input type="hidden" name="id" value="<?= htmlspecialchars($banner->id) ?>">
                        <?php endif; ?>

                        <div class="mb-3">
                            <label for="nome" class="form-label">Nome do Banner</label>
                            <input type="text" class="form-control" id="nome" name="nome" value="<?= htmlspecialchars($banner->nome ?? '') ?>" required>
                        </div>

                        <div class="mb-3">
                            <label for="url_link" class="form-label">URL do Link (para onde o banner aponta)</label>
                            <input type="url" class="form-control" id="url_link" name="url_link" value="<?= htmlspecialchars($banner->url_link ?? '') ?>" required>
                        </div>

                        <div class="mb-3">
                            <label for="url_imagem_banner" class="form-label">URL da Imagem do Banner</label>
                            <input type="url" class="form-control" id="url_imagem_banner" name="url_imagem_banner" value="<?= htmlspecialchars($banner->url_imagem_banner ?? '') ?>" required>
                        </div>

                        <div class="d-flex justify-content-end">
                            <a href="?page=banners" class="btn btn-secondary me-2">Cancelar</a>
                            <button type="submit" class="btn btn-primary">Salvar</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>

<?php require_once $basePath . '/templates/footer.php'; ?>
