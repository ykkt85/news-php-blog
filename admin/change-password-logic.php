<?php
require 'config/database.php';

// 変更ボタンがクリックされた時
if (isset($_POST['submit'])){
    $email = filter_var($_POST['email'], FILTER_VALIDATE_EMAIL);
    $previouspassword = filter_var($_POST['previouspassword'], FILTER_SANITIZE_FULL_SPECIAL_CHARS);
    $createdpassword = filter_var($_POST['createdpassword'], FILTER_SANITIZE_FULL_SPECIAL_CHARS);
    $confirmedpassword = filter_var($_POST['confirmedpassword'], FILTER_SANITIZE_FULL_SPECIAL_CHARS);

    //フォームの値確認
    // 以前のパスワードが異なる場合
    if ($previouspassword /*以下途中*/){
        $_SESSION['change-password-error'] = "パスワードが違います";
 
    // フォームに全ての値が入力されている場合
    } else {
        $hashed_password = password_hash($createdpassword, PASSWORD_DEFAULT);
        // 途中
        // $hashed_access_token = password_hash($access_token, PASSWORD_DEFAULT);
        
    }

    // $_SESSION['change-password-error']に何らかの値が含まれている場合
    if (isset($_SESSION['change-password-error'])){
        // change-passwordページに値を渡してリダイレクト
        $_SESSION['change-password-data'] = $_POST;
        header('location:' . ROOT_URL . 'change-password.php');
    
    } else {
        // データベースに登録
        $inset_user_query = "INSERT INTO users (email, password, role_ID, access_token, created_at, updated_at, is_deleted) VALUES('$email', '$hashed_password', 0, NULL, CURRENT_TIMESTAMP(), CURRENT_TIMESTAMP(), 0)";
        $insert_user_result = mysqli_query($connection, $inset_user_query);
        
        // DB接続エラーがない場合
        if (!mysqli_errno($connection)){
            $_SESSION['change-password-success'] = "ユーザー登録が完了しました。ログインしてください";
            header('location:' . ROOT_URL . 'login.php');
            die();
        
        } else {
            $_SESSION['change-password-error'] = "ユーザーを登録できません";
            $_SESSION['change-password-data'] = $_POST;
            header('location: ' . ROOT_URL . 'change-password.php');
            die();
        }
    }

} else {
    // 変更ボタンがクリックされずに画面遷移した場合
    header(('location:' . ROOT_URL));
    die();
}
?>