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

    <title>Reset Password</title>
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
                            <form class="login-form" onsubmit="return sendReset(this)">
                                <h2 class="text-site-primary text-center mb-5">RESET PASSWORD</h2>
                                <div class="mt-4">
                                    <input type="text" name="email" id="email" class="form-control site-btn site-primary-radius" placeholder="Email">
                                </div>
                                <div class="mt-4">
                                    <button class="btn btn-site-primary" type="submit">SUBMIT</button>
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
        function sendReset(data) {
            var email = $('#email');

            email.removeClass('is-invalid');

            if (email.val() == '') {
                Swal.fire("Error", "Email is  required.", "error")
                    .then(function () {
                        email.addClass('is-invalid');
                    });
            } else {
                var formData = new FormData(data);

                $.ajax({
                    url: "/api/account/resetPassword.php",
                    type: "POST",
                    data: formData,
                    success: function (msg) {
                        if (msg.toLowerCase().indexOf('success') >= 0) {
                            Swal.fire('success', 'Reset password link has been sent to the email provided.', 'success')
                                .then(function () {
                                    location.href="login.php";
                                });
                        } else {
                            Swal.fire('Error', msg, 'error')
                                .then(function () {
                                    email.addClass('is-invalid');
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