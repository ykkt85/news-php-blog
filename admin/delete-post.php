<?php
require __DIR__ . '/../config/database.php';

// URLにpost_IDの値が含まれている場合
if (isset($_GET['post_ID'])){
    $postID = filter_var($_GET['post_ID'], FILTER_SANITIZE_NUMBER_INT);
    $isDeleted = filter_var($_POST['is_deleted'], FILTER_SANITIZE_NUMBER_INT);

    // DBのuser_IDとログインユーザーが異なる場合
    $connection = dbconnect();
    $stmt = $connection->prepare('SELECT user_ID FROM posts WHERE post_ID=? AND is_deleted=0 LIMIT 1');
    $stmt->bind_param('i', $postID);
    $stmt->execute();
    $stmt->bind_result($userID);
    $stmt->fetch();
    if ($_SESSION['user_ID'] !== $userID){
        $_SESSION['nonadmin_error'] = 'アクセス権限がありません';
        header('location: ' . ROOT_URL . 'message.php');
        die();
    }
    
    // DBの値を上書き
    $connection = dbconnect();
    $stmt = $connection->prepare('UPDATE posts SET updated_at=CURRENT_TIMESTAMP(), is_deleted=1 WHERE post_ID=? LIMIT 1');
    $stmt->bind_param('i', $postID);
    $stmt->execute();

    // DBの値を取り出す
    $stmt = $connection->prepare('SELECT title FROM posts WHERE post_ID=? LIMIT 1');
    $stmt->bind_param('i', $postID);
    $stmt->execute();
    $stmt->bind_result($title);
    $stmt->fetch();

    // エラーの有無を確認
    if (isset($title)){
        $_SESSION['delete_post_success'] = "投稿「 " . h($title) . "」が削除されました";
    } else {
        $_SESSION['delete_post_error'] = "投稿「 ". h($title) . "」の削除に失敗しました";
    }
    header('location: ' . ROOT_URL . 'admin/');
    die();

// URLにpost_IDの値が含まれていない場合
} else {
    header('location: ' . ROOT_URL . 'admin/');
    die();
}
?>