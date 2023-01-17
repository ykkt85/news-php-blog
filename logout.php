<?php
require 'config/database.php';

// セッションを破棄
session_destroy();
header('location: ' . ROOT_URL);
die();
?>