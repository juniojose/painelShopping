<?php

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
