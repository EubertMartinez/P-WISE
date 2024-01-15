<?php 

require_once '../shared/header.php'; 
require_once './validation.php';
require_once './../data/member/MemberData.php';
require_once './../repository/member/MembersRepository.php'; 

$data = new MembersRepository();
$members = $data->GetMembers($parentId);

$filter = isset($_GET['filter']) ? $_GET['filter'] : '';
$converedDate = $month.' '.$year;
$cycleLog = $amount->GetCurrentCycleBudgetLog($parentId);

$ddate = date('m/d/Y');
$date  = new DateTime($ddate);
$current_date = $date->format('Y-m-d');
$date->modify('last day of this month');
$last_day_this_month = $date->format('Y-m-d');

$cycleData = $amount->GetRemainingDays($parentId);

$remainingDays = 0;
$startDate = '';
$endDate = '';

if (count($cycleData) > 0) {
    $remainingDays = $cycleData[0]["remaining_days"];
    $startDate = date("M d, Y", strtotime($cycleData[0]["start"]));
    $endDate = date("M d, Y", strtotime($cycleData[0]["end"]));
} 

$expensesCategories = $amount->GetBudgetLimitsByParentIdAndCycleId($parentId, $currentCycleId);

if (empty($filter)) {
    $members = $data->GetMembers($parentId);
} else {
    if ($filter == 'monthly') {
        $year  = isset($_GET['year'])  ? $_GET['year']  : 0;
        $month = isset($_GET['month']) ? $_GET['month'] : 0;

        $members = $data->GetMembersByMonth($id, $month, $year);
        $budget = $amount->GetCycleBudgetByMonth($parentId, $month, $year);
        $cycleLog = $amount->GetMonthlyBudgetLog($parentId, $month, $year);

        $month = date("F", mktime(0, 0, 0, $month, 10));
        $converedDate = $month.' '.$year;
    }

    if ($filter == 'range') {
        $start  = isset($_GET['start'])  ? $_GET['start']  : '';
        $end = isset($_GET['end']) ? $_GET['end'] : '';

        $members = $data->GetMembersByRange($parentId, $start, $end);
        $budget = $amount->GetCycleBudgetByDateRange($parentId, $start, $end);
        $cycleLog = $amount->GetRangeBudgetLog($parentId, $start, $end);

        $converedDate = date("M d, Y", strtotime($start)).' to '.date("M d, Y", strtotime($end));
    }
}
?>

<div class="container-fluid">
    <h3 class="mt-4 text-site-primary text-center">Budget</h3>

    <?php if ($wallet[0]['wallet'] < 0): ?>
        <div class="row mt-5">
            <div class="col">
                <div class="alert alert-danger w-100 text-center">
                    <strong class="w-100 d-block">OVER SPENDING!</strong> The system detected that you are spending too much than the budget on your wallet. Kindly adjust your budget to avoid over spending.</a>
                </div>
            </div>
        </div>
    <?php endif; ?>

    <ul class="nav nav-tabs mt-5" id="myTab" role="tablist">
        <li class="nav-item border" style="width:50%">
            <a class="nav-link site-tab-nav-link active" 
                id="home-tab" 
                data-toggle="tab" 
                href="#home" 
                role="tab" 
                aria-controls="home" 
                aria-selected="true">
                My Budget
            </a>
        </li>
        <li class="nav-item border" style="width:50%">
            <a class="nav-link site-tab-nav-link" 
                id="profile-tab" 
                data-toggle="tab" 
                href="#profile" 
                role="tab" 
                aria-controls="profile" 
                aria-selected="false">
                Budget Limits
            </a>
        </li>
    </ul>
    <div class="tab-content" id="myTabContent">
        <div class="tab-pane fade show active" id="home" role="tabpanel" aria-labelledby="home-tab">
            <?php require_once './partials/budget/budget.php'; ?>
        </div>
        <div class="tab-pane fade" id="profile" role="tabpanel" aria-labelledby="profile-tab">
            <?php require_once './partials/budget/limits.php'; ?>
        </div>
    </div>
</div>

<?php require_once '../shared/note.php'; ?>
<?php require_once '../shared/footer.php'; ?>