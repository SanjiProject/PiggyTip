<?php
require_once __DIR__ . '/app/Bootstrap.php';

use App\Controllers\AuthController;
use App\Controllers\DashboardController;
use App\Controllers\PageController;
use App\Controllers\ApiController;
use App\Controllers\ProfileController;
use App\Middleware\Auth;
use App\Lib\Helpers;

$uri = parse_url($_SERVER['REQUEST_URI'], PHP_URL_PATH);
$base = rtrim(App\Config::basePathFromDocroot(), '/');
$baseNoPublic = rtrim(preg_replace('#/public$#','', $base), '/');
foreach ([$base, $baseNoPublic] as $b) {
    if ($b !== '' && $b !== '/' && str_starts_with($uri, $b)) {
        $uri = substr($uri, strlen($b));
        break;
    }
}
// If routed via root front controller, requests may still include /public prefix
if (str_starts_with($uri, '/public')) {
    $uri = substr($uri, 7);
    if ($uri === '' || $uri === false) { $uri = '/'; }
}
$route = '/' . ltrim($uri, '/');
if ($route === '//' || $route === '') { $route = '/'; }
// Support query-based routing and ignore accidental extra query strings after the route
if (!isset($_SERVER['REDIRECT_URL']) && isset($_GET['route'])) {
    $r = (string)$_GET['route'];
    $rPath = parse_url($r, PHP_URL_PATH);
    if ($rPath === null || $rPath === false) { $rPath = $r; }
    $route = '/' . ltrim($rPath, '/');
}

switch (true) {
    case $route === '/favicon.ico':
        header('Content-Type: image/webp');
        header('Cache-Control: public, max-age=604800');
        readfile(APP_BASE_PATH . '/img/logo.webp');
        exit;

    case $route === '/icon/tron.svg':
        header('Content-Type: image/svg+xml');
        header('Cache-Control: public, max-age=86400');
        $cacheDir = APP_BASE_PATH . '/assets/cache';
        $cacheFile = $cacheDir . '/tron-trx-logo.svg';
        if (is_file($cacheFile)) { readfile($cacheFile); exit; }
        $remote = 'https://cryptologos.cc/logos/tron-trx-logo.svg?v=040';
        $svg = null;
        // Try cURL first
        if (function_exists('curl_init')) {
            $ch = curl_init($remote);
            curl_setopt_array($ch, [CURLOPT_RETURNTRANSFER=>true, CURLOPT_FOLLOWLOCATION=>true, CURLOPT_TIMEOUT=>10, CURLOPT_CONNECTTIMEOUT=>5, CURLOPT_USERAGENT=>'tipsupport-icon-proxy']);
            $res = curl_exec($ch);
            $code = (int)curl_getinfo($ch, CURLINFO_HTTP_CODE);
            curl_close($ch);
            if ($code === 200 && is_string($res) && $res !== '') { $svg = $res; }
        }
        // Fallback to file_get_contents
        if ($svg === null) {
            $ctx = stream_context_create(['http'=>['timeout'=>8,'header'=>"User-Agent: tipsupport-icon-proxy\r\n"],'https'=>['timeout'=>8,'header'=>"User-Agent: tipsupport-icon-proxy\r\n"]]);
            $res = @file_get_contents($remote, false, $ctx);
            if ($res !== false) { $svg = $res; }
        }
        if ($svg) {
            if (!is_dir($cacheDir)) @mkdir($cacheDir, 0777, true);
            @file_put_contents($cacheFile, $svg);
            echo $svg; exit;
        }
        // Last-resort fallback
        readfile(APP_BASE_PATH . '/assets/fallbacks/tron.svg');
        exit;

    case $route === '/sitemap.xml':
        PageController::sitemap();
        break;

    case $route === '/':
        echo PageController::landing();
        break;

    case $route === '/login' && $_SERVER['REQUEST_METHOD'] === 'GET':
        echo AuthController::loginForm();
        break;
    case $route === '/login' && $_SERVER['REQUEST_METHOD'] === 'POST':
        Helpers::requireCsrf();
        AuthController::login();
        break;
    case $route === '/logout':
        AuthController::logout();
        break;

    case $route === '/register' && $_SERVER['REQUEST_METHOD'] === 'GET':
        echo AuthController::registerForm();
        break;
    case $route === '/register' && $_SERVER['REQUEST_METHOD'] === 'POST':
        Helpers::requireCsrf();
        AuthController::register();
        break;

    case $route === '/dashboard':
        Auth::requireLogin();
        echo DashboardController::index();
        break;
    case $route === '/dashboard/payments':
        Auth::requireLogin();
        echo DashboardController::payments();
        break;

    case $route === '/dashboard/links':
        Auth::requireLogin();
        echo DashboardController::links();
        break;
    case $route === '/links/create' && $_SERVER['REQUEST_METHOD'] === 'POST':
        Auth::requireLogin();
        Helpers::requireCsrf();
        DashboardController::createLink();
        break;
    case preg_match('#^/links/update/(\\d+)$#', $route, $m):
        Auth::requireLogin();
        Helpers::requireCsrf();
        DashboardController::updateLink((int)$m[1]);
        break;
    case preg_match('#^/links/delete/(\\d+)$#', $route, $m):
        Auth::requireLogin();
        Helpers::requireCsrf();
        DashboardController::deleteLink((int)$m[1]);
        break;
    case $route === '/links/reorder' && $_SERVER['REQUEST_METHOD'] === 'POST':
        Auth::requireLogin();
        Helpers::requireCsrf();
        ApiController::reorderLinks();
        break;

    case $route === '/dashboard/wallets':
        Auth::requireLogin();
        echo DashboardController::wallets();
        break;
    case $route === '/dashboard/quick-add' && $_SERVER['REQUEST_METHOD'] === 'GET':
        Auth::requireLogin();
        echo DashboardController::quickAdd();
        break;
    case $route === '/dashboard/quick-add' && $_SERVER['REQUEST_METHOD'] === 'POST':
        Auth::requireLogin();
        Helpers::requireCsrf();
        DashboardController::bulkAddPayments();
        break;
    case $route === '/wallets/create' && $_SERVER['REQUEST_METHOD'] === 'POST':
        Auth::requireLogin();
        Helpers::requireCsrf();
        DashboardController::createWallet();
        break;
    case preg_match('#^/wallets/update/(\\d+)$#', $route, $m):
        Auth::requireLogin();
        Helpers::requireCsrf();
        DashboardController::updateWallet((int)$m[1]);
        break;
    case preg_match('#^/wallets/delete/(\\d+)$#', $route, $m):
        Auth::requireLogin();
        Helpers::requireCsrf();
        DashboardController::deleteWallet((int)$m[1]);
        break;

    case $route === '/dashboard/profile':
        Auth::requireLogin();
        echo DashboardController::profile();
        break;
    case $route === '/profile/update' && $_SERVER['REQUEST_METHOD'] === 'POST':
        Auth::requireLogin();
        Helpers::requireCsrf();
        ProfileController::updateProfile();
        break;
    case $route === '/profile/avatar' && $_SERVER['REQUEST_METHOD'] === 'POST':
        Auth::requireLogin();
        Helpers::requireCsrf();
        ProfileController::uploadAvatar();
        break;

    case $route === '/api/track' && $_SERVER['REQUEST_METHOD'] === 'POST':
        ApiController::track();
        break;
    case $route === '/api/comment' && $_SERVER['REQUEST_METHOD'] === 'POST':
        ApiController::postComment();
        break;
    case $route === '/api/comment/delete' && $_SERVER['REQUEST_METHOD'] === 'POST':
        Auth::requireLogin();
        ApiController::deleteComment();
        break;

    case $route === '/export/csv':
        Auth::requireLogin();
        ApiController::exportCsv();
        break;

    case preg_match('#^/r/(\\d+)$#', $route, $m):
        ApiController::redirectLink((int)$m[1]);
        break;

    case $route === '/api/comments':
        ApiController::comments();
        break;
    case $route === '/api/captcha':
        ApiController::captcha();
        break;
    case $route === '/sponsor' && $_SERVER['REQUEST_METHOD'] === 'GET':
        echo PageController::becomeSponsor();
        break;
    case $route === '/sponsor/submit' && $_SERVER['REQUEST_METHOD'] === 'POST':
        ApiController::sponsorSubmit();
        break;

    // Removed /u/{slug} route in favor of username only

    case preg_match('#^/([A-Za-z0-9_]{1,32})$#', $route, $m):
        echo PageController::publicByUsername($m[1]);
        break;

    default:
        http_response_code(404);
        echo PageController::notFound();
}