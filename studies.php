<?php
require 'config/database.php';

// 研究タグのついてる記事一覧ページに移動
header('location: '.ROOT_URL.'tag-posts.php?tag_ID=7');
die();
?>