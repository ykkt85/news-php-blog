<?php
require 'config/database.php';

// delete-tag.phpのURLにtag_IDの値が含まれている場合
if (isset($_GET['tag_ID'])){
    $tagID = filter_var($_GET['tag_ID'], FILTER_SANITIZE_NUMBER_INT);
    
    // DBの内容を上書き
    $updateTagQuery = "UPDATE tags SET is_deleted=1 WHERE tag_ID=$tagID LIMIT 1";
    $updateTagResult = mysqli_query($connection, $updateTagQuery);

    // 削除したタグがついていた記事のタグ表示を変更
    $updateDeletedQuery = "UPDATE posts SET tag_ID=10 WHERE tag_ID=$tagID";
    $updateDeletedResult = mysqli_query($connection, $updateDeletedQuery);
    
    // DBの値を取り出す
    $fetchTagQuery = "SELECT * FROM tags WHERE tag_ID=$tagID LIMIT 1";
    $fetchTagResult = mysqli_query($connection, $fetchTagQuery);
    $tag = mysqli_fetch_assoc($fetchTagResult);
    
    // エラーがない場合
    if (!mysqli_errno($connection)){
        $_SESSION['delete-tag-success'] = "タグ「 {$tag['tag_title']} 」が削除されました";
        header('location: ' . ROOT_URL . 'admin/manage-tags.php');
    
    // エラーがある場合
    } else {
        $_SESSION['delete-tag-error'] = "タグ「 {$tag['tag_title']} 」の削除に失敗しました";
        header('location: ' . ROOT_URL . 'admin/manage-tags.php');
    }

// delete-tag.phpのURLにtag_IDの値が含まれていない場合
} else {
    header('location: ' . ROOT_URL . 'admin/manage-tags.php');
    die();
}
?>