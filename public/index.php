<?php

require_once __DIR__.'/../vendor/autoload.php';

use Symfony\Component\HttpFoundation\Request;

define('APP_CONFIG', include(__DIR__ . '/../src/config/common.php'));

session_start();

$request = Request::createFromGlobals();

$path = $request->getPathInfo();
$router = new core\Router();
$response = $router->dispatch($path);
$response->send();