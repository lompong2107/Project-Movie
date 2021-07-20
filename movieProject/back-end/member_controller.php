<?php
header('Content-Type:application/json');
session_start();
require_once "../connectDB.php";
date_default_timezone_set("Asia/Bangkok");

if ($_POST["submit"] == "register") {
    $email = $_POST["emailReg"];
    $password = $_POST["pwd"];
    $firstname = $_POST["firstname"];
    $lastname = $_POST["lastname"];
    $telephone = $_POST["telephone"];
    $sql = "INSERT INTO member (status_id, email, password, firstname, lastname, tel_phone) VALUES (2, '{$email}', '{$password}', '{$firstname}', '{$lastname}', '{$telephone}')";
    $query = $conn->query($sql);
    if ($query) {
        echo json_encode(array("status" => "1", "message" => "สมัครสมาชิกสำเร็จ"));
    } else if ($conn->errno == 1062) {
        echo json_encode(array("status" => "0", "message" => "อีเมลล์นี้ถูกใช้แล้ว"));
    } else {
        echo json_encode(array("status" => "0", "message" => "สมัครสมาชิกไม่สำเร็จ"));
    }
}

if ($_POST["submit"] == "login") {
    $email = $_POST["email"];
    $password = $_POST["password"];
    $sql = "SELECT * FROM member WHERE email = '{$email}' AND password = '{$password}' AND status_id != 1";
    $query = $conn->query($sql);
    $result = $query->num_rows;
    if ($result == 1) {
        $result = $query->fetch_assoc();
        $_SESSION["member_id"] = $result["member_id"];
        $_SESSION["tel_phone"] = $result["tel_phone"];
        $_SESSION["firstname"] = $result["firstname"];
        $_SESSION["status"] = $result["status_id"];
        echo json_encode(array("status" => "1", "message" => "เข้าสู่ระบบสำเร็จ"));
    } else {
        echo json_encode(array("status" => "0", "message" => "ชื่อผู้ใช้หรือรหัสผ่านไม่ถูกต้อง"));
    }
}

if ($_POST["submit"] == "logout") {
    session_destroy();
    echo json_encode(array("status" => "1", "message" => "ออกจากระบบสำเร็จ"));
}