<?php
require __DIR__ . '/config/database.php';

// mb_send_mailの設定
mb_language("Japanese");
mb_internal_encoding("UTF-8");
date_default_timezone_set('Asia/Tokyo');

// contact.phpのフォームが送信された場合
if (isset($_POST['submit'])){
    $title = filter_var($_POST['title'], FILTER_SANITIZE_FULL_SPECIAL_CHARS);
    $name = filter_var($_POST['name'], FILTER_SANITIZE_FULL_SPECIAL_CHARS);
    $email = filter_var($_POST['email'], FILTER_SANITIZE_EMAIL);
    $body = filter_var($_POST['body'], FILTER_SANITIZE_FULL_SPECIAL_CHARS);

    // フォーム内容を確認
    if (!$title){
        $_SESSION['contact_error'][] = "件名を入力してください";
    }
    if (!$name){
        $_SESSION['contact_error'][] = "名前を入力してください";
    }
    if (!$email){
        $_SESSION['contact_error'][] = "メールアドレスを入力してください";
    }
    if (!$body){
        $_SESSION['contact_error'][] = "本文を入力してください";
    }

    // この時点でエラーがある場合
    if (isset($_SESSION['contact_error'])){
        $_SESSION['contact_data'] = $_POST;
        header('location: ' . ROOT_URL . 'contact.php');
        die();

    } else {
        // DBに値を記録
        $connection = dbconnect();
        $stmt = $connection->prepare('INSERT INTO contacts (title, name, email, body, created_at, is_deleted) VALUES(?, ?, ?, ?, CURRENT_TIMESTAMP(), 0)');
        $stmt->bind_param('ssss', $title, $name, $email, $body);
        $success = $stmt->execute();
                
        // エラーがない場合
        if ($success){
            $_SESSION['contact_success'] = "お問い合わせいただきありがとうございます";
            header('location: ' . ROOT_URL . 'message.php');
            
            // パスワード変更用メールを送るための設定
            // メールタイトル
            $autoReplyTitle = 'お問い合わせ内容 | Tsukuba University News';
            // メール本文
            $autoReplyBody = "お問い合わせを受け付けました。\n\n";
            $autoReplyBody .= "≪ お問い合わせ内容 ≫\n";        
            $autoReplyBody .= "タイトル：" . $title . "\n";
            $autoReplyBody .= "お名前：" . $name . " 様\n";
            $autoReplyBody .= "内容：" . $body . "\n\n";
            $autoReplyBody .= "このメールは自動送信メールです。返信いただいても回答いたしかねますので、予めご了承ください。\n\n";
            $autoReplyBody .= "Tsukuba University News";
            // ヘッダー
            $header = "From: Tsukuba University News <name@gmail.com>\n";
            // 送信
            mb_send_mail($email, $autoReplyTitle, $autoReplyBody, $header);
        }
    }
// contact.phpのフォームが送信されていない場合
} else {
    header('location: ' . ROOT_URL . 'index.php');
    die();
}
?>