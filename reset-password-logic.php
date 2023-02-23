<?php
require __DIR__ . '/config/database.php';

// mb_send_mailの設定
mb_language("Japanese");
mb_internal_encoding("UTF-8");
date_default_timezone_set('Asia/Tokyo');

// reset-password.phpのフォームが送信された場合
if (isset($_POST['submit'])){
    $email = filter_var($_POST['email'], FILTER_VALIDATE_EMAIL);

    // DBにメールアドレスが登録されているか確認
    $connection = dbconnect();
    $stmt = $connection->prepare('SELECT email FROM users WHERE email=? AND is_deleted=0 LIMIT 1');
    $stmt->bind_param('s', $email);
    $stmt->execute();
    $stmt->bind_result($dbEmail);
    $stmt->fetch();

    // DBにメールアドレスが登録されていない場合
    if ($dbEmail === NULL){
        $_SESSION['reset_password_error'] = "そのメールアドレスは登録されていません";
        $_SESSION['reset_password_data'] = $_POST;
        header('location: ' . ROOT_URL . 'reset-password.php');
        die();
    }

    // URL用のトークンを生成
    $token = bin2hex(random_bytes(32));

    // DBにトークンを登録
    $connection = dbconnect();
    $stmt = $connection->prepare('UPDATE users SET token=?, updated_at=CURRENT_TIMESTAMP() WHERE email=? AND is_deleted=0 LIMIT 1');
    $stmt->bind_param('ss', $token, $dbEmail);
    $success = $stmt->execute();

    // エラーがある場合
    if (!$success){
        $_SESSION['reset_password_error'] = "パスワードを変更できません";
        $_SESSION['reset_password_data'] = $_POST;
        header('location: ' . ROOT_URL . 'reset-password.php');
        die();

    // エラーがない場合
    } else {
        $_SESSION['reset_password_success'] = "パスワード変更用のURLを送信しました。登録済みのメールアドレスを確認してください";
        header('location:' . ROOT_URL . 'message.php');
        
        // パスワード変更用メールを送るための設定
        // メールタイトル
        $autoReplyTitle = 'パスワード変更 | Tsukuba University News';
        // メール本文
        $autoReplyBody = "下記URLから新しいパスワードを設定してください\n";
        $autoReplyBody .= "http://localhost:8888/TsukubaUniversityNews/new-password.php?token=" . $token . "\n\n";
        $autoReplyBody .= "Tsukuba University News";
        // ヘッダー
        $header = "From: Tsukuba University News <name@gmail.com>\n";
        // 送信
        mb_send_mail($email, $autoReplyTitle, $autoReplyBody, $header);
    }

// reset-password.phpのフォームが送信されていない場合
} else {
    header(('location:' . ROOT_URL));
    die();
}
?>