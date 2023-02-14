<?php
require 'config/database.php';

// add-post.phpのフォームから値が送られてきた場合
if (isset($_POST['submit'])){
    $userID = $_SESSION['user_ID'];
    $title = filter_var($_POST['title'], FILTER_SANITIZE_FULL_SPECIAL_CHARS);
    $tagID = filter_var($_POST['tag_ID'], FILTER_SANITIZE_NUMBER_INT);
    $isFeatured = filter_var($_POST['is_featured'], FILTER_SANITIZE_NUMBER_INT);
    $thumbnail = $_FILES['thumbnail'];
    $body = filter_var($_POST['body'], FILTER_SANITIZE_FULL_SPECIAL_CHARS);

    // 注目記事がチェックされていれば1にする
    $isFeatured = $isFeatured == 1 ?: 0;

    // フォーム内容を確認
    if (!$title){
        $_SESSION['add_post_error'] = "タイトルを入力してください";
    } elseif (!$thumbnail['name']){
        $_SESSION['add_post_error'] = "画像を選択してください";
    } elseif (!$body){
        $_SESSION['add_post_error'] = "本文を入力してください";
    } else {
        // 画像の名前を変更
        $time = time();
        $thumbnailName = $time . $thumbnail['name'];
        $thumbnailTmpName = $thumbnail['tmp_name'];
        $thumbnailDescriptionPath = '../images/' . $thumbnailName;

        // 画像ファイルの拡張子を確認
        $allowedFiles = ['png', 'jpg', 'jpeg'];
        $extension = explode('.', $thumbnailName);
        $extension = end($extension);
        if (in_array($extension, $allowedFiles)){
            // データサイズを確認
            if ($thumbnail['size'] < 2_000_000){
                move_uploaded_file($thumbnailTmpName, $thumbnailDescriptionPath);
            } else {
                $_SESSION['add_post_error'] = "画像サイズが大きすぎます。2MB以下の画像を指定し直してください";
            }
        } else {
            $_SESSION['add_post_error'] = "JPG、JPEG、またはPNGファイルを指定してください";
        }
    }

    // この時点でエラーがある場合
    if (isset($_SESSION['add_post_error'])){
        $_SESSION['add_post_data'] = $_POST;
        header('location: ' . ROOT_URL . 'admin/add-post.php');
        die();

    } else {
        $connection = dbconnect();
        // 注目記事に指定された時他の記事を注目記事から外す
        if ($isFeatured == 1){
            $stmt = $connection->query('UPDATE posts SET is_featured=0');
        }

        // DBに値を記録
        $stmt = $connection->prepare('INSERT INTO posts (title, tag_ID, is_featured, thumbnail, body, status_ID, user_ID, created_at, updated_at, is_deleted) VALUES(?, ?, ?, ?, ?, 0, ?, CURRENT_TIMESTAMP(), CURRENT_TIMESTAMP(), 0)');
        $stmt->bind_param('siissi', $title, $tagID, $isFeatured, $thumbnailName, $body, $userID);
        $success = $stmt->execute();

        // エラーがない場合
        if ($success){
            $_SESSION['add_post_success'] = "新しい記事が投稿されました";
            header(('location: ' . ROOT_URL . 'admin/'));
            die();
        }
    }

// フォームから値が送られていない場合
} else {
    header('location: ' . ROOT_URL . 'admin/');
    die();
}