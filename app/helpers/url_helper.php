<?php

function url_assets($path = '')
{
    return BASE_URL . 'public/' . trim($path, '/');
}

function base_url()
{
    return BASE_URL;
}

function getHead($data = [])
{
    $viewPath = __DIR__ . '/../views/templates/head.php';
    require_once $viewPath;
}

function getHeader($data = [])
{
    $viewPath = __DIR__ . '/../views/templates/header.php';
    require_once $viewPath;
}

function getFooter($data = [])
{
    $viewPath = __DIR__ . '/../views/templates/footer.php';
    require_once $viewPath;
}

function getJS($data = [])
{
    $viewPath = __DIR__ . '/../views/templates/js.php';
    require_once $viewPath;
}

function getPop($data = [])
{
    $viewPath = __DIR__ . '/../views/templates/pop.php';
    require_once $viewPath;
}

function getNavegador($data = [])
{
    $viewPath = __DIR__ . '/../views/templates/navegador.php';
    require_once $viewPath;
}
