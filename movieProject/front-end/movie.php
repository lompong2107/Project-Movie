<?php 
session_start();
require_once "../connectDB.php";
$sql = "SELECT * FROM movie WHERE getOutDate >= CURDATE();";
$query = $conn->query($sql);

$sqlDateTime = "SELECT * FROM datetime GROUP BY dateDay HAVING dateDay >= CURDATE();";
$queryDateTime = $conn->query($sqlDateTime);

function DateThai($strDate)
	{
		$strYear = date("Y",strtotime($strDate))+543;
		$strMonth= date("n",strtotime($strDate));
		$strDay= date("j",strtotime($strDate));
		$strMonthCut = Array(
            "0"=>"",
            "1"=>"มกราคม",
            "2"=>"กุมภาพันธ์",
            "3"=>"มีนาคม",
            "4"=>"เมษายน",
            "5"=>"พฤษภาคม",
            "6"=>"มิถุนายน",
            "7"=>"กรกฎาคม",
            "8"=>"สิงหาคม",
            "9"=>"กันยายน",
            "10"=>"ตุลาคม",
            "11"=>"พฤศจิกายน",
            "12"=>"ธันวาคม"
        );
		$strMonthThai=$strMonthCut[$strMonth];
		return "$strDay $strMonthThai $strYear";
    }
?>
<script src="https://code.jquery.com/jquery-3.4.1.min.js"></script>
<div class="container">
<div class="text-center"><h1>ภาพยนตร์สุดเจ๋ง</h1></div>
    <div class="row text-center">
        <?php
        $i = 0;
        foreach($query as $key => $value) {
        ?>
        <div class="col-3">
            <div class="bg-light">
                <a data-toggle="modal" data-target="#buyTicket1" href="#" class="btn" id="<?=$value['movie_id'];?>">
                    <img src="image/movie/<?=$value["movie_image"];?>" width=200> <br />
                    <label>วันที่เข้าฉาย : <?=$value["getInDate"];?></label> <br>
                    <label><?=$value["movie_name"];?></label>
                </a>
            </div>
        </div>
        <script>
        $(document).ready(() => {
            $("#<?=$value['movie_id'];?>").click(() => {
                $.ajax({
                    type: "POST",
                    url: "back-end/ticket_controller.php",
                    data: {
                        movie_id: <?=$value["movie_id"];?> ,
                        submit : "selectMovie"
                    },
                    success: data => {
                        $("#branch").empty()
                        $("#showtime").empty()
                        $('#seat').empty()
                        $("#movie_image").attr("src", "image/movie/" + data.movie_image)
                        $("#movie_name").text(data.movie_name)
                        $("#type_movie_name").text("หมวดหมู่ : " + data.type_movie_name)
                        $("#timeMovie").text("ความยาว : " + data.timeMovie + " นาที")
                        $("#getInDate").text("วันที่เข้าฉาย : " + data.getInDate)
                        $("#getOutDate").text("วันที่หมดการฉาย : " + data.getOutDate)
                        $("#buy_movie_id").val(data.movie_id)
                        $("#dateTicket").change(() => {
                            $.ajax({
                                type: "POST",
                                url: "back-end/ticket_controller.php",
                                data: {
                                    movie_id: $("#buy_movie_id").val(),
                                    dateTicket: $("#dateTicket").val(),
                                    submit : "selectDate"
                                },
                                success: data => {
                                    $("#branch").empty()
                                    $('#seat').empty()
                                    let i=0
                                    $.each(data, () => {
                                        $.each(data[i], (key, value) => {
                                            $('#branch').append("<option value='"+key+"'>"+ value +"</option>");
                                        })
                                        i++
                                    })
                                    $("#branch").change(() => {
                                        $.ajax({
                                            type: "POST",
                                            url: "back-end/ticket_controller.php",
                                            data: {
                                                movie_id: $("#buy_movie_id").val(),
                                                dateTicket: $("#dateTicket").val(),
                                                branch_id: $("#branch").val(),
                                                submit : "selectBranch"
                                            },
                                            success: data => {
                                                $("#showtime").empty()
                                                $('#seat').empty()
                                                let i=0
                                                $.each(data, () => {
                                                    $.each(data[i], (key, value) => {
                                                        $('#showtime').append("<option value='"+key+"'>"+ value +"</option>");
                                                    })
                                                    i++
                                                })
                                                $("#showtime").change(() => {
                                                    $.ajax({
                                                        type: "POST",
                                                        url: "back-end/ticket_controller.php",
                                                        data: {
                                                            movie_id: $("#buy_movie_id").val(),
                                                            dateTicket: $("#dateTicket").val(),
                                                            branch_id: $("#branch").val(),
                                                            submit : "selectTime"
                                                        },
                                                        success: data => {
                                                            var html = "<div class='row'><div class='col w-100 text-center'> หน้าจอ </div>"
                                                            $('#seat').empty()
                                                            let i=0
                                                            let j=""
                                                            
                                                            $.each(data, () => {
                                                                $.each(data[i], (key, value) => {
                                                                 
                                                                    if (j != value) {
                                                                        html += "</div><div class='row'>"
                                                                    }
                                                                    html += "<div class='col'>"
                                                                    html += "<input type='checkbox' name='" + key + "' id='" + key + "' />"
                                                                    html += "<label for='" + key + "'><img src='image/icon/" + value + ".png' width='25'></label></div>"
                                                                    if (j != value) {
                                                                        j = value 
                                                                    }
                                                                    $('#seat').html(html)
                                                                })
                                                                i++
                                                            })
                                                        },
                                                        error: () => {
                                                            console.log("ERROR SEAT")               
                                                        }
                                                    })
                                                }).change()
                                            },
                                            error: () => {
                                                console.log("ERROR SHOW TIME")               
                                            }
                                        })
                                    }).change()
                                },
                                error: () => {
                                    console.log("ERROR BRANCH")                
                                }
                            })
                        }).change()
                    }
                })
            })
        })
        </script>
        <?php
        if ($i == 3) {
            $i = 0;
        ?>
    </div>
    <div class='row text-center pt-3'>
        <?php } $i++; } ?>
    </div>
</div>

<!-- Modal buyTicket1 -->
<div class="modal fade" id="buyTicket1" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel"
    aria-hidden="true">
    <div class="modal-dialog" role="document">
        <div class="modal-content" style="width: 600px;">
            <div class="modal-header">
                <h5 class="modal-title" id="exampleModalLabel">ซื้อตั๋ว</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <form id="buyTicket-form">
                <div class="modal-body">
                    <div class="form-group">
                        <div class="row">
                            <div class="col-3">
                                <img id="movie_image" src="" width="100">
                            </div>
                            <div class="col-9">
                                <b><label id="movie_name"></label></b> <br>
                                <label id="type_movie_name"></label> <br>
                                <label id="timeMovie"></label> <br>
                                <label id="getInDate"></label> <br>
                                <label id="getOutDate"></label>
                            </div>
                        </div>
                    </div>
                    <div class="form-group">
                        <label>เลือกวันที่ :</label>
                        <select name="dateTicket" id="dateTicket" class="form-control">
                            <?php
                            foreach ($queryDateTime as $key => $value) {
                            ?>
                                <option value="<?=$value['dateDay']?>"><?=DateThai($value['dateDay']);?></option>
                            <?php } ?>
                        </select>
                    </div>
                    <div class="form-group">
                        <label>เลือกสาขา :</label>
                        <select name="branch" id="branch" class="form-control">
                        </select>
                    </div>
                    <div class="form-group">
                        <label>เลือกรอบฉาย :</label>
                        <select name="showtime" id="showtime" class="form-control">
                        </select>
                    </div>
                    <div class="form-group">
                        <div>เลือกที่นั่ง :</div>
                        <div id="seat" name="seat" class="border"></div>
                    </div>
                    <div class="form-group">
                        <label>เบอร์โทร :</label>
                        <input type="number" name="phone" id="phone" class="form-control" required value="<?php if (isset($_SESSION['tel_phone'])) echo $_SESSION['tel_phone']?>">
                    </div>
                    <div class="form-group">
                        <label>เลขบัตรเครดิต :</label>
                        <input type="number" name="account" id="account" class="form-control" required>
                    </div>
                    <div class="form-group">
                        <label>เลข CCV :</label>
                        <input type="password" name="cvv" id="cvv" class="form-control" required>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-dismiss="modal">ปิด</button>
                    <button type="submit" id="submitMovie" class="btn btn-success">ซื้อตั๋ว</button>
                    <input type="hidden" id="submit" name="submit" value="buyTicket">
                    <input type="hidden" id="buy_movie_id">
                </div>
            </form>
        </div>
    </div>
</div>

<script src="https://code.jquery.com/jquery-3.4.1.min.js"></script>
<script>
    $(document).ready(() => {
        $("#buyTicket-form").submit(e => {
            e.preventDefault()
            $.ajax({
                type: "POST",
                url: "back-end/ticket_controller.php",
                data: $("#buyTicket-form").serialize(),
                success: data => {
                    console.log(data)
                    alert(data.message)
                    if (data.status == 1) {
                        location.href = "index.php"
                    }
                },
                error: data => {
                    console.log("ERROR ADD");
                }
            })
        })
    })
</script>