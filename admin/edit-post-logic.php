<?php
require __DIR__ . '/../config/database.php';

// edit-post.phpのフォームから値が送信された場合
if (isset($_POST['submit'])){
    $postID = filter_var($_POST['post_ID'], FILTER_SANITIZE_NUMBER_INT);
    $title = filter_var($_POST['title'], FILTER_SANITIZE_FULL_SPECIAL_CHARS);
    $categoryID = filter_var($_POST['category_ID'], FILTER_SANITIZE_NUMBER_INT);
    $isFeatured = filter_var($_POST['is_featured'], FILTER_SANITIZE_NUMBER_INT);
    $thumbnail = $_FILES['thumbnail'];
    $previousThumbnailName = filter_var($_POST['previous_thumbnail_name'], FILTER_SANITIZE_FULL_SPECIAL_CHARS);
    $body = filter_var($_POST['body'], FILTER_SANITIZE_FULL_SPECIAL_CHARS);
    $token = filter_var($_POST['token'], FILTER_SANITIZE_FULL_SPECIAL_CHARS);
    
    // DBのuser_IDとログインユーザーが異なる場合、または
    // トークンが異なる場合
    $connection = dbconnect();
    $stmt = $connection->prepare('SELECT user_ID FROM posts WHERE post_ID=? AND is_deleted=0 LIMIT 1');
    $stmt->bind_param('i', $postID);
    $stmt->execute();
    $stmt->bind_result($userID);
    $stmt->fetch();
    if ($_SESSION['user_ID'] !== $userID || $_SESSION['token'] !== $token){
        $_SESSION['nonadmin_error'] = 'アクセス権限がありません';
        unset($_SESSION['token']);
        header('location: ' . ROOT_URL . 'message.php');
        die();
    }

    // 注目記事がチェックされていれば1にする
    $isFeatured = $isFeatured == 1 ?: 0;

    // フォーム内容を確認
    // タイトルが空欄の場合
    if (!$title) {
        $_SESSION['edit_post_error'][] = "タイトルを入力してください";
    }
    // 本文が空欄の場合
    if (!$body){
        $_SESSION['edit_post_error'][] = "本文を入力してください";
    }

    // 画像が選択されていない場合
    if (!$thumbnail['name'] && !$previousThumbnailName){
        $_SESSION['edit_post_error'][] = "画像を選択してください";
    } else {
        // 新しい画像をアップロードする場合以前の画像を消去
        if ($thumbnail['name']){
            $previousThumbnailPath = '../images/' . $previousThumbnailName;
            if ($previousThumbnailPath){
                unlink($previousThumbnailPath);
            }

            // 画像の名前を変更
            $time = time();
            $thumbnailName = $time . h($thumbnail['name']);
            $thumbnailTmpName = h($thumbnail['tmp_name']);
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
                    $_SESSION['edit_post_error'][] = "画像サイズが大きすぎます。2MB以下の画像を指定し直してください";
                }
            } else {
                $_SESSION['edit_post_error'][] = "JPG、JPEG、またはPNGファイルを指定してください";
            }
        }
    }

    // この時点でエラーがある場合
    if (isset($_SESSION['edit_post_error'])){
        $_SESSION['edit_post_data'] = $_POST;
        header('location: ' . ROOT_URL . 'admin/edit-post.php?post_ID=' . $postID);

    } else {
        // 注目記事が指定された場合
        if ($isFeatured == 1){
            $stmt = dbconnect();
            $stmt = $connection->prepare('UPDATE posts SET is_featured=0');
            $stmt->execute();
        }

        // 新しい画像をアップロードした場合
        $thumbnailToInsert = $thumbnailName ?? $previousThumbnailName;

        // DBにデータを記録
        $connection = dbconnect();
        $stmt = $connection->prepare('UPDATE posts SET title=?, category_ID=?, is_featured=?, thumbnail=?, body=?, updated_at=CURRENT_TIMESTAMP() WHERE post_ID=? LIMIT 1');
        $stmt->bind_param('siisss', $title, $categoryID, $isFeatured, $thumbnailToInsert, $body, $postID);
        $result = $stmt->execute();

        // エラーがない場合
        if ($result){
            $_SESSION['edit_post_success'] = "記事を編集しました";
            header('location: ' . ROOT_URL . 'admin/');
            die();
        }
    }

// edit-post.phpのフォームから値が送信されていない場合
} else {
    header('location: ' . ROOT_URL . 'admin/');
    die();
}
?>