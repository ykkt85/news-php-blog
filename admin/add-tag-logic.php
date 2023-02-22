<?php
require __DIR__ . '/../config/database.php';

// add-tag.phpのフォームから値が渡された場合
if(isset($_POST['submit'])){
    $tagTitle = filter_var($_POST['tag_title'], FILTER_SANITIZE_FULL_SPECIAL_CHARS);
    $description = filter_var($_POST['description'], FILTER_SANITIZE_FULL_SPECIAL_CHARS);

    //フォームの値確認
    // タグ名が空の場合
    if (!$tagTitle){
        $_SESSION['add_tag_error'] = "タグ名を入力してください";
    
    // 説明が空の場合
    } elseif (!$description){
        $_SESSION['add_tag_error'] = "説明を入力してください";
    
    } else {
        // タグ名に被りがないか確認
        $connection = dbconnect();
        $stmt = $connection->prepare('SELECT tag_title FROM tags WHERE tag_title=?');
        $stmt->bind_param('s', $tagTitle);
        $success = $stmt->execute();
        $stmt->bind_result($tagID);
        $stmt->fetch();
        if ($tagID !== NULL){
            $_SESSION['add_tag_error'] = "そのタグ名は既に登録されています";
        }
    }

    // $_SESSION['add_tag_error']に何らかの値が含まれている場合
    if (isset($_SESSION['add_tag_error'])){
        // add-tagページに値を渡してリダイレクト
        $_SESSION['add_tag_data'] = $_POST;
        header('location: ' . ROOT_URL . 'admin/add-tag.php');
        die();

    } else {
        // DBに値を記録
        $connection = dbconnect();
        $stmt = $connection->prepare('INSERT INTO tags (tag_title, description, created_at, updated_at, is_deleted) VALUES(?, ?, CURRENT_TIMESTAMP(), CURRENT_TIMESTAMP(), 0)');
        $stmt->bind_param('ss', $tagTitle, $description);
        $success = $stmt->execute();

        // エラーがある場合
        if (!$success){
            $_SESSION['add_tag_error'] = "タグを登録できません";
            $_SESSION['add_tag_data'] = $_POST;
            header('location: ' . ROOT_URL . 'admin/add-tag.php');
            die();
        // エラーがない場合
        } else {
            $_SESSION['add_tag_success'] = "タグ「" . h($tagTitle) . "」が登録されました";
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