<?php require_once __DIR__ . '/../templates/header.php'; ?>

<div class="container" id="main-content">
    <!-- Seção do Banner Hero -->
    <section class="my-4">
        <div class="card text-bg-dark">
            <img src="https://via.placeholder.com/1920x480.png?text=Banner+Promocional" class="card-img" alt="Banner">
            <div class="card-img-overlay d-flex flex-column justify-content-center text-center">
                <h1 class="card-title">Título do Banner</h1>
                <p class="card-text">Subtítulo ou breve descrição da promoção.</p>
                <div class="mt-3">
                    <a href="https://www.google.com" class="btn btn-primary site-link">Visitar Agora</a>
                </div>
            </div>
        </div>
    </section>

    <!-- Seção do Grid de Empresas -->
    <section>
        <h2 class="mb-4">Nossas Lojas</h2>
        <div class="row row-cols-2 row-cols-sm-3 row-cols-md-4 row-cols-lg-6 g-4">
            <!-- Card de Exemplo 1 -->
            <div class="col">
                <div class="card h-100">
                    <a href="https://www.google.com" class="site-link">
                        <img src="https://via.placeholder.com/150x150.png?text=Logo+1" class="card-img-top" alt="Logo Empresa 1">
                    </a>
                </div>
            </div>
            <!-- Card de Exemplo 2 -->
            <div class="col">
                <div class="card h-100">
                    <a href="https://www.bing.com" class="site-link">
                        <img src="https://via.placeholder.com/150x150.png?text=Logo+2" class="card-img-top" alt="Logo Empresa 2">
                    </a>
                </div>
            </div>
            <!-- Adicionar mais cards conforme necessário -->
        </div>
    </section>
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

    siteLinks.forEach(link => {
        link.addEventListener('click', function (event) {
            event.preventDefault(); // Impede a navegação padrão
            const url = this.getAttribute('href');
            
            if (url && url !== '#') {
                // Define a URL do iframe
                siteIframe.src = url;

                // Oculta o conteúdo principal e mostra o iframe
                mainContent.style.display = 'none';
                iframeContainer.style.display = 'block';
            }
        });
    });

    // Opcional: Lógica para voltar à página inicial (ex: clicando no logo)
    const homeLink = document.getElementById('home-logo-link');
    if(homeLink) {
        homeLink.addEventListener('click', function(event) {
            event.preventDefault();
            // Mostra o conteúdo principal e oculta o iframe
            mainContent.style.display = 'block';
            iframeContainer.style.display = 'none';
            // Limpa o iframe
            siteIframe.src = 'about:blank';
        });
    }
});
</script>

<?php require_once __DIR__ . '/../templates/footer.php'; ?>
