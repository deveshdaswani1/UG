$(document).ready(function () {
    $('#loginPage').on('submit', function (e) {
        e.preventDefault();

        var email = $('#email').val();
        var password = $('#password').val();

        $.ajax({
            url: './backend/auth/authHandler.php?action=login',
            type: 'POST',
            data: {
                email: email,
                password: password
            },
            dataType: 'json',
            success: function (response) {
                console.log(response);
                if (response.status == 'success') {
                    Swal.fire({
                        icon: 'success',
                        title: 'Success',
                        text: response.message
                    }).then((result) => {
                        if (result.isConfirmed) {
                            if (response.role == 'admin') {
                                window.location.href = "./admin/index.php";
                            } else {
                                window.location.href = "./pages/index.php";
                            }
                        }
                    });
                } else {
                    Swal.fire({
                        icon: 'error',
                        title: 'Oops...',
                        text: response.message
                    });
                }
            },
            error: function (jqXHR, textStatus, errorThrown) {
                console.error(textStatus, errorThrown);
                Swal.fire({
                    icon: 'error',
                    title: 'Error',
                    text: 'An error occurred during the login process. Please try again.'
                });
            }
        });
    });
    $('#registrationPage').on('submit', function (e) {
        e.preventDefault();

        var username = $('#Username').val();
        var email = $('#email').val();
        var password = $('#password').val();
        var cpassword = $('#cpassword').val();

        if (password !== cpassword) {
            Swal.fire({
                icon: 'error',
                title: 'Oops...',
                text: 'Passwords do not match!'
            });
            return;
        }

        $.ajax({
            url: './../../backend/auth/authHandler.php?action=registration',
            type: 'POST',
            data: {
                username: username,
                email: email,
                password: password
            },
            dataType:'json',
            success: function (response) {
                if (response.status == 'success') {
                    Swal.fire({
                        icon: 'success',
                        title: 'Success',
                        text: response
                    }).then((result) => {
                        if (result.isConfirmed) {
                            window.location.href = './../../index.html';
                        }
                    });
                } else {
                    console.log(response);
                    Swal.fire({
                        icon: 'error',
                        title: 'Oops...',
                        text: response.message
                    });
                }
            },
            error: function (jqXHR, textStatus, errorThrown) {
                console.error(textStatus, errorThrown);
                Swal.fire({
                    icon: 'error',
                    title: 'Error',
                    text: 'An error occurred during the registration process. Please try again.'
                });
            }
        });
    });
});
