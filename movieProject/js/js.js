$(document).ready(() => {
    $("#content").load("front-end/movie.php")
    $("input[name=pwd], input[name=confirm_password]").change(() => {
        var password = $("input[name=pwd]").val()
        var confirm_password = $("input[name=confirm_password]").val()
        if (password == "" && confirm_password == "") {
            $("#error").hide()
            $("register").disabled = false
        } else if (password != confirm_password) {
            $("#error").show()
            $("register").disabled = true
        } else {
            $("#error").hide()
            $("register").disabled = false
        }
    }).change()

    $("#frmLogin").submit(e => {
        e.preventDefault()
        $.ajax({
            type: "POST",
            url: "back-end/member_controller.php",
            data: {
                email: $("#email").val(),
                password: $("#password").val(),
                submit: "login"
            },
            success: data => {
                alert(data.message)
                if (data.status == 1)
                    location.reload()
            }
        })
    })

    $("#frmRegister").submit(e => {
        e.preventDefault()
        $.ajax({
            type: "POST",
            url: "back-end/member_controller.php",
            data: {
                emailReg: $("#emailReg").val(),
                pwd: $("#pwd").val(),
                firstname: $("#firstname").val(),
                lastname: $("#lastname").val(),
                telephone: $("#telephone").val(),
                submit: "register"
            },
            success: data => {
                alert(data.message)
                if (data.status == 1) {
                    location.reload()
                }
            }
        })
    })

    $("#logout").click(() => {
        $.ajax({
            type: "POST",
            url: "back-end/member_controller.php",
            data: {
                submit: "logout"
            },
            success: data => {
                alert(data.message)
                if (data.status == 1)
                    location.reload()
            }
        })
    })
})