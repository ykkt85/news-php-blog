<?php
require __DIR__ . '/config/database.php';

// セッションを破棄
session_destroy();
header('location: ' . ROOT_URL);
die();
?>