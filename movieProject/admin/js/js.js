$(document).ready(() => {
    $("#login-form").submit(e => {
        e.preventDefault()
        $.ajax({
            type: "POST",
            url: "admin_controller.php",
            data: {
                email: $("#email").val(),
                password: $("#password").val(),
                submit: "login"
            },
            success: data => {
                alert(data.message)
                if (data.status == 1)
                    location.href = "index.php"
            }
        })
    })

    $("#logout").click(() => {
        $.ajax({
            type: "POST",
            url: "admin_controller.php",
            data: {
                submit: "logout"
            },
            success: data => {
                alert(data.message)
                if (data.status == 1)
                    location.href = "index.php"
            }
        })
    })
})