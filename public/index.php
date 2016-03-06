<?php
error_reporting(E_ALL | E_NOTICE);
ini_set('display_errors', 'on');
/**
 * Front controller.
 * Inicializar aquí la aplicación y hacer dispatch.
 */
$loader = include_once '../vendor/autoload.php';
include_once '../app/App.php';

$request = Framework\Http\Request::createFromGlobals();
$app = new App();
$app::register('loader', $loader);
$app->run($request);