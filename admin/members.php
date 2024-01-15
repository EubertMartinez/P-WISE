<?php
session_start();
if (isset($_SESSION['account_type'])) {
    $account_type = $_SESSION['account_type'];

    if ($account_type == 2) { // Personal account
        header("Location: ../personal/dashboard.php");
        exit;
    }

    if ($account_type == 3) { // Member account
        header("Location: ../member/dashboard.php");
        exit;
    }
}
require_once '../shared/header.php';

require_once './validation.php';
require_once './../helpers/PageIdex.php';
require_once './../data/member/MemberData.php';
require_once './../repository/member/MembersRepository.php';



$data = new MembersRepository();
$members = $data->GetMembers($parentId);
$activities = $data->GetMembersActivityLogs($parentId);
?>

<div class="container-fluid">
    <h3 class="mt-4 text-site-primary text-center">Household Members</h3>

    <?php if ($wallet[0]['wallet'] < 0): ?>
        <div class="row mt-5">
            <div class="col">
                <div class="alert alert-danger w-100 text-center">
                    <strong class="w-100 d-block">OVER SPENDING!</strong> The system detected that you are spending too much than the budget on your wallet. Kindly adjust your budget to avoid over spending.</a>
                </div>
            </div>
        </div>
    <?php endif; ?>

    <div class="row mt-5">
        <?php foreach($members as $member): ?>
            <?php 
                $role   = $member['role'] == 'Admin' ? 'Head' : $member['role'];
                $image  = empty($member['image']) 
                        ? './../assets/core/images/user.jpg' 
                        : './../assets/users/'.$member['image']; 
                $name   = $member['firstname'].' '.$member['lastname'];
            ?>
            <div class="col col-lg-4 col-xl-3 text-center">
                <div class="border shadow tile-container mb-4 site-bg-primary-light">
                    <strong class="d-block mb-3"><?php echo $role; ?></strong>
                    <img src="<?php echo $image; ?>" alt="" class="border shadow" style="width: 120px;height: 120px; border-radius: 100%">
                    <p class="mt-3"><?php echo $name; ?></p>
                    <!-- <strong>Php <?php echo number_format($member['amount'] ,2); ?></strong> -->
                </div>
            </div>
        <?php endforeach; ?>
    </div>

    <div class="row mt-5">
        <div class="d-flex justify-content-center w-100">
            <button class="btn btn-site-primary-inline px-4 btn-add-member">Add Member</button>
        </div>
    </div>

    <div class="row my-5">
        <div class="col">
            <div class="border shadow tile-container">
                <h5 class="text-site-primary mb-4 text-center">Recent Activities</h5>

                <div class="py-3 table-responsive">
                    <table class="table" id="budget-history-table">
                        <thead>
                            <tr>
                                <th class="d-none">ID</th>
                                <th scope="col">Name</th>
                                <th scope="col">Action</th>
                                <th scope="col">Activity</th>
                                <th scope="col">Date</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php $count = 1; ?>
                            <?php foreach($activities as $activity): ?>
                                <tr>
                                    <td class="d-none"><?php echo $count; ?></td>
                                    <td scope="col"><?php echo $activity['name']; ?></td>
                                    <td scope="col"><?php echo $activity['type']; ?></td>
                                    <td scope="col"><?php echo $activity['description']; ?></td>
                                    <td scope="col"><?php echo date("M d, Y h:ia", strtotime($activity['created'])); ?></td>
                                </tr>
                            <?php $count++; ?>
                            <?php endforeach; ?>
                        </tbody>
                    </table>
                </div>
                
            </div>
        </div>
    </div>
</div>

<div class="modal fade" id="add-member-modal" tabindex="-1" role="dialog" aria-labelledby="add-member-modalLabel" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="add-member-modalLabel">Add Member</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <form id="add-member-form" class="row" onsubmit="return SubmitAddMemberForm(this)">
                    <input type="hidden" name="parent_id" value="<?php echo $id; ?>">
                    <input type="hidden" name="accountType" value="3">
                    <div class="col-12 mb-4">
                        <input type="text" name="firstname" id="firstname" class="form-control site-btn site-primary-radius" placeholder="First Name">
                    </div>
                    <div class="col-12 mb-4">
                        <input type="text" name="lastname" id="lastname" class="form-control site-btn site-primary-radius input-margin-top" placeholder="Last Name">
                    </div>
                    <div class="col-12 mb-4">
                        <input type="text" name="username" id="username" class="form-control site-btn site-primary-radius input-margin-top" placeholder="Username">
                    </div>
                    <div class="col-12 mb-4">
                        <input type="text" name="email" id="email" class="form-control site-btn site-primary-radius" placeholder="Email Address">
                    </div>
                    <div class="col-12">
                        <input type="text" name="mobile" id="mobile" class="form-control site-btn site-primary-radius input-margin-top" placeholder="Mobile Number">
                    </div>
                </form>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary site-primary-radius site-btn-secondary px-3" data-dismiss="modal">Close</button>
                <button type="button" class="btn btn-site-primary-inline px-3 btn-submit-add-member-form">Save</button>
            </div>
        </div>
    </div>
</div>
<?php require_once '../shared/note.php'; ?>
<?php require_once '../shared/footer.php'; ?>

<script>
    $(function () {
        $('.btn-add-member').on('click', function () {
            $('#firstname').val('');
            $('#lastname').val('');
            $('#email').val('');
            $('#mobile').val('');
            $('#username').val('');

            $('#add-member-modal').modal('show');
        });

        $('.btn-submit-add-member-form').on('click', function () {
            $('#add-member-form').submit();
        });

        $('#budget-history-table').DataTable();
    })

    function SubmitAddMemberForm(data) {
        var firstname = $('#firstname'),
            lastname = $('#lastname'),
            email = $('#email'),
            mobile = $('#mobile'),
            username = $('#username');

        firstname.removeClass('is-invalid');
        lastname.removeClass('is-invalid');
        email.removeClass('is-invalid');
        mobile.removeClass('is-invalid');
        username.removeClass('is-invalid');

        if (firstname.val() == '' || lastname.val() == '' || email.val() == '' || mobile.val() == '' || username.val() == '') {
            Swal.fire('Error', 'All fields are required!', 'error')
                .then(function () {
                    if(firstname.val() == '') firstname.addClass('is-invalid');
                    if(lastname.val() == '') lastname.addClass('is-invalid');
                    if(email.val() == '') email.addClass('is-invalid');
                    if(mobile.val() == '') mobile.addClass('is-invalid');
                    if(username.val() == '') username.addClass('is-invalid');
                });
        } else {
            var formData = new FormData(data);

            $.ajax({
                url: "/api/admin/addMember.php",
                type: "POST",
                data: formData,
                success: function (msg) {
                    if (msg.toLowerCase().indexOf('success') >= 0) {
                        Swal.fire('success', 'New member has been successfully created.', 'success')
                            .then(function () {
                                location.reload();
                            });
                    } else {
                        Swal.fire('Error', msg, 'error')
                            .then(function () {
                                firstname.addClass('is-invalid');
                                lastname.addClass('is-invalid');
                                email.addClass('is-invalid');
                                mobile.addClass('is-invalid');
                                username.addClass('is-invalid');
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