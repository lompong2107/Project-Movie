<?php
define("dbHost", "localhost");
define("dbUsername", "6206021421121");
define("dbPassword", "AZVtQZt8f2Fr1ZiA");
define("dbName", "spf");
$conn = new mysqli(dbHost, dbUsername, dbPassword, dbName);
$conn -> set_charset("utf8");
if ($conn->connect_error) {
    die("การเชื่อมต่อฐานข้อมูลไม่สำเร็จ " . $conn->connect_error);
}
