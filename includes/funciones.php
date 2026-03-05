<?php

function debuguear($variable): string
{
    echo "<pre>";
    var_dump($variable);
    echo "</pre>";
    exit;
}
function s($html): string
{
    $s = htmlspecialchars($html);
    return $s;
}


function pagina_actual($path): bool
{
    return $_SERVER['PATH_INFO'] === $path;
}

function is_auth(): bool
{
    if (session_status() != PHP_SESSION_ACTIVE) {
        session_start();
    }
    return isset($_SESSION['nombre']) && !empty($_SESSION);
}

function is_admin(): bool
{
    if (session_status() != PHP_SESSION_ACTIVE) {
        session_start();
    }
    return isset($_SESSION['admin']) && !empty($_SESSION['admin']);
}
