<?php
require __DIR__ . '/config/database.php';

// mb_send_mailの設定
mb_language("Japanese");
mb_internal_encoding("UTF-8");
date_default_timezone_set('Asia/Tokyo');

// reset-password.phpのフォームが送信された場合
if (isset($_POST['submit'])){
    $email = filter_var($_POST['email'], FILTER_VALIDATE_EMAIL);
    $createdPassword = filter_var($_POST['createdpassword'], FILTER_SANITIZE_FULL_SPECIAL_CHARS);
    $confirmedPassword = filter_var($_POST['confirmedpassword'], FILTER_SANITIZE_FULL_SPECIAL_CHARS);
    $pattern = '/\A(?=.*?[a-z])(?=.*?[A-Z])(?=.*?\d)(?=.*?[!-\/:-@[-`{-~])[!-~]{8,100}+\z/i';
    $result = preg_match($pattern, $createdPassword);

    //フォームの値確認
    // パスワードが８文字未満の場合
    if (strlen($createdPassword) < 8 || strlen($confirmedPassword) < 8){
        $_SESSION['reset_password_error'] = "パスワードは８文字以上で設定してください";
    
    // パスワードが異なる場合
    } elseif ($createdPassword !== $confirmedPassword){
        $_SESSION['reset_password_error'] = "パスワードが違います";
    
    // パスワードに大文字・記号・数字のいずれかが使われていない場合
    } elseif ($result === 0) {
        $_SESSION['reset_password_error'] = "パスワードに半角英大文字・小文字・数字・記号が含まれていません";
    }

    // この時点でエラーがある場合
    if (isset($_SESSION['reset_password_error'])){
        // new-passwordページに値を渡してリダイレクト
        $_SESSION['reset_password_data'] = $_POST;
        header('location: '.ROOT_URL.'reset-password.php');
        die();
        
    } else {
        // URL用のトークンを生成
        $token = bin2hex(random_bytes(32));

        // DBにトークンを登録
        $connection = dbconnect();
        $stmt = $connection->prepare('UPDATE users SET token=?, updated_at=CURRENT_TIMESTAMP(), is_deleted=1 WHERE email=? AND is_deleted=0 LIMIT 1');
        if (!$stmt){
            die($connection->error);
        }
        $stmt->bind_param('ss', $token, $email);
        $success = $stmt->execute();

        // エラーがある場合
        if (!$success){
            $_SESSION['reset_password_error'] = "パスワードを変更できません";
            $_SESSION['reset_password_data'] = $_POST;
            header('location: ' . ROOT_URL . 'reset-password.php');
            die();

        // エラーがない場合
        } else {
            $_SESSION['reset_password_success'] = "確認メールを送信しました。登録済みのメールアドレスを確認してください";
            header('location:' . ROOT_URL . 'message.php');

            // パスワード変更用メールを送るための設定
            // メールタイトル
            $autoReplyTitle = 'パスワード変更 | Tsukuba University News';
            // メール本文
            $autoReplyBody = "パスワードが変更されました。下記URLにアクセスしてください。\n";
            $autoReplyBody .= "http://localhost:8888/TsukubaUniversityNews/new-password.php?token=" . $token . "\n\n";
            $autoReplyBody .= "Tsukuba University News";
            // ヘッダー
            $header = "From: Tsukuba University News <name@gmail.com>\n";
            // 送信
            mb_send_mail($email, $autoReplyTitle, $autoReplyBody, $header);
        }
    }

// reset-password.phpのフォームが送信されていない場合
} else {
    header(('location:' . ROOT_URL));
    die();
}
?>