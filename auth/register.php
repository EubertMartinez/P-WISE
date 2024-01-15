<?php
session_start();
if (isset($_SESSION['account_type'])) {
    $account_type = $_SESSION['account_type'];

    switch ($account_type) {
        case 1:
            header("Location: ../admin/dashboard.php");
            break;
        case 2:
            header("Location: ../personal/dashboard.php");
            break;
        case 3:
            header("Location: ../member/dashboard.php");
            break;
        default:
            // Handle any other cases or errors
            break;
    }
}
?>
<!doctype html>
<html lang="en">
<head>
    <!-- Required meta tags -->
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <!-- Bootstrap CSS -->
    <link href="./../node_modules/bootstrap/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="./../node_modules/font-awesome/css/font-awesome.min.css">
    <link rel="stylesheet" href="./../node_modules/sweetalert2/dist/sweetalert2.min.css">
    <link rel="stylesheet" href="./../assets/core/css/style.css">  

    <script src="./../node_modules/jquery/dist/jquery.min.js"></script>
    <title>Register</title>
</head>
<body>
    <div class="container">
        <div class="row py-3">
            <div class="col-6 text-start">
                <img src="./../assets/core/images/pwise-green-logo.png" alt="">
            </div>
            <div class="col-6 text-end mt-auto">
                <a href="login.php" class="btn btn-site-outline">LOGIN</a>
            </div>
        </div>
        <div class="row my-4">
            <div class="col" id="choose-account-container">
                <h2 class="text-site-primary text-center mb-5">Choose account type</h2>

                <div class="row">
                    <div class="col-6 justify-content-end d-flex">
                        <div class="inline-block create-account-tile shadow" data-account-type="2" data-account-type-label="Personal Account">
                            <i class="fa fa-user create-account-tile-icon text-site-primary"></i>
                            <h5 class="text-site-primary text-center mt-3">Personal</h5>
                        </div>
                    </div>
                    <div class="col-6 text-start">
                        <div class="inline-block create-account-tile shadow" data-account-type="1" data-account-type-label="Household Admin Account">
                            <i class="fa fa-users create-account-tile-icon text-site-primary"></i>
                            <h5 class="text-site-primary text-center mt-3">Household</h5>
                        </div>
                    </div>
                </div>

                <div class="my-4 text-center text-secondary m-auto choose-account-label-container">
                    <h4>Get peace of mind knowing that your finances are handled wisely</h4>
                </div>
            </div>

            <div class="col d-none" id="create-account-container">
                <div class="row border shadow mx-0 site-primary-radius">
                    <div class="col col-md-7 p-3 position-relative">
                        <div class="h-100 d-flex align-items-center justify-content-center">
                            <form class="login-form" id="login-form" onsubmit="return submitForm(this)">
                                <h2 class="text-site-primary text-center mb-2">Create Account</h2>
                                <h6 class="text-site-primary text-center mb-5" id="account-type-label"></h6>
                                <input type="hidden" name="accountType" id="account-type">
                                <div class="mt-4">
                                    <div class="row">
                                        <div class="col-md-6">
                                            <input type="text" name="firstname" id="firstname" class="form-control site-btn site-primary-radius" placeholder="First Name">
                                        </div>
                                        <div class="col-md-6">
                                            <input type="text" name="lastname" id="lastname" class="form-control site-btn site-primary-radius input-margin-top" placeholder="Last Name">
                                        </div>
                                    </div>
                                </div>
                                <div class="mt-4">
                                    <input type="text" name="email" id="email" class="form-control site-btn site-primary-radius" placeholder="Email Address">
                                </div>
                                <div class="mt-4">
                                    <div class="row">
                                        <div class="col-md-6">
                                            <input type="text" name="username" id="username" class="form-control site-btn site-primary-radius" placeholder="Username">
                                        </div>
                                        <div class="col-md-6">
                                            <input type="text" name="mobile" id="mobile" class="form-control site-btn site-primary-radius input-margin-top" placeholder="Mobile Number">
                                        </div>
                                    </div>
                                </div>
                                <div class="mt-4">
                                    <div class="row">
                                        <div class="col-md-6">
                                            <input type="password" name="password" id="password" class="form-control site-btn site-primary-radius" placeholder="Password">
                                        </div>
                                        <div class="col-md-6">
                                            <input type="password" id="confirm-password" class="form-control site-btn site-primary-radius input-margin-top" placeholder="Confirm Password">
                                        </div>
                                    </div>
                                </div>
                                <div class="my-4">
                                    <button class="btn btn-site-primary" type="submit">SIGN UP</button>
                                </div>
                            </form>
                        </div>
                    </div>
                    <div class="d-none d-md-block col-md-5 p-3 position-relative">
                        <div class="d-flex h-100 align-items-center">
                            <img src="./../assets/core/images/login-banner.png" alt="" class="w-100 site-primary-radius">
                        </div>
                    </div>
                </div>
            </div>
            
        </div>
    </div>
    


    <!-- Optional JavaScript; choose one of the two! -->

    <!-- Option 1: Bootstrap Bundle with Popper -->
    <script src="./../node_modules/bootstrap/dist/js/bootstrap.bundle.min.js"></script>

    <!-- Option 2: Separate Popper and Bootstrap JS -->
    <!--
    <script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.9.2/dist/umd/popper.min.js" integrity="sha384-IQsoLXl5PILFhosVNubq5LC7Qb9DXgDA9i+tQ8Zj3iwWAwPtgFTxbJ8NT4GN1R8p" crossorigin="anonymous"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/js/bootstrap.min.js" integrity="sha384-cVKIPhGWiC2Al4u+LWgxfKTRIcfu0JTxR+EQDz/bgldoEyl4H0zUF0QKbrJ0EcQF" crossorigin="anonymous"></script>
    -->

    <script src="./../node_modules/sweetalert2/dist/sweetalert2.all.min.js"></script>

    <script>
        $(function () {
            $('.create-account-tile').on('click', function () {
                var accountType = $(this).data('account-type'),
                    accountTypeLabel = $(this).data('account-type-label');

                console.log(accountTypeLabel);
                $('#account-type').val(accountType);
                $('#account-type-label').html(accountTypeLabel);

                $('#choose-account-container').addClass('d-none');
                $('#create-account-container').removeClass('d-none');
            });
        })

        function submitForm(data) {
            var firstname = $('#firstname'),
                lastname = $('#lastname'),
                email = $('#email'),
                username = $('#username'),
                mobile = $('#mobile'),
                password = $('#password'),
                confirmPassword = $('#confirm-password');

            firstname.removeClass('is-invalid');
            lastname.removeClass('is-invalid');
            email.removeClass('is-invalid');
            username.removeClass('is-invalid');
            mobile.removeClass('is-invalid');
            password.removeClass('is-invalid');
            confirmPassword.removeClass('is-invalid');

            if (firstname.val() == '' || lastname.val() == '' || email.val() == '' || username.val() == '' || mobile.val() == '' || password.val() == '' || confirmPassword.val() == '') {
                Swal.fire("Error", "All fields are required.", "error")
                    .then(function () {
                        if (firstname.val() == '') firstname.addClass('is-invalid');
                        if (lastname.val() == '') lastname.addClass('is-invalid');
                        if (email.val() == '') email.addClass('is-invalid');
                        if (username.val() == '') username.addClass('is-invalid');
                        if (mobile.val() == '') mobile.addClass('is-invalid');
                        if (password.val() == '') password.addClass('is-invalid');
                        if (confirmPassword.val() == '') confirmPassword.addClass('is-invalid');
                    });
            } else if (password.val() != confirmPassword.val()) {
                Swal.fire("Error", "Two passwords did not match.", "error")
                    .then(function () {
                        password.addClass('is-invalid');
                        confirmPassword.addClass('is-invalid');
                    });
            } else {
                var formData = new FormData(data);

                $.ajax({
                    url: "/api/account/register.php",
                    type: "POST",
                    data: formData,
                    success: function (msg) {
                        if (msg.toLowerCase().indexOf('success') >= 0) {
                            Swal.fire('Success', 'Account has been successfully created. <b><br>Please Check your Email to verify the Account<br><b>', 'success')
                                .then(function () {
                                    location.href="login.php";
                                });
                        } else {
                            Swal.fire('Error', msg, 'error')
                        }
                    },
                    cache: false,
                    contentType: false,
                    processData: false
                });
            }

            return false;
        }
    </script>
</body>
</html>