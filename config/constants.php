<?php
session_start();

// DB接続に必要な情報
define('ROOT_URL', 'http://localhost:8888/TsukubaUniversityNews/');
define('DB_HOST', 'localhost:8889');
define('DB_USER', 'root');
define('DB_PASS', 'root');
define('DB_NAME', 'news-php-blog');

// htmlspecialcharsを省略
function h($value){
    return htmlspecialchars($value, ENT_QUOTES);
}
?>