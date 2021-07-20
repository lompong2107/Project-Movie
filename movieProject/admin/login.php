<?php require_once "head.php" ?>
    <style>
        body {
            margin: 0;
            padding: 0;
            background-color: #000000;
            height: 100vh;
        }

        #login .container #login-row #login-column #login-box {
            margin-top: 120px;
            max-width: 600px;
            height: 300px;
            border: 1px solid #000000;
            background-color: #FFFFFF;
            border-radius: 10px;
        }

        #login .container #login-row #login-column #login-box #login-form {
            padding: 20px;
        }

    </style>
</head>

<body>
    <div id="login">
        <div class="container">
            <div id="login-row" class="row justify-content-center align-items-center">
                <div id="login-column" class="col-md-6">
                    <div id="login-box" class="col-md-12">
                        <form id="login-form" class="form">
                        <h3 class="text-center">Login</h3>
                            <div class="form-group">
                            <label>Email :</label><br>
                                <input type="text" class="form-control" id="email" name="email" placeholder="อีเมล" required>
                            </div>
                            <div class="form-group">
                            <label>Password :</label><br>
                                <input type="password" class="form-control" id="password" name="password" placeholder="รหัสผ่าน"
                                    required>
                            </div>
                            <button type="submit" id="login" name="login" class="btn btn-dark btn-md">เข้าสู่ระบบ</button>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
</body>