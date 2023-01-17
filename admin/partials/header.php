<?php
require '../partials/header.php';

// セッションの値を持たずにadminにアクセスした場合
if(!isset($_SESSION['user_ID'])){
    header('location: ' . ROOT_URL);
}
?>