<?php
session_start();
require_once "head.php";
?>
<title>SPF|Manage</title>
</head>

<body>
    <?php require_once "navbar.php";?>
    <div class="container">
        <div id="content" style="margin-top: 70px;"></div>
    </div>

    <!-- Modal Login -->
    <div class="modal fade" id="login" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel"
        aria-hidden="true">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="exampleModalLabel">เข้าสู่ระบบ</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <form id="frmLogin">
                    <div class="modal-body">
                        <div class="form-group">
                            <input type="text" class="form-control" id="email" name="email" placeholder="อีเมล" required>
                        </div>
                        <div class="form-group">
                            <input type="password" class="form-control" id="password" name="password" placeholder="รหัสผ่าน" required>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-dismiss="modal">ปิด</button>
                        <button type="submit" id="login" name="login" class="btn btn-success">เข้าสู่ระบบ</button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <!-- Modal Register -->
    <div class="modal fade" id="register" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel"
        aria-hidden="true">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="exampleModalLabel">สมัครสมาชิก</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <form id="frmRegister">
                    <div class="modal-body">
                        <div class="form-group">
                            <div class="row mr-0 ml-0">
                                <div class="col-xs-6 w-50 pr-2"><input type="text" class="form-control" id="firstname" name="firstname"
                                        placeholder="ชื่อจริง" required></div>
                                <div class="col-xs-6 w-50 pl-2"><input type="text" class="form-control" id="lastname" name="lastname"
                                        placeholder="นามสกุล" required></div>
                            </div>
                        </div>
                        <div class="form-group">
                            <input type="text" class="form-control" id="telephone" name="telephone" placeholder="เบอร์โทรศัพท์"
                                required>
                        </div>
                        <div class="form-group">
                            <input type="email" class="form-control" id="emailReg" name="emailReg" placeholder="อีเมล" required>
                        </div>
                        <div class="form-group">
                            <input type="password" class="form-control" id="pwd" name="pwd" placeholder="รหัสผ่าน" required>
                        </div>
                        <div class="form-group">
                            <input type="password" class="form-control" id="confirm_password" name="confirm_password"
                                placeholder="ยืนยันรหัสผ่าน" required>
                        </div>
                        <div id="error" class="alert alert-danger">
                            รหัสผ่านไม่ตรงกัน
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-dismiss="modal">ปิด</button>
                        <button type="submit" id="register" name="register" class="btn btn-success">สมัครสมาชิก</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
    <script>
        function doCallAjax(url) {
            $("#content").load(url)
        }
    </script>
</body>

</html>