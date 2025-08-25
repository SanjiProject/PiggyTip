<?php
namespace App;

ini_set('display_errors', '1');
error_reporting(E_ALL);

if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

define('APP_BASE_PATH', realpath(__DIR__ . '/..'));

$envPath = APP_BASE_PATH . '/.env';
if (is_file($envPath)) {
    $lines = file($envPath, FILE_IGNORE_NEW_LINES | FILE_SKIP_EMPTY_LINES);
    foreach ($lines as $line) {
        if (str_starts_with(trim($line), '#')) continue;
        [$k, $v] = array_map('trim', array_pad(explode('=', $line, 2), 2, ''));
        if ($k !== '') {
            $_ENV[$k] = $v;
            $_SERVER[$k] = $v;
            if (function_exists('putenv')) { \putenv($k . '=' . $v); }
        }
    }
}

require_once __DIR__ . '/Lib/Helpers.php';
require_once __DIR__ . '/Lib/Validator.php';

// Load centralized configuration (falls back to environment variables inside the file)
/** @var array $APP_CONFIG */
$APP_CONFIG = require APP_BASE_PATH . '/config/app.php';
if (!empty($APP_CONFIG['app']['timezone'])) {
    @date_default_timezone_set((string)$APP_CONFIG['app']['timezone']);
}

spl_autoload_register(function ($class) {
    if (str_starts_with($class, 'App\\')) {
        $relative = str_replace('App\\', '', $class);
        $relative = str_replace('\\', DIRECTORY_SEPARATOR, $relative);
        $path = __DIR__ . DIRECTORY_SEPARATOR . $relative . '.php';
        if (is_file($path)) require_once $path;
    }
});

use PDO;

class DB {
    private static ?PDO $pdoInstance = null;

    public static function pdo(): PDO {
        if (self::$pdoInstance instanceof PDO) {
            return self::$pdoInstance;
        }
        global $APP_CONFIG;
        $dbCfg = $APP_CONFIG['database'] ?? [];
        $host = (string)($dbCfg['host'] ?? '127.0.0.1');
        $port = (int)($dbCfg['port'] ?? 3306);
        $db   = (string)($dbCfg['database'] ?? 'piggytip');
        $user = (string)($dbCfg['username'] ?? 'piggytip');
        $pass = (string)($dbCfg['password'] ?? 'PASSWORD');
        $charset = (string)($dbCfg['charset'] ?? 'utf8mb4');
        $dsn = "mysql:host={$host};port={$port};dbname={$db};charset={$charset}";
        $options = [
            PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
            PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
            PDO::ATTR_EMULATE_PREPARES => false,
        ];
        try {
            self::$pdoInstance = new PDO($dsn, $user, $pass, $options);
            return self::$pdoInstance;
        } catch (\Throwable $e) {
            http_response_code(500);
            echo 'DB connection failed: ' . htmlspecialchars($e->getMessage(), ENT_QUOTES, 'UTF-8');
            exit;
        }
    }
}

class Config {
    public static function appUrl(): string {
        global $APP_CONFIG;
        // Prefer config/app.php; if not provided, auto-detect from the current request
        $base = $APP_CONFIG['app']['url'] ?? self::detectBaseUrl();
        // Normalize: strip trailing /public if present
        if (str_ends_with($base, '/public')) { $base = substr($base, 0, -7); }
        return rtrim($base, '/');
    }
    public static function detectBaseUrl(): string {
        $scheme = (!empty($_SERVER['HTTPS']) && $_SERVER['HTTPS'] !== 'off') ? 'https' : 'http';
        $host = $_SERVER['HTTP_HOST'] ?? 'localhost';
        $script = $_SERVER['SCRIPT_NAME'] ?? '';
        $dir = rtrim(str_replace(['/public/index.php','/index.php'], '', $script), '/');
        return "$scheme://$host$dir";
    }
    public static function basePathFromDocroot(): string { $script = $_SERVER['SCRIPT_NAME'] ?? ''; return rtrim(str_replace('/index.php', '', $script), '/'); }
}
