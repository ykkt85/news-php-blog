<?php
require 'config/database.php';

if (isset($_GET['tag_ID'])){
    $tag_ID = filter_var($_GET['tag_ID'], FILTER_SANITIZE_NUMBER_INT);
    //$is_deleted = filter_var($_POST['is_deleted'], FILTER_SANITIZE_NUMBER_INT);
    
    // DBの内容を上書き
    $update_tag_query = "UPDATE tags SET is_deleted=1 WHERE tag_ID=$tag_ID LIMIT 1";
    $update_tag_result = mysqli_query($connection, $update_tag_query);

    // 削除したタグがついていた記事のタグ表示を変更
    $update_deleted_query = "UPDATE posts SET tag_ID=10 WHERE tag_ID=$tag_ID";
    $update_deleted_result = mysqli_query($connection, $update_deleted_query);
    
    // DBの内容を取り出す
    $fetch_tag_query = "SELECT * FROM tags WHERE tag_ID=$tag_ID LIMIT 1";
    $fetch_tag_result = mysqli_query($connection, $fetch_tag_query);
    $tag = mysqli_fetch_assoc($fetch_tag_result);
    
    // エラーがない場合
    if (!mysqli_errno($connection)){
        $_SESSION['delete-tag-success'] = "タグ「 {$tag['tag_title']} 」が削除されました";
        header('location: ' . ROOT_URL . 'admin/manage-tags.php');
    
    } else {
        $_SESSION['delete-tag-error'] = "タグ「 {$tag['tag_title']} 」の削除に失敗しました";
        header('location: ' . ROOT_URL . 'admin/manage-tags.php');
    }

// セッション値がない場合
} else {
    header('location: ' . ROOT_URL . 'admin/manage-tags.php');
    die();
}
?>