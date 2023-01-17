<?php
require 'config/database.php';
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
                <li><a href="<?php echo ROOT_URL ?>tab-a.php">TabA</a></li>
                <li><a href="<?php echo ROOT_URL ?>tab-b.php">TabB</a></li>
                <li><a href="<?php echo ROOT_URL ?>tab-c.php">TabC</a></li>
                <li><a href="<?php echo ROOT_URL ?>tab-d.php">TabD</a></li>
                <?php if (isset($_SESSION['user_ID'])): ?>
                    <li class="nav__settings"><a href="<?php echo ROOT_URL ?>admin/">管理</a>
                        <ul>
                            <li><a href="<?php echo ROOT_URL ?>logout.php">Logout</a></li>
                        </ul>
                    </li>
                <?php endif; ?>
            </ul>
            <button id="open__nav-btn"><i class="uil uil-bars"></i></button>
            <button id="close__nav-btn"><i class="uil uil-multiply"></i></button>
        </div>
    </nav>
    <!--================ END OF NAV ================-->