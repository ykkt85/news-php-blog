<?php
require 'config/database.php';

// 登録ボタンがクリックされた時
if(isset($_POST['submit'])){
    $tag_title = filter_var($_POST['tag_title'], FILTER_SANITIZE_FULL_SPECIAL_CHARS);
    $description = filter_var($_POST['description'], FILTER_SANITIZE_FULL_SPECIAL_CHARS);

    //フォームの値確認
    // タグ名が空の場合
    if (!$tag_title){
        $_SESSION['add-tag-error'] = "タグ名を入力してください";
    
    // 説明が空の場合
    } elseif (!$description){
        $_SESSION['add-tag-error'] = "説明を入力してください";
    
    } else {
        // tagに被りがないか確認
        $check_tag_query = "SELECT * FROM tags WHERE tag_title='$tag_title'";
        $check_tag_result = mysqli_query($connection, $check_tag_query);
        if (mysqli_num_rows($check_tag_result) > 0){
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
        $insert_tag_query = "INSERT INTO tags (tag_title, description) VALUES('$tag_title', '$description')";
        $insert_tag_result = mysqli_query($connection, $insert_tag_query);

        // DB接続エラーがある場合
        if (mysqli_errno($connection)) {
            $_SESSION['add-tag-error'] = "タグを登録できません";
            $_SESSION['add-tag-data'] = $_POST;
            header('location: ' . ROOT_URL . 'admin/add-tag.php');
            die();

        } else {
            $_SESSION['add-tag-success'] = "タグ「 $tag_title 」が登録されました";
            header('location: ' . ROOT_URL . 'admin/manage-tags.php');
            die();
        }
    }

// 登録ボタンがクリックされずに画面遷移した場合
} else {
    header('location: ' . ROOT_URL . 'admin/manage-tags.php');
    die();
}
?>