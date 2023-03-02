<?php
require __DIR__ . '/../config/database.php';

// delete-category.phpのURLにcategory_IDの値が含まれている場合
if (isset($_GET['category_ID'])){
    $categoryID = filter_var($_GET['category_ID'], FILTER_SANITIZE_NUMBER_INT);

    // 管理者以外（投稿者）がアクセスした場合
    if ($_SESSION['role_ID'] === 0){
        $_SESSION['nonadmin_error'] = 'アクセス権限がありません';
        header('location: ' . ROOT_URL . 'message.php');
        die();
    }
    
    // DBの内容を上書き
    $connection = dbconnect();
    $stmt = $connection->prepare('UPDATE categories SET updated_at=CURRENT_TIMESTAMP(), is_deleted=1 WHERE category_ID=? LIMIT 1');
    $stmt->bind_param('i', $categoryID);
    $successCategories = $stmt->execute();


    // 削除したタグがついていた記事のタグ表示を変更
    $stmt = $connection->prepare('UPDATE posts SET category_ID=10 WHERE category_ID=?');
    $stmt->bind_param('i', $categoryID);
    $successPosts = $stmt->execute();

    
    // DBの値を取り出す
    $stmt = $connection->prepare('SELECT category_title FROM categories WHERE category_ID=? LIMIT 1');
    $stmt->bind_param('i', $categoryID);
    $successCategoryTitle = $stmt->execute();
    $stmt->bind_result($categoryTitle);
    $stmt->fetch();
    
    // 全ての動作においてエラーがない場合
    if ($successCategories && $successPosts && $successCategoryTitle){
        $_SESSION['delete_category_success'] = "タグ「 {$categoryTitle} 」が削除されました";
    // エラーがある場合
    } else {
        $_SESSION['delete_category_error'] = "タグ「 {$categoryTitle} 」の削除に失敗しました";
    }
    header('location: ' . ROOT_URL . 'admin/manage-categories.php');
    die();

// delete-category.phpのURLにcategory_IDの値が含まれていない場合
} else {
    header('location: ' . ROOT_URL . 'admin/manage-categories.php');
    die();
}
?>