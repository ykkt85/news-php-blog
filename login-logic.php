<?php
require 'config/database.php';

// ログインボタンがクリックされたとき
if (isset($_POST['submit'])){
    $email = filter_var($_POST['email'], FILTER_VALIDATE_EMAIL);
    $password = filter_var($_POST['password'], FILTER_SANITIZE_FULL_SPECIAL_CHARS);

    // フォームの値確認
    // メールアドレスがからの場合
    if (!$email){
        $_SESSION['login-error'] = "メールアドレスを入力してください";
    
    // パスワードが空の場合
    } elseif (!$password){
        $_SESSION['login-error'] = "パスワードを入力してください";
    
    // フォームに全ての値が入力されている場合
    } else {
        $fetch_user_query = "SELECT * FROM users WHERE email='$email'";
        $fetch_user_result = mysqli_query($connection, $fetch_user_query);
        
        // データベース内において合致するメールアドレスを探す
        if (mysqli_num_rows($fetch_user_result) === 1){
            $user_record = mysqli_fetch_assoc($fetch_user_result);
            $db_password = $user_record['password'];
            
            // パスワードを比較
            if (password_verify($password, $db_password)){
                $_SESSION['user_ID'] = $user_record['user_ID'];
                $_SESSION['role_ID'] = $user_record['role_ID'];
                $_SESSION['email'] = $user_record['email'];
                header('location: ' . ROOT_URL . 'admin/index.php');
            
            } else {
                $_SESSION['login-error'] = "パスワードが正しくありません";
            }
            
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
} else {
    header('location:' . ROOT_URL);
    die();
}
?>