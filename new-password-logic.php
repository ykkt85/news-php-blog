<?php
require __DIR__ . '/config/database.php';

// new-password.phpからフォームが送信された場合
if (isset($_POST['submit'])){
    $token = filter_var($_POST['token'], FILTER_SANITIZE_FULL_SPECIAL_CHARS);
        // DBに同じトークンがあるか確認
        $connection = dbconnect();
        $stmt = $connection->prepare('SELECT user_ID, role_ID, token FROM users WHERE token=? AND is_deleted=1 LIMIT 1');
        $stmt->bind_param('s', $token);
        $success = $stmt->execute();
        $stmt->bind_result($userID, $roleID, $dbToken);
        $stmt->fetch();

        // 値取得時にエラーがある場合
        if ($dbToken === NULL){
            $_SESSION['new_password_error'] = "無効のURLです";
            header('location: ' . ROOT_URL . 'message.php');
            die();
        } else {
            // DBに登録
            $connection = dbconnect();
            $stmt = $connection->prepare('UPDATE users SET token=NULL, updated_at=CURRENT_TIMESTAMP(), is_deleted=0 WHERE token=? LIMIT 1');
        
            // SQL文の値を変更
            $stmt->bind_param('s', $dbToken);
            $success = $stmt->execute();
        }

        // 値更新時にエラーがある場合
        if (!$success) {
            $_SESSION['new_password_error'] = "予期せぬエラーが発生しました";
            $_SESSION['new_password_data'] = $_POST;
            header('location: ' . ROOT_URL . 'new-password.php?token=' . $token);
            die();
            
        // エラーがない場合
        } else {
            $_SESSION['new_password_success'] = "パスワードを変更しました";
            $_SESSION['user_ID'] = $userID;
            $_SESSION['role_ID'] = $roleID;
            header('location:' . ROOT_URL . 'message.php');
            die();
        }
    
// 変更ボタンがクリックされずに画面遷移した場合
} else {
    header(('location:' . ROOT_URL));
    die();
}
?>