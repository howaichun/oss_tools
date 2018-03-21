<?php
$dbname = 'test_okay_mall';
$dsn = 'mysql:host=127.0.0.1;dbname='.$dbname;
$username = '';
$password = '';
$options = array(
    PDO::MYSQL_ATTR_INIT_COMMAND => 'SET NAMES utf8',
);

try{
    $pdo = new PDO($dsn, $username, $password, $options);

//默认这个不是长连接，如果需要数据库长连接，需要最后加一个参数：array(PDO::ATTR_PERSISTENT => true) 变成这样
//    $pdo = new PDO($dsn, $username, $password,
//        array(
//            PDO::MYSQL_ATTR_INIT_COMMAND => 'SET NAMES utf8',
//            PDO::ATTR_PERSISTENT => true
//        )
//    );

    echo "数据库连接成功!\n";
}catch (PDOException $e) {
    die ("Error!: " . $e->getMessage() . "<br/>");
}

