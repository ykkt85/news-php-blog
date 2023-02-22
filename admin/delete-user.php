<?php
require __DIR__ . '/../config/database.php';

// user_IDのセッション値を受け取った場合
if (isset($_GET['user_ID'])){
    $userID = filter_var($_GET['user_ID'], FILTER_SANITIZE_NUMBER_INT);
    $isDeleted = filter_var($_POST['is_deleted'], FILTER_SANITIZE_NUMBER_INT);
    
    // DBの内容を上書き
    $connection = dbconnect();
    $stmt = $connection->prepare('UPDATE users SET updated_at=CURRENT_TIMESTAMP(), is_deleted=1 WHERE user_ID=? LIMIT 1');
    $stmt->bind_param('i', $userID);
    $successUpdate = $stmt->execute();

    // DBの内容を取り出す
    $stmt = $connection->prepare('SELECT email FROM users WHERE user_ID=? LIMIT 1');
    $stmt->bind_param('i', $userID);
    $successSelect = $stmt->execute();
    $stmt->bind_result($email);
    $stmt->fetch();
    
    // エラーがない場合
    if ($successUpdate && $successSelect){
        $_SESSION['delete_user_success'] = "ユーザー {$email} が削除されました";
        header('location: ' . ROOT_URL . 'admin/manage-users.php');
    // エラーがある場合
    } else {
        $_SESSION['delete_user_error'] = "ユーザー {$email} の削除に失敗しました";
        header('location: ' . ROOT_URL . 'admin/manage-users.php');
    }

// セッション値がない場合
} else {
    header('location: ' . ROOT_URL . 'admin/manage-users.php');
    die();
}
?>