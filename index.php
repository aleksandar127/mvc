<?php



require(__DIR__.'/vendor/autoload.php');
include('./config/constants.php');
include('./core/helpers.php');
session_start();

$db=new \Core\DB();
$request = new \Core\Request();
if(! \Core\Session::exists(CURRENT_USER_SESSION_NAME) &&  \Core\Cookie::exists(REMEMBER_ME_COOKIE_NAME)){
    \App\Models\Users::loginUserFromCookie();
}
$router = new \Core\Router($request);




