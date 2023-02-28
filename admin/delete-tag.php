<?php
require __DIR__ . '/../config/database.php';

// delete-tag.phpのURLにtag_IDの値が含まれている場合
if (isset($_GET['tag_ID'])){
    $tagID = filter_var($_GET['tag_ID'], FILTER_SANITIZE_NUMBER_INT);

    // 管理者以外（投稿者）がアクセスした場合
    if ($_SESSION['role_ID'] === 0){
        $_SESSION['nonadmin_error'] = 'アクセス権限がありません';
        header('location: ' . ROOT_URL . 'message.php');
        die();
    }
    
    // DBの内容を上書き
    $connection = dbconnect();
    $stmt = $connection->prepare('UPDATE tags SET updated_at=CURRENT_TIMESTAMP(), is_deleted=1 WHERE tag_ID=? LIMIT 1');
    $stmt->bind_param('i', $tagID);
    $successTags = $stmt->execute();


    // 削除したタグがついていた記事のタグ表示を変更
    $stmt = $connection->prepare('UPDATE posts SET tag_ID=10 WHERE tag_ID=?');
    $stmt->bind_param('i', $tagID);
    $successPosts = $stmt->execute();

    
    // DBの値を取り出す
    $stmt = $connection->prepare('SELECT tag_title FROM tags WHERE tag_ID=? LIMIT 1');
    $stmt->bind_param('i', $tagID);
    $successTagTitle = $stmt->execute();
    $stmt->bind_result($tagTitle);
    $stmt->fetch();
    
    // 全ての動作においてエラーがない場合
    if ($successTags && $successPosts && $successTagTitle){
        $_SESSION['delete_tag_success'] = "タグ「 {$tagTitle} 」が削除されました";
        header('location: ' . ROOT_URL . 'admin/manage-tags.php');
    
    // エラーがある場合
    } else {
        $_SESSION['delete_tag_error'] = "タグ「 {$tagTitle} 」の削除に失敗しました";
        header('location: ' . ROOT_URL . 'admin/manage-tags.php');
    }

// delete-tag.phpのURLにtag_IDの値が含まれていない場合
} else {
    header('location: ' . ROOT_URL . 'admin/manage-tags.php');
    die();
}
?>