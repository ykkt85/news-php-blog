<?php
require 'config/database.php';

// new-password.phpからフォームが送信された場合
if (isset($_POST['submit'])){
    $token = filter_var($_POST['token'], FILTER_SANITIZE_FULL_SPECIAL_CHARS);
    $createdPassword = filter_var($_POST['createdpassword'], FILTER_SANITIZE_FULL_SPECIAL_CHARS);
    $confirmedPassword = filter_var($_POST['confirmedpassword'], FILTER_SANITIZE_FULL_SPECIAL_CHARS);

    //フォームの値確認
    // パスワードが８文字未満の場合
    if (strlen($createdPassword) < 8 || strlen($confirmedPassword) < 8){
        $_SESSION['new_password_error'] = "パスワードは８文字以上で設定してください";
    // パスワードが異なる場合
    } elseif ($createdPassword !== $confirmedPassword){
        $_SESSION['new_password_error'] = "パスワードが違います";
    }

    // この時点でエラーがある場合
    if (isset($_SESSION['new_password_error'])){
        // new-passwordページに値を渡してリダイレクト
        $_SESSION['new_password_data'] = $_POST;
        header('location: '.ROOT_URL.'new-password.php?token='.$token);
        die();

    } else {
        // パスワードをハッシュ化
        $hashed = password_hash($createdPassword, PASSWORD_DEFAULT);

        // DBに同じトークンがあるか確認
        $connection = dbconnect();
        $selectStmt = $connection->prepare('SELECT user_ID, role_ID, token FROM users WHERE token=? AND is_deleted=0 LIMIT 1');
        $selectStmt->bind_param('s', $token);
        $success = $selectStmt->execute();

        // 値取得時にエラーがある場合
        if (!$success){
            $_SESSION['new_password_error'] = "無効のURLです";
            header('location: ' . ROOT_URL . 'message.php');
            die();
        } else {
            $selectStmt->bind_result($userID, $roleID, $token);
            $selectStmt->fetch();
        }

        // DBに登録
        $connection = dbconnect();
        $updateStmt = $connection->prepare('UPDATE users SET password=?, token=NULL, updated_at=CURRENT_TIMESTAMP() WHERE token=? AND is_deleted=0 LIMIT 1');
    
        // SQL文の値を変更
        $updateStmt->bind_param('ss', $hashed, $token);
        $success = $updateStmt->execute();

        // 値更新時にエラーがある場合
        if (!$success) {
            $_SESSION['new_password_error'] = "パスワードを変更できません";
            $_SESSION['new_password_data'] = $_POST;
            header('location: ' . ROOT_URL . 'new-password.php?token=' . $token);
            die();
            
        // エラーがない場合
        } else {
            $_SESSION['new_password_success'] = "パスワードを変更しました";
            $_SESSION['user_ID'] = $userID;
            $_SESSION['role_ID'] = $roleID;
            header('location:' . ROOT_URL . 'admin/');
            die();
        }
    }

// 変更ボタンがクリックされずに画面遷移した場合
} else {
    header(('location:' . ROOT_URL));
    die();
}
?>