<?php
require 'config/database.php';

// mb_send_mailの設定
mb_language("Japanese");
mb_internal_encoding("UTF-8");

// contact.phpのフォームが送信された場合
if (isset($_POST['submit'])){
    $title = filter_var($_POST['title'], FILTER_SANITIZE_FULL_SPECIAL_CHARS);
    $name = filter_var($_POST['name'], FILTER_SANITIZE_FULL_SPECIAL_CHARS);
    $email = filter_var($_POST['email'], FILTER_SANITIZE_EMAIL);
    $body = filter_var($_POST['body'], FILTER_SANITIZE_FULL_SPECIAL_CHARS);

    // フォーム内容を確認
    if (!$title){
        $_SESSION['contact-error'] = "タイトルを入力してください";
    } elseif (!$name){
        $_SESSION['contact-error'] = "名前を入力してください";
    } elseif (!$email){
        $_SESSION['contact-error'] = "メールアドレスを入力してください";
    } elseif (!$body){
        $_SESSION['contact-error'] = "本文を入力してください";
    }

    // この時点でエラーがあるとき
    if (isset($_SESSION['contact-error'])){
        $_SESSION['contact-data'] = $_POST;
        header('location: ' . ROOT_URL . 'contact.php');
        die();

    } else {
        // DBに値を記録
        $query = "INSERT INTO contacts (title, name, email, body, created_at, is_deleted) VALUES('$title', '$name', '$email', '$body', CURRENT_TIMESTAMP(), 0)";
        $result = mysqli_query($connection, $query);

        // パスワード変更用のメールを送る設定途中
        // ヘッダー途中
        // $header = "MIME-Version: 1.0\n";
        $header = "From: Tsukuba University News <name@gmail.com>\n";
        // メールタイトル
        $auto_reply_title = 'お問い合わせ内容 | Tsukuba University News';
        // メール本文
        $auto_reply_body = "お問い合わせを受け付けました。\n\n";
        $auto_reply_body .= "≪ お問い合わせ内容 ≫\n";        
        $auto_reply_body .= "タイトル：" . $title . "\n";
        $auto_reply_body .= "お名前：" . $name . " 様\n";
        $auto_reply_body .= "内容：" . $body . "\n\n";
        $auto_reply_body .= "Tsukuba University News";
        // 送信
        mb_send_mail($email, $auto_reply_title, $auto_reply_body, $header);
                
        // エラーがない場合
        if (!mysqli_errno($connection)){
            $_SESSION['contact-success'] = "お問い合わせいただきありがとうございます";
            var_dump(mb_send_mail($email, $auto_reply_title, $auto_reply_body, $header));
            var_dump($email, $auto_reply_title, $auto_reply_body, $header);
            //header(('location: ' . ROOT_URL . 'message.php'));
            die();
        
        // エラーがある場合
        } else {
            var_dump(mysqli_error($connection));
        }
    }

// contact.phpのフォームが送信されていない場合
} else {
    header('location: ' . ROOT_URL . 'index.php');
    die();
}