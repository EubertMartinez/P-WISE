<?php require_once '../shared/header.php'; ?>
<?php require_once './validation.php'; ?>

<div class="container-fluid">
    <h3 class="mt-4 text-site-primary text-center">Password</h3>

    <div class="row mt-5">
        <form class="col" onsubmit="return SubmitUpdatePasswordForm(this)">
            <div class="form-group">
                New Password:
                <input type="password" class="form-control" name="password" id="pass1">
            </div>
            <div class="form-group">
                Retype Password:
                <input type="password" class="form-control" id="pass2">
            </div>
            
            <div class="form-group d-flex justify-content-center mt-5">
                <input type="submit" class="btn btn-primary px-5" value="Update">
            </div>
        </form>
    </div>
</div>
<?php require_once '../shared/footer.php'; ?>

<script>
    function SubmitUpdatePasswordForm(data) {
        var pass1 = $('#pass1'),
            pass2 = $('#pass2');

        pass1.removeClass('is-invalid');
        pass2.removeClass('is-invalid');

        if (pass1.val() == '' || pass2.val() == '') {
            Swal.fire('Error', 'All fields are required.', 'error')
                .then(function () {
                    if (pass1.val() == '') pass1.addClass('is-invalid');
                    if (pass2.val() == '') pass2.addClass('is-invalid');
                });
        } else if (pass1.val() != pass2.val()) {
            Swal.fire('Error', 'Two passwords did not match.', 'error')
                .then(function () {
                    if (pass1.val() == '') pass1.addClass('is-invalid');
                    if (pass2.val() == '') pass2.addClass('is-invalid');
                });
        } else {
            var formData = new FormData(data);

            $.ajax({
                url: "/api/account/updatePassword.php",
                type: "POST",
                data: formData,
                success: function (msg) {
                    if (msg = 'success') {
                        Swal.fire('success', 'Password has been successfully updated.', 'success')
                            .then(function () {
                                location.reload();
                            });
                    } else {
                        Swal.fire('Error', msg, 'error')
                            .then(function () {
                                pass1.addClass('is-invalid');
                                pass2.addClass('is-invalid');
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