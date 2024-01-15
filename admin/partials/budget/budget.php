<div class="row mt-5">
    <div class="col-lg-12 mb-4 d-flex">
        <div class="border shadow site-bg-gray tile-container h-100 d-flex align-content-between w-100  flex-wrap">
            <div class="w-100">
                <strong class="d-block mb-4">
                    Cycle Budget<br>
                    <?php if ($isCycleStarted === TRUE): ?>
                        <small id="cycle-budget">Covered Date: <br><span><?php echo $startDate.' to '.$endDate; ?></span></small>
                    <?php endif; ?>
                </strong>
                <small>Adjusted Amount From Savings</small>
                <h6 class="mb-4">₱<?php echo number_format($cycleAdustedSavings[0]['adjusted_saving'],2) ?? 0; ?></h6>
                <small>Total Amount</small>
                <h3>₱<?php echo number_format($budget + $cycleAdustedSavings[0]['adjusted_saving'],2); ?></h3>
            </div>
            <div class="w-100 mt-4">
                <?php if ($isCycleStarted === TRUE): ?>
                    <button class="btn btn-site-primary btn-add-budget">Add Budget</button>
                <?php else: ?>
                    <button class="btn btn-site-primary btn-open-cycle">Start Cycle</button>
                <?php endif; ?>
            </div>
        </div>
        <?php
        $total_budget = number_format($budget + $cycleAdustedSavings[0]['adjusted_saving'], 2);

        $fifty_percent = str_replace(',','',$total_budget) * 0.5;
        $thirty_percent = str_replace(',','',$total_budget) * 0.3;
        $twenty_percent = str_replace(',','',$total_budget) * 0.2;

        ?>
        <div class="border shadow site-bg-white tile-container h-100 d-flex align-content-between w-100  flex-wrap">
            <div class="w-100">
                <strong class="d-block mb-4">
                    For starter Cycle Suggestions<br>
                </strong>
                <div>
                    <div class="w-100 text-white shadow text-center pt-2 pb-2 mt-2 mb-2 font-weight-bold" style="background: #E31A04; border-radius: 50px;">High Priority (50%)
                        <?php if ($total_budget > 0): ?>
                            ₱<?php echo number_format($fifty_percent, 2); ?>
                        <?php endif; ?>
                    </div>
                    <div class="w-100 text-white shadow text-center pt-2 pb-2 mt-2 mb-2 font-weight-bold" style="background: #FF7A00; border-radius: 50px;">Medium Priority (30%)
                        <?php if ($total_budget > 0): ?>
                            ₱<?php echo number_format($thirty_percent, 2); ?>
                        <?php endif; ?>
                    </div>
                    <div class="w-100 text-white shadow text-center pt-2 pb-2 mt-2 mb-2 font-weight-bold" style="background: #CFC700; border-radius: 50px;">Low Priority (20%)
                        <?php if ($total_budget > 0): ?>
                            ₱<?php echo number_format($twenty_percent, 2); ?>
                        <?php endif; ?>
                    </div>
                </div>

            </div>
            <div class="w-100 mt-4">
                <button class="btn btn-primary w-100 rounded-pill <?= ($total_budget > 0) ? '' : 'disabled' ?>" id="<?= ($total_budget > 0) ? 'budgetUseSuggestion' : '' ?>">Use suggestion</button>
            </div>
        </div>
    </div>
</div>
<div class="row mt-5">
    <div class="col mb-4">
        <div class="border shadow tile-container h-100">
            <div class="w-100">
                <strong class="d-block mb-4 text-center">
                    <?php if ($isCycleStarted === TRUE): ?>
                        Household Contributions for the cycle of <?php echo $startDate.' to '.$endDate; ?>
                    <?php else: ?>
                        Household Contributions
                    <?php endif; ?>
                </strong>
            </div>
            <div class="row">
                <?php foreach($members as $member): ?>
                    <?php 
                        $role   = $member['role'] == 'Admin' ? 'Head' : $member['role'];
                        $image  = empty($member['image']) 
                                ? './../assets/core/images/user.jpg' 
                                : './../assets/users/'.$member['image']; 
                        $name   = $member['firstname'].' '.$member['lastname'];
                    ?>
                    <div class="col col-md-4 col-lg-3 text-center">
                        <div class="d-inline-block mb-4">
                            <strong class="d-block mb-3"><?php echo $role; ?></strong>
                            <img src="<?php echo $image; ?>" alt="" class="border shadow" style="width: 120px;height: 120px; border-radius: 100%">
                            <p class="mt-3"><?php echo $name; ?></p>
                            <strong>₱<?php echo number_format($member['amount'] ,2); ?></strong>
                        </div>
                    </div>
                <?php endforeach; ?>
            </div>
        </div>
    </div>
</div>
<div class="row my-5">
    <div class="col">
        <div class="border shadow tile-container">
            <h5 class="text-site-primary mb-4 text-center">
                <?php if ($isCycleStarted === TRUE): ?>
                    Budget history for <?php echo $startDate.' to '.$endDate; ?>
                <?php else: ?>
                    Budget history
                <?php endif; ?>
            </h5>
            <div class="py-3 table-responsive">
                <table class="table table-striped" id="budget-logs-table">
                    <thead>
                        <tr>
                            <th scope="col" class="d-none">Id</th>
                            <th scope="col">Name</th>
                            <th scope="col">Date Added</th>
                            <th scope="col">Amount</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php foreach($cycleLog as $log): ?>
                        <tr>
                            <th scope="row" class="d-none"><?php echo $log['id']; ?></th>
                            <td><?php echo $log['name']; ?></td>
                            <td><?php echo date("M d, Y h:ia", strtotime($log['created'])); ?></td>
                            <td>₱<?php echo number_format($log['amount'], 2); ?></td>
                        </tr>
                        <?php endforeach; ?>
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>

<div class="modal fade" id="add-budget-modal" tabindex="-1" role="dialog" aria-labelledby="add-budget-modalLabel" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="add-budget-modalLabel">Add Budget</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <form id="add-budget-form" onsubmit="return SubmitAddBudgetForm(this)">
                    <input type="hidden" name="cycle_id" value="<?php echo $currentCycleId; ?>">
                    <?php $cycleLabel = $startDate.' - '.$endDate; ?>
                    <input type="hidden" name="id" value="<?php echo $id; ?>">
                    <div class="col-12 my-4 p-0">
                        Cycle
                        <input type="text"  class="form-control site-btn site-primary-radius" placeholder="amount" value="<?php echo $cycleLabel; ?>" readonly>
                    </div>
                    <div class="col-12 my-4 p-0">
                        Amount
                        <input type="number" name="amount" id="amount" class="form-control site-btn site-primary-radius" placeholder="amount">
                    </div>
                </form>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary site-primary-radius site-btn-secondary px-3" data-dismiss="modal">Close</button>
                <button type="button" class="btn btn-site-primary-inline px-3 btn-save-budget">Save</button>
            </div>
        </div>
    </div>
</div>

<div class="modal fade" id="start-cycle-modal" tabindex="-1" role="dialog" aria-labelledby="start-cycle-modalLabel" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="start-cycle-modalLabel">Start Cycle</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <form id="start-cycle-form" onsubmit="return StartCycleValidation(this)">
                    <input type="hidden" name="id" value="<?php echo $id; ?>">
                    <div class="col-12 my-4 p-0">
                        Start Date:
                        <input type="date" class="form-control" name="cycle-start" id="cycle-start" min="<?php echo $current_date; ?>" max="<?php echo $last_day_this_month; ?>">
                    </div>
                    <div class="col-12 my-4 p-0">
                        End Date:
                        <input type="date" class="form-control" name="cycle-end" id="cycle-end" min="<?php echo $current_date; ?>" max="<?php echo $last_day_this_month; ?>">
                    </div>
                </form>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary site-primary-radius site-btn-secondary px-3" data-dismiss="modal">Close</button>
                <button type="button" class="btn btn-site-primary-inline px-3 btn-start-Cycle">Start</button>
            </div>
        </div>
    </div>
</div>

<script>

    $(document).ready(function () {
        $('#budgetUseSuggestion').click(function () {
            Swal.fire({
                title: "Are you sure?",
                text: "You won't be able to revert this!",
                icon: "warning",
                showCancelButton: true,
                confirmButtonColor: "#3085d6",
                cancelButtonColor: "#d33",
                confirmButtonText: "Yes, use this suggestion!"
            }).then((result) => {
                if (result.isConfirmed) {
                    $.ajax({
                        type: 'POST',
                        url: '/api/suggestions/budgetUseSuggestionDataAjax.php',
                        data: {
                            parentId: '<?=$id;?>',
                            budget: '<?=$total_budget;?>',
                        },
                        dataType: 'json',
                        success: function (response) {
                            if (response.success) {
                                Swal.fire({
                                    title: "Success!",
                                    text: "Suggestion has been applied",
                                    icon: "success"
                                }).then(function () {
                                    location.reload();
                                });
                            } else {
                                Swal.fire({
                                    title: "Cancelled!",
                                    icon: "error"
                                });
                            }
                        },
                        error: function (xhr, status, error) {
                            console.error('AJAX request error: ' + error);
                        }
                    });
                }
            });
        });
    });

    $(function () {
        $('#budget-logs-table').DataTable();

        $('.btn-add-budget').on('click', function () {
            $('#add-budget-modal').modal('show');
        });

        $('.btn-save-budget').on('click', function () {
            $('#add-budget-form').submit();
        });

        $('#weeklyFilterStartDate').on('change', function () {
            var $this = $(this),
                endDate = $('#weeklyFilterEndDate');

            if ($this.val() == '') endDate.val('');
            else {
                var date = new Date($this.val());
                date.setDate(date.getDate() + 7);

                var dd = date.getDate();
                dd = dd.toString().length < 2 ? '0' + dd : dd;

                var mm = date.getMonth() + 1;
                mm = mm.toString().length < 2 ? '0' + mm : mm;

                var y = date.getFullYear();
                var someFormattedDate = y + '/' + mm + '/' + dd;
                var newDate = new Date(someFormattedDate).toISOString().split('T')[0];

                endDate.val(newDate);
            }
        });

        $('.btn-monthly-filter').on('click', function () {
            var month = $('#monthlyFilterMonth'),
                year  = $('#monthlyFilterYear');

            month.removeClass('is-invalid');
            year.removeClass('is-invalid');

            if (month.val() == '' || year.val() == '') {
                Swal.fire('Error', 'All fields are required!', 'error')
                    .then(function () {
                        if (month.val() == '') month.addClass('is-invalid');
                        if (year.val() == '') year.addClass('is-invalid');
                    });
            } else {
                location.href="budget.php?filter=monthly&month=" + month.val() + "&year=" + year.val();
            }
            
        });

        $('.btn-weekly-filter').on('click', function () {
            var startDate = $('#weeklyFilterStartDate'),
                endDate   = $('#weeklyFilterEndDate');

            startDate.removeClass('is-invalid');

            if (startDate.val() == '') {
                Swal.fire('Error', 'Start date is required!', 'error')
                    .then(function () {
                        startDate.addClass('is-invalid');
                    });
            } else {
                location.href="budget.php?filter=range&start=" + startDate.val() + "&end=" + endDate.val();
            }
            
        });

        $('.btn-custom-filter').on('click', function () {
            var startDate = $('#customFilterStartDate'),
                endDate   = $('#customFilterEndDate');

            startDate.removeClass('is-invalid');

            if (startDate.val() == '' || endDate.val() == '') {
                Swal.fire('Error', 'All fields are required!', 'error')
                    .then(function () {
                        if (startDate.val() == '') startDate.addClass('is-invalid');
                        if (endDate.val() == '') endDate.addClass('is-invalid');
                        
                    });
            } else {
                location.href="budget.php?filter=range&start=" + startDate.val() + "&end=" + endDate.val();
            }
        });

        $('.btn-open-cycle').on('click', function () {
            $('#start-cycle-modal').modal('show');
        })

        $('.btn-start-Cycle').on('click', function () {
            $('#start-cycle-form').submit();
        })
    });

    function StartCycleValidation(data) {
        var start = $('#cycle-start'),
            end   = $('#cycle-end');

        start.removeClass('is-invalid');
        end.removeClass('is-invalid');

        if (start.val() == '' || end.val() == '') {
            Swal.fire('Error', 'All fields are required', 'error')
                .then(function () {
                    if (start.val() == '') start.addClass('is-invalid');
                    if (end.val() == '') end.addClass('is-invalid');
                });
        } else {
            Swal.fire({
                title: 'Confirmation',
                html: 'Do you really want to start a cycle?',
                showCancelButton: true,
                confirmButtonText: 'Yes',
                icon: 'info'
            }).then((result) => {
                if (result.isConfirmed) {
                    $.post("/api/budget/startCycle.php", {
                        start: start.val(),
                        end: end.val()
                    }, function(data){
                        if (data == "success") {
                            Swal.fire('Success', "Cycle has been successfully started.", 'success')
                                .then(function () {
                                    location.reload();
                                });
                        } else {
                            Swal.fire("Error", "Unable to start cycle", "error"); 
                        }
                    });
                } 
            });
        }

        return false;
            
    }

    function SubmitAddBudgetForm(data) {
        var amount = $('#amount');

        amount.removeClass('is-invalid');

        if (amount.val() == '' || amount.val() < 1) {
            Swal.fire('Error', 'Invalid amount!', 'error')
                .then(function () {
                    amount.addClass('is-invalid');
                });
        } else {
            var formData = new FormData(data);

            $.ajax({
                url: "/api/budget/addAmount.php",
                type: "POST",
                data: formData,
                success: function (msg) {
                    if (msg.toLowerCase().indexOf('success') >= 0) {
                        Swal.fire('success', 'Amount has been added to budget.', 'success')
                            .then(function () {
                                location.reload();
                            });
                    } else {
                        Swal.fire('Error', msg, 'error')
                            .then(function () {
                                amount.addClass('is-invalid');
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
