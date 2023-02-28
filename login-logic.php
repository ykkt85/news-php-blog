<?php
require __DIR__ . '/config/database.php';

// login.phpからフォームが送信されたとき
if (isset($_POST['submit'])){
    $email = filter_var($_POST['email'], FILTER_VALIDATE_EMAIL);
    $password = filter_var($_POST['password'], FILTER_SANITIZE_FULL_SPECIAL_CHARS);

    // フォームの値確認
    // メールアドレスが空の場合
    if (!$email){
        $_SESSION['login_error'] = "メールアドレスを入力してください";
    
    // パスワードが空の場合
    } elseif (!$password){
        $_SESSION['login_error'] = "パスワードを入力してください";
    
    // フォームに全ての値が入力されている場合
    } else {
        // DBに接続
        $connection = dbconnect();
        $stmt = $connection->prepare('SELECT user_ID, email, password, role_ID FROM users WHERE email=?');
        $stmt->bind_param('s', $email);
        $stmt->execute();
        $stmt->bind_result($userID, $dbEmail, $hashed, $roleID);
        $stmt->fetch();

        // 入力されたメールアドレスが存在しない場合
        if (!isset($dbEmail)){
            $_SESSION['login_error'] = "メールアドレスまたはパスワードが正しくありません";
        
        // パスワードが正しい場合
        } elseif (password_verify($password, $hashed)){
            // 各セッション値を設定
            session_regenerate_id();
            $_SESSION['user_ID'] = $userID;
            $_SESSION['email'] = $email;
            $_SESSION['role_ID'] = $roleID;
            header('location: ' . ROOT_URL . 'admin/index.php');
        
        // パスワードが正しくない場合
        } else {
            $_SESSION['login_error'] = "メールアドレスまたはパスワードが正しくありません";
        }
    }

    // エラーがある場合
    if (isset($_SESSION['login_error'])){
        $_SESSION['login_data'] = $_POST;
        header('location:' . ROOT_URL . 'login.php');
        die();
    }

// login.phpからフォームが送信されていない場合
} else {
    header('location:' . ROOT_URL);
    die();
}
?>