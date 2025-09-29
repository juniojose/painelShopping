<?php

/**
 * Gerencia o upload de um arquivo, salvando-o em um subdiretório específico.
 *
 * @param array|null $file O arquivo de $_FILES.
 * @param string $subdirectory O subdiretório dentro de 'public/uploads/' (ex: 'logos', 'banners').
 * @param string|null $current_path O caminho do arquivo atual (para substituição).
 * @return string|null O novo caminho do arquivo, o caminho atual se nenhum novo arquivo for enviado, ou null em caso de falha.
 */
function handle_file_upload($file, $subdirectory, $current_path = null)
{
    $basePath = realpath(__DIR__ . '/../..');
    $upload_dir = $basePath . '/public/uploads/' . $subdirectory . '/';

    // Garante que o diretório de upload exista.
    if (!is_dir($upload_dir)) {
        if (!mkdir($upload_dir, 0775, true)) {
            error_log("Falha ao criar o diretório de upload: " . $upload_dir);
            return null;
        }
    }

    // Se nenhum arquivo novo for enviado, retorna o caminho atual.
    if (!isset($file) || $file['error'] === UPLOAD_ERR_NO_FILE) {
        return $current_path;
    }

    // Verifica outros erros de upload.
    if ($file['error'] !== UPLOAD_ERR_OK) {
        error_log("Erro de upload de arquivo: " . $file['error']);
        return null;
    }

    $file_tmp_path = $file['tmp_name'];
    $file_name = basename($file['name']);
    $file_ext = strtolower(pathinfo($file_name, PATHINFO_EXTENSION));
    $allowed_ext = ['jpg', 'jpeg', 'png', 'gif', 'svg', 'webp'];

    if (in_array($file_ext, $allowed_ext)) {
        $new_file_name = uniqid('', true) . '.' . $file_ext;
        $destination = $upload_dir . $new_file_name;

        if (move_uploaded_file($file_tmp_path, $destination)) {
            // Remove o arquivo antigo se um novo foi enviado com sucesso.
            if ($current_path && file_exists($basePath . '/public' . $current_path)) {
                unlink($basePath . '/public' . $current_path);
            }
            // Retorna o caminho relativo para salvar no banco.
            return '/uploads/' . $subdirectory . '/' . $new_file_name;
        }
    }

    error_log("Falha ao mover o arquivo ou tipo de arquivo inválido.");
    return null;
}


/**
 * Garante que a requisição seja do tipo POST antes de executar uma função.
 * Se não for POST, redireciona para a URL de fallback.
 * 
 * @param callable $callback A função a ser executada.
 * @param string $fallback_url A URL para redirecionar em caso de falha.
 */
function handlePostRequest(callable $callback, $fallback_url) {
    if ($_SERVER['REQUEST_METHOD'] === 'POST') {
        $callback();
    } else {
        redirect($fallback_url);
    }
}

/**
 * Redireciona o usuário para uma URL especificada e termina a execução do script.
 * 
 * @param string $url A URL de destino.
 */
function redirect($url) {
    header('Location: ' . $url);
    exit;
}

/**
 * Verifica se uma cor hexadecimal é considerada "clara".
 * Usado para determinar se o texto sobre a cor deve ser escuro ou claro.
 *
 * @param string $hex_color A cor no formato hexadecimal (ex: #RRGGBB ou #RGB).
 * @return bool Retorna true se a cor for clara, false se for escura.
 */
function is_color_light($hex_color) {
    $hex_color = ltrim($hex_color, '#');

    if (strlen($hex_color) == 3) {
        $r = hexdec(substr($hex_color, 0, 1) . substr($hex_color, 0, 1));
        $g = hexdec(substr($hex_color, 1, 1) . substr($hex_color, 1, 1));
        $b = hexdec(substr($hex_color, 2, 1) . substr($hex_color, 2, 1));
    } else if (strlen($hex_color) == 6) {
        $r = hexdec(substr($hex_color, 0, 2));
        $g = hexdec(substr($hex_color, 2, 2));
        $b = hexdec(substr($hex_color, 4, 2));
    } else {
        // Retorna um padrão (escuro) se o formato for inválido
        return false;
    }

    // Fórmula para calcular o brilho percebido
    $brightness = (($r * 299) + ($g * 587) + ($b * 114)) / 1000;

    return $brightness > 155; // Limiar comum para brilho
}

/**
 * Extrai o ID de um vídeo do YouTube de uma URL.
 *
 * @param string $url A URL do vídeo do YouTube.
 * @return string|null O ID do vídeo ou null se não for uma URL válida do YouTube.
 */
function get_youtube_video_id($url) {
    if (preg_match('/(?:https?:\/\/)?(?:www\.)?(?:youtube\.com\/(?:[^\/\n\s]+\/\S+\/|(?:v|e(?:mbed)?)\/|\S*?[?&]v=)|youtu\.be\/)([a-zA-Z0-9_-]{11})/', $url, $matches)) {
        return $matches[1];
    }
    return null;
}

/**
 * Gera o código de incorporação para um vídeo do YouTube.
 *
 * @param string $video_id O ID do vídeo do YouTube.
 * @return string O código HTML para incorporar o vídeo.
 */
function get_youtube_embed_code($video_id) {
    return '<iframe width="100%" height="100%" src="https://www.youtube.com/embed/' . htmlspecialchars($video_id) . '" frameborder="0" allow="accelerometer; autoplay; clipboard-write; encrypted-media; gyroscope; picture-in-picture" allowfullscreen></iframe>';
}
