<?php $limitTotalAmount = 0; ?>
<?php foreach($expensesCategories as $item): ?>
    <?php $limitTotalAmount = $limitTotalAmount + $item['limits']; ?>
<?php endforeach; ?>

<div class="row mt-5">
    <div class="col-12">
        <h4 class="mb-5 text-center">Budget Limits</h4>
    </div>

    <div class="col-12">
        <div class="border shadow tile-container">
            <div class="py-3 table-responsive">
                <table class="table border w-100" id="category-table">
                    <thead>
                        <tr>
                            <th scope="col" class="d-none">ID</th>
                            <th scope="col">Category Name</th>
                            <th scope="col">Budget Limit</th>
                            <th scope="col">Total Expenses</th>
                            <?php if ($isCycleStarted): ?>
                                <th scope="col">Actions</th>
                            <?php endif; ?>
                        </tr>
                    </thead>
                    <tbody>
                        <?php foreach($expensesCategories as $item): ?>
                            <tr>
                                <td scope="col" class="d-none"><?php echo $item['id']; ?></td>
                                <td scope="col"><?php echo $item['name']; ?></td>
                                <td scope="col">₱<?php echo $item['limits']; ?></td>
                                <td scope="col">₱<?php echo $item['expenses']; ?></td>
                                <?php if ($isCycleStarted): ?>
                                    <td scope="col">
                                        <button class="btn btn-sm btn-primary btn-adjust-limit"
                                            data-category="<?php echo $item['id']; ?>"
                                            data-category-name="<?php echo $item['name']; ?>"
                                            data-limit-amount="<?php echo $item['limits']; ?>"
                                            data-expenses="<?php echo $item['expenses']; ?>"
                                            data-limit-id="<?php echo $item['limit_id']; ?>">
                                            Adjust
                                        </button>
                                    </td>
                                <?php endif; ?>
                            </tr>
                        <?php endforeach; ?>
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>

<div class="modal fade" id="add-limit-modal" tabindex="-1" role="dialog" aria-labelledby="add-limit-modalLabel" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="add-limit-modalLabel">Adjust Budget Limit</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <form id="adjust-limit-form" onsubmit="return SubmitAdjustLimitForm(this)">
                    <input type="hidden" name="parent" value="<?php echo $parentId; ?>">
                    <input type="hidden" name="cycle" value="<?php echo $currentCycleId; ?>">
                    <input type="hidden" name="category" id="category">
                    <input type="hidden" name="id" id="limit-id">

                    <input type="hidden" id="totalBudget" value="<?php echo $budget + $cycleAdustedSavings[0]['adjusted_saving']; ?>">
                    <input type="hidden" id="totalLimit" value="<?php echo $limitTotalAmount; ?>">
                    <input type="hidden" id="currentLimit">
                    <input type="hidden" id="expenses">
                    <div class="col-12 my-4 p-0">
                        Category Name
                        <input type="text" id="category-name" class="form-control site-btn site-primary-radius" placeholder="Category Name" disabled>
                    </div>
                    <div class="col-12 my-4 p-0">
                        Budget Limit
                        <input type="number" name="amount" id="limit-amount" class="form-control site-btn site-primary-radius" placeholder="Budget Limit Amount">
                    </div>
                </form>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary site-primary-radius site-btn-secondary px-3" data-dismiss="modal">Close</button>
                <button type="button" class="btn btn-site-primary-inline px-3" id="btn-submit-budget-limit">Save</button>
            </div>
        </div>
    </div>
</div>

<script>
    $(function () {
        $('#category-table').DataTable();

        $('#btn-submit-budget-limit').on('click', function() {
            $('#adjust-limit-form').submit();
        });

        $('.btn-adjust-limit').on('click', function () {
            var $this = $(this),
                category = $this.data('category'),
                categoryName = $this.data('category-name'),
                limitAmount = $this.data('limit-amount'),
                expenses = $this.data('expenses')
                currentLimit = $this.data('current-limit'),
                limitId = $this.data('limit-id');

            $('#category').val(category);
            $('#expenses').val(expenses);
            $('#category-name').val(categoryName);
            $('#limit-amount').val(limitAmount);
            $('#currentLimit').val(limitAmount);
            $('#limit-id').val(limitId);

            $('#add-limit-modal').modal('show');
        });


    });

    function SubmitAdjustLimitForm(data) {
        var expenses     = $('#expenses').val(),
            currentLimit = $('#currentLimit').val(),
            limitAmount  = $('#limit-amount').val(),
            totalBudget  = $('#totalBudget').val(),
            totalLimit   = $('#totalLimit').val();

            console.log(+(((+totalLimit) - (+currentLimit)) + (+limitAmount)));

        if (limitAmount == '' || +limitAmount < 1) {
            Swal.fire('Error', 'Budget limit is required.', 'error');
        } else if (+limitAmount < +expenses) {
            Swal.fire('Error', 'Budget limit should be greater than or equal to the current expenses of the selected category', 'error');
        } else if (+totalBudget < +(((+totalLimit) - (+currentLimit)) + (+limitAmount))) {
            

            Swal.fire('Error', 'Not enough budget.', 'error');
        } else {
            var formData = new FormData(data);
        
            $.ajax({
                url: "/api/budget/adjustBudget.php",
                type: "POST",
                data: formData,
                success: function (msg) {
                    if (msg.toLowerCase().indexOf('success') >= 0) {
                        Swal.fire('success', 'Budget has been adjusted successfully.', 'success')
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