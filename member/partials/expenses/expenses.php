<div class="row mt-5 text-center w1-100">
    <div class="col d-flex w-100 justify-content-center">
        <?php if ($isCycleStarted === TRUE): ?>
            <button class="btn btn-site-primary-inline btn-add-expenses">Add Expenses</button>
        <?php else: ?>
            <h5 class="text-danger">Cycle is not yet started</h5>
        <?php endif; ?>
    </div>
</div>

<div class="row mt-5">
    <div class="col">
        <div class="border shadow tile-container">
            <h5 class="text-site-primary mb-4 text-center">Percentage</h5>

            <?php if ($isCycleStarted !== TRUE): ?>
                <h6 class="my-5 text-center">No Data!</h6>
            <?php else: ?>
                <?php

                    $totalPercentage = 0;
                    foreach($expensesPercentage as $totalPercent) {
                        if($totalPercent['percentage'] > 0) {
                            $totalPercentage += $totalPercent['percentage'];
                        }
                    }
                ?>
                <div style="display: flex; align-items: center; justify-content: center;">Total: &nbsp;<strong><?=number_format($totalPercentage, 2);?>%</strong></div>
                <?php foreach($expensesPercentage as $item): ?>
                    <?php
                    if ($item['percentage'] < 1) continue;

                    $percentage = (int)number_format($item['percentage'], 0);
                    if ($percentage <= 33) $color = 'bg-success';
                    elseif ($percentage <= 66) $color = 'bg-warning';
                    else $color = 'bg-danger';
                    ?>
                    <div class="row my-3">
                        <div class="col-md-2">
                            <h6><?php echo $item['category']; ?></h6>
                        </div>
                        <div class="col-md-10">
                            <div class="row">
                                <div class="col">
                                    <div class="progress site-primary-radius">
                                        <div class="progress-bar <?php echo $color; ?>"
                                             role="progressbar"
                                             style="width: <?php echo number_format($item['percentage'], 0); ?>%">
                                            <?php echo number_format($item['percentage'], 2); ?>%
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                <?php endforeach; ?>
            <?php endif; ?>
        </div>
    </div>
</div>

<div class="row my-5">
    <div class="col">
        <div class="border shadow tile-container">
            <h5 class="text-site-primary mb-4 text-center">History</h5>

            <div class="py-3 table-responsive">
                <table class="table table-striped" id="expenses-logs-table">
                    <thead>
                    <tr>
                        <th scope="col" class="d-none">ID</th>
                        <th scope="col">Priority</th>
                        <th scope="col">Category</th>
                        <th scope="col">Expenses</th>
                        <th scope="col">Amount</th>
                        <th scope="col">Date</th>
                        <th scope="col">Added By</th>
                    </tr>
                    </thead>
                    <tbody>
                    <?php foreach($expensesDetails as $details): ?>
                        <tr>
                            <th scope="row" class="d-none"><?php echo $details['id']; ?></th>
                            <td><?php echo $details['priority']; ?></td>
                            <td><?php echo $details['category']; ?></td>
                            <td><?php echo $details['expenses']; ?></td>
                            <td>₱<?php echo $details['amount']; ?></td>
                            <td><?php echo date("M d, Y h:ia", strtotime($details['created'])); ?></td>
                            <td><?php echo $details['user']; ?></td>
                        </tr>
                    <?php endforeach; ?>
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>

<div class="modal fade" id="add-expenses-modal" tabindex="-1" role="dialog" aria-labelledby="add-expenses-modalLabel" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="add-expenses-modalLabel">Add Expenses</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <form id="add-expenses-form" onsubmit="return SubmitAddExpensesForm(this)">
                    <input type="hidden" name="cycle_id" value="<?php echo $currentCycleId; ?>">
                    <div class="col-12 my-4 p-0">
                        Expenses Category
                        <select name="category" id="category" class="form-control site-btn site-primary-radius">
                            <option value="" data-visible="true">Select Category</option>
                            <?php foreach($expensesCategories as $expensesCategory): ?>
                                <option value="<?php echo $expensesCategory['id']; ?>"
                                        data-priority="<?php echo $expensesCategory['priority_level_id']; ?>"
                                        data-expenses="<?php echo $expensesCategory['expenses']; ?>"
                                        data-limits="<?php echo $expensesCategory['limits']; ?>"
                                        data-limit-id="<?php echo $expensesCategory['limit_id']; ?>">
                                    <?php echo $expensesCategory['name']; ?>
                                </option>
                            <?php endforeach; ?>
                        </select>
                    </div>
                    <div class="col-12 my-4 p-0">
                        Priority Level
                        <select name="priority" id="priority" class="form-control site-btn site-primary-radius" disabled>
                            <option value=""></option>
                            <?php foreach($priorityLevels as $priorityLevel): ?>
                                <option value="<?php echo $priorityLevel['id']; ?>">
                                    <?php echo $priorityLevel['name']; ?>
                                </option>
                            <?php endforeach; ?>
                        </select>
                    </div>
                    <div class="col-12 my-4 p-0">
                        Expenses Name
                        <select name="expenses" id="expenses" class="form-control site-btn site-primary-radius">
                            <option value="" data-visible="true">Select Expenses Name</option>
                            <?php foreach($expensesName as $name): ?>
                                <option value="<?php echo $name['id']; ?>" data-category="<?php echo $name['expenses_category_id']; ?>">
                                    <?php echo $name['name']; ?>
                                </option>
                            <?php endforeach; ?>
                            <option value="other" data-visible="true">Other (New Expenses Name)</option>
                        </select>
                    </div>

                    <div class="col-12 my-4 p-0">
                        Amount
                        <input type="number" name="amount" id="amount" class="form-control site-btn site-primary-radius" placeholder="amount">
                    </div>
                </form>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary site-primary-radius site-btn-secondary px-3" data-dismiss="modal">Close</button>
                <button type="button" class="btn btn-site-primary-inline px-3 btn-save-budget" id="btn-add-expenses">Save</button>
            </div>
        </div>
    </div>
</div>

<script>
    $(function () {
        $('#expenses-logs-table').DataTable();
        $('#expenses option').hide();

        $('#btn-add-expenses').on('click', function () {
            $('#add-expenses-form').submit();
        });

        $('.btn-add-expenses').on('click', function () {
            $('#add-expenses-modal').modal('show');
        })

        $('#category').on('change', function () {
            var $this = $(this),
                category = $this.val(),
                priorityLevel = $('#category').find(':selected').data('priority'),
                priorityValue = category == '' ? '' : priorityLevel;

            $('#priority').val(priorityValue);
            $('#expenses').val('');

            $('#expenses option').hide();
            $('#expenses option[data-category="' + category + '"]').show();
        });

        $('#amount').on('keyup', function () {
            var $this = $(this),
                btnSubmit = $('#btn-add-expenses');

            btnSubmit.addClass('d-none');

            if ($this.val() != '') {
                btnSubmit.removeClass('d-none');
            }
        })
    });
    let is_overspent = 0;
    function SubmitAddExpensesForm(data) {
        var amount = parseFloat($('#amount').val()), // Convert to a float
            expenses = $('#expenses').val(),
            currentExpenses = parseFloat($('#category').find(':selected').data('expenses')), // Convert to a float
            currentLimit = parseFloat($('#category').find(':selected').data('limits')); // Convert to a float

        if (expenses == '' || isNaN(amount) || amount < 1) {
            Swal.fire('Error', 'Invalid data. Please check your entries.', 'error');
        } else {
            if (currentLimit < amount + currentExpenses) {
                Swal.fire({
                    icon: 'warning',
                    title: 'Not Enough Budget',
                    html: "You don't have enough budget for the <strong>" + $('#category option:selected').html() + "</strong> category. Please adjust your budget to avoid overspending.",
                    showCancelButton: true,
                    confirmButtonText: `Continue Overspending`
                }).then((result) => {
                    /* Read more about isConfirmed, isDenied below */
                    if (result.isConfirmed) {
                        is_overspent = 1;
                        SaveExpenses(data);
                    }
                })
            } else {
                SaveExpenses(data);
            }
        }

        return false;
    }

    function SaveExpenses(data) {
        var formData = new FormData(data);
        formData.append('is_overspent', is_overspent);
        $.ajax({
            url: "/api/budget/addExpenses.php",
            type: "POST",
            data: formData,
            success: function (msg) {
                if (msg.toLowerCase().indexOf('success') >= 0) {
                    Swal.fire('success', 'Expenses has been added.', 'success')
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
</script>