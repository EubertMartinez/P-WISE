<?php require_once '../shared/header.php'; ?>
<?php require_once './validation.php'; ?>
<?php require_once './../data/member/MemberData.php'; ?>
<?php require_once './../repository/member/MembersRepository.php'; ?>

<?php require_once './../data/reports/CycleData.php'; ?>
<?php require_once './../repository/reports/CycleRepository.php'; ?>

<?php $report  = new CycleRepository(); ?>
<?php $cycles  = $report->GetCyclesByParentId($parentId); ?>
<?php $years   = $report->GetCycleYearsByParentId($parentId); ?>
<?php $months  = $report->GetCycleMonthsByParentId($parentId); ?>

<?php $data = new MembersRepository(); ?>
<?php $members = $data->GetMembers($parentId); ?>

<!-- <?php $expensesPercentage = $expenses->GetExpensesByParentId($parentId); ?> -->
<!-- <?php $expensesDetails = $expenses->GetExpensesDetailsByParentId($parentId); ?> -->

<?php 
$cycleData = $amount->GetRemainingDays($parentId);

$remainingDays = 0;
$startDate = '';
$endDate = '';

if (count($cycleData) > 0) {
    $remainingDays = $cycleData[0]["remaining_days"];
    $startDate = date("M d, Y", strtotime($cycleData[0]["start"]));
    $endDate = date("M d, Y", strtotime($cycleData[0]["end"]));
} 

$cycleLabel = $startDate.' - '.$endDate;

$cycleId = isset($_GET['cycle_id']) ? $_GET['cycle_id'] : 0;
$month   = isset($_GET['month']) ? $_GET['month'] : "";
$year    = isset($_GET['year']) ? $_GET['year'] : "";

$expensesPercentage = $report->GetCyclePercentageByCycleId($parentId, $cycleId);
$budgets = $report->GetBudgetsByParentIdAndCycleId($parentId, $cycleId);
$savings = $report->GetSavingsByParentIdAndCycleId($parentId, $cycleId);
$expensesDetails = $report->GetExpensesByParentIdAndCycleId($parentId, $cycleId);
?>

<div class="container-fluid">
    <h3 class="my-4 text-site-primary text-center">Reports</h3>
    
    <?php if ($cycleId > 0): ?>
        <h6 class="my-4 text-center"><span id="cycleHeading"></span> Cycle</h6>
    <?php endif; ?>

    <div class="row">
        <div class="col-12 mb-3">
            <strong>Filter</strong>
        </div>
        <div class="col-6 col-md-3 mb-3">
            Year:
            <select name="year" id="year" class="form-control">
                <option value="">Select Year</option>
                <?php foreach($years as $item): ?>
                    <option value="<?php echo $item['year']; ?>"><?php echo $item['year']; ?></option>
                <?php endforeach; ?>
            </select>
        </div>
        <div class="col-6 col-md-3 mb-3">
            Month:
            <select name="month" id="month" class="form-control">
                <option value="" data-visible="true">Select Month</option>
                <?php foreach($months as $item): ?>
                    <?php
                        $monthLabel = '';

                        if ($item['month'] == 1)  $monthLabel = 'January';
                        if ($item['month'] == 2)  $monthLabel = 'February';
                        if ($item['month'] == 3)  $monthLabel = 'March';
                        if ($item['month'] == 4)  $monthLabel = 'April';
                        if ($item['month'] == 5)  $monthLabel = 'May';
                        if ($item['month'] == 6)  $monthLabel = 'June';
                        if ($item['month'] == 7)  $monthLabel = 'July';
                        if ($item['month'] == 8)  $monthLabel = 'August';
                        if ($item['month'] == 9)  $monthLabel = 'September';
                        if ($item['month'] == 10) $monthLabel = 'October';
                        if ($item['month'] == 11) $monthLabel = 'November';
                        if ($item['month'] == 12) $monthLabel = 'December';
                    ?>
                    <option value="<?php echo $item['month']; ?>"><?php echo $monthLabel; ?></option>
                <?php endforeach; ?>
            </select>
        </div>
        <div class="col-12 col-md-4 mb-3">
            Cycle:
            <select name="cycle" id="cycle" class="form-control">
                <option value="" data-visible="true">Select Cycle</option>
                <?php foreach($cycles as $cycle): ?>
                    <option value="<?php echo $cycle['id']; ?>"
                        data-start-year-month="<?php echo $cycle['start_year'].'_'.$cycle['start_month']; ?>"
                        data-end-year-month="<?php echo $cycle['end_year'].'_'.$cycle['end_month']; ?>">
                        <?php echo date("M d, Y", strtotime($cycle['start'])).' to '.date("M d, Y", strtotime($cycle['end'])); ?>
                    </option>
                <?php endforeach; ?>
            </select>
        </div>
        <div class="col-12 col-md-2 mb-3 d-flex align-items-end">
            <button class="d-block w-100 btn btn-primary" id="btn-view">View</button>
        </div>
    </div>
    
    <?php if ($cycleId > 0): ?>
        <div class="row mt-5">
            <div class="col-lg-6">
                <div class="card mb-4">
                    <div class="card-header"><i class="fas fa-chart-pie mr-1"></i>Expenses By Priorities</div>
                    <div class="card-body"><canvas id="myPieChart" width="100%" height="50"></canvas></div>
                </div>
            </div>
            <div class="col-lg-6">
                <div class="card mb-4">
                    <div class="card-header"><i class="fas fa-chart-bar mr-1"></i>Expenses By Categories</div>
                    <div class="card-body"><canvas id="myBarChart" width="100%" height="50"></canvas></div>
                </div>
            </div>
        </div>  
        
        <div class="row">
            <div class="col-12 col-xl-12 mt-5">
                <h5 class="text-site-primary mb-4 text-center">Budgets</h5>
                <div class="py-3 table-responsive">
                    <table class="table border" id="budget-table">
                        <thead>
                            <tr>
                                <th scope="col">Name</th>
                                <th scope="col">Amount</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php $count = 1; ?>
                            <?php foreach($budgets as $budget): ?>
                                <tr>
                                    <td scope="col"><?php echo $budget['firstname'].' '.$budget['lastname']; ?></td>
                                    <td scope="col">₱<?php echo $budget['amount']; ?></td>
                                </tr>
                            <?php $count++; ?>
                            <?php endforeach; ?>
                        </tbody>
                    </table>
                </div>
            </div>
            <!-- <div class="col-12 col-xl-6 mt-5">
                <h5 class="text-site-primary mb-4 text-center">Savings</h5>

                <div class="py-3 table-responsive">
                    <table class="table border" id="savings-table">
                        <thead>
                            <tr>
                                <th scope="col">Name</th>
                                <th scope="col">Amount</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php $count = 1; ?>
                            <?php foreach($savings as $saving): ?>
                                <tr>
                                    <td scope="col"><?php echo $saving['firstname'].' '.$saving['lastname']; ?></td>
                                    <td scope="col">₱<?php echo $saving['amount']; ?></td>
                                </tr>
                            <?php $count++; ?>
                            <?php endforeach; ?>
                        </tbody>
                    </table>
                </div>
            </div> -->
        </div>

        <div class="row mt-5">
            <div class="col">
                <h5 class="text-site-primary mb-4 text-center">Expenses</h5>
                <div class="py-3 table-responsive">
                    <table class="table border table-striped" id="expenses-table">
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
    <?php else: ?>
        <div class="h-100" style="min-height: calc(100vh - 400px)"></div>
    <?php endif; ?>
</div>

<?php require_once '../shared/note.php'; ?>
<?php require_once '../shared/footer.php'; ?>

<script>
    $(function () {
        setTimeout(() => {
            hideCycles();

            var $year  = '<?php echo $year; ?>',
                $month = '<?php echo $month; ?>',
                $cycle = '<?php echo $cycleId > 0 ? $cycleId : ""; ?>';

            $('#year').val($year);
            $('#month').val($month);
            $('#cycle').val($cycle);

            if (!($year == '' || $month == '')) {
                showCycles($month, $year);
                $('#cycleHeading').html( $( "#cycle option:selected" ).text() );
            }

            toggleViewButton();
        }, 100);
        
        $('#year').on('change', function () {
            $('#cycle').val('');
            hideCycles();

            $year = $(this).val(),
            $month  = $('#month').val();

            showCycles($month, $year);
        });

        $('#month').on('change', function () {
            $('#cycle').val('');
            hideCycles();

            $month = $(this).val(),
            $year  = $('#year').val();

            showCycles($month, $year);
        });

        $('#cycle').on('change', function () {
            toggleViewButton();
        });

        $('#btn-view').on('click', function () {
            $month = $('#month').val();
            $year  = $('#year').val();
            $cycle = $('#cycle').val();

            var isValid = $month != '' || $year != '' || $cycle != '';

            if (isValid) {
                location.href = 'reports.php?cycle_id=' + $cycle + '&year=' + $year + '&month=' + $month;
            }
        });
    })

    $('#budget-table').DataTable();
    $('#savings-table').DataTable();
    $('#expenses-table').DataTable();

    function toggleViewButton() {
        $month = $('#month').val();
        $year  = $('#year').val();
        $cycle = $('#cycle').val();

        isDisabled = $month == '' || $year == '' || $cycle == '';
        $('#btn-view').attr('disabled', isDisabled);
       
        if ($month != '' && $year != '') $('#cycle').attr('disabled', false);
    }

    function showCycles(month, year) {
        cycle = `${year}_${month}`;

        if (month != '' && year != '') {
            $('#cycle option[data-start-year-month="' + cycle + '"]').show();
            $('#cycle option[data-end-year-month="' + cycle + '"]').show();
            $('#cycle').attr('disabled', false);
        } else {
            $('#cycle').attr('disabled', true);
        }
    }

    function hideCycles() {
        $('#cycle option').hide();
        $('#cycle option[data-visible="true"]').show();
        $('#cycle').attr('disabled', true);
        toggleViewButton();
    }

    /** ================================================ */

    var labels = [],
        amount = [],
        high = 0,
        medium = 0,
        low = 0;
    <?php foreach($expensesPercentage as $item): ?>
        var item = +('<?php echo $item['amount']; ?>');
        amount.push(item);

        var name = '<?php echo $item['category']; ?>';
        labels.push(name);

        var priority = +('<?php echo $item['priority_level_id']; ?>');

        if (priority == 1) {
            high += item;
        } else if (priority == 2) {
            medium += item;
        } else {
            low += item;
        }
    <?php endforeach; ?>

    // Set new default font family and font color to mimic Bootstrap's default styling
    Chart.defaults.global.defaultFontFamily = '-apple-system,system-ui,BlinkMacSystemFont,"Segoe UI",Roboto,"Helvetica Neue",Arial,sans-serif';
    Chart.defaults.global.defaultFontColor = '#292b2c';

    // Bar Chart Example
    var ctx = document.getElementById("myBarChart");
    var myLineChart = new Chart(ctx, {
        type: 'bar',
        data: {
            labels: labels,
            datasets: [{
                // label: "Revenue",
                backgroundColor: "rgba(2,117,216,1)",
                borderColor: "rgba(2,117,216,1)",
                data: amount
            }],
        },
        options: {
            scales: {
                xAxes: [{
                    time: {
                    // unit: 'month'
                    },
                    gridLines: {
                    display: false
                    },
                    ticks: {
                    maxTicksLimit: 6
                    }
                }], yAxes: [{
                    ticks: {
                    min: 0,
                    // max: 15000,
                    maxTicksLimit: 5
                    },
                    gridLines: {
                    display: true
                    }
                }],
            }, legend: {
                display: false
            }
        }
    });

    // Set new default font family and font color to mimic Bootstrap's default styling
    Chart.defaults.global.defaultFontFamily = '-apple-system,system-ui,BlinkMacSystemFont,"Segoe UI",Roboto,"Helvetica Neue",Arial,sans-serif';
    Chart.defaults.global.defaultFontColor = '#292b2c';

    // Pie Chart Example
    var ctx = document.getElementById("myPieChart");
    var myPieChart = new Chart(ctx, {
        type: 'pie',
        data: {
            labels: ["High", "Medium", "Low"],
            datasets: [{
                data: [high, medium, low],
                backgroundColor: ['#dc3545', 'orange', '#28a745'],
            }],
        },
    });

</script>
