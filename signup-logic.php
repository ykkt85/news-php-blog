<?php
require 'config/database.php';

// signup.phpのフォームが送信された場合
if (isset($_POST['submit'])){
    $email = filter_var($_POST['email'], FILTER_VALIDATE_EMAIL);
    $createdPassword = filter_var($_POST['createdpassword'], FILTER_SANITIZE_FULL_SPECIAL_CHARS);
    $confirmedPassword = filter_var($_POST['confirmedpassword'], FILTER_SANITIZE_FULL_SPECIAL_CHARS);

    //フォームの値確認
    // メールアドレスが空の場合
    if (!$email){
        $_SESSION['signup_error'] = "メールアドレスを入力してください";
    
    // パスワードが８文字未満の場合
    } elseif (strlen($createdPassword) < 8 || strlen($confirmedPassword) < 8){
        $_SESSION['signup_error'] = "パスワードは８文字以上で設定してください";
    
    // パスワードが異なる場合
    } elseif ($createdPassword !== $confirmedPassword){
        $_SESSION['signup_error'] = "パスワードが違います";
    
    // フォームに全ての値が入力されている場合
    } else {
        // パスワードをハッシュ化
        $hashedPassword = password_hash($createdPassword, PASSWORD_DEFAULT);
        // メールアドレスが既にDBに登録されていないか確認
        $connection = dbconnect();
        $stmt = $connection->prepare('SELECT email FROM users WHERE email=?');
        $stmt->bind_param('s', $email);
        $success = $stmt->execute();
        $stmt->bind_result($dbEmail);
        $stmt->fetch();
        
        // メールアドレスが既にDBに登録されている場合
        if ($dbEmail !== NULL){
            $_SESSION['signup_error'] = "そのメールアドレスは既に登録されています"; 
        }
    }

    // この時点でエラーがある場合、signup.phpに値を渡してリダイレクト
    if (isset($_SESSION['signup_error'])){
        $_SESSION['signup_data'] = $_POST;
        header('location:' . ROOT_URL . 'signup.php');
    
    } else {
        // DBに登録
        $connection = dbconnect();
        $stmt = $connection->prepare('INSERT INTO users (email, password, role_ID, token, created_at, updated_at, is_deleted) VALUES(?, ?, 0, NULL, CURRENT_TIMESTAMP(), CURRENT_TIMESTAMP(), 0)');
        $stmt->bind_param('ss', $email, $hashedPassword);
        $success = $stmt->execute();

        // エラーがある場合
        if (!$success){
            $_SESSION['signup_error'] = "ユーザーを登録できません";
            $_SESSION['signup_data'] = $_POST;
            header('location: ' . ROOT_URL . 'signup.php');
            die();
        // エラーがない場合
        } else {
            $_SESSION['signup_success'] = "ユーザー登録が完了しました。ログインしてください";
            header('location:' . ROOT_URL . 'login.php');
            die();
        }
    }

// 登録ボタンがクリックされずに画面遷移した場合
} else {
    header(('location:' . ROOT_URL));
    die();
}
?>