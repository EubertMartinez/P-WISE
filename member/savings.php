<?php 

require_once '../shared/header.php'; 
require_once './validation.php'; 

$savings = $savingsData->GetSavingsByParentId($parentId);
$cycleSavingsAmount = $savingsData->GetCycleSavings($currentCycleId);

$cycleData = $amount->GetRemainingDays($parentId);

$remainingDays = 0;
$startDate = '';
$endDate = '';

if (count($cycleData) > 0) {
    $remainingDays = $cycleData[0]["remaining_days"];
    $startDate = date("M d, Y", strtotime($cycleData[0]["start"]));
    $endDate = date("M d, Y", strtotime($cycleData[0]["end"]));
} 

$totalSavings = $expenses->GetTotalSavingsAmount($parentId);

$prevMonth = date("m", strtotime("first day of previous month"));
$prevMonthYear = date("Y", strtotime("first day of previous month"));

$recentSavings = $expenses->GetRecentSavingsAmount($parentId, $prevMonth, $prevMonthYear);

?>

<div class="container-fluid">
    <h3 class="mt-4 text-site-primary text-center">Savings</h3>

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
        <div class="col-lg-6 mb-4">
            <div class="border shadow site-bg-gray tile-container h-100 d-flex align-content-between w-100  flex-wrap">
                <div class="w-100">
                    <strong class="d-block mb-4">
                        Cycle Savings<br>
                        <?php if ($isCycleStarted === TRUE): ?>
                            <small id="cycle-budget">Covered Date: <br><span><?php echo $startDate.' to '.$endDate; ?></span></small>
                        <?php endif; ?>
                    </strong>
                    <small>Added Amount</small>
                    <h6>₱<?php echo number_format($cycleSavingsAmount[0]['cycle_saving'] ,2); ?></h6>
                    <small>Adjusted Amount</small>
                    <h6 class="text-danger text-bold">-₱<?php echo number_format($cycleAdustedSavings[0]['adjusted_saving'] ,2); ?></h6>
                    <?php $totalCurrentCycleSavings = $cycleSavingsAmount[0]['cycle_saving'] - $cycleAdustedSavings[0]['adjusted_saving']; ?>
                    <?php if ($totalCurrentCycleSavings < 0): ?>
                        <small>Total Amount</small>
                        <h3 class="text-danger">-₱<?php echo number_format(str_replace("-", "", $totalCurrentCycleSavings), 2); ?></h3>
                    <?php else: ?>
                        <small>Total Amount</small>
                        <h3>₱<?php echo number_format($totalCurrentCycleSavings ,2); ?></h3>
                    <?php endif; ?>
                    
                </div>
                <div class="w-100 mt-4">
                    <?php if ($isCycleStarted === TRUE): ?>
                        <div class="row">
                            <div class="col-12">
                                <button class="btn btn-site-primary btn-add-savings">Add Savings</button>
                            </div>
                        </div>
                    <?php else: ?>
                        <h6>Cycle not started</h6>
                    <?php endif; ?>
                </div>
            </div>
        </div>

        <div class="col-lg-6 mb-4">
            <div class="total-savings-tile h-100 border shadow tile-container">
                <p>Total Savings</p>
                <h3 class="text-site-primary">₱<?php echo number_format($totalSavings[0]['savings'], 2); ?></h3>
                <small class="d-block mt-4">Recent Savings</small>
                <strong>₱<?php echo number_format($recentSavings[0]['savings'] ?? 0, 2); ?></strong>
                <small class="d-block mt-4">Total Adjusted Savings To Budget</small>
                <strong class="text-danger">-₱<?php echo number_format($totalAdjustedSavings[0]['adjusted_saving'], 2); ?></strong>
            </div>
        </div>
    </div>

    <!--<div class="row my-5">-->
    <!--    <div class="col">-->
    <!--        <div class="border shadow tile-container">-->
    <!--            <h5 class="text-site-primary mb-4 text-center">-->
                    <!-- <?php if ($isCycleStarted === TRUE): ?>
    <!--                    Budget history for <?php echo $startDate.' to '.$endDate; ?>-->
    <!--                <?php else: ?>-->
    <!--                    Budget history-->
    <!--                <?php endif; ?> -->
    <!--                Savings History-->
    <!--            </h5>-->
    <!--            <div class="py-3 table-responsive">-->
    <!--                <table class="table table-striped" id="savings-table">-->
    <!--                    <thead>-->
    <!--                        <tr>-->
    <!--                            <th scope="col" class="d-none">Id</th>-->
    <!--                            <th scope="col">Name</th>-->
    <!--                            <th scope="col">Date Added</th>-->
    <!--                            <th scope="col">Remarks</th>-->
    <!--                            <th scope="col">Amount</th>-->
    <!--                        </tr>-->
    <!--                    </thead>-->
    <!--                    <tbody>-->
    <!--                        <?php foreach($savings as $log): ?>-->
    <!--                        <tr>-->
    <!--                            <th scope="row" class="d-none"><?php echo $log['id']; ?></th>-->
    <!--                            <td><?php echo $log['user']; ?></td>-->
    <!--                            <td><?php echo date("M d, Y h:ia", strtotime($log['created'])); ?></td>-->
    <!--                            <td><?php echo $log['remarks']; ?></td>-->
    <!--                            <td>₱<?php echo number_format($log['amount'], 2); ?></td>-->
    <!--                        </tr>-->
    <!--                        <?php endforeach; ?>-->
    <!--                    </tbody>-->
    <!--                </table>-->
    <!--            </div>-->
    <!--        </div>-->
    <!--    </div>-->
    <!--</div>-->
</div>

<div class="modal fade" id="add-savings-modal" tabindex="-1" role="dialog" aria-labelledby="add-savings-modalLabel" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="add-savings-modalLabel">Add Savings</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <form id="add-savings-form" onsubmit="return SubmitAddSavingsForm(this)">
                    <input type="hidden" name="cycle_id" value="<?php echo $currentCycleId; ?>">
                    <?php $cycleLabel = $startDate.' - '.$endDate; ?>
                    <input type="hidden" name="id" value="<?php echo $id; ?>">
                    <div class="col-12 my-4 p-0">
                        Cycle
                        <input type="text" class="form-control site-btn site-primary-radius" placeholder="amount" value="<?php echo $cycleLabel; ?>" readonly>
                    </div>
                    <div class="col-12 my-4 p-0">
                        Amount
                        <input type="number" name="amount" id="amount" class="form-control site-btn site-primary-radius" placeholder="amount">
                    </div>
                </form>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary site-primary-radius site-btn-secondary px-3" data-dismiss="modal">Close</button>
                <button type="button" class="btn btn-site-primary-inline px-3 btn-save-savings">Save</button>
            </div>
        </div>
    </div>
</div>

<div class="modal fade" id="adjust-savings-modal" tabindex="-1" role="dialog" aria-labelledby="adjust-savings-modalLabel" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="adjust-savings-modalLabel">Add Savings to Budget</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <form id="adjust-savings-form" onsubmit="return SubmitAdjustSavingsForm(this)">
                    <input type="hidden" name="cycle_id" value="<?php echo $currentCycleId; ?>">
                    <?php $cycleLabel = $startDate.' - '.$endDate; ?>
                    <input type="hidden" name="id" value="<?php echo $id; ?>">
                    <input type="hidden" id="totalSavings" value="<?php echo $totalSavings[0]['savings']; ?>">
                    <div class="col-12 my-4 p-0">
                        Cycle
                        <input type="text" class="form-control site-btn site-primary-radius" placeholder="amount" value="<?php echo $cycleLabel; ?>" readonly>
                    </div>
                    <div class="col-12 my-4 p-0">
                        Amount
                        <input type="number" name="amount" id="ajust_amount" class="form-control site-btn site-primary-radius" placeholder="amount">
                    </div>
                </form>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary site-primary-radius site-btn-secondary px-3" data-dismiss="modal">Close</button>
                <button type="button" class="btn btn-site-primary-inline px-3 btn-save-adjust-savings">Save</button>
            </div>
        </div>
    </div>
</div>

<script>
    $(function () {
        $('#savings-table').DataTable();

        $('.btn-add-savings').on('click', function () {
            $('#add-savings-modal').modal('show');
        });

        $('.btn-save-savings').on('click', function () {
            $('#add-savings-form').submit();
        })

        $('.btn-adjust-savings').on('click', function () {
            $('#adjust-savings-modal').modal('show');
        });

        $('.btn-save-adjust-savings').on('click', function () {
            $('#adjust-savings-form').submit();
        })
    })

    function SubmitAddSavingsForm(data) {
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
                url: "/api/budget/addSavings.php",
                type: "POST",
                data: formData,
                success: function (msg) {
                    if (msg.toLowerCase().indexOf('success') >= 0) {
                        Swal.fire('success', 'Amount has been added to savings.', 'success')
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

    function SubmitAdjustSavingsForm(data) {
        var amount  = $('#ajust_amount'),
            savings = $('#totalSavings').val();

        amount.removeClass('is-invalid');

        if (amount.val() == '' || amount.val() < 1) {
            Swal.fire('Error', 'Invalid amount!', 'error')
                .then(function () {
                    amount.addClass('is-invalid');
                });
        } else if (+savings < +(amount.val())) {
            Swal.fire('Error', 'Savings is not enough.', 'error')
                .then(function () {
                    amount.addClass('is-invalid');
                })
        } else {
            var formData = new FormData(data);

            $.ajax({
                url: "/api/budget/adjustSavings.php",
                type: "POST",
                data: formData,
                success: function (msg) {
                    if (msg.toLowerCase().indexOf('success') >= 0) {
                        Swal.fire('success', 'Savings amount has been added to budget.', 'success')
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

<?php require_once '../shared/note.php'; ?>
<?php require_once '../shared/footer.php'; ?>