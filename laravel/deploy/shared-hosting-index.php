<?php

/*
|--------------------------------------------------------------------------
| index.php for shared hosting WITHOUT a configurable document root
|--------------------------------------------------------------------------
| Use ONLY when the domain must point at public_html and cannot point at
| laravel/public. See docs/DEPLOYMENT.md, section A (Layout 2).
|
|   /home/<account>/hudaya/          ← the Laravel app (NOT web-accessible)
|   /home/<account>/public_html/     ← contents of laravel/public + this file as index.php
|
| Change APP_DIR below if you named the app folder differently.
*/

use Illuminate\Foundation\Application;
use Illuminate\Http\Request;

define('LARAVEL_START', microtime(true));

$appDir = dirname(__DIR__).'/hudaya';

if (file_exists($maintenance = $appDir.'/storage/framework/maintenance.php')) {
    require $maintenance;
}

require $appDir.'/vendor/autoload.php';

/** @var Application $app */
$app = require_once $appDir.'/bootstrap/app.php';

// public_html is the public folder: images, build files and uploads are resolved from here.
$app->usePublicPath(__DIR__);

$app->handleRequest(Request::capture());
