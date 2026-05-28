<?php
declare(strict_types=1);

/**
 * Front Controller
 *
 * Run with the built-in PHP server (development):
 *   php -S localhost:8080 -t public public/index.php
 *
 * Production: point the web-server document root at /public.
 * The .htaccess rewrites all requests here.
 */

// ── Dev-server: serve static files & proxy /assets/ from frontend-template ──
if (PHP_SAPI === 'cli-server') {
    $uri = urldecode(parse_url($_SERVER['REQUEST_URI'], PHP_URL_PATH));

    // Let existing files in /public be served normally (CSS, JS, images …)
    if ($uri !== '/' && is_file(__DIR__ . $uri)) {
        return false;
    }

    // Proxy /assets/* → frontend-template/assets/* (no duplication in dev)
    if (str_starts_with($uri, '/assets/')) {
        $asset = dirname(__DIR__) . '/frontend-template' . $uri;
        if (is_file($asset)) {
            static $mimes = [
                'css'  => 'text/css',
                'js'   => 'application/javascript',
                'png'  => 'image/png',
                'gif'  => 'image/gif',
                'jpg'  => 'image/jpeg',
                'jpeg' => 'image/jpeg',
                'svg'  => 'image/svg+xml',
                'ico'  => 'image/x-icon',
                'woff' => 'font/woff',
                'woff2'=> 'font/woff2',
                'ttf'  => 'font/ttf',
                'swf'  => 'application/x-shockwave-flash',
            ];
            $ext = strtolower(pathinfo($asset, PATHINFO_EXTENSION));
            header('Content-Type: ' . ($mimes[$ext] ?? 'application/octet-stream'));
            readfile($asset);
            exit;
        }
        http_response_code(404);
        exit;
    }
}

// ── Bootstrap ──────────────────────────────────────────────────────────────
define('ROOT',      dirname(__DIR__));
define('APP',       ROOT . '/app');
define('TEMPLATES', ROOT . '/templates');
define('DATA',      ROOT . '/data');

require APP . '/Core/Router.php';
require APP . '/Core/View.php';
require APP . '/Repositories/JsonOfferRepository.php';
require APP . '/Repositories/JsonEmployerRepository.php';
require APP . '/Controllers/HomeController.php';
require APP . '/Controllers/OfferController.php';
require APP . '/Controllers/EmployerController.php';

// ── Routes ─────────────────────────────────────────────────────────────────
$router = new \App\Core\Router();

$router->get('/',                  [\App\Controllers\HomeController::class,     'index']);
$router->get('/offers',            [\App\Controllers\OfferController::class,    'index']);
$router->get('/offers/{id}',       [\App\Controllers\OfferController::class,    'show']);
$router->get('/employers/{id}',    [\App\Controllers\EmployerController::class, 'show']);

$router->dispatch(
    $_SERVER['REQUEST_METHOD'],
    parse_url($_SERVER['REQUEST_URI'], PHP_URL_PATH)
);
