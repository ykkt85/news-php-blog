<?php
// 使わないかも
require 'config/database.php';

// change-password.phpのフォームから値が送信された場合
if (isset($_POST['submit'])){
    $email = filter_var($_POST['email'], FILTER_VALIDATE_EMAIL);
    $previouspassword = filter_var($_POST['previouspassword'], FILTER_SANITIZE_FULL_SPECIAL_CHARS);

    // DBからパスワードを取り出し
    $query = "SELECT * FROM users where email=$email AND is_deleted=0";
    $users = mysqli_query($connection, $query);
    $user = mysqli_fetch_assoc($users);

    // パスワードが正しいか確認
    if (password_verify($previouspassword, $user['password'])){
        // アクセストークンを生成
        $access_token = bin2hex(random_bytes(32));
    
    // パスワードが異なる場合
    } else {
        $_SESSION['change-password-error'] = "パスワードが違います";
        $_SESSION['change-password-data'] = $_POST;
        header('location:' . ROOT_URL . 'admin/change-password.php');
    }

    // パスワード変更用のメールを送る
    // 途中
    // メールタイトル
    $auto_reply_title = 'パスワード変更｜Tsukuba University News';
    // メール本文
    $auto_reply_text = "下記URLから新しいパスワードを設定してください\n";
    $auto_reply_text .= "http://localhost:8888/TsukubaUniversityNews/admin/new-password.php?access_token=".$access_token."\n";
    $auto_reply_text .= "Tsukuba University News";
    mb_send_mail($_POST['email'], $auto_reply_title, $auto_reply_body);


    // DBにアクセストークンを登録
    $insert_user_query = "UPDATE users SET access_token=$access_token updated_at=CURRENT_TIMESTAMP() WHERE email=$email AND is_deleted=0 LIMIT 1";
    $insert_user_result = mysqli_query($connection, $insert_user_query);
        
    // DB接続エラーがない場合
    if (!mysqli_errno($connection)){
        $_SESSION['change-password-success'] = "パスワード変更用のURLを送信しました。登録済みのメールアドレスを確認してください";
        header('location:' . ROOT_URL . 'admin/');
        die();
    
    } else {
        $_SESSION['change-password-error'] = "パスワードを変更できません";
        $_SESSION['change-password-data'] = $_POST;
        header('location: ' . ROOT_URL . 'admin/change-password.php');
        die();
    }

} else {
    // 変更ボタンがクリックされずに画面遷移した場合
    header(('location:' . ROOT_URL));
    die();
}
?>