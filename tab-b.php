<?php
require 'config/database.php';

// ミニ特集タグのついてる記事一覧ページに移動
header('location: '.ROOT_URL.'tag-posts.php?tag_ID=3');
die();
?>