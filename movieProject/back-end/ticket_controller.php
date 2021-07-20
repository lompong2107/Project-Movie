<?php
header('Content-Type:application/json');
session_start();
require_once "../connectDB.php";

if ($_POST["submit"] == "selectDate") {
    $sql =  "SELECT * FROM showtimes LEFT JOIN cinema ON cinema.cinema_id = showtimes.cinema_id".
            " LEFT JOIN branch ON branch.branch_id = cinema.branch_id".
            " LEFT JOIN datetime ON datetime.dateTime_id = showtimes.dateTime_id WHERE datetime.dateDay = '{$_POST['dateTicket']}' AND showtimes.movie_id = {$_POST['movie_id']} GROUP BY branch.branch_name";
    $result = $conn->query($sql);
    while ($row = $result->fetch_array()) {
        $json_data[] = array(
            $row['branch_id'] => $row['branch_name']
        );
    }
    echo json_encode($json_data);
}

if ($_POST["submit"] == "selectBranch") {
    $sql =  "SELECT * FROM showtimes LEFT JOIN cinema ON cinema.cinema_id = showtimes.cinema_id".
            " LEFT JOIN branch ON branch.branch_id = cinema.branch_id".
            " LEFT JOIN datetime ON datetime.dateTime_id = showtimes.dateTime_id WHERE datetime.dateDay = '{$_POST['dateTicket']}' AND showtimes.movie_id = {$_POST['movie_id']} AND branch.branch_id = {$_POST['branch_id']} GROUP BY datetime.dateTime";
    $result = $conn->query($sql);
    while ($row = $result->fetch_array()) {
        $json_data[] = array(
            $row['dateTime_id'] => $row['dateTime']
        );
    }
    echo json_encode($json_data);
}

if ($_POST["submit"] == "selectMovie") {
    $sql = "SELECT * FROM movie LEFT JOIN type_movie ON movie.type_movie_id = type_movie.type_movie_id WHERE movie_id = '{$_POST['movie_id']}';";
    $query = $conn->query($sql);
    $result = $query->fetch_assoc();
    echo json_encode(array("movie_id" => "{$result['movie_id']}", "movie_name" => "{$result['movie_name']}", "movie_image" => "{$result['movie_image']}", 
    "getInDate" => "{$result['getInDate']}", "getOutDate" => "{$result['getOutDate']}", "type_movie_name" => "{$result['type_movie_name']}",
    "timeMovie" => "{$result['timeMovie']}",
    ));
}

if ($_POST["submit"] == "selectTime") {
    $sql =  "SELECT * FROM showtimes LEFT JOIN cinema ON cinema.cinema_id = showtimes.cinema_id".
            " LEFT JOIN branch ON branch.branch_id = cinema.branch_id".
            " LEFT JOIN datetime ON datetime.dateTime_id = showtimes.dateTime_id WHERE datetime.dateDay = '{$_POST['dateTicket']}' AND showtimes.movie_id = {$_POST['movie_id']} AND branch.branch_id = {$_POST['branch_id']} GROUP BY showtimes.cinema_id";
    $query = $conn->query($sql);
    $result = $query->fetch_assoc();
    $sql = "SELECT * FROM seat LEFT JOIN type_seat ON type_seat.type_seat_id = seat.type_seat_id WHERE seat.cinema_id = {$result['cinema_id']} ORDER BY LENGTH(seat.seat_id) DESC, seat.seat_id DESC;";
    $result = $conn->query($sql);
    while ($row = $result->fetch_assoc()) {
        $json_data[] = array(
            $row['seat_id'] => $row['type_seat_name']
        );
    }
    echo json_encode($json_data);
}

if ($_POST["submit"] == "buyTicket") {
    $dateTicket = $_POST["dateTicket"];
    $branch = $_POST["branch"];
    $showtime = $_POST["showtime"];
    $phone = $_POST["phone"];
    $account = $_POST["account"];
    $cvv = $_POST["cvv"];
    $price = 0;
    $priceTotal = 0;
    $priceAll = 0;
        foreach ($_POST as $key => $value) {
            $sqlSeat = "SELECT * FROM seat LEFT JOIN type_seat ON type_seat.type_seat_id = seat.type_seat_id WHERE seat.seat_id = '{$key}'";
            $query = $conn->query($sqlSeat);
            $result = $query->num_rows;
            if ($result > 0) {
                $result = $query->fetch_assoc();
                foreach ($_POST as $key2 => $value2) {
                    $priceAll += $result["price"];
                }
                $credit_card = "SELECT * FROM credit_card WHERE card_id = '{$account}' AND cvv = '{$cvv}'";
                $querycredit_card = $conn->query($credit_card);
                $money = $querycredit_card->fetch_assoc();
                if (!$money) {
                    echo json_encode(array("status" => "0", "message" => "ไม่พบบัญชีนี้"));
                    exit;
                }
                if ($money["card_balance"] > $priceAll) {
                    if (isset($_SESSION["member_id"])) {
                        $price = $result["price"]-(($result["price"]*10)/100);
                        $priceTotal += $price;
                    } else {
                        $price = $result["price"];
                        $priceTotal += $price;
                    }
                } else {
                    echo json_encode(array("status" => "0", "message" => "เงินไม่พอ"));
                    exit;
                }
                if (isset($_SESSION['member_id'])) {
                    $sql = "INSERT INTO ticket VALUES (NULL, {$_SESSION['member_id']}, {$showtime}, '{$key}', '{$branch}', {$price}, '{$phone}');";
                } else {
                    $sql = "INSERT INTO ticket VALUES (NULL, NULL, {$showtime}, '{$key}', '{$branch}', {$price}, '{$phone}');";
                }
                $query = $conn->query($sql);
            }
        }
        $updatemoney = "UPDATE credit_card SET card_balance = {$money['card_balance']}-{$priceTotal} WHERE card_id = '{$account}'";
        $queryupdate = $conn->query($updatemoney);
    if ($query) {
        echo json_encode(array("status" => "1", "message" => "ซื้อตั๋วสำเร็จ"));
    } else {
        echo json_encode(array("status" => "0", "message" => "ซื้อตั๋วไม่สำเร็จ"));
    }
   
   
}
?>