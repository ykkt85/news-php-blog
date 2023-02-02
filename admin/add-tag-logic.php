<?php
require 'config/database.php';

// add-tag.phpのフォームから値が渡された場合
if(isset($_POST['submit'])){
    $tagTitle = filter_var($_POST['tag_title'], FILTER_SANITIZE_FULL_SPECIAL_CHARS);
    $description = filter_var($_POST['description'], FILTER_SANITIZE_FULL_SPECIAL_CHARS);

    //フォームの値確認
    // タグ名が空の場合
    if (!$tagTitle){
        $_SESSION['add-tag-error'] = "タグ名を入力してください";
    
    // 説明が空の場合
    } elseif (!$description){
        $_SESSION['add-tag-error'] = "説明を入力してください";
    
    } else {
        // タグ名に被りがないか確認
        $checkTagQuery = "SELECT * FROM tags WHERE tag_title='$tagTitle'";
        $checkTagResult = mysqli_query($connection, $checkTagQuery);
        if (mysqli_num_rows($checkTagResult) > 0){
            $_SESSION['add-tag-error'] = "そのタグ名は既に登録されています"; 
        }
    }

    // $_SESSION['add-tag-error']に何らかの値が含まれている場合
    if (isset($_SESSION['add-tag-error'])){
        // add-tagページに値を渡してリダイレクト
        $_SESSION['add-tag-data'] = $_POST;
        header('location: ' . ROOT_URL . 'admin/add-tag.php');
        die();

    } else {
        // DBに値を記録
        $insertTagQuery = "INSERT INTO tags (tag_title, description) VALUES('$tagTitle', '$description')";
        $insertTagResult = mysqli_query($connection, $insertTagQuery);

        // DB接続エラーがある場合
        if (mysqli_errno($connection)) {
            $_SESSION['add-tag-error'] = "タグを登録できません";
            $_SESSION['add-tag-data'] = $_POST;
            header('location: ' . ROOT_URL . 'admin/add-tag.php');
            die();

        } else {
            $_SESSION['add-tag-success'] = "タグ「" . h($tagTitle) . " 」が登録されました";
            header('location: ' . ROOT_URL . 'admin/manage-tags.php');
            die();
        }
    }

// フォームの値が渡されずに画面遷移した場合
} else {
    header('location: ' . ROOT_URL . 'admin/manage-tags.php');
    die();
}
?>