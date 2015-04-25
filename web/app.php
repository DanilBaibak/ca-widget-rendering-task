<?php

use Symfony\Component\HttpFoundation\Request;

require_once __DIR__.'/../app/bootstrap.php.cache';
require_once __DIR__.'/../app/AppKernel.php';

$env = isset($_SERVER['SYMFONY_ENV']) ? $_SERVER['SYMFONY_ENV'] : 'prod';
$dbg = isset($_SERVER['SYMFONY_DEBUG']) ? $_SERVER['SYMFONY_DEBUG'] : 1;

$kernel = new AppKernel($env, $dbg);
$kernel->loadClassCache();

$request = Request::createFromGlobals();
$response = $kernel->handle($request);
$response->send();
$kernel->terminate($request, $response);