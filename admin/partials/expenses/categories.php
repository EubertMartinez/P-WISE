<div class="row mt-5">
    <div class="col-12">
        <div class="d-flex w-100 justify-content-center">
            <button class="btn btn-site-primary-inline btn-add-category">Add Category</button>
        </div>
    </div>
    <div class="col-12">
        <div class="border shadow tile-container mt-5">
            <div class="py-3 table-responsive">
                <table class="table border w-100" id="category-table">
                    <thead>
                        <tr>
                            <th scope="col" class="d-none">ID</th>
                            <th scope="col">Category Name</th>
                            <th scope="col">Created By</th>
                            <th scope="col">Date Created</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php foreach($expensesCategories as $item): ?>
                            <tr>
                                <td scope="col" class="d-none"><?php echo $item['id']; ?></td>
                                <td scope="col"><?php echo $item['name']; ?></td>
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

<div class="modal fade" id="add-category-modal" tabindex="-1" role="dialog" aria-labelledby="add-category-modalLabel" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="add-category-modalLabel">Add Expenses Category</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <form id="add-category-form" onsubmit="return SubmitAddCategoryForm(this)">
                    <input type="hidden" name="parent" value="<?php echo $parentId; ?>">
                    <div class="col-12 my-4 p-0">
                        Category Name
                        <input type="text" name="category" id="add_category" class="form-control site-btn site-primary-radius" placeholder="New Category Name">
                    </div>
                    <div class="col-12 my-4 p-0">
                        Priority Level
                        <select name="priority" id="add_priority" class="form-control site-btn site-primary-radius">
                            <option value=""></option>
                            <?php foreach($priorityLevels as $priorityLevel): ?>
                                <option value="<?php echo $priorityLevel['id']; ?>">
                                    <?php echo $priorityLevel['name']; ?>
                                </option>
                            <?php endforeach; ?>
                        </select>
                    </div>
                </form>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary site-primary-radius site-btn-secondary px-3" data-dismiss="modal">Close</button>
                <button type="button" class="btn btn-site-primary-inline px-3 btn-save-budget" id="btn-submite-add-category">Save</button>
            </div>
        </div>
    </div>
</div>

<script>
    $(function () {
        $('#category-table').DataTable();

        $('#btn-submite-add-category').on('click', function() {
            $('#add-category-form').submit();
        });

        $('.btn-add-category').on('click', function () {
            $('#add-category-modal').modal('show');
        });
    });

    function SubmitAddCategoryForm(data) {
        var category = $('#add_category'),
            priority = $('#add_priority');

        category.css('border', '1px solid rgb(206, 212, 218)');
        priority.css('border', '1px solid rgb(206, 212, 218)');

        if (category.val() == '' || priority.val() == '') {
            Swal.fire('Error', 'All fields are required!', 'error')
                .then(function () {
                    if (category.val() == '') {
                        category.css('border', '2px solid red');
                    }

                    if (priority.val() == '') {
                        priority.css('border', '2px solid red');
                    }
                });
        } else {
            var formData = new FormData(data);
        
            $.ajax({
                url: "/api/budget/addCategory.php",
                type: "POST",
                data: formData,
                success: function (msg) {
                    if (msg.toLowerCase().indexOf('success') >= 0) {
                        Swal.fire('success', 'Category has been added.', 'success')
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