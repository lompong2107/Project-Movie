<?php
session_start();
require_once "../connectDB.php";
    $sql =  "SELECT * FROM ticket LEFT JOIN member ON member.member_id = ticket.member_id".
        " LEFT JOIN showtimes ON showtimes.showTime_id = ticket.showTime_id".
        " LEFT JOIN movie ON movie.movie_id = showtimes.movie_id".
        " LEFT JOIN datetime ON datetime.dateTime_id = showtimes.dateTime_id".
        " LEFT JOIN seat ON seat.seat_id = ticket.seat_id".
        " LEFT JOIN cinema ON cinema.cinema_id = showtimes.cinema_id WHERE member.member_id = {$_SESSION['member_id']} GROUP BY ticket.ticket_id, movie.movie_name";
    $query = $conn->query($sql);
    $result = $query->fetch_assoc();
?>
<div class="container">
    ชื่อ : <?=$result['firstname']." ".$result['lastname']?>
    <?php foreach ($query as $key => $value) {?>
    ชื่อหนัง : <?=$value['movie_name']?> <br>
    <?php } ?>
    <?php foreach ($query as $key => $value) {?>
             <div class="w-50">
                ที่นั่ง : <?=$value["seat_id"]?>
             </div>
    <?php } ?>
</div>
<script src="https://code.jquery.com/jquery-3.4.1.min.js"></script>
