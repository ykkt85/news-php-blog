<?php
require __DIR__ . '/../config/database.php';
?>

<!DOCTYPE html>
<html lang="ja">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Tsukuba University News</title>

    <!-- CSS LINK -->
    <link rel="stylesheet" href="<?php echo ROOT_URL ?>css/style.css">
    <!-- ICONSCOUT CDN -->
    <link rel="stylesheet" href="https://unicons.iconscout.com/release/v4.0.0/css/line.css">
    <!-- MONTSERRAT GOOGLE FONT -->
    <link href="https://fonts.googleapis.com/css2?family=Montserrat:wght@300;400;500;600;700&display=swap" rel="stylesheet">

</head>
<body>
    <nav>
        <div class="container nav__container">
            <a href="<?php echo ROOT_URL ?>" class="nav__logo">Tsukuba University News</a>
            <ul class="nav__items">
                <li><a href="<?php echo ROOT_URL ?>tag-tokusyu.php">特集</a></li>
                <li><a href="<?php echo ROOT_URL ?>tag-minitokusyu.php">ミニ特集</a></li>
                <li><a href="<?php echo ROOT_URL ?>tag-sports.php">スポーツ</a></li>
                <li><a href="<?php echo ROOT_URL ?>tag-studies.php">研究</a></li>
                <!-- ログイン中の場合は表示 -->
                <?php if (isset($_SESSION['user_ID'])): ?>
                    <li><a href="<?php echo ROOT_URL ?>admin/">管理</a></li>
                <?php endif; ?>
            </ul>
            <button id="open__nav-btn"><i class="uil uil-bars"></i></button>
            <button id="close__nav-btn"><i class="uil uil-multiply"></i></button>
        </div>
    </nav>
    <!--================ END OF NAV ================-->