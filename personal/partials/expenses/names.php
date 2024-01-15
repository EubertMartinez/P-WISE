<div class="row mt-5">
    <div class="col-12">
        <div class="d-flex w-100 justify-content-center">
            <button class="btn btn-site-primary-inline btn-add-expenses-name">Add Expenses Name</button>
        </div>
    </div>
    <div class="col-12">
        <div class="border shadow tile-container mt-5">
            <div class="py-3 table-responsive">
                <table class="table border w-100" id="expenses-name-table">
                    <thead>
                        <tr>
                            <th scope="col" class="d-none">ID</th>
                            <th scope="col">Expenses Name</th>
                            <th scope="col">Category</th>
                            <th scope="col">Created By</th>
                            <th scope="col">Date Created</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php foreach($expensesName as $item): ?>
                            <tr>
                                <td scope="col" class="d-none"><?php echo $item['id']; ?></td>
                                <td scope="col"><?php echo $item['name']; ?></td>
                                <td scope="col"><?php echo $item['category']; ?></td>
                                <td scope="col"><?php echo $item['user']; ?></td>
                                <td scope="col"><?php echo date("M d, Y h:ia", strtotime($item['created'])); ?></td>
                            </tr>
                        <?php endforeach; ?>
                    </tbody>
                </table>
            </div>
        </div>
            
    </div>
</div>

<div class="modal fade" id="add-expenses-name-modal" tabindex="-1" role="dialog" aria-labelledby="add-expenses-name-modalLabel" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="add-expenses-name-modalLabel">Add Expenses Category</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <form id="add-expenses-name-form" onsubmit="return SubmitAddExpensesNameForm(this)">
                    <input type="hidden" name="parent" value="<?php echo $parentId; ?>">
                    <div class="col-12 my-4 p-0">
                        Expenses Name
                        <input type="text" name="expenses" id="expenses_name" class="form-control site-btn site-primary-radius" placeholder="New Expenses Name">
                    </div>
                    <div class="col-12 my-4 p-0">
                        Category
                        <select name="category" id="expenses_name_category" class="form-control site-btn site-primary-radius">
                            <option value=""></option>
                            <?php foreach($expensesCategories as $item): ?>
                                <option value="<?php echo $item['id']; ?>">
                                    <?php echo $item['name']; ?>
                                </option>
                            <?php endforeach; ?>
                        </select>
                    </div>
                </form>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary site-primary-radius site-btn-secondary px-3" data-dismiss="modal">Close</button>
                <button type="button" class="btn btn-site-primary-inline px-3 btn-save-budget" id="btn-submit-add-expenses-name">Save</button>
            </div>
        </div>
    </div>
</div>

<script>
    $(function () {
        $('#expenses-name-table').DataTable();

        $('#btn-submit-add-expenses-name').on('click', function() {
            $('#add-expenses-name-form').submit();
        });

        $('.btn-add-expenses-name').on('click', function () {
            $('#add-expenses-name-modal').modal('show');
        });
    });

    function SubmitAddExpensesNameForm(data) {
        var expenses = $('#expenses_name'),
            category = $('#expenses_name_category');

        category.css('border', '1px solid rgb(206, 212, 218)');
        expenses.css('border', '1px solid rgb(206, 212, 218)');

        if (category.val() == '' || expenses.val() == '') {
            Swal.fire('Error', 'All fields are required!', 'error')
                .then(function () {
                    if (category.val() == '') {
                        category.css('border', '2px solid red');
                    }

                    if (expenses.val() == '') {
                        expenses.css('border', '2px solid red');
                    }
                });
        } else {
            var formData = new FormData(data);
        
            $.ajax({
                url: "/api/budget/addExpensesName.php",
                type: "POST",
                data: formData,
                success: function (msg) {
                    if (msg.toLowerCase().indexOf('success') >= 0) {
                        Swal.fire('success', 'Expenses name has been added.', 'success')
                            .then(function () {
                                location.reload();
                            });
                    } else {
                        Swal.fire('Error', msg, 'error');
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