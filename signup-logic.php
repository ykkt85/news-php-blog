<?php
require __DIR__ . '/config/database.php';

// signup.phpのフォームが送信された場合
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
    $createdPassword = filter_var($_POST['createdpassword'], FILTER_SANITIZE_FULL_SPECIAL_CHARS);
    $confirmedPassword = filter_var($_POST['confirmedpassword'], FILTER_SANITIZE_FULL_SPECIAL_CHARS);
    $pattern = '/\A(?=.*?[a-z])(?=.*?[A-Z])(?=.*?\d)(?=.*?[!-\/:-@[-`{-~])[!-~]{8,100}+\z/i';

    //フォームの値確認
    // メールアドレスが空欄の場合
    if (!$email){
        $_SESSION['signup_error'][] = "メールアドレスを入力してください";
    }
    // パスワードが８文字未満の場合
    if (strlen($createdPassword) < 8 || strlen($confirmedPassword) < 8){
        $_SESSION['signup_error'][] = "パスワードは８文字以上で設定してください";

    } else {
        // メールアドレスが既にDBに登録されていないか確認
        $connection = dbconnect();
        $stmt = $connection->prepare('SELECT email FROM users WHERE email=?');
        $stmt->bind_param('s', $email);
        $success = $stmt->execute();
        $stmt->bind_result($dbEmail);
        $stmt->fetch();
        
        // メールアドレスが既にDBに登録されている場合
        if ($dbEmail !== NULL){
            $_SESSION['signup_error'][] = "そのメールアドレスは既に登録されています"; 
        }

        // パスワードが異なる場合
        if (!hash_equals($createdPassword, $confirmedPassword)){
            $_SESSION['signup_error'][] = "パスワードが違います";
        }
        // パスワードに大文字・記号・数字のいずれかが使われていない場合
        if (preg_match($pattern, $createdPassword) === 0){
            $_SESSION['signup_error'][] = "パスワードに半角英大文字・小文字・数字・記号が含まれていません";
        }
    }

    // この時点でエラーがある場合、signup.phpに値を渡してリダイレクト
    if (isset($_SESSION['signup_error'])){
        $_SESSION['signup_data'] = $_POST;
        header('location:' . ROOT_URL . 'signup.php');
    
    } else {
        // パスワードをハッシュ化
        $hashedPassword = password_hash($createdPassword, PASSWORD_DEFAULT);

        // DBに登録
        $connection = dbconnect();
        $stmt = $connection->prepare('INSERT INTO users (email, password, role_ID, token, created_at, updated_at, is_deleted) VALUES(?, ?, 0, NULL, CURRENT_TIMESTAMP(), CURRENT_TIMESTAMP(), 0)');
        $stmt->bind_param('ss', $email, $hashedPassword);
        $success = $stmt->execute();

        // エラーがある場合
        if (!$success){
            $_SESSION['signup_error'][] = "ユーザーを登録できません";
            $_SESSION['signup_data'] = $_POST;
            header('location: ' . ROOT_URL . 'signup.php');
            die();

        // エラーがない場合
        } else {
            $_SESSION['signup_success'] = "ユーザー登録が完了しました。ログインしてください";
            header('location:' . ROOT_URL . 'login.php');
            die();
        }
    }

// 登録ボタンがクリックされずに画面遷移した場合
} else {
    header('location:' . ROOT_URL);
    die();
}
?>