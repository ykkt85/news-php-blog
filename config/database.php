<?php
require 'config/constants.php';

// DBに接続
function dbconnect(){
    $connection = new mysqli(DB_HOST, DB_USER, DB_PASS, DB_NAME);
    if (!$connection){
        die($connection->error);
    }
    return $connection;
}
?>