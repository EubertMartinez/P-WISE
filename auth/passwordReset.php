<?php
require_once './../helpers/PageIdex.php';
require_once './../data/account/UpdateData.php';
require_once './../repository/account/UpdateRepository.php'; 

$id   = isset($_GET['id']) ? $_GET['id'] : 0;
$data = new UpdateRepository();
$user = $data->GetUserData($id);

if (count($user) < 1)
    header('location: accountNotFound.php');

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

    <title>Password Reset</title>
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
            <div class="col">
                <div class="row border shadow mx-0 site-primary-radius">
                    <div class="col col-md-7 p-3 position-relative">
                        <div class="h-100 d-flex align-items-center justify-content-center">
                            <form class="login-form" onsubmit="return SendResetPassword(this)">
                                <h2 class="text-site-primary text-center mb-5">PASSWORD RESET</h2>
                                <input type="hidden" name="id" value="<?php echo $id; ?>">
                                <div class="mt-4">
                                    <input type="text" id="email" class="form-control site-btn site-primary-radius" placeholder="Email" value="<?php echo $user[0]['email']; ?>" disabled>
                                </div>
                                <div class="mt-4">
                                    <input type="text" id="username" class="form-control site-btn site-primary-radius" placeholder="Username" value="<?php echo $user[0]['username']; ?>" disabled>
                                </div>
                                <div class="mt-4">
                                    <input type="password" name="password" id="pass1" class="form-control site-btn site-primary-radius" placeholder="New Password">
                                </div>
                                <div class="mt-4">
                                    <input type="password" id="pass2" class="form-control site-btn site-primary-radius" placeholder="Retype Password">
                                </div>
                                <div class="mt-4">
                                    <button class="btn btn-site-primary" type="submit">RESET PASSWORD</button>
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
        function SendResetPassword(data) {
            var pass1 = $('#pass1'),
                pass2 = $('#pass2');

            pass1.removeClass('is-invalid');
            pass2.removeClass('is-invalid');

            if (pass1.val() == '' || pass2.val() == '') {
                Swal.fire("Error", "Passwords are required.", "error")
                    .then(function () {
                        if (pass1.val() == '') pass1.addClass('is-invalid');
                        if (pass2.val() == '') pass2.addClass('is-invalid');
                    });
            } else if (pass1.val() != pass2.val()) {
                Swal.fire("Error", "Two passwords did not match.", "error")
                    .then(function () {
                        pass1.addClass('is-invalid');
                        pass2.addClass('is-invalid');
                    });
            } else {
                var formData = new FormData(data);

                $.ajax({
                    url: "/api/account/updatePassword.php",
                    type: "POST",
                    data: formData,
                    success: function (msg) {
                        if (msg == 'success' || +msg == 1) {
                            Swal.fire('success', 'Password reset has been successfully.', 'success')
                                .then(function () {
                                    location.href="login.php";
                                });
                        } else {
                            Swal.fire('Error', msg, 'error')
                                .then(function () {
                                    username.addClass('is-invalid');
                                    password.addClass('is-invalid');
                                });
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