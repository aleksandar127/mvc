<?php


use \App\Models\Users;

function sanitize($dirty)
{
    return htmlentities($dirty, ENT_QUOTES, 'UTF-8');
}

function dnd($val)
{
    echo '<pre>';
    var_dump($val);
    echo '</pre>';
    die();
}

function currentUser()
{
    return \App\Models\Users::currentLoggedInUser();
}

function posted_values($post)
{
    $clean = [];
    foreach ($post as $key => $val) {
        $clean[$key] = sanitize($val);
    }
    return $clean;
}

function currentPage()
{
    $currentPage = $_SERVER['REQUEST_URI'];
    if ($currentPage == ROOT || $_SERVER['QUERY_STRING'] == 'path=home/index') {
        return ROOT . 'home';
    }
    return ROOT . str_replace('path=', '', $_SERVER['QUERY_STRING']);
}
