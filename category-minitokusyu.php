<?php
require __DIR__ . '/config/database.php';

// ミニ特集カテゴリの記事一覧ページに移動
header('location: ' . ROOT_URL . 'category-posts.php?category_ID=3');
die();
?>