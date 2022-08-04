// form connexion
function connexion() {
    $(document).ready(() => {

        var username = $('.input_user').val();
        var password = $('.input_pass').val();

        var test = 0;
        if (username == "" || username.length < 4) {
            $('.input_user').addClass('is-invalid');
            test = 0;
        }
        else {
            $('.input_user').removeClass('is-invalid');
            test = 1;
        }

        if (password == "" || password.length < 4) {
            $('.input_pass').addClass('is-invalid');
            test = 0;
        }
        else {
            $('.input_pass').removeClass('is-invalid');
            if (test == 0) { }
        }

        if (test == 1) {
            $.ajax({
                url: './traitement/connexion/transfert_connexion.php',
                method: 'POST',
                data: {
                    username: username,
                    password: password
                },
                success: function (data) {
                    if (data == "false") {
                        Swal.fire({
                            position: 'top-end',
                            icon: 'warning',
                            toast: true,
                            title: "Donnée(s) invalide(s) !",
                            showConfirmButton: false,
                            timer: 3000
                        })
                    }
                    else {
                        location.href = "../../public/accueil.php";
                    }
                    // console.log(data);
                }
            })
        }
    })
}


$('#username').keypress(function (e) {
    if (e.which == 13) {
        e.preventDefault();
        $('#password').focus();
    }
});

$('#password').keypress(function (e) {
    if (e.which == 13) {
        e.preventDefault();
        connexion();
    }
});

