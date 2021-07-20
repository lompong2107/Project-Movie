<nav id="nav" class="navbar fixed-top navbar-expand-lg navbar-light bg-dark">
    <a class="navbar-brand text-white" href="#">SPF</a>
    <button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#navbarText"
        aria-controls="navbarText" aria-expanded="false" aria-label="Toggle navigation">
        <span class="navbar-toggler-icon"></span>
    </button>
    <div class="collapse navbar-collapse" id="navbarText">
        <ul class="navbar-nav mr-auto">
            <!-- <li class="nav-item active">
                <a class="nav-link text-white" href="JavaScript:doCallAjax('dashboard.php')">หน้าหลัก</a>
            </li> -->
            <li class="nav-item">
                <a class="nav-link text-white" href="JavaScript:doCallAjax('movie.php');">การจัดการภาพยนตร์</a>
            </li>
            <!-- <li class="nav-item">
                <a class="nav-link text-white" href="JavaScript:doCallAjax('promotion.php');">การจัดการโปรโมชั่น</a>
            </li> -->
            <li class="nav-item">
                <a class="nav-link text-white" href="JavaScript:doCallAjax('seat.php');">การจัดการที่นั่ง</a>
            </li>
            <li class="nav-item">
                <a class="nav-link text-white" href="JavaScript:doCallAjax('showtime.php');">การจัดการรอบฉาย</a>
            </li>
            <li class="nav-item">
                <a class="nav-link text-white" href="JavaScript:doCallAjax('typeSeat.php');">การจัดการประเภทที่นั่ง</a>
            </li>
        </ul>
        <span class="navbar-text">
            <div class="btn-group dropleft">
                <button type="button" class="btn btn-secondary dropdown-toggle" data-toggle="dropdown"
                    aria-haspopup="true" aria-expanded="false">
                    <?=$_SESSION["adminFirstname"];?>
                </button>
                <div class="dropdown-menu">
                    <a class="nav-link" id="logout">ออกจากระบบ</a>
                </div>
            </div>
        </span>
    </div>
</nav>