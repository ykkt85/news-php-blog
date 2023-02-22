<?php
require __DIR__ . '/../../partials/header.php';

// セッションの値を持たずにadmin以下のファイルにアクセスした場合
if(!isset($_SESSION['user_ID'])){
    header('location: ' . ROOT_URL);
}
?>