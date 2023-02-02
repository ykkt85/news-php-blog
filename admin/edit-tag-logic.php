<?php
require 'config/database.php';

// edit-tag.phpからフォームが送信された場合
if(isset($_POST['submit'])){
    $tagID = filter_var($_POST['tag_ID'], FILTER_SANITIZE_NUMBER_INT);
    $tagTitle = filter_var($_POST['tag_title'], FILTER_SANITIZE_FULL_SPECIAL_CHARS);
    $description = filter_var($_POST['description'], FILTER_SANITIZE_FULL_SPECIAL_CHARS);

    // タグ名が空欄の場合
    if (!$tagTitle){
        $_SESSION['edit-tag-error'] = "タグ名を入力してください";
    
    // 説明が空欄の場合
    } elseif (!$description){ 
        $_SESSION['edit-tag-error'] = "説明を入力してください";

    } else {
        $query = "UPDATE tags SET tag_title='$tagTitle', description='$description', updated_at=CURRENT_TIMESTAMP() WHERE tag_ID=$tagID LIMIT 1";
        $result = mysqli_query($connection, $query);

        if (mysqli_errno($connection)){
            $_SESSION['edit-tag-error'] = "タグを編集できませんでした";
        } else {
            $_SESSION['edit-tag-success'] = "タグ「 " . h($tagTitle) . " 」を編集しました";
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