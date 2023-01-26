<?php
require 'config/database.php';

// PHPMailerへの接続
// 途中
use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;
require '../sendemail/PHPMailer/src/Exception.php';
require '../sendemail/PHPMailer/src/PHPMailer.php';
require '../sendemail/PHPMailer/src/SMTP.php';
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

        // 問い合わせ先に自動メールを送信
        $mail = new PHPMailer(true);
        $mail->isSMTP();
        $mail->Host = 'smtp.gmail.com';
        $mail->SMTPAuth = true;
        $mail->Username = '●●';
        $mail->Password = '●●';
        $mail->SMTPSecure = 'ssl';
        $mail->Port = 465;

        $mail->setFrom('●●');
        $mail->addAddress($_POST['email']);
        $mail->isHTML(true);

        $mail->Subject = '筑波大学新聞';
        $mail->Body = "お問い合わせくださりありがとうございます。\r\n返信には2営業日程度かかることがあります。";

        $mail->send();

        echo
            "
            <script>
                alert('送信に成功しました');
            </script>
            ";

        // 途中
        $auto_reply_title = 'お問い合わせありがとうございます';
        $auto_reply_text = "この度は、お問い合わせ頂き誠にありがとうございます。
        下記の内容でお問い合わせを受け付けました。\n\n";
        $auto_reply_text .= "お問い合わせ日時：" . date("Y-m-d H:i") . "\n";
        $auto_reply_text .= "氏名：" . $_POST['your_name'] . "\n";
        $auto_reply_text .= "メールアドレス：" . $_POST['email'] . "\n\n";
        $auto_reply_text .= "GRAYCODE 事務局";
        mb_send_mail($_POST['email'], $auto_reply_title, $auto_reply_body);
        
        // エラーがない場合
        if (!mysqli_errno($connection) && mb_send_mail($email, $email->Subject, $email->body)){
            $_SESSION['contact-success'] = "お問い合わせいただきありがとうございます";
            header(('location: ' . ROOT_URL . 'contact.php'));
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