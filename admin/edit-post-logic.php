<?php
require 'config/database.php';

// edit-post.phpのフォームから値が送信された場合
if (isset($_POST['submit'])){
    $post_ID = filter_var($_POST['post_ID'], FILTER_SANITIZE_NUMBER_INT);
    $title = filter_var($_POST['title'], FILTER_SANITIZE_FULL_SPECIAL_CHARS);
    $tag_ID = filter_var($_POST['tag_ID'], FILTER_SANITIZE_NUMBER_INT);
    $is_featured = filter_var($_POST['is_featured'], FILTER_SANITIZE_NUMBER_INT);
    $thumbnail = $_FILES['thumbnail'];
    $previous_thumbnail_name = filter_var($_POST['previous_thumbnail_name'], FILTER_SANITIZE_FULL_SPECIAL_CHARS);
    $body = filter_var($_POST['body'], FILTER_SANITIZE_FULL_SPECIAL_CHARS);
    
    // 注目記事がチェックされていれば1にする
    $is_featured = $is_featured == 1 ?: 0;

    // フォーム内容を確認
    if (!$title) {
        $_SESSION['edit-post-error'] = "タイトルを入力してください";
    } elseif (!$thumbnail['name'] && !$previous_thumbnail_name){
        $_SESSION['edit-post-error'] = "画像を選択してください";
    } elseif (!$body){
        $_SESSION['edit-post-error'] = "本文を入力してください";
    } else {

        // 新しい画像をアップロードする場合以前の画像を消去
        if ($thumbnail['name']){
            $previous_thumbnail_path = '../images/' . $previous_thumbnail_name;
            if ($previous_thumbnail_path){
                unlink($previous_thumbnail_path);
            }

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
                    $_SESSION['edit-post-error'] = "画像サイズが大きすぎます。2MB以下の画像を指定し直してください";
                }
            } else {
                $_SESSION['edit-post-error'] = "JPG、JPEG、またはPNGファイルを指定してください";
            }
        }
    }

    // この時点でエラーがある場合
    if (isset($_SESSION['edit-post-error'])){
        $_SESSION['edit-post-data'] = $_POST;
        $_SESSION['post_ID'] = $post_ID;
        header('location: ' . ROOT_URL . 'admin/edit-post.php?post_ID=' . $_SESSION['post_ID']);

    } else {
        // 注目記事が指定された場合
        if ($is_featured == 1){
            $zero_all_featured_query = "UPDATE posts SET is_featured=0";
            $zero_all_featured_result = mysqli_query($connection, $zero_all_featured_query);
        }

        // 新しい画像をアップロードした場合
        $thumbnail_to_insert = $thumbnail_name ?? $previous_thumbnail_name;

        // データベースにデータを記録
        $query = "UPDATE posts SET title='$title', tag_ID=$tag_ID, is_featured=$is_featured, thumbnail='$thumbnail_to_insert', body='$body', updated_at=CURRENT_TIMESTAMP() WHERE post_ID=$post_ID LIMIT 1";
        $result = mysqli_query($connection, $query);

        // エラーがない場合
        if (!mysqli_errno($connection)){
            $_SESSION['edit-post-success'] = "記事を編集しました";
            header(('location: ' . ROOT_URL . 'admin/'));
            die();
        }
    }

// edit-post.phpのフォームから値が送信されていない場合
} else {
    header('location: ' . ROOT_URL . 'admin/');
    die();
}