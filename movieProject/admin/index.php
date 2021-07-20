<?php
session_start();
require_once "../connectDB.php";
if ($_SESSION["adminStatus"] != "1") {
    header("location: login.php");
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin</title>
    <?php require_once "head.php" ?>
</head>
<body>
    <?php require_once "navbar.php"; ?>
    <div class="container">
        <div id="content-admin" style="margin-top: 70px;"></div>
    </div>
    <script>
        doCallAjax("movie.php")
        function doCallAjax(url) {
            $("#content-admin").load(url)
        }
    </script>
</body>
</html>