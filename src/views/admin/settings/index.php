<?php
$basePath = realpath(__DIR__ . '/../../../../');

// Proteção e includes
require_once $basePath . '/src/lib/auth.php';
require_auth();
require_once $basePath . '/config/database.php';
require_once $basePath . '/src/models/Setting.php';

// Busca as configurações atuais para preencher o formulário
$db = Database::getInstance();
$settingModel = new Setting($db);
$settings = $settingModel->getAllSettings();

$pageTitle = 'Configurações do Tema';
?>

<div class="container mt-5">
    <div class="row justify-content-center">
        <div class="col-md-8">
            <h2><?= $pageTitle ?></h2>

            <?php
            if (isset($_SESSION['message'])) {
                echo '<div class="alert alert-success">' . htmlspecialchars($_SESSION['message']) . '</div>';
                unset($_SESSION['message']);
            }
            ?>

            <div class="card">
                <div class="card-body">
                    <form action="?controller=settings&action=update" method="POST" enctype="multipart/form-data">
                        
                        <h5 class="mt-4">Cabeçalho</h5>
                        <hr>

                        <div class="mb-3">
                            <label for="header_logo_url" class="form-label">Logo do Cabeçalho</label>
                            <input type="file" class="form-control" id="header_logo_url" name="header_logo_url">
                            <?php if (!empty($settings['header_logo_url'])): ?>
                                <div class="mt-2">
                                    <small>Logo Atual:</small><br>
                                    <img src="<?= htmlspecialchars(rtrim(BASE_URL, '/') . '/' . ltrim($settings['header_logo_url'], '/')) ?>" alt="Logo" style="max-height: 60px; background-color: #f0f0f0; padding: 5px; border-radius: 5px;">
                                    <input type="hidden" name="current_header_logo_url" value="<?= htmlspecialchars($settings['header_logo_url']) ?>">
                                </div>
                            <?php endif; ?>
                        </div>

                        <div class="row">
                            <div class="col-md-6 mb-3">
                                <label for="header_cor_fundo" class="form-label">Cor de Fundo do Cabeçalho</label>
                                <input type="color" class="form-control form-control-color" id="header_cor_fundo" name="header_cor_fundo" value="<?= htmlspecialchars($settings['header_cor_fundo'] ?? '#FFFFFF') ?>" title="Escolha uma cor">
                            </div>
                            <div class="col-md-6 mb-3">
                                <label for="header_cor_letra" class="form-label">Cor da Letra do Cabeçalho</label>
                                <input type="color" class="form-control form-control-color" id="header_cor_letra" name="header_cor_letra" value="<?= htmlspecialchars($settings['header_cor_letra'] ?? '#000000') ?>" title="Escolha uma cor">
                            </div>
                        </div>

                        <h5 class="mt-4">Rodapé</h5>
                        <hr>

                        <div class="row">
                            <div class="col-md-6 mb-3">
                                <label for="footer_cor_fundo" class="form-label">Cor de Fundo do Rodapé</label>
                                <input type="color" class="form-control form-control-color" id="footer_cor_fundo" name="footer_cor_fundo" value="<?= htmlspecialchars($settings['footer_cor_fundo'] ?? '#F8F9FA') ?>" title="Escolha uma cor">
                            </div>
                            <div class="col-md-6 mb-3">
                                <label for="footer_cor_letra" class="form-label">Cor da Letra do Rodapé</label>
                                <input type="color" class="form-control form-control-color" id="footer_cor_letra" name="footer_cor_letra" value="<?= htmlspecialchars($settings['footer_cor_letra'] ?? '#6C757D') ?>" title="Escolha uma cor">
                            </div>
                        </div>

                        <h5 class="mt-4">Vitrine</h5>
                        <hr>

                        <div class="mb-3">
                            <label for="companies_per_page" class="form-label">Lojas por Página</label>
                            <input type="number" class="form-control" id="companies_per_page" name="companies_per_page" value="<?= htmlspecialchars($settings['companies_per_page'] ?? '12') ?>" min="1" required>
                            <small class="form-text text-muted">Quantidade de lojas exibidas na página inicial antes da paginação aparecer.</small>
                        </div>

                        <div class="d-flex justify-content-end mt-4">
                            <button type="submit" class="btn btn-primary">Salvar Alterações</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
