<?php
require 'config/database.php';

// login.phpからフォームが送信されたとき
if (isset($_POST['submit'])){
    $email = filter_var($_POST['email'], FILTER_VALIDATE_EMAIL);
    $password = filter_var($_POST['password'], FILTER_SANITIZE_FULL_SPECIAL_CHARS);

    // フォームの値確認
    // メールアドレスが空の場合
    if (!$email){
        $_SESSION['login-error'] = "メールアドレスを入力してください";
    
    // パスワードが空の場合
    } elseif (!$password){
        $_SESSION['login-error'] = "パスワードを入力してください";
    
    // フォームに全ての値が入力されている場合
    } else {
        $fetchUserQuery = "SELECT * FROM users WHERE email='$email'";
        $fetchUserResult = mysqli_query($connection, $fetchUserQuery);
        
        // DBから合致するメールアドレスを取得
        if (mysqli_num_rows($fetchUserResult) === 1){
            $userRecord = mysqli_fetch_assoc($fetchUserResult);
            $dbPassword = $userRecord['password'];
            
            // パスワードを比較
            if (password_verify($password, $dbPassword)){
                $_SESSION['user_ID'] = $userRecord['user_ID'];
                $_SESSION['role_ID'] = $userRecord['role_ID'];
                $_SESSION['email'] = $userRecord['email'];
                header('location: ' . ROOT_URL . 'admin/index.php');
            // 入力したパスワードと登録したパスワードが異なる場合
            } else {
                $_SESSION['login-error'] = "パスワードが正しくありません";
            }

        // DBから合致するメールアドレスを取得できない場合
        } else {
            $_SESSION['login-error'] = "ユーザーが見つかりません";
        }
    }

    // エラーがある場合
    if (isset($_SESSION['login-error'])){
        $_SESSION['login-data'] = $_POST;
        header('location:' . ROOT_URL . 'login.php');
        die();
    }

// login.phpからフォームが送信されていない場合
} else {
    header('location:' . ROOT_URL);
    die();
}
?>