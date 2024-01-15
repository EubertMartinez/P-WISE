<?php require_once '../shared/header.php'; ?>
<?php require_once './validation.php'; ?>
<?php
require_once './../data/account/UpdateData.php';
require_once './../repository/account/UpdateRepository.php'; 

$data = new UpdateRepository();
$user = $data->GetUserData($id);

?>

<div class="container-fluid">
    <h3 class="mt-4 text-site-primary text-center">Profile</h3>

    <div class="row mt-5">
        <form class="col" onsubmit="return SubmitUpdateUserDataForm(this)">
            <div class="form-group">
                First Name:
                <input type="text" class="form-control" name="fname" id="firstname" value="<?php echo $user[0]['firstname']; ?>">
            </div>
            <div class="form-group">
                Last Name:
                <input type="text" class="form-control" name="lname" id="lastname" value="<?php echo $user[0]['lastname']; ?>">
            </div>
            <div class="form-group">
                Email:
                <input type="text" class="form-control" value="<?php echo $user[0]['email']; ?>" disabled>
            </div>
            <div class="form-group">
                Username:
                <input type="text" class="form-control" value="<?php echo $user[0]['username']; ?>" disabled>
            </div>
            <div class="form-group">
                Mobile:
                <input type="number" class="form-control" name="phone" id="mobile" value="<?php echo $user[0]['mobile']; ?>">
            </div>
            <div class="form-group d-flex justify-content-center mt-5">
                <input type="submit" class="btn btn-primary px-5" value="Update">
            </div>
        </form>
    </div>
</div>
<?php require_once '../shared/footer.php'; ?>

<script>
    function SubmitUpdateUserDataForm(data) {
        var fname = $('#firstname'),
            lname = $('#lastname'),
            phone = $('#mobile');

        fname.removeClass('is-invalid');
        lname.removeClass('is-invalid');
        phone.removeClass('is-invalid');

        if (fname.val() == '' || lname.val() == '' || phone.val() == '') {
            Swal.fire('Error', 'All fields are required.', 'error')
                .then(function () {
                    if (fname.val() == '') fname.addClass('is-invalid');
                    if (lname.val() == '') lname.addClass('is-invalid');
                    if (phone.val() == '') phone.addClass('is-invalid');
                });
        } else {
            var formData = new FormData(data);

            $.ajax({
                url: "/api/account/updateData.php",
                type: "POST",
                data: formData,
                success: function (msg) {
                    if (msg = 'success') {
                        Swal.fire('success', 'Profile has been successfully updated.', 'success')
                            .then(function () {
                                location.reload();
                            });
                    } else {
                        Swal.fire('Error', msg, 'error')
                            .then(function () {
                                fname.addClass('is-invalid');
                                lname.addClass('is-invalid');
                                phone.addClass('is-invalid');
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
