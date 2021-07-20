<?php
// ทำ Delete
require_once "../connectDB.php";
$sqlMovie = "SELECT * FROM movie LEFT JOIN type_movie ON movie.type_movie_id = type_movie.type_movie_id;";
$movie = $conn->query($sqlMovie);

$sqlType_movie = "SELECT * FROM type_movie;";
$type_movie = $conn->query($sqlType_movie);
?>
<div class="container">
    <div class="text-center">
        <h1>การจัดการภาพยนตร์</h1>
    </div>
    <div class="text-right"><a href="#" data-toggle="modal" data-target="#addMovie"> +
            เพิ่มภาพยนตร์</a></div>
    <table class="table">
        <tr>
            <th>ชื่อภาพยนตร์</th>
            <th>หน้าปก</th>
            <th>หมวดหมู่</th>
            <th>ความยาวภาพยนตร์</th>
            <th>วันที่เข้าฉาย</th>
            <th>วันที่หมดการฉาย</th>
            <th>การจัดการ</th>
        </tr>
        <?php while ($row = $movie->fetch_assoc()) { ?>
        <tr>
            <td><?=$row["movie_name"]?></td>
            <td><img src="../image/movie/<?=$row["movie_image"]?>" width="100"></td>
            <td><?=$row["type_movie_name"]?></td>
            <td><?=$row["timeMovie"]?></td>
            <td><?=$row["getInDate"]?></td>
            <td><?=$row["getOutDate"]?></td>
            <td>
                <button id="<?=$row['movie_id'];?>" data-toggle="modal" data-target="#editMovie" class="btn btn-primary">แก้ไข</button>
                <button id="deleteMovie" class="btn btn-danger <?=$row['movie_id'];?>">ลบ</button>
            </td>
        </tr>
        <script>
            $("#<?=$row['movie_id'];?>").click(() => {
                $.ajax({
                    type: "POST",
                    url: "admin_controller.php",
                    data: {
                        movie_id: <?=$row["movie_id"];?> ,
                        submit : "editMovie"
                    },
                    success: data => {
                        var chk
                        $("#edit_type_movie_id").empty()
                        $.each(data, (key, value) => {
                            $("#output_img2").attr("src","../image/movie/"+value["movie_image"]);
                            $("#edit_movie_name").val(value["movie_name"])
                            $("#edit_movie_length").val(value["timeMovie"])
                            $("#edit_getInDate").val(value["getInDate"])
                            $("#edit_getOutDate").val(value["getOutDate"])
                            $("#edit_movie_id").val(value["movie_id"])
                            chk = value["type_movie_id_selected"]
                        })
                        for (let i=0; i < data.length-1; i++) {
                            $.each(data[i], (key, value2) => {
                                if (key == chk)
                                    $('#edit_type_movie_id').append("<option selected value='"+key+"'>"+value2+"</option>"); 
                                else
                                    $('#edit_type_movie_id').append("<option value='"+key+"'>"+value2+"</option>"); 
                            })
                        }
                    }
                })
            })
            $(".<?=$row['movie_id'];?>").click(() => {
                $.ajax({
                    type: "POST",
                    url: "admin_controller.php",
                    data: {
                        movie_id: <?=$row["movie_id"];?> ,
                        submit : "delMovie"
                    },
                    success: data => {
                        alert(data.message)
                        if (data.status == 1) {
                            location.href = "index.php"
                            $("#content-admin").load("movie.php")
                        }
                    }
                })
            })
        </script>
        <?php } ?>
    </table>

    <!-- Modal addMovie -->
    <div class="modal fade" id="addMovie" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel"
        aria-hidden="true">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="exampleModalLabel">เพิ่มภาพยนตร์</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <form id="addMovie-form" enctype="multipart/form-data">
                    <div class="modal-body">
                        <div class="mx-auto">
                            <div class="form-group">
                                <label>ชื่อภาพยนตร์ :</label>
                                <input type="text" class="form-control" id="movie_name" name="movie_name"
                                    placeholder="ชื่อภาพยนตร์" required>
                            </div>
                            <div class="form-group">
                                <label>ประเภทภาพยนตร์ :</label>
                                <select id="type_movie_id" name="type_movie_id" class="form-control">
                                    <?php while ($row = $type_movie->fetch_assoc()) {?>
                                    <option value="<?=$row['type_movie_id']?>"><?=$row['type_movie_name']?></option>
                                    <?php }?>
                                </select>
                            </div>
                            <div class="form-group">
                                <input type="file" id="movie_img" name="movie_img" value="empty"
                                    onchange="preview_img(event)">
                            </div>
                            <div class="form-group">
                                <div class="text-center">
                                    <img id="output_img" src="" width="200">
                                </div>
                            </div>
                            <div class="form-group">
                                <label>วันที่เข้าฉาย :</label>
                                <input type="date" class="form-control" id="getInDate" name="getInDate" required>
                                <label>วันที่หมดการฉาย :</label>
                                <input type="date" class="form-control" id="getOutDate" name="getOutDate" required>
                            </div>
                            <div class="form-group">
                                <div class="row mr-0 ml-0">
                                    <label class="mb-auto mt-auto">ความยาว :</label>
                                    <div class="pl-2" style="width: 30%;">
                                        <input type="number" class="form-control" id="movie_length" name="movie_length"
                                            placeholder="ความยาวภาพยนตร์" required>
                                    </div>
                                    <label class="mb-auto mt-auto pl-2">นาที</label>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-dismiss="modal">ปิด</button>
                        <button type="submit" id="submitMovie" class="btn btn-success">เพิ่ม</button>
                        <input type="hidden" id="submit" name="submit" value="addMovie">
                    </div>
                </form>
            </div>
        </div>
    </div>

    <!-- Modal editMovie -->
    <div class="modal fade" id="editMovie" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel"
        aria-hidden="true">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="exampleModalLabel">แก้ไขภาพยนตร์</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <form id="editMovie-form" enctype="multipart/form-data">
                    <div class="modal-body">
                        <div class="mx-auto">
                            <div class="form-group">
                                <label>ชื่อภาพยนตร์ :</label>
                                <input type="text" class="form-control" id="edit_movie_name" name="edit_movie_name"
                                    placeholder="ชื่อภาพยนตร์" required>
                            </div>
                            <div class="form-group">
                                <label>ประเภทภาพยนตร์ :</label>
                                <select id="edit_type_movie_id" name="edit_type_movie_id" class="form-control">
                                </select>
                            </div>
                            <div class="form-group">
                                <input type="file" id="movie_img" name="movie_img" value="empty"
                                    onchange="preview_img_edit(event)">
                            </div>
                            <div class="form-group">
                                <div class="text-center">
                                    <img id="output_img2" src="" width="200">
                                </div>
                            </div>
                            <div class="form-group">
                                <label>วันที่เข้าฉาย :</label>
                                <input type="date" class="form-control" id="edit_getInDate" name="edit_getInDate" required>
                                <label>วันที่หมดการฉาย :</label>
                                <input type="date" class="form-control" id="edit_getOutDate" name="edit_getOutDate" required>
                            </div>
                            <div class="form-group">
                                <div class="row mr-0 ml-0">
                                    <label class="mb-auto mt-auto">ความยาว :</label>
                                    <div class="pl-2" style="width: 30%;">
                                        <input type="number" class="form-control" id="edit_movie_length" name="edit_movie_length"
                                            placeholder="ความยาวภาพยนตร์" required>
                                    </div>
                                    <label class="mb-auto mt-auto pl-2">นาที</label>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-dismiss="modal">ปิด</button>
                        <button type="submit" id="editMovie" class="btn btn-success">แก้ไข</button>
                        <input type="hidden" id="editSubmit" name="editSubmit" value="updateMovie">
                        <input type="hidden" id="edit_movie_id">
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>
<script src="https://code.jquery.com/jquery-3.4.1.min.js"></script>
<script>
    $("#addMovie-form").submit(e => {
        e.preventDefault()

        var formData = new FormData($("#addMovie-form")[0])
        formData.append("movie_name", $("#movie_name").val())
        formData.append("type_movie_id", $("#type_movie_id").val())
        formData.append("getInDate", $("#getInDate").val())
        formData.append("getOutDate", $("#getOutDate").val())
        formData.append("movie_length", $("#movie_length").val())

        $.ajax({
            type: "POST",
            // enctype: 'multipart/form-data',
            url: "admin_controller.php",
            cache: false,
            contentType: false,
            processData: false,
            data: formData,
            success: data => {
                alert(data.message)
                if (data.status == 1) {
                    location.href = "index.php"
                    $("#content-admin").load("movie.php")
                }
            }
        })
    })

    $("#editMovie-form").submit(e => {
        e.preventDefault()

        var formData = new FormData($("#editMovie-form")[0])
        formData.append("movie_name", $("#edit_movie_name").val())
        formData.append("type_movie_id", $("#edit_type_movie_id").val())
        formData.append("getInDate", $("#edit_getInDate").val())
        formData.append("getOutDate", $("#edit_getOutDate").val())
        formData.append("movie_length", $("#edit_movie_length").val())
        formData.append("movie_id", $("#edit_movie_id").val())
        formData.append("submit", $("#editSubmit").val())

        $.ajax({
            type: "POST",
            // enctype: 'multipart/form-data',
            url: "admin_controller.php",
            cache: false,
            contentType: false,
            processData: false,
            data: formData,
            success: data => {
                console.log(data);
                
                alert(data.message)
                if (data.status == 1) {
                    location.href = "index.php"
                    $("#content-admin").load("movie.php")
                }
            }
        })
    })

    function preview_img(e) {
        var reader = new FileReader()
        reader.onload = function () {
            var output = document.getElementById('output_img')
            output.src = reader.result
        }
        reader.readAsDataURL(e.target.files[0])
    }

    function preview_img_edit(e) {
        var reader = new FileReader()
        reader.onload = function () {
            var output = document.getElementById('output_img2')
            output.src = reader.result
        }
        reader.readAsDataURL(e.target.files[0])
    }
</script>