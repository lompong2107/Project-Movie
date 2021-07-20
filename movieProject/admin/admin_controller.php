<?php
header('Content-Type:application/json');
session_start();
date_default_timezone_set("Asia/Bangkok");
require_once "../connectDB.php";

if ($_POST["submit"] == "login") {
    $email = $_POST["email"];
    $password = $_POST["password"];
    $sql = "SELECT * FROM member WHERE email = '{$email}' AND password = '{$password}' AND status_id = '1'";
    $query = $conn->query($sql);
    $result = $query->num_rows;
    if ($result > 0) {
        $result = $query->fetch_assoc();
        $_SESSION["adminFirstname"] = $result["firstname"];
        $_SESSION["adminStatus"] = $result["status_id"];
        echo json_encode(array("status" => "1", "message" => "เข้าสู่ระบบสำเร็จ"));
    } else {
        echo json_encode(array("status" => "0", "message" => "เข้าสู่ระบบไม่สำเร็จ"));
    }
}

if ($_POST["submit"] == "logout") {
    unset($_SESSION["adminFirstname"], $_SESSION["adminStatus"]);
    echo json_encode(array("status" => "1", "message" => "ออกจากระบบสำเร็จ"));
}

if ($_POST["submit"] == "addMovie") {
    $movie_name = $_POST["movie_name"];
    $type_movie_id = $_POST["type_movie_id"];
    $movie_img_name = $_FILES["movie_img"]["name"];
    $movie_img_tmp = $_FILES["movie_img"]["tmp_name"];
    $movie_length = $_POST["movie_length"];
    $getInDate = $_POST["getInDate"];
    $getOutDate = $_POST["getOutDate"];

    $img_lname = pathinfo($movie_img_name, PATHINFO_EXTENSION);
    $img_name = date('dmYHis') . "." . $img_lname;
    $sql = "INSERT INTO movie ";
    $sql .= " VALUES (NULL, '{$type_movie_id}', '{$movie_name}', '{$img_name}', '{$movie_length}', '{$getInDate}', '{$getOutDate}')";
    $query = $conn->query($sql);
    if ($query) {
        copy($movie_img_tmp, "../image/movie/" . $img_name);
        echo json_encode(array("status" => "1", "message" => "เพิ่มภาพยนตร์สำเร็จ"));
    } else
        echo json_encode(array("status" => "0", "message" => "เพิ่มภาพยนตร์ไม่สำเร็จ"));
}

if ($_POST["submit"] == "editMovie") {
    $sql = "SELECT * FROM type_movie";
    $result = $conn->query($sql);
    while ($row = $result->fetch_array()) {
        $json_data[] = array(
            $row['type_movie_id'] => $row['type_movie_name']
        );
    }
    $sql = "SELECT * FROM movie LEFT JOIN type_movie ON movie.type_movie_id = type_movie.type_movie_id WHERE movie_id = '{$_POST['movie_id']}';";
    $query = $conn->query($sql);
    $result = $query->fetch_assoc();
    $json_data[] = array("movie_id" => "{$result['movie_id']}", "movie_name" => "{$result['movie_name']}", "movie_image" => "{$result['movie_image']}", 
    "getInDate" => "{$result['getInDate']}", "getOutDate" => "{$result['getOutDate']}", "type_movie_name" => "{$result['type_movie_name']}",
    "type_movie_id_selected" => "{$result['type_movie_id']}", "timeMovie" => "{$result['timeMovie']}");
    echo json_encode($json_data);
}

if ($_POST["submit"] == "updateMovie") {
    $movie_id = $_POST["movie_id"];
    $movie_name = $_POST["movie_name"];
    $type_movie_id = $_POST["type_movie_id"];
    $movie_img_name = $_FILES["movie_img"]["name"];
    $movie_img_tmp = $_FILES["movie_img"]["tmp_name"];
    $movie_length = $_POST["movie_length"];
    $getInDate = $_POST["getInDate"];
    $getOutDate = $_POST["getOutDate"];
    if ($movie_img_name != "") {
        $img_lname = pathinfo($movie_img_name, PATHINFO_EXTENSION);
        $img_name = date('dmYHis') . "." . $img_lname;
        copy($movie_img_tmp, "../image/movie/" . $img_name);
        $sql = "UPDATE movie SET type_movie_id = {$type_movie_id}, movie_name = '{$movie_name}', ".
        "movie_image = '{$img_name}', timeMovie = '{$movie_length}', getInDate = '{$getInDate}', getOutDate = '{$getOutDate}' ".
        "WHERE movie_id = {$movie_id}";
    } else {
        $sql = "UPDATE movie SET type_movie_id = {$type_movie_id}, movie_name = '{$movie_name}', ".
        "timeMovie = '{$movie_length}', getInDate = '{$getInDate}', getOutDate = '{$getOutDate}' ".
        "WHERE movie_id = {$movie_id}";
    }
    $query = $conn->query($sql);
    if ($query) {
        echo json_encode(array("status" => "1", "message" => "แก้ไขภาพยนตร์สำเร็จ"));
    } else
        echo json_encode(array("status" => "0", "message" => "แก้ไขภาพยนตร์ไม่สำเร็จ"));
}

if ($_POST["submit"] == "delMovie") {
    $sql = "SELECT * FROM movie WHERE movie_id =  {$_POST['movie_id']}";
    $query = $conn->query($sql);
    $result = $query->num_rows;
    if ($result > 0) {
        $result = $query->fetch_assoc();
        unlink("../image/movie/" . $result["movie_image"]);
        $sql = "DELETE FROM movie WHERE movie_id = {$_POST['movie_id']}";
        $query = $conn->query($sql);
        if ($query) {
            echo json_encode(array("status" => "1", "message" => "ลบข้อมูลสำเร็จ"));
        } else
            echo json_encode(array("status" => "0", "message" => "ลบข้อมูลไม่สำเร็จ"));
    }
}

if ($_POST["submit"] == "selectCinema") {
    $sql = "SELECT * FROM cinema WHERE branch_id = {$_POST['branch_id']}";
    $result = $conn->query($sql);
    while ($row = $result->fetch_array()) {
        $json_data[] = array(
            $row['cinema_id'] => $row['cinema_name']
        );
    }
    echo json_encode($json_data);
}

if ($_POST["submit"] == "addSeat") {
    $cinema_id = $_POST["cinema_id"];
    $type_seat_id = $_POST["type_seat_id"];
    $seat_name = $_POST["seat_name"];
    $qty = $_POST["qty"];
    for ($i = 1; $i <= $qty; $i++) {
        $sql = "INSERT INTO seat VALUES ('{$seat_name}{$i}', {$cinema_id}, {$type_seat_id}, '{$seat_name}');";
        $query = $conn->query($sql);
    }
    if ($query) {
        echo json_encode(array("status" => "1", "message" => "เพิ่มที่นั่งสำเร็จ"));
    } else
        echo json_encode(array("status" => "0", "message" => "เพิ่มที่นั่งไม่สำเร็จ"));
}

if ($_POST["submit"] == "editSeat") {
    $sql = "SELECT * FROM branch";
    $result = $conn->query($sql);
    while ($row = $result->fetch_array()) {
        $json_branch[] = array(
            $row['branch_id'] => $row['branch_name']
        );
    }
    
    $sql = "SELECT * FROM cinema WHERE branch_id = {$_POST['branch_id']}";
    $result = $conn->query($sql);
    while ($row = $result->fetch_array()) {
        $json_cinema[] = array(
            $row['cinema_id'] => $row['cinema_name']
        );
    }

    $sql = "SELECT * FROM type_seat";
    $result = $conn->query($sql);
    while ($row = $result->fetch_array()) {
        $json_type_seat[] = array(
            $row['type_seat_id'] => $row['type_seat_name']
        );
    }
    foreach(range('A', 'K') as $alphabet) {
        $json_seatName[] = array(
            $alphabet => $alphabet
        );
    }
    // $sql = "SELECT * FROM seat LEFT JOIN type_seat ON seat.type_seat_id = type_seat.type_seat_id WHERE seat_name = '{$_POST['seat_name']}';";
    // $query = $conn->query($sql);
    // $result1 = $query->fetch_assoc();
    $sqlSeat =  "SELECT *, cinema.cinema_name, COUNT(seat.seat_name) AS qty FROM seat LEFT JOIN type_seat ON seat.type_seat_id = type_seat.type_seat_id ".
            " LEFT JOIN cinema ON seat.cinema_id = cinema.cinema_id".
            " LEFT JOIN branch ON branch.branch_id = cinema.branch_id WHERE seat.cinema_id = {$_POST['cinema_id']} AND seat.seat_id = '{$_POST['seat_id']}' GROUP BY seat.seat_name, seat.cinema_id;";
    $querySeat = $conn->query($sqlSeat);
    $result = $querySeat->fetch_assoc();
    $sqlQty =  "SELECT *, cinema.cinema_name, COUNT(seat.seat_name) AS qty FROM seat LEFT JOIN type_seat ON seat.type_seat_id = type_seat.type_seat_id ".
            " LEFT JOIN cinema ON seat.cinema_id = cinema.cinema_id".
            " LEFT JOIN branch ON branch.branch_id = cinema.branch_id WHERE seat.cinema_id = {$result['cinema_id']} AND seat.seat_name = '{$_POST['seat_name']}'";
    $queryQty = $conn->query($sqlQty);
    $resultQty = $queryQty->fetch_assoc();
            $json_data[] = array(
                "qty" => "{$resultQty['qty']}", "type_seat_id" => "{$result['type_seat_id']}", "branch_id_select" => "{$result['branch_id']}",
                "seat_select" => "{$resultQty['seat_name']}", "cinema_select" => "{$resultQty['cinema_id']}"
            );
    $json_all[] = array(
        "branch" => $json_branch,
        "cinema" => $json_cinema,
        "type_seat" => $json_type_seat,
        "seat_name" => $json_seatName,
        "data" => $json_data
    );
    echo json_encode($json_all);
}

if ($_POST["submit"] == "updateSeat") {
    $cinema_id = $_POST["edit_cinema_id"];
    $type_seat_id = $_POST["edit_type_seat_id"];
    $seat_name = $_POST["edit_seat_name"];
    $sql = "DELETE FROM seat WHERE cinema_id = {$_POST['oldCinema_id']} AND seat_name = '{$_POST['oldSeatName']}'";
    $query = $conn->query($sql);
    $qty = $_POST["edit_qty"];
    for ($i = 1; $i <= $qty; $i++) {
        $sql = "INSERT INTO seat VALUES ('{$seat_name}{$i}', {$cinema_id}, {$type_seat_id}, '{$seat_name}');";
        $query = $conn->query($sql);
    }
    if ($query) {
        echo json_encode(array("status" => "1", "message" => "แก้ไขที่นั่งสำเร็จ"));
    } else
        echo json_encode(array("status" => "0", "message" => "แก้ไขที่นั่งไม่สำเร็จ"));
}

if ($_POST["submit"] == "delSeat") {
    $sql = "DELETE FROM seat WHERE cinema_id = {$_POST['cinema_id']} AND seat_name = '{$_POST['seat_name']}'";
    $query = $conn->query($sql);
    if ($query) {
        echo json_encode(array("status" => "1", "message" => "ลบที่นั่งสำเร็จ"));
    } else
        echo json_encode(array("status" => "0", "message" => "ลบที่นั่งไม่สำเร็จ"));
}

if ($_POST["submit"] == "selectDateDay") {
    $sql = "SELECT * FROM datetime WHERE dateDay = '{$_POST['dateDay']}';";
    $result = $conn->query($sql);
    while ($row = $result->fetch_array()) {
        $json_data[] = array(
            $row['dateTime_id'] => $row['dateTime']
        );
    }
    echo json_encode($json_data);
}

if ($_POST["submit"] == "addShowTime") {
    $cinema_id = $_POST["cinema_id"];
    $movie_id = $_POST["movie_id"];
    $dateDay = $_POST["dateDay"];
    $dateTime = $_POST["dateTime"];
    $sql = "SELECT * FROM datetime WHERE dateDay = '{$dateDay}' AND dateTime = '{$dateTime}';";
    $query = $conn->query($sql);
    $result = $query->fetch_assoc();
    $sqlChk = "SELECT * FROM showtimes WHERE movie_id = {$movie_id} AND dateTime_id = {$result['dateTime_id']} AND cinema_id = {$cinema_id}";
    $queryChk = $conn->query($sqlChk);
    $resultChk = $queryChk->num_rows;
    if ($resultChk > 0) {
        echo json_encode(array("status" => "0", "message" => "มีรอบฉายของภาพยนตร์นี้แล้ว"));
    } else {
        $sql = "INSERT INTO showtimes VALUES (NULL, {$result['dateTime_id']}, {$movie_id}, {$cinema_id});";
        $query = $conn->query($sql);
        if ($query) {
            echo json_encode(array("status" => "1", "message" => "เพิ่มรอบฉายสำเร็จ"));
        } else
            echo json_encode(array("status" => "0", "message" => "เพิ่มรอบฉายไม่สำเร็จ"));
    }
}

if ($_POST["submit"] == "editShowTime") {
    $sql = "SELECT * FROM branch;";
    $result = $conn->query($sql);
    while ($row = $result->fetch_array()) {
        $json_branch[] = array(
            $row['branch_id'] => $row['branch_name']
        );
    }

    $sql = "SELECT * FROM datetime GROUP BY dateDay;";
    $result = $conn->query($sql);
    while ($row = $result->fetch_array()) {
        $json_dateDay[] = array(
            $row['dateTime_id'] => $row['dateDay']
        );
    }

    $sql = "SELECT * FROM movie;";
    $result = $conn->query($sql);
    while ($row = $result->fetch_array()) {
        $json_movie[] = array(
            $row['movie_id'] => $row['movie_name']
        );
    }

    $sql =  "SELECT * FROM showtimes LEFT JOIN cinema ON cinema.cinema_id = showtimes.cinema_id".
            " LEFT JOIN branch ON branch.branch_id = cinema.branch_id".
            " LEFT JOIN datetime ON datetime.dateTime_id = showtimes.dateTime_id WHERE showTime_id = {$_POST['showTime_id']}";
    $query = $conn->query($sql);
    $result = $query->fetch_assoc();
            $json_data[] = array(
                "branch" => "{$result['branch_id']}", "cinema" => "{$result['cinema_id']}", "movie" => "{$result['movie_id']}",
                "dateDay" => "{$result['dateDay']}", "showTime_id" => "{$result['showTime_id']}"
            );
    $json_all[] = array(
        "branchAll" => $json_branch,
        "movieAll" => $json_movie,
        "dateDayAll" => $json_dateDay,
        "data" => $json_data
    );
    echo json_encode($json_all);
}

if ($_POST["submit"] == "selectShowTimeCinema") {
    $sql = "SELECT * FROM cinema WHERE branch_id = {$_POST['branch_id']}";
    $result = $conn->query($sql);
    while ($row = $result->fetch_array()) {
        $json_cinema[] = array(
            $row['cinema_id'] => $row['cinema_name']
        );
    }
    $sql =  "SELECT * FROM showtimes LEFT JOIN cinema ON cinema.cinema_id = showtimes.cinema_id".
            " LEFT JOIN branch ON branch.branch_id = cinema.branch_id".
            " LEFT JOIN datetime ON datetime.dateTime_id = showtimes.dateTime_id WHERE showTime_id = {$_POST['showTime_id']}";
    $query = $conn->query($sql);
    $result = $query->fetch_assoc();
            $json_data[] = array(
                "cinema" => "{$result['cinema_id']}",
            );
            $json_all[] = array(
                "cinemaAll" => $json_cinema,
                "data" => $json_data
            );
            echo json_encode($json_all);
}

if ($_POST["submit"] == "selectShowTimeDateTime") {
    $sql = "SELECT * FROM datetime WHERE dateDay = '{$_POST['dateDay']}'";
    $result = $conn->query($sql);
    while ($row = $result->fetch_array()) {
        $json_dateTime[] = array(
            $row['dateTime_id'] => $row['dateTime']
        );
    }
    $sql =  "SELECT * FROM showtimes LEFT JOIN cinema ON cinema.cinema_id = showtimes.cinema_id".
            " LEFT JOIN branch ON branch.branch_id = cinema.branch_id".
            " LEFT JOIN datetime ON datetime.dateTime_id = showtimes.dateTime_id WHERE showTime_id = {$_POST['showTime_id']}";
    $query = $conn->query($sql);
    $result = $query->fetch_assoc();
            $json_data[] = array(
                "dateTime" => "{$result['dateTime']}",
            );
            $json_all[] = array(
                "dateTimeAll" => $json_dateTime,
                "data" => $json_data
            );
            echo json_encode($json_all);
}

if ($_POST["submit"] == "updateShowTime") {
    $showTime_id = $_POST["showTime_id"];
    $movie_id = $_POST["edit_movie_id"];
    $cinema_id = $_POST["edit_cinema_id"];
    $dateDay = $_POST["edit_dateDay"];
    $dateTime = $_POST["edit_dateTime"];
    $sqlDateTime = "SELECT * FROM datetime WHERE dateDay = '{$dateDay}' AND dateTime = '{$dateTime}'";
    $queryDatetime = $conn->query($sqlDateTime);
    $resultDateTime = $queryDatetime->fetch_assoc(); 
    $sql = "UPDATE showtimes SET movie_id = {$movie_id},".
    " cinema_id = {$cinema_id}, dateTime_id = {$resultDateTime['dateTime_id']} WHERE showTime_id = {$showTime_id}";
    $query = $conn->query($sql);
    if ($query) {
        echo json_encode(array("status" => "1", "message" => "แก้ไขรอบฉายสำเร็จ"));
    } else
        echo json_encode(array("status" => "0", "message" => "แก้ไขรอบฉายไม่สำเร็จ"));
}

if ($_POST["submit"] == "delShowTime") {
    $sql = "DELETE FROM showtimes WHERE showTime_id = {$_POST['showTime_id']}";
    $query = $conn->query($sql);
    if ($query) {
        echo json_encode(array("status" => "1", "message" => "ลบรอบฉายสำเร็จ"));
    } else
        echo json_encode(array("status" => "0", "message" => "ลบรอบฉายไม่สำเร็จ"));
}

if ($_POST["submit"] == "addTypeSeat") {
    $sql = "INSERT INTO type_seat VALUES (NULL, '{$_POST['type_seat_name']}', {$_POST['price']});";
    $query = $conn->query($sql);
    if ($query) {
        echo json_encode(array("status" => "1", "message" => "เพิ่มที่นั่งสำเร็จ"));
    } else
        echo json_encode(array("status" => "0", "message" => "เพิ่มที่นั่งไม่สำเร็จ"));
}

if ($_POST["submit"] == "editTypeSeat") {
    $type_seat_id = $_POST["type_seat_id"];
    $sql = "SELECT * FROM type_seat WHERE type_seat_id = {$type_seat_id}";
    $query = $conn->query($sql);
    $result=$query->fetch_assoc();
    echo json_encode(array("type_seat_id" => "{$result['type_seat_id']}", "type_seat_name" => "{$result['type_seat_name']}", "price" => "{$result['price']}"));
}

if ($_POST["submit"] == "updateTypeSeat") {
    $type_seat_id = $_POST["type_seat_id"];
    $type_seat_name = $_POST["type_seat_name"];
    $price = $_POST["price"];
    $sql = "UPDATE type_seat SET type_seat_name = '{$type_seat_name}', price = {$price} WHERE type_seat_id = {$type_seat_id}";
    $query = $conn->query($sql);
    if ($query) {
        echo json_encode(array("status" => "1", "message" => "แก้ไขประเภทที่นั่งสำเร็จ"));
    } else
        echo json_encode(array("status" => "0", "message" => "แก้ไขประเภทที่นั่งไม่สำเร็จ"));
}

if ($_POST["submit"] == "delTypeSeat") {
    $sql = "DELETE FROM type_seat WHERE type_seat_id = {$_POST['type_seat_id']}";
    $query = $conn->query($sql);
    if ($query) {
        echo json_encode(array("status" => "1", "message" => "ลบประเภทที่นั่งสำเร็จ"));
    } else
        echo json_encode(array("status" => "0", "message" => "ลบประเภทที่นั่งไม่สำเร็จ"));
}
?>