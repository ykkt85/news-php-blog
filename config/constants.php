<?php
session_start();

//.envファイル読み込み
require __DIR__ . '/../vendor/autoload.php';
$dotenv = Dotenv\Dotenv::createImmutable(__DIR__);
$dotenv->load();

define('ROOT_URL', 'http://localhost:8888/TsukubaUniversityNews/');
define('DB_HOST', $_ENV['DB_HOST']);
define('DB_USER', $_ENV['DB_USER']);
define('DB_PASS', $_ENV['DB_PASS']);
define('DB_NAME', $_ENV['DB_NAME']);

// htmlspecialcharsを省略
function h($value){
    return htmlspecialchars($value, ENT_QUOTES);
}
?>