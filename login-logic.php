<?php
require __DIR__ . '/config/database.php';

// login.phpからフォームが送信されたとき
if (isset($_POST['submit'])){

    // メールアドレス判定
    if(strpos($_POST['email'], '@docomo.ne.jp') !== false || strpos($_POST['email'], '@ezweb.ne.jp') !== false){
		$pattern = '/^([a-zA-Z])+([a-zA-Z0-9\._-])*@(docomo\.ne\.jp|ezweb\.ne\.jp)$/';
		if(preg_match($pattern, $_POST['email']) === 1) {
			$email = $_POST['email'];
		}
    } else {
        $email = filter_var($_POST['email'], FILTER_VALIDATE_EMAIL);
    }

    // パスワード判定
    $password = filter_var($_POST['password'], FILTER_SANITIZE_FULL_SPECIAL_CHARS);

    // フォームの値確認
    // メールアドレスが空欄の場合
    if (!$email){
        $_SESSION['login_error'][] = "メールアドレスを入力してください";
    }
    // パスワードが空欄の場合
    if (!$password){
        $_SESSION['login_error'][] = "パスワードを入力してください";

    // フォームに全ての値が入力されている場合
    } else {
        // DBに接続
        $connection = dbconnect();
        $stmt = $connection->prepare('SELECT user_ID, email, password, role_ID FROM users WHERE email=?');
        $stmt->bind_param('s', $email);
        $stmt->execute();
        $stmt->bind_result($userID, $dbEmail, $hashed, $roleID);
        $stmt->fetch();

        // メールアドレスが存在する場合、かつ
        // パスワードが一致する場合
        if (isset($dbEmail) && password_verify($password, $hashed)){
            // 各セッション値を設定
            session_regenerate_id();
            $_SESSION['user_ID'] = $userID;
            $_SESSION['role_ID'] = $roleID;
            header('location: ' . ROOT_URL . 'admin/index.php');
        
        // 入力されたメールアドレスが存在しない場合、または
        // パスワードが正しくない場合
        } else {
            $_SESSION['login_error'][] = "メールアドレスまたはパスワードが正しくありません";
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