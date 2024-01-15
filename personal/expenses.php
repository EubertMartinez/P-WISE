<?php require_once '../shared/header.php'; ?>
<?php require_once './validation.php'; ?>
<?php

$priorityLevels = $expenses->GetPrioritiesLevel();
$expensesCategories = $expenses->GetExpensesCategoryByParentId($parentId, $currentCycleId);
$expensesName = $expenses->GetExpensesNameByExpensesParentId($parentId);
$monthlyExpenses = $expenses->GetMonthlyExpensesByParentId($parentId);
$expensesPercentage = $expenses->GetExpensesByParentId($parentId);
$expensesDetails = $expenses->GetExpensesDetailsByParentId($parentId);

?>

<div class="container-fluid">
    <h3 class="mt-4 text-site-primary text-center">Expenses</h3>

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
        <li class="nav-item tab-item border">
            <a class="nav-link site-tab-nav-link active" 
                id="home-tab" 
                data-toggle="tab" 
                href="#home" 
                role="tab" 
                aria-controls="home" 
                aria-selected="true">
                My Expenses
            </a>
        </li>
        <li class="nav-item tab-item border">
            <a class="nav-link site-tab-nav-link" 
                id="profile-tab" 
                data-toggle="tab" 
                href="#profile" 
                role="tab" 
                aria-controls="profile" 
                aria-selected="false">
                Expenses Categories
            </a>
        </li>
        <li class="nav-item tab-item border">
            <a class="nav-link site-tab-nav-link" 
                id="contact-tab" 
                data-toggle="tab" 
                href="#contact" 
                role="tab" 
                aria-controls="contact" 
                aria-selected="false">
                Expenses Names
            </a>
        </li>
    </ul>
    <div class="tab-content" id="myTabContent">
        <div class="tab-pane fade show active" id="home" role="tabpanel" aria-labelledby="home-tab">
            <?php require_once './partials/expenses/expenses.php'; ?>
        </div>
        <div class="tab-pane fade" id="profile" role="tabpanel" aria-labelledby="profile-tab">
            <?php require_once './partials/expenses/categories.php'; ?>
        </div>
        <div class="tab-pane fade" id="contact" role="tabpanel" aria-labelledby="contact-tab">
             <?php require_once './partials/expenses/names.php'; ?>
        </div>
    </div>
</div>

<?php require_once '../shared/note.php'; ?>
<?php require_once '../shared/footer.php'; ?>