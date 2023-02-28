<?php
require __DIR__ . '/../config/database.php';

// edit-tag.phpからフォームが送信された場合
if(isset($_POST['submit'])){
    $tagID = filter_var($_POST['tag_ID'], FILTER_SANITIZE_NUMBER_INT);
    $tagTitle = filter_var($_POST['tag_title'], FILTER_SANITIZE_FULL_SPECIAL_CHARS);
    $description = filter_var($_POST['description'], FILTER_SANITIZE_FULL_SPECIAL_CHARS);

    // 管理者以外（投稿者）がアクセスした場合
    if ($_SESSION['role_ID'] === 0){
        $_SESSION['nonadmin_error'] = 'アクセス権限がありません';
        header('location: ' . ROOT_URL . 'message.php');
        die();
    }

    // タグ名が空欄の場合
    if (!$tagTitle){
        $_SESSION['edit_tag_error'] = "タグ名を入力してください";
    
    // 説明が空欄の場合
    } elseif (!$description){ 
        $_SESSION['edit_tag_error'] = "説明を入力してください";

    } else {
        $connection = dbconnect();
        $stmt = $connection->prepare('UPDATE tags SET tag_title=?, description=?, updated_at=CURRENT_TIMESTAMP() WHERE tag_ID=? LIMIT 1');
        $stmt->bind_param('ssi', $tagTitle, $description, $tagID);
        $success = $stmt->execute();
        if (!$success){
            $_SESSION['edit_tag_error'] = "タグを編集できませんでした";
        } else {
            $_SESSION['edit_tag_success'] = "タグ「" . h($tagTitle) . "」を編集しました";
        }
    }

    header('location: ' . ROOT_URL . 'admin/manage-tags.php');
    die();

// edit-tag.phpからフォームが送信されていない場合
} else {
    header('location: ' . ROOT_URL . 'admin/manage-tag.php');
    die();
}
?>