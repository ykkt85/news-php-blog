<?php
require 'config/database.php';

// スポーツタグのついてる記事一覧ページに移動
header('location: '.ROOT_URL.'tag-posts.php?tag_ID=6');
die();
?>