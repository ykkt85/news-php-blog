<?php
require 'config/database.php';

// 登録ボタンがクリックされた時
if (isset($_POST['submit'])){
    $user_ID = filter_var($_POST['user_ID'], FILTER_SANITIZE_NUMBER_INT);
    $email = filter_var($_POST['email'], FILTER_VALIDATE_EMAIL);
    $createdpassword = filter_var($_POST['createdpassword'], FILTER_SANITIZE_FULL_SPECIAL_CHARS);
    $confirmedpassword = filter_var($_POST['confirmedpassword'], FILTER_SANITIZE_FULL_SPECIAL_CHARS);
    $role_ID = filter_var($_POST['role_ID'], FILTER_SANITIZE_NUMBER_INT);

    //フォームの値確認
    // メールアドレスが空の場合
    if (!$email){
        $_SESSION['add-user-error'] = "メールアドレスを入力してください";
    
    // パスワードが８文字以下の場合
    } elseif (strlen($createdpassword) < 8 || strlen($confirmedpassword) < 8){
        $_SESSION['add-user-error'] = "パスワードは８文字以上で設定してください";
    
    // パスワードが異なる場合
    } elseif ($createdpassword !== $confirmedpassword){
        $_SESSION['add-user-error'] = "パスワードが違います";
    
    // フォームに全ての値が入力されている場合
    } else {
        // パスワードをハッシュ化
        $hashed_password = password_hash($createdpassword, PASSWORD_DEFAULT);
        
        // アクセストークンの処理（途中）
        // $hashed_access_token = password_hash($access_token, PASSWORD_DEFAULT);

        // DBに値を記録
        $check_user_query = "SELECT * FROM users WHERE email='$email'";
        $check_user_result = mysqli_query($connection, $check_user_query);
        if (mysqli_num_rows($check_user_result) === 1){
            $_SESSION['add-user-error'] = "そのメールアドレスは既に登録されています"; 
        }
    }

    // $_SESSION['add-user-error']に何らかの値が含まれている場合
    if (isset($_SESSION['add-user-error'])){
        // signupページに値を渡してリダイレクト
        $_SESSION['add-user-data'] = $_POST;
        header('location:' . ROOT_URL . 'admin/add-user.php');
    
    } else {
        // DBに登録
        $insert_user_query = "INSERT INTO users (email, password, role_ID, access_token, created_at, updated_at, is_deleted) VALUES('$email', '$hashed_password', $role_ID, NULL, CURRENT_TIMESTAMP(), CURRENT_TIMESTAMP(), 0)";
        $insert_user_result = mysqli_query($connection, $insert_user_query);

        // DBの内容を取り出す（途中）
        /*$fetch_user_query = "SELECT * FROM users WHERE user_ID=$user_ID LIMIT 1";
        $fetch_user_result = mysqli_query($connection, $fetch_user_query);
        $user = mysqli_fetch_assoc($fetch_user_result);*/
        
        // エラーがない場合
        if (!mysqli_errno($connection)){
            $_SESSION['add-user-success'] = "ユーザーの登録が完了しました";
            // $_SESSION['add-user-success'] = "ユーザー {$user['email']} の登録が完了しました";
            header('location:' . ROOT_URL . 'admin/manage-users.php');
            die();
            
        } else {
            $_SESSION['add-user-error'] = "ユーザーの登録に失敗しました";
            // $_SESSION['add-user-error'] = "ユーザー {$user['email']} の登録に失敗しました";
            header('location: ' . ROOT_URL . 'admin/manage-users.php');
        }
    }

// 登録ボタンがクリックされずに画面遷移した場合
} else {
    header(('location:' . ROOT_URL));
    die();
}
?>