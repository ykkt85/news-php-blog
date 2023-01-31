<?php
require 'config/database.php';

// mb_send_mailの設定
mb_language("Japanese");
mb_internal_encoding("UTF-8");

// reset-password.phpのフォームが送信された場合
if (isset($_POST['submit'])){
    $email = filter_var($_POST['email'], FILTER_VALIDATE_EMAIL);

    // URL用のトークンを生成
    $token = bin2hex(random_bytes(32));

    // パスワード変更用メールを送るための設定
    // メールタイトル
    $auto_reply_title = 'パスワード変更 | Tsukuba University News';
    // メール本文
    $auto_reply_body = "下記URLから新しいパスワードを設定してください\n";
    $auto_reply_body .= "http://localhost:8888/TsukubaUniversityNews/new-password.php?token=" . $token . "\n\n";
    $auto_reply_body .= "Tsukuba University News";
    // ヘッダー
	$header = "From: Tsukuba University News <name@gmail.com>\n";
    // 送信
    mb_send_mail($email, $auto_reply_title, $auto_reply_body, $header);

    // DBにトークンを登録
    $insert_user_query = "UPDATE users SET token='$token', updated_at=CURRENT_TIMESTAMP() WHERE email='$email' AND is_deleted=0 LIMIT 1";
    $insert_user_result = mysqli_query($connection, $insert_user_query);
        
    // DB接続エラーがない場合
    if (!mysqli_errno($connection)){
        $_SESSION['reset-password-success'] = "パスワード変更用のURLを送信しました。登録済みのメールアドレスを確認してください";
        header('location:' . ROOT_URL . 'reset-password.php');
        die();
    
    // DB接続エラーがある場合
    } else {
        $_SESSION['reset-password-error'] = "パスワードを変更できません";
        $_SESSION['reset-password-data'] = $_POST;
        header('location: ' . ROOT_URL . 'reset-password.php');
        die();
    }

// reset-password.phpのフォームが送信されていない場合
} else {
    header(('location:' . ROOT_URL));
    die();
}
?>