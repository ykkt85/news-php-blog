<?php
require 'config/database.php';

// new-password.phpからフォームが送信された場合
if (isset($_POST['submit'])){
    $token = filter_var($_POST['token'], FILTER_SANITIZE_FULL_SPECIAL_CHARS);
    $createdPassword = filter_var($_POST['createdpassword'], FILTER_SANITIZE_FULL_SPECIAL_CHARS);
    $confirmedPassword = filter_var($_POST['confirmedpassword'], FILTER_SANITIZE_FULL_SPECIAL_CHARS);

    // DBからトークンを取得
    $query = "SELECT * FROM users WHERE token='$token' AND is_deleted=0";
    $result = mysqli_query($connection, $query);
    $user = mysqli_fetch_assoc($result);

    //フォームの値確認
    // パスワードが８文字未満の場合
    if (strlen($createdPassword) < 8 || strlen($confirmedPassword) < 8){
        $_SESSION['new-password-error'] = "パスワードは８文字以上で設定してください";
    // パスワードが異なる場合
    } elseif ($createdPassword !== $confirmedPassword){
        $_SESSION['new-password-error'] = "パスワードが違います";
    // トークンが異なる場合
    } elseif (mysqli_num_rows($result) != 1) {
        $_SESSION['new-password-error'] = "パスワード変更用のURLが違います";
    // フォームに全ての値が正しく入力されている場合はパスワードをハッシュ化
    } else {
        $hashedPassword = password_hash($createdPassword, PASSWORD_DEFAULT);
    }

    // この時点でエラーがある場合
    if (isset($_SESSION['new-password-error'])){
        // new-passwordページに値を渡してリダイレクト
        $_SESSION['new-password-data'] = $_POST;
        header('location: '.ROOT_URL.'new-password.php?token='.$token);
        die();
    
    } else {
        // DBに登録
        $insertUserQuery = "UPDATE users SET password='$hashedPassword', token=NULL, updated_at=CURRENT_TIMESTAMP() WHERE token='$token' AND is_deleted=0 LIMIT 1";
        $insertUserResult = mysqli_query($connection, $insertUserQuery);
        
        // DB接続エラーがない場合
        if (!mysqli_errno($connection)){
            $_SESSION['new-password-success'] = "パスワードを変更しました";
            $_SESSION['user_ID'] = $user['user_ID'];
            $_SESSION['role_ID'] = $user['role_ID'];
            header('location:' . ROOT_URL . 'admin/');
            die();
        
        // DB接続エラーがある場合
        } else {
            $_SESSION['new-password-error'] = "パスワードを変更できません";
            $_SESSION['new-password-data'] = $_POST;
            header('location: ' . ROOT_URL . 'new-password.php?token='. $token);
            die();
        }
    }

} else {
    // 変更ボタンがクリックされずに画面遷移した場合
    header(('location:' . ROOT_URL));
    die();
}
?>