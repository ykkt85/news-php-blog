<?php
require __DIR__ . '/config/database.php';

// new-password.phpからフォームが送信された場合
if (isset($_POST['submit'])){
    $token = filter_var($_POST['token'], FILTER_SANITIZE_FULL_SPECIAL_CHARS);
    $createdPassword = filter_var($_POST['createdpassword'], FILTER_SANITIZE_FULL_SPECIAL_CHARS);
    $confirmedPassword = filter_var($_POST['confirmedpassword'], FILTER_SANITIZE_FULL_SPECIAL_CHARS);
    $pattern = '/\A(?=.*?[a-z])(?=.*?[A-Z])(?=.*?\d)(?=.*?[!-\/:-@[-`{-~])[!-~]{8,100}+\z/i';

    //フォームの値確認
    // パスワードが８文字未満の場合
    if (strlen($createdPassword) < 8 || strlen($confirmedPassword) < 8){
        $_SESSION['new_password_error'] = "パスワードは８文字以上で設定してください";

    // パスワードが異なる場合
    } elseif (!hash_equals($createdPassword, $confirmedPassword)){
        $_SESSION['new_password_error'] = "パスワードが違います";

    // パスワードに大文字・記号・数字のいずれかが使われていない場合
    } elseif (preg_match($pattern, $createdPassword) === 0){
        $_SESSION['new_password_error'] = "パスワードに半角英大文字・小文字・数字・記号が含まれていません";
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
        $selectStmt = $connection->prepare('SELECT token FROM users WHERE token=? AND is_deleted=0 LIMIT 1');
        $selectStmt->bind_param('s', $token);
        $success = $selectStmt->execute();
        $selectStmt->bind_result($dbToken);
        $selectStmt->fetch();

        // 値取得時にエラーがある場合
        if (!$success || $dbToken === NULL){
            $_SESSION['new_password_error'] = "無効のURLです";
            header('location: ' . ROOT_URL . 'message.php');
            die();
        }

        // DBに登録
        $updateStmt = $connection->prepare('UPDATE users SET password=?, token=NULL, updated_at=CURRENT_TIMESTAMP() WHERE token=? AND is_deleted=0 LIMIT 1');
    
        // SQL文の値を変更
        //途中
        $updateStmt->bind_param('ss', $hashed, $dbToken);
        $success = $updateStmt->execute();

        // 値更新時にエラーがある場合
        if (!$success) {
            $_SESSION['new_password_error'] = "パスワードを変更できません";
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