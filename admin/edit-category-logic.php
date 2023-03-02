<?php
require __DIR__ . '/../config/database.php';

// edit-category.phpからフォームが送信された場合
if(isset($_POST['submit'])){
    $categoryID = filter_var($_POST['category_ID'], FILTER_SANITIZE_NUMBER_INT);
    $categoryTitle = filter_var($_POST['category_title'], FILTER_SANITIZE_FULL_SPECIAL_CHARS);
    $description = filter_var($_POST['description'], FILTER_SANITIZE_FULL_SPECIAL_CHARS);
    $token = filter_var($_POST['token'], FILTER_SANITIZE_FULL_SPECIAL_CHARS);

    // 管理者以外（投稿者）がアクセスした場合、または
    // トークンが異なる場合
    if ($_SESSION['role_ID'] === 0 || $_SESSION['token'] !== $token){
        $_SESSION['nonadmin_error'] = 'アクセス権限がありません';
        unset($_SESSION['token']);
        header('location: ' . ROOT_URL . 'message.php');
        die();
    }

    // カテゴリ名が空欄の場合
    if (!$categoryTitle){
        $_SESSION['edit_category_error'][] = "カテゴリ名を入力してください";
    }
    // 説明が空欄の場合
    if (!$description){ 
        $_SESSION['edit_category_error'][] = "説明を入力してください";
    }

    // この時点でエラーがある場合
    if (isset($_SESSION['edit_category_error'])){
        header('location: ' . ROOT_URL . 'admin/edit-category.php?category_ID='. $categoryID);
        die();

    } else {
        // DBにデータを記録
        $connection = dbconnect();
        $stmt = $connection->prepare('UPDATE categories SET category_title=?, description=?, updated_at=CURRENT_TIMESTAMP() WHERE category_ID=? LIMIT 1');
        $stmt->bind_param('ssi', $categoryTitle, $description, $categoryID);
        $success = $stmt->execute();

        // エラーがある場合
        if (!$success){
            $_SESSION['edit_category_error'] = "カテゴリを編集できませんでした";
        // エラーがない場合
        } else {
            $_SESSION['edit_category_success'] = "カテゴリ「" . h($categoryTitle) . "」を編集しました";
        }
        header('location: ' . ROOT_URL . 'admin/manage-categories.php');
        die();
    }

// edit-category.phpからフォームが送信されていない場合
} else {
    header('location: ' . ROOT_URL . 'admin/manage-categories.php');
    die();
}
?>