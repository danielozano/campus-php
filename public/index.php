<?php
error_reporting(E_ALL);
ini_set('display_errors', 'on');
/**
 * Front controller.
 * Inicializar aquí la aplicación y hacer dispatch.
 */
include_once '../vendor/autoload.php';
include_once '../app/App.php';

$request = Framework\Http\Request::createFromGlobals();
$app = new App();
$app->run($request);