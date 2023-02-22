<?php
require __DIR__ . '/../config/database.php';

// URLにpost_IDの値が含まれている場合
if (isset($_GET['post_ID'])){
    $postID = filter_var($_GET['post_ID'], FILTER_SANITIZE_NUMBER_INT);
    $isDeleted = filter_var($_POST['is_deleted'], FILTER_SANITIZE_NUMBER_INT);
    
    // DBの値を上書き
    $connection = dbconnect();
    $stmt = $connection->prepare('UPDATE posts SET updated_at=CURRENT_TIMESTAMP(), is_deleted=1 WHERE post_ID=? LIMIT 1');
    $stmt->bind_param('i', $postID);
    $success = $stmt->execute();

    // DBの値を取り出す
    $stmt = $connection->prepare('SELECT title FROM posts WHERE post_ID=? LIMIT 1');
    $stmt->bind_param('i', $postID);
    $success = $stmt->execute();
    $stmt->bind_result($title);
    $stmt->fetch();

    if (isset($title)){
        $_SESSION['delete_post_success'] = "投稿「 " . h($title) . "」が削除されました";
        header('location: ' . ROOT_URL . 'admin/');
    } else {
        $_SESSION['delete_post_error'] = "投稿「 ". h($title) . "」の削除に失敗しました";
        header('location: ' . ROOT_URL . 'admin/');
    }

// URLにpost_IDの値が含まれていない場合
} else {
    header('location: ' . ROOT_URL . 'admin/');
    die();
}
?>