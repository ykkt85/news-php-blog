<?php
require __DIR__ . '/config/database.php';

// 特集タグのついてる記事一覧ページに移動
header('location: '.ROOT_URL.'category-posts.php?category_ID=1');
die();
?>