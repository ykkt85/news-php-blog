<?php
require __DIR__ . '/../config/database.php';

// add-category.phpのフォームから値が渡された場合
if(isset($_POST['submit'])){
    $categoryTitle = filter_var($_POST['category_title'], FILTER_SANITIZE_FULL_SPECIAL_CHARS);
    $description = filter_var($_POST['description'], FILTER_SANITIZE_FULL_SPECIAL_CHARS);

    //フォームの値確認
    // カテゴリ名が空の場合
    if (!$categoryTitle){
        $_SESSION['add_category_error'] = "カテゴリ名を入力してください";
    
    // 説明が空の場合
    } elseif (!$description){
        $_SESSION['add_category_error'] = "説明を入力してください";
    
    } else {
        // カテゴリ名に被りがないか確認
        $connection = dbconnect();
        $stmt = $connection->prepare('SELECT category_title FROM categories WHERE category_title=?');
        $stmt->bind_param('s', $categoryTitle);
        $success = $stmt->execute();
        $stmt->bind_result($categoryID);
        $stmt->fetch();
        if ($categoryID !== NULL){
            $_SESSION['add_category_error'] = "そのカテゴリ名は既に登録されています";
        }
    }

    // $_SESSION['add_category_error']に何らかの値が含まれている場合
    if (isset($_SESSION['add_category_error'])){
        // add-categoryページに値を渡してリダイレクト
        $_SESSION['add_category_data'] = $_POST;
        header('location: ' . ROOT_URL . 'admin/add-category.php');
        die();

    } else {
        // DBに値を記録
        $connection = dbconnect();
        $stmt = $connection->prepare('INSERT INTO categories (category_title, description, created_at, updated_at, is_deleted) VALUES(?, ?, CURRENT_TIMESTAMP(), CURRENT_TIMESTAMP(), 0)');
        $stmt->bind_param('ss', $categoryTitle, $description);
        $success = $stmt->execute();

        // エラーがある場合
        if (!$success){
            $_SESSION['add_category_error'] = "カテゴリを登録できません";
            $_SESSION['add_category_data'] = $_POST;
            header('location: ' . ROOT_URL . 'admin/add-category.php');
            die();
        // エラーがない場合
        } else {
            $_SESSION['add_category_success'] = "カテゴリ「" . h($categoryTitle) . "」が登録されました";
            header('location: ' . ROOT_URL . 'admin/manage-categories.php');
            die();
        }
    }

// フォームの値が渡されずに画面遷移した場合
} else {
    header('location: ' . ROOT_URL . 'admin/manage-categories.php');
    die();
}
?>