<?php
require 'config/database.php';

mb_language("Japanese");
mb_internal_encoding("UTF-8");

// 変更ボタンがクリックされた時
if (isset($_POST['submit'])){
    $email = filter_var($_POST['email'], FILTER_VALIDATE_EMAIL);

    // トークンを生成
    // これ数字だけ？途中
    $token = bin2hex(random_bytes(32));

    // パスワード変更用のメールを送る設定途中
    // ヘッダー途中
    // $header = "MIME-Version: 1.0\n";
	$header = "From: Tsukuba University News <●●>\n";
    // メールタイトル
    $auto_reply_title = 'パスワード変更 | Tsukuba University News';
    // メール本文
    $auto_reply_body = "下記URLから新しいパスワードを設定してください\n";
    $auto_reply_body .= "http://localhost:8888/TsukubaUniversityNews/new-password.php?token=" . $token . "\n\n";
    $auto_reply_body .= "Tsukuba University News";
    // 送信
    mb_send_mail($email, $auto_reply_title, $auto_reply_body, $header);


    // DBにトークンを登録
    $insert_user_query = "UPDATE users SET token='$token', updated_at=CURRENT_TIMESTAMP() WHERE email='$email' AND is_deleted=0 LIMIT 1";
    $insert_user_result = mysqli_query($connection, $insert_user_query);
        
    // DB接続エラーがない場合
    if (!mysqli_errno($connection)){
        $_SESSION['reset-password-success'] = "パスワード変更用のURLを送信しました。登録済みのメールアドレスを確認してください";
        //header('location:' . ROOT_URL . 'reset-password.php');
        var_dump(mb_send_mail($email, $auto_reply_title, $auto_reply_body, $header));
        die();
    
    } else {
        $_SESSION['reset-password-error'] = "パスワードを変更できません";
        $_SESSION['reset-password-data'] = $_POST;
        header('location: ' . ROOT_URL . 'reset-password.php');
        // var_dump(mysqli_error($connection));
        // var_dump($insert_user_query);
        // var_dump($insert_user_result);
        die();
    }

} else {
    // 変更ボタンがクリックされずに画面遷移した場合
    header(('location:' . ROOT_URL));
    die();
}
?>