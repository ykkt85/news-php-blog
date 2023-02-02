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
        $_SESSION['signup-error'] = "メールアドレスを入力してください";
    
    // パスワードが８文字未満の場合
    } elseif (strlen($createdPassword) < 8 || strlen($confirmedPassword) < 8){
        $_SESSION['signup-error'] = "パスワードは８文字以上で設定してください";
    
    // パスワードが異なる場合
    } elseif ($createdPassword !== $confirmedPassword){
        $_SESSION['signup-error'] = "パスワードが違います";
    
    // フォームに全ての値が入力されている場合
    } else {
        $hashedPassword = password_hash($createdPassword, PASSWORD_DEFAULT);
        $checkUserQuery = "SELECT * FROM users WHERE email='$email'";
        $checkUserResult = mysqli_query($connection, $checkUserQuery);
        
        // パスワードが既にDBに登録されている場合
        if (mysqli_num_rows($checkUserResult) === 1){
            $_SESSION['signup-error'] = "そのメールアドレスは既に登録されています"; 
        }
    }

    // この時点でエラーがある場合、signup.phpに値を渡してリダイレクト
    if (isset($_SESSION['signup-error'])){
        $_SESSION['signup-data'] = $_POST;
        header('location:' . ROOT_URL . 'signup.php');
    
    } else {
        // DBに登録
        $insertUserQuery = "INSERT INTO users (email, password, role_ID, token, created_at, updated_at, is_deleted) VALUES('$email', '$hashedPassword', 0, NULL, CURRENT_TIMESTAMP(), CURRENT_TIMESTAMP(), 0)";
        $insertUserResult = mysqli_query($connection, $insertUserQuery);
        
        // DB接続エラーがない場合
        if (!mysqli_errno($connection)){
            $_SESSION['signup-success'] = "ユーザー登録が完了しました。ログインしてください";
            header('location:' . ROOT_URL . 'login.php');
            die();
        
        // DB接続エラーがある場合
        } else {
            $_SESSION['signup-error'] = "ユーザーを登録できません";
            $_SESSION['signup-data'] = $_POST;
            header('location: ' . ROOT_URL . 'signup.php');
            die();
        }
    }

// 登録ボタンがクリックされずに画面遷移した場合
} else {
    header(('location:' . ROOT_URL));
    die();
}
?>