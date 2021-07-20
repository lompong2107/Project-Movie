<?php 
require_once "../connectDB.php";
$sql = "SELECT * FROM movie;";
$query = $conn->query($sql);
?>
<div class="container">
    <div class="text-center">
    <?php
        $i = 0;
        foreach($query as $key => $value) { 
        ?>
    <img src="image/movie/<?=$value["movie_image"];?>" width=200> <br />
                <label>วันที่เข้าฉาย : <?=$value["getInDate"];?></label>
                <label><?=$value["movie_name"];?></label>
        <?php } ?>
    </div>
</div>