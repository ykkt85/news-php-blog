<?php
require 'config/database.php';

// edit-post.phpのフォームから値が送信された場合
if (isset($_POST['submit'])){
    $postID = filter_var($_POST['post_ID'], FILTER_SANITIZE_NUMBER_INT);
    $title = filter_var($_POST['title'], FILTER_SANITIZE_FULL_SPECIAL_CHARS);
    $tagID = filter_var($_POST['tag_ID'], FILTER_SANITIZE_NUMBER_INT);
    $isFeatured = filter_var($_POST['is_featured'], FILTER_SANITIZE_NUMBER_INT);
    $thumbnail = $_FILES['thumbnail'];
    $previousThumbnailName = filter_var($_POST['previous_thumbnail_name'], FILTER_SANITIZE_FULL_SPECIAL_CHARS);
    $body = filter_var($_POST['body'], FILTER_SANITIZE_FULL_SPECIAL_CHARS);
    
    // 注目記事がチェックされていれば1にする
    $isFeatured = $isFeatured == 1 ?: 0;

    // フォーム内容を確認
    if (!$title) {
        $_SESSION['edit-post-error'] = "タイトルを入力してください";
    } elseif (!$thumbnail['name'] && !$previousThumbnailName){
        $_SESSION['edit-post-error'] = "画像を選択してください";
    } elseif (!$body){
        $_SESSION['edit-post-error'] = "本文を入力してください";
    } else {

        // 新しい画像をアップロードする場合以前の画像を消去
        if ($thumbnail['name']){
            $previousThumbnailPath = '../images/' . $previousThumbnailName;
            if ($previousThumbnailPath){
                unlink($previousThumbnailPath);
            }

            // 画像の名前を変更
            $time = time();
            $thumbnailName = $time . $thumbnail['name'];
            $thumbnailTmpName = $thumbnail['tmp_name'];
            $thumbnailDescriptionPath = '../images/' . $thumbnailName;

            // データの拡張子を確認
            $allowedFiles = ['png', 'jpg', 'jpeg'];
            $extension = explode('.', $thumbnailName);
            $extension = end($extension);

            if (in_array($extension, $allowedFiles)){
                // データの大きさを確認
                if ($thumbnail['size'] < 2_000_000){
                    move_uploaded_file($thumbnailTmpName, $thumbnailDescriptionPath);
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
        $_SESSION['post_ID'] = $postID;
        header('location: ' . ROOT_URL . 'admin/edit-post.php?post_ID=' . $_SESSION['post_ID']);

    } else {
        // 注目記事が指定された場合
        if ($isFeatured == 1){
            $zeroAllFeaturedQuery = "UPDATE posts SET is_featured=0";
            $zeroAllFeaturedResult = mysqli_query($connection, $zeroAllFeaturedQuery);
        }

        // 新しい画像をアップロードした場合
        $thumbnailToInsert = $thumbnailName ?? $previousThumbnailName;

        // データベースにデータを記録
        $query = "UPDATE posts SET title='$title', tag_ID=$tagID, is_featured=$isFeatured, thumbnail='$thumbnailToInsert', body='$body', updated_at=CURRENT_TIMESTAMP() WHERE post_ID=$postID LIMIT 1";
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