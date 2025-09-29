<?php 
require_once __DIR__ . '/../templates/header.php'; 
require_once __DIR__ . '/../src/lib/helpers.php';

// --- LÓGICA DE PAGINAÇÃO E BUSCA DE DADOS ---

// Includes
require_once __DIR__ . '/../config/database.php';
require_once __DIR__ . '/../src/models/Banner.php';
require_once __DIR__ . '/../src/models/Company.php';

$db = Database::getInstance();

// Busca banners (sem paginação)
$bannerModel = new Banner($db);
$banners = $bannerModel->findAll()->fetchAll(PDO::FETCH_ASSOC);

// Lógica de busca e paginação para empresas
$companyModel = new Company($db);

// 1. Obter termo de busca e configurações
$searchTerm = isset($_GET['search']) ? trim($_GET['search']) : '';
$companiesPerPage = (int)($themeSettings['companies_per_page'] ?? 12);

// 2. Obter total de itens e calcular total de páginas (com base na busca, se houver)
if (!empty($searchTerm)) {
    $totalCompanies = $companyModel->countAllBySearch($searchTerm);
} else {
    $totalCompanies = $companyModel->countAll();
}
$totalPages = $companiesPerPage > 0 ? ceil($totalCompanies / $companiesPerPage) : 0;

// 3. Obter página atual
$currentPage = isset($_GET['p']) && is_numeric($_GET['p']) ? (int)$_GET['p'] : 1;
if ($currentPage < 1) {
    $currentPage = 1;
} elseif ($currentPage > $totalPages && $totalPages > 0) {
    $currentPage = $totalPages;
}

// 4. Calcular offset e buscar empresas (com base na busca, se houver)
$offset = ($currentPage - 1) * $companiesPerPage;
if (!empty($searchTerm)) {
    $companies = $companyModel->findWithPaginationAndSearch($searchTerm, $companiesPerPage, $offset)->fetchAll(PDO::FETCH_ASSOC);
} else {
    $companies = $companyModel->findWithPagination($companiesPerPage, $offset)->fetchAll(PDO::FETCH_ASSOC);
}

// 5. Montar query string para links de paginação
$paginationQueryString = '';
if (!empty($searchTerm)) {
    $paginationQueryString .= '&search=' . urlencode($searchTerm);
}

?>

<div class="container" id="main-content">
    <!-- Seção do Banner Hero -->
    <?php if (!empty($banners)): ?>
    <section class="my-4">
        <div id="heroCarousel" class="carousel slide" data-bs-ride="carousel">
            <div class="carousel-indicators">
                <?php foreach ($banners as $index => $banner): ?>
                    <button type="button" data-bs-target="#heroCarousel" data-bs-slide-to="<?= $index ?>" class="<?= $index === 0 ? 'active' : '' ?>" aria-current="true" aria-label="Slide <?= $index + 1 ?>"></button>
                <?php endforeach; ?>
            </div>
            <div class="carousel-inner">
                <?php foreach ($banners as $index => $banner): ?>
                    <div class="carousel-item <?= $index === 0 ? 'active' : '' ?>">
                        <a href="<?= htmlspecialchars($banner['url_link']) ?>" class="site-link">
                            <img src="<?= htmlspecialchars(rtrim(BASE_URL, '/') . '/' . ltrim($banner['url_imagem_banner'], '/')) ?>" class="d-block w-100" alt="<?= htmlspecialchars($banner['nome']) ?>">
                        </a>
                    </div>
                <?php endforeach; ?>
            </div>
            <button class="carousel-control-prev" type="button" data-bs-target="#heroCarousel" data-bs-slide="prev">
                <span class="carousel-control-prev-icon" aria-hidden="true"></span>
                <span class="visually-hidden">Previous</span>
            </button>
            <button class="carousel-control-next" type="button" data-bs-target="#heroCarousel" data-bs-slide="next">
                <span class="carousel-control-next-icon" aria-hidden="true"></span>
                <span class="visually-hidden">Next</span>
            </button>
        </div>
    </section>
    <?php endif; ?>

    <!-- Seção do Grid de Empresas -->
    <section>
        <?php if (empty($searchTerm)): // Mostra o título apenas se não for uma busca ?>
            <h2 class="mb-4">Nossas Lojas</h2>
        <?php else: ?>
            <h2 class="mb-4">Resultado da busca por: "<?= htmlspecialchars($searchTerm) ?>"</h2>
        <?php endif; ?>

        <?php
            $columns = $themeSettings['companies_columns'] ?? 3;
            // Garante que o valor seja um número entre 1 e 6 para segurança e para não quebrar o grid
            $columns = max(1, min(6, (int)$columns)); 
            $gridClass = "row row-cols-2 row-cols-md-" . $columns . " g-4";
        ?>
        <div class="<?= $gridClass ?>">
            <?php if (!empty($companies)): ?>
                <?php foreach ($companies as $company): ?>
                    <div class="col">
                        <div class="card h-100">
                            <a href="<?= htmlspecialchars($company['url_site']) ?>" class="site-link text-decoration-none text-body d-block p-2">
                                <div style="aspect-ratio: 1 / 1; width: 100%;">
                                    <?php 
                                    $video_id = get_youtube_video_id($company['url_youtube']);
                                    if ($video_id): 
                                    ?>
                                        <?= get_youtube_embed_code($video_id) ?>
                                    <?php else: ?>
                                        <img src="<?= htmlspecialchars(rtrim(BASE_URL, '/') . '/' . ltrim($company['url_logo'], '/')) ?>" alt="<?= htmlspecialchars($company['nome']) ?>" style="width: 100%; height: 100%; object-fit: contain;">
                                    <?php endif; ?>
                                </div>
                                <div class="card-body p-2">
                                    <h6 class="card-title text-center small mb-0"><?= htmlspecialchars($company['nome']) ?></h6>
                                </div>
                            </a>
                        </div>
                    </div>
                <?php endforeach; ?>
            <?php else: ?>
                <div class="col-12">
                    <p class="text-center">Nenhuma loja encontrada.</p>
                </div>
            <?php endif; ?>
        </div>
    </section>

    <!-- Seção de Paginação -->
    <?php if ($totalPages > 1): ?>
    <nav aria-label="Navegação de página" class="mt-5 d-flex justify-content-center">
        <ul class="pagination">
            <li class="page-item <?= $currentPage <= 1 ? 'disabled' : '' ?>">
                <a class="page-link" href="?p=<?= $currentPage - 1 ?><?= $paginationQueryString ?>">Anterior</a>
            </li>
            <?php for ($i = 1; $i <= $totalPages; $i++): ?>
                <li class="page-item <?= $i === $currentPage ? 'active' : '' ?>">
                    <a class="page-link" href="?p=<?= $i ?><?= $paginationQueryString ?>"><?= $i ?></a>
                </li>
            <?php endfor; ?>
            <li class="page-item <?= $currentPage >= $totalPages ? 'disabled' : '' ?>">
                <a class="page-link" href="?p=<?= $currentPage + 1 ?><?= $paginationQueryString ?>">Próximo</a>
            </li>
        </ul>
    </nav>
    <?php endif; ?>

</div>

<!-- Container do Iframe (inicialmente oculto) -->
<div class="container-fluid p-0 flex-grow-1" id="iframe-container" style="display: none;">
    <iframe id="site-iframe" src="about:blank" class="w-100 h-100 border-0"></iframe>
</div>

<script>
document.addEventListener('DOMContentLoaded', function () {
    const mainContent = document.getElementById('main-content');
    const iframeContainer = document.getElementById('iframe-container');
    const siteIframe = document.getElementById('site-iframe');
    const siteLinks = document.querySelectorAll('.site-link');
    const pageHeader = document.querySelector('header');
    const pageFooter = document.querySelector('footer');

    function showIframe(url) {
        // Define a URL do iframe
        siteIframe.src = url;

        // Calcula a altura disponível
        const headerHeight = pageHeader.offsetHeight;
        const footerHeight = pageFooter.offsetHeight;
        const availableHeight = window.innerHeight - headerHeight - footerHeight;

        // Define a altura do container do iframe e o exibe
        iframeContainer.style.height = availableHeight + 'px';
        mainContent.style.display = 'none';
        iframeContainer.style.display = 'block';
    }

    function showMainContent() {
        mainContent.style.display = 'block';
        iframeContainer.style.display = 'none';
        siteIframe.src = 'about:blank';
    }

    siteLinks.forEach(link => {
        link.addEventListener('click', function (event) {
            event.preventDefault();
            const url = this.getAttribute('href');
            if (url && url !== '#') {
                showIframe(url);
            }
        });
    });

    const homeLink = document.getElementById('home-logo-link');
    if (homeLink) {
        homeLink.addEventListener('click', function(event) {
            event.preventDefault(); // Previne o comportamento padrão para garantir o redirecionamento
            window.location.href = this.getAttribute('href'); // Redireciona para a URL base, limpando os filtros
        });
    }
});
</script>

<?php require_once __DIR__ . '/../templates/footer.php'; ?>