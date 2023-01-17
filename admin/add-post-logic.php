<?php
require 'config/database.php';

// フォームからデータが送られてきたとき
if (isset($_POST['submit'])){
    $user_ID = $_SESSION['user_ID'];
    $title = filter_var($_POST['title'], FILTER_SANITIZE_FULL_SPECIAL_CHARS);
    $tag_ID = filter_var($_POST['tag_ID'], FILTER_SANITIZE_NUMBER_INT);
    $is_featured = filter_var($_POST['is_featured'], FILTER_SANITIZE_NUMBER_INT);
    $thumbnail = $_FILES['thumbnail'];
    $body = filter_var($_POST['body'], FILTER_SANITIZE_FULL_SPECIAL_CHARS);

    // 注目記事がチェックされてれば1にする
    $is_featured = $is_featured == 1 ?: 0;

    // フォーム内容を確認
    if (!$title){
        $_SESSION['add-post-error'] = "タイトルを入力してください";
    } elseif (!$thumbnail['name']){
        $_SESSION['add-post-error'] = "画像を選択してください";
    } elseif (!$body){
        $_SESSION['add-post-error'] = "本文を入力してください";
    } else {
        // 画像の名前を変更
        $time = time();
        $thumbnail_name = $time . $thumbnail['name'];
        $thumbnail_tmp_name = $thumbnail['tmp_name'];
        $thumbnail_description_path = '../images/' . $thumbnail_name;

        // データの拡張子を確認
        $allowed_files = ['png', 'jpg', 'jpeg'];
        $extension = explode('.', $thumbnail_name);
        $extension = end($extension);
        if (in_array($extension, $allowed_files)){
            // データの大きさを確認
            if ($thumbnail['size'] < 2_000_000){
                move_uploaded_file($thumbnail_tmp_name, $thumbnail_description_path);
            } else {
                $_SESSION['add-post-error'] = "画像サイズが大きすぎます。2MB以下の画像を指定し直してください";
            }
        } else {
            $_SESSION['add-post-error'] = "JPG、JPEG、またはPNGファイルを指定してください";
        }
    }

    // この時点でエラーがあるとき
    if (isset($_SESSION['add-post-error'])){
        $_SESSION['add-post-data'] = $_POST;
        header('location: ' . ROOT_URL . 'admin/add-post.php');
        die();

    } else {
        // 注目記事が指定されたとき
        if ($is_featured == 1){
            $zero_all_featured_query = "UPDATE posts SET is_featured=0";
            $zero_all_featured_result = mysqli_query($connection, $zero_all_featured_query);
        }

        // データベースにデータを記録
        $query = "INSERT INTO posts (title, tag_ID, is_featured, thumbnail, body, status_ID, user_ID, created_at, updated_at, is_deleted) VALUES('$title', $tag_ID, $is_featured, '$thumbnail_name', '$body', 0, $user_ID, CURRENT_TIMESTAMP(), CURRENT_TIMESTAMP(), 0)";
        $result = mysqli_query($connection, $query);

        // エラーがない場合
        if (!mysqli_errno($connection)){
            $_SESSION['add-post-success'] = "新しい記事を投稿しました";
            header(('location: ' . ROOT_URL . 'admin/'));
            die();
        } else {
            var_dump($connection->error);
        }
    }
} else {
    header('location: ' . ROOT_URL . 'admin/index.php');
    die();
}