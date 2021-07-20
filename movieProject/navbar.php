<nav class="navbar fixed-top navbar-expand-lg navbar-light bg-light">
    <a class="navbar-brand" href="#">SPF</a>
    <button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#navbarText"
        aria-controls="navbarText" aria-expanded="false" aria-label="Toggle navigation">
        <span class="navbar-toggler-icon"></span>
    </button>
    <div class="collapse navbar-collapse" id="navbarText">
        <ul class="navbar-nav mr-auto">
            <!-- <li class="nav-item active">
                <a id="main" class="nav-link" href="JavaScript:doCallAjax('front-end/index.php')">หน้าหลัก</a>
            </li> -->
            <li class="nav-item">
                <a id="movie" class="nav-link" href="JavaScript:doCallAjax('front-end/movie.php')">ภาพยนต์</a>
            </li>
            <!-- <li class="nav-item">
                <a id="promotion" class="nav-link" href="JavaScript:doCallAjax('front-end/promotion.php')">โปรโมชั่น</a>
            </li> -->
            <!-- <?php // if (isset($_SESSION['member_id'])) { ?>
            <li class="nav-item">
                <a id="promotion" class="nav-link" href="JavaScript:doCallAjax('front-end/ticket.php')">ตั๋ว</a>
            </li>
            <?php // } ?> -->
        </ul>
        <?php if (isset($_SESSION["firstname"])) {?>
        <span class="navbar-text">
            <div class="btn-group dropleft">
                <button type="button" class="btn btn-secondary dropdown-toggle" data-toggle="dropdown"
                    aria-haspopup="true" aria-expanded="false">
                    <?=$_SESSION["firstname"];?>
                </button>
                <div class="dropdown-menu">
                    <a href="#" class="nav-link" id="logout">ออกจากระบบ</a>
                </div>
            </div>
        </span>
        <?php } else {?>
            <ul class="navbar-nav mr-0">
                <li class="nav-item">
                    <button class="btn" data-toggle="modal" data-target="#login">เข้าสู่ระบบ</button>/
                    <button class="btn" data-toggle="modal" data-target="#register">สมัครสมาชิก</button>
                </li>
            </ul>
        <?php }?>
    </div>
</nav>