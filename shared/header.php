<?php session_start();
$base_name = basename($_SERVER['PHP_SELF']);
$rootDirectory = $_SERVER['DOCUMENT_ROOT'];
date_default_timezone_set('Asia/Manila');
/*error_reporting(0);*/
?>
<?php require_once './../helpers/PageIdex.php'; ?>
<?php require_once './../data/budget/AmountData.php'; ?>
<?php require_once './../repository/budget/AmountRepository.php'; ?>
<?php require_once './../data/budget/ExpensesData.php'; ?>
<?php require_once './../repository/budget/ExpensesRepository.php'; ?>
<?php require_once './../data/budget/SavingsData.php'; ?>

<?php include('./../repository/suggestions/SuggestionData.php'); ?>
<?php include('./../repository/suggestions/UseSuggestionData.php'); ?>

<?php require_once './../repository/budget/SavingsRepository.php'; ?>


<?php $savingsData = new SavingsRepository(); ?>
<?php $id = isset($_SESSION['user_id']) ? $_SESSION['user_id'] : 0; ?>

<?php /*$notificationData = new Notification(); */?><!--
--><?php /*$notificationCount = $notificationData->TotalNotification($id) */?>

<?php $account_type = isset($_SESSION['account_type']) ? $_SESSION['account_type'] : 0; ?>
<?php $firstname = isset($_SESSION['firstname']) ? $_SESSION['firstname'] : 0; ?>
<?php $parentId = isset($_SESSION['parent_id']) ? $_SESSION['parent_id'] : 0; ?>
<?php $parentParentId = isset($_SESSION['parent_parent_id']) ? $_SESSION['parent_id'] : ""; ?>
<?php $amount = new AmountRepository(); ?>
<?php $isCycleStarted = $amount->IsCycleStarted($parentId); ?>
<?php $currentCycleId = $amount->GetCurrentCycleId($parentId); ?>
<?php $budget = $amount->GetCurrentCycleBudget($parentId); ?>
<?php $ddate = date('m/d/Y'); ?>
<?php $date  = new DateTime($ddate); ?>
<?php $month = date("F", strtotime($date->format('Y-m-d'))); ?>
<?php $year  = date("Y", strtotime($date->format('Y-m-d'))); ?>
<?php $expenses = new ExpensesRepository(); ?>
<?php $wallet = $expenses->GetRemainingWalletAmount($parentId); ?>
<?php $totalAdjustedSavings = $savingsData->GetTotalAdjustedSavingsByParentId($parentId); ?>
<?php $cycleAdustedSavings = $savingsData->GetAdjustedSavingsByCycleId($currentCycleId); ?>

<?php
if(!isset($_SESSION['user_id'])) {
    header('location: ../auth/login.php');
}
$pageTitle = "";

$pageTitle = match ($_SESSION['account_type']) {
    '1' => 'Household',
    '2' => 'Personal',
    '3' => 'Member',
    default => 'Unknown',
};




?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8" />
    <meta http-equiv="X-UA-Compatible" content="IE=edge" />
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no" />
    <meta name="description" content="" />
    <meta name="author" content="" />
    <title><?=$pageTitle;?> </title>
    <link rel="stylesheet" href="./../../node_modules/font-awesome/css/font-awesome.min.css">
    <link href="./../assets/core/css/admin.css" rel="stylesheet" />
    <link href="https://cdn.datatables.net/1.10.20/css/dataTables.bootstrap4.min.css" rel="stylesheet" crossorigin="anonymous" />
    <link rel="stylesheet" href="./../../node_modules/sweetalert2/dist/sweetalert2.min.css">
    <script src="./../../node_modules/sweetalert2/dist/sweetalert2.all.min.js"></script>
    <script src="https://code.jquery.com/jquery-3.4.1.min.js" crossorigin="anonymous"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.11.2/js/all.min.js" crossorigin="anonymous"></script>
</head>
<style>

    .dropdown-item:active {
        background: none;
        color: black;
    }
</style>
<body class="sb-nav-fixed">
<nav class="sb-topnav navbar navbar-expand navbar-dark bg-dark" style="background: #376d08 !important;">
    <a class="navbar-brand" href="<?php echo ($account_type == 1) ? '../admin/dashboard.php' : (($account_type == 2) ? '../personal/dashboard.php' : '../member/dashboard.php'); ?>"">
    <img src="./../assets/core/images/pwise-white-logo.png" alt="" style="height: 1.8rem">
    </a>
    <button class="btn btn-link btn-sm order-1 order-lg-0" id="sidebarToggle" href="#"><i class="fas fa-bars"></i></button>
    <!-- Navbar-->
    <ul class="navbar-nav ml-auto">
        <!-- <li class="nav-item dropdown">
            <a class="nav-link" id="userDropdown" href="#" role="button" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false"><i class="fa fa-bell"></i></a>
            <div class="dropdown-menu dropdown-menu-right" aria-labelledby="userDropdown">
                <p class="text-center">No notifications</p>
            </div>
        </li> -->
        <li class="nav-item notification-icon">
            <a class="nav-link" id="notifDropdown" href="#" role="button" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                <i class="fas fa-bell fa-fw"></i>
            </a>
            <span class="notification_counter" style="display: none; align-items: center; justify-content: center; font-size: 11px; margin-top: -5px; height: 15px; width: 15px; border-radius: 50%; background: #FB4D24; color: #fff; position: relative; top: -32px; right: -19px;"></span>
            <div class="dropdown-menu dropdown-menu-right prevent-close" aria-labelledby="notifDropdown" style="right: 3%; padding: 0;">
                <div id="notification-container" style=" max-height: 80vh; overflow: auto;">
                    <!-- Notifications will be added here -->
                </div>
            </div>

        </li>
        <li class="nav-item dropdown">
            <a class="nav-link" id="userDropdown" href="#" role="button" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false"><i class="fas fa-user fa-fw"></i></a>
            <div class="dropdown-menu dropdown-menu-right" aria-labelledby="userDropdown">
                <a class="dropdown-item" href="<?php echo ($account_type == 1) ? '../admin/profile.php' : (($account_type == 2) ? '../personal/profile.php' : '../member/profile.php'); ?>">Profile</a>
                <a class="dropdown-item" href="<?php echo ($account_type == 1) ? '../admin/password.php' : (($account_type == 2) ? '../personal/password.php' : '../member/password.php'); ?>">Change Password</a>
                <div class="dropdown-divider"></div>
                <a class="dropdown-item" href="/auth/logout.php">Logout</a>
            </div>
        </li>
    </ul>
</nav>
<div id="layoutSidenav">
    <div id="layoutSidenav_nav">
        <nav class="sb-sidenav accordion sb-sidenav-dark" id="sidenavAccordion" style="background: #589c1c">
            <div class="sb-sidenav-menu">
                <div class="nav">
                    <div class="sb-sidenav-menu-heading text-white">Pages</div>

                    <a class="nav-link text-white" href="<?php echo ($account_type == 1) ? '../admin/dashboard.php' : (($account_type == 2) ? '../personal/dashboard.php' : '../member/dashboard.php'); ?>">
                        <span>Dashboard</span>
                    </a>

                    <a class="nav-link text-white" href="<?php echo ($account_type == 1) ? '../admin/budget.php' : (($account_type == 2) ? '../personal/budget.php' : '../member/budget.php'); ?>">
                        <span>Budget</span>
                    </a>

                    <a class="nav-link text-white" href="<?php echo ($account_type == 1) ? '../admin/expenses.php' : (($account_type == 2) ? '../personal/expenses.php' : '../member/expenses.php'); ?>">
                        <span>Expenses</span>
                    </a>

                    <a class="nav-link text-white" href="<?php echo ($account_type == 1) ? '../admin/savings.php' : (($account_type == 2) ? '../personal/savings.php' : '../member/savings.php'); ?>">
                        <span>Savings</span>
                    </a>
                    <a class="nav-link text-white" href="<?php echo ($account_type == 1) ? '../admin/suggestions.php' : (($account_type == 2) ? '../personal/suggestions.php' : '../member/suggestions.php'); ?>">
                        <span>Suggestions</span>
                    </a>

                    <?php if ($account_type == 1): ?>
                        <a class="nav-link text-white" href="../admin/members.php">
                            <span>Household Members</span>
                        </a>
                    <?php endif; ?>

                    <a class="nav-link text-white" href="<?php echo ($account_type == 1) ? '../admin/reports.php' : (($account_type == 2) ? '../personal/reports.php' : '../member/reports.php'); ?>">
                        <span>Reports</span>
                    </a>
                </div>
            </div>
        </nav>
    </div>

    <div id="layoutSidenav_content">
        <main>
            <script>
                TotalNotification();
                function capitalizeFirstLetter(string) {
                    if (typeof string === 'string' && string.length > 0) {
                        return string.charAt(0).toUpperCase() + string.slice(1);
                    }
                    return string;
                }
                function formatDate(date) {
                    const options = { year: 'numeric', month: 'long', day: 'numeric' };
                    return date.toLocaleDateString('en-US', options);
                }

                /*$(document).ready(function() {
                    $('.prevent-close .dropdown-item').on('click', function(e) {
                        console.log("QWEQWE");
                        e.stopPropagation();
                    });
                });*/

                function formatTimeDifference(timeDifferenceInSeconds) {
                    if (timeDifferenceInSeconds < 60) {
                        return "Just now";
                    } else if (timeDifferenceInSeconds < 3600) {
                        const minutes = Math.floor(timeDifferenceInSeconds / 60);
                        return minutes + (minutes === 1 ? " minute ago" : " minutes ago");
                    } else if (timeDifferenceInSeconds < 86400) {
                        const hours = Math.floor(timeDifferenceInSeconds / 3600);
                        return hours + (hours === 1 ? " hour ago" : " hours ago");
                    } else if (timeDifferenceInSeconds < 604800) {
                        const days = Math.floor(timeDifferenceInSeconds / 86400);
                        return days + (days === 1 ? " day ago" : " days ago");
                    } else if (timeDifferenceInSeconds < 2419200) {
                        const weeks = Math.floor(timeDifferenceInSeconds / 604800);
                        return weeks + (weeks === 1 ? " week ago" : " weeks ago");
                    } else if (timeDifferenceInSeconds < 29030400) {
                        const months = Math.floor(timeDifferenceInSeconds / 2419200);
                        return months + (months === 1 ? " month ago" : " months ago");
                    } else {
                        const years = Math.floor(timeDifferenceInSeconds / 29030400);
                        return years + (years === 1 ? " year ago" : " years ago");
                    }
                }


                // Define an array to store displayed notification IDs
                let displayedNotificationIDs = [];
                function TotalNotification() {
                    $.ajax({
                        type: 'POST',
                        url: '/api/notifications/NotificationsAjax.php',
                        data: {
                            id: '<?=$id;?>',
                            account_type: '<?=$account_type?>',
                            parent_id: '<?=$_SESSION['parent_parent_id'] ?? ""?>',
                        },
                        dataType: 'json',
                        success: function (response) {
                            //console.log(response);

                            // Clear existing notifications before displaying new ones
                            $('#notification-container').empty();

                            if (response.data.data && response.data.data.length > 0) {
                               if(response.data.total_unread > 0) {
                                   $('.notification_counter').css('display', 'flex');
                                   $('.notification_counter').html(response.data.total_unread);
                               }
                                $('#notification-container').append('' +
                                    '<div style="display: flex; padding: 20px; justify-content: space-between;">' +
                                        '<span style="font-weight: 700; font-size: 16px;">Notifications</span>' +
                                        '<a href="notifications.php" style="color: #589C1C; font-size: 14px; text-decoration: underline;">See all</a>' +
                                    '</div>' +
                                    '');
                                // Sort notifications by time_stamp in descending order
                                response.data.data.sort((a, b) => {
                                    const timeA = new Date(a.time_stamp).getTime();
                                    const timeB = new Date(b.time_stamp).getTime();
                                    return timeB - timeA;
                                });

                                response.data.data.forEach(function (notification) {
                                    let user_name = "";
                                    let logged_in_id = '<?=$id?>';
                                    let message = "";
                                    let textColor = "#000"; // Default text color
                                    let markAsReadText = "Mark as read";
                                    let fontWeight = "normal"; // Default font weight
                                    let notificationIcon = "";
                                    let circleColor = "";
                                    let border = "";
                                    var backgroundColor = "";

                                    // console.log(notification.id);
                                    const fullname = capitalizeFirstLetter(notification.firstname) + " " + capitalizeFirstLetter(notification.lastname);
                                    const ParentFirstName = capitalizeFirstLetter(notification.parent_user_firstname);
                                    const ParentLastName = capitalizeFirstLetter(notification.parent_user_lastname);
                                    const memberToMemberFirstname = capitalizeFirstLetter(notification.member_to_member_user_firstname);
                                    const memberToMemberLastname = capitalizeFirstLetter(notification.member_to_member_user_lastname);

                                    const accountType = '<?=$_SESSION['account_type'];?>';
                                    const parentId = '<?=$_SESSION['parent_parent_id'];?>';
                                    let message4 = (notification.account_type == 1 && notification.sender_id != notification.user_id) ? '<strong>[Member]</strong> ' : '';
                                    let message5 = (notification.account_type == 3 && notification.sender_id != notification.user_id && notification.parent_id == notification.sender_id) ? '<strong>[Household]</strong> ' : '<strong>[Co-member]</strong> ';


                                    let message2 =  (notification.user_id == notification.sender_id) ? 'You have' : fullname + ' has';
                                    if(notification.user_id == notification.sender_id) {
                                        message5 = '';
                                    }

                                    let startDate = new Date(notification.start);
                                    let endDate = new Date(notification.end);
                                    // console.log(notification);
                                    switch(notification.notification_type) {
                                        case "overspent":
                                            circleColor = '#F7BB07';
                                            notificationIcon = '<i class="fas fa-exclamation-triangle" style="color: #fff;"></i>';

                                            /*console.log(notification.account_type);

                                            console.log(accountType);*/

                                            if (notification.account_type == 1) {
                                                message = message4 + message2 +
                                                    " overspent in the " + notification.expenses_name_name +
                                                    " expenses, add more budget on <strong>" + notification.expenses_category_name + "</strong> category.";

                                            } else if(notification.account_type == 2) {
                                                message = "You have overspent in the " + notification.expenses_name_name +
                                                    " expenses, add more budget on <strong>" + notification.expenses_category_name + "</strong> category.";
                                            } else if(notification.account_type == 3) {
                                                message = message5 + message2 +
                                                    " overspent in the " + notification.expenses_name_name +
                                                    " expenses, add more budget on <strong>" + notification.expenses_category_name + "</strong> category.";
                                            } else {
                                                message = "You have overspent in the " + notification.expenses_name_name +
                                                    " expenses, add more budget on <strong>" + notification.expenses_category_name + "</strong> category.";
                                            }
                                            break;

                                        case "cycle_overspent":
                                            circleColor = '#FB4D24';
                                            border = '';
                                            notificationIcon = '<svg xmlns="http://www.w3.org/2000/svg" height="1em" viewBox="0 0 512 512" style="fill: #fff;"><path d="M192 96H320l47.4-71.1C374.5 14.2 366.9 0 354.1 0H157.9c-12.8 0-20.4 14.2-13.3 24.9L192 96zm128 32H192c-3.8 2.5-8.1 5.3-13 8.4l0 0 0 0C122.3 172.7 0 250.9 0 416c0 53 43 96 96 96H416c53 0 96-43 96-96c0-165.1-122.3-243.3-179-279.6c-4.8-3.1-9.2-5.9-13-8.4zM289.9 336l47 47c9.4 9.4 9.4 24.6 0 33.9s-24.6 9.4-33.9 0l-47-47-47 47c-9.4 9.4-24.6 9.4-33.9 0s-9.4-24.6 0-33.9l47-47-47-47c-9.4-9.4-9.4-24.6 0-33.9s24.6-9.4 33.9 0l47 47 47-47c9.4-9.4 24.6-9.4 33.9 0s9.4 24.6 0 33.9l-47 47z"/> </svg>';

                                            if (notification.account_type == 1) {
                                                 message = `${message4}${message2} overspent on your recent cycle, having ₱${notification.savings}, add more budget on your next cycle to prevent overspending.`;
                                            } else if (notification.account_type == 2) {
                                                 message = `You have overspent on your current cycle, having ₱${notification.savings}, add more budget on your current cycle to prevent overspending.`;
                                            } else if (notification.account_type == 3) {
                                                 message = `${message5}${message2} overspent on your recent cycle, having ₱${notification.savings}, add more budget on your next cycle to prevent overspending.`;
                                            } else {
                                                 message = `You have overspent in the ${notification.expenses_name_name} expenses, add more budget on <strong>${notification.expenses_category_name}</strong> category.`;
                                            }

                                            break;
                                        case "savings":

                                            circleColor = '#589C1C';
                                            notificationIcon = '<i class="fas fa-coins" style=" color: #fff;"></i>';
                                            message = `Great job! you have got ₱${notification.savings} peso(s) savings on your recent cycle from your recent cycle.`;
                                            break;
                                        case "no_overspend":
                                            circleColor = '#fff';
                                            border = '2px solid #589C1C';
                                            notificationIcon = '<i class="fas fa-check" style=" color: #589C1C;"></i>';
                                            message = `Nice, you have followed your category expenses accordingly on your recent cycle from your recent cycle.`;
                                            break;
                                        default:
                                        // code block
                                    }

                                    /*switch(notification.notification_type) {
                                        case "overspent":
                                            circleColor = '#F7BB07';
                                            notificationIcon = '<i class="fas fa-exclamation-triangle" style="color: #fff;"></i>';

                                            if (notification.account_type == 1) {
                                                message = message4 + message2 +
                                                    " overspent in the " + notification.expenses_name_name +
                                                    " expenses, add more budget on <strong>" + notification.expenses_category_name + "</strong> category.";

                                            } else if(notification.account_type == 2) {
                                                message = "You have overspent in the " + notification.expenses_name_name +
                                                    " expenses, add more budget on <strong>" + notification.expenses_category_name + "</strong> category.";
                                            } else if(notification.account_type == 3) {
                                                message = message5 + message2 +
                                                    " overspent in the " + notification.expenses_name_name +
                                                    " expenses, add more budget on <strong>" + notification.expenses_category_name + "</strong> category.";
                                            } else {
                                                message = "You have overspent in the " + notification.expenses_name_name +
                                                    " expenses, add more budget on <strong>" + notification.expenses_category_name + "</strong> category.";
                                            }
                                            break;

                                        case "cycle_overspent":
                                            circleColor = '#FB4D24';
                                            border = '';
                                            notificationIcon = '<svg xmlns="http://www.w3.org/2000/svg" height="1em" viewBox="0 0 512 512" style="fill: #fff;"><path d="M192 96H320l47.4-71.1C374.5 14.2 366.9 0 354.1 0H157.9c-12.8 0-20.4 14.2-13.3 24.9L192 96zm128 32H192c-3.8 2.5-8.1 5.3-13 8.4l0 0 0 0C122.3 172.7 0 250.9 0 416c0 53 43 96 96 96H416c53 0 96-43 96-96c0-165.1-122.3-243.3-179-279.6c-4.8-3.1-9.2-5.9-13-8.4zM289.9 336l47 47c9.4 9.4 9.4 24.6 0 33.9s-24.6 9.4-33.9 0l-47-47-47 47c-9.4 9.4-24.6 9.4-33.9 0s-9.4-24.6 0-33.9l47-47-47-47c-9.4-9.4-9.4-24.6 0-33.9s24.6-9.4 33.9 0l47 47 47-47c9.4-9.4 24.6-9.4 33.9 0s9.4 24.6 0 33.9l-47 47z"/> </svg>';

                                            if (notification.account_type == 1) {
                                                message = `${message4}${message2} overspent on your recent cycle, having ₱${notification.savings}  budget from <strong>${formatDate(startDate)}</strong> to <strong>${formatDate(endDate)}</strong>, add more budget on your next cycle to prevent overspending.`;
                                            } else if (notification.account_type == 2) {
                                                message = `You have overspent on your recent cycle, having ₱${notification.savings}  budget from <strong>${formatDate(startDate)}</strong> to <strong>${formatDate(endDate)}</strong>, add more budget on your next cycle to prevent overspending.`;
                                            } else if (notification.account_type == 3) {
                                                message = `${message5}${message2} overspent on your recent cycle, having ₱${notification.savings} budget from <strong>${formatDate(startDate)}</strong> to <strong>${formatDate(endDate)}</strong>, add more budget on your next cycle to prevent overspending.`;
                                            } else {
                                                message = `You have overspent in the ${notification.expenses_name_name} expenses, add more budget on <strong>${notification.expenses_category_name}</strong> category.`;
                                            }

                                            break;
                                        case "savings":

                                            circleColor = '#589C1C';
                                            notificationIcon = '<i class="fas fa-coins" style=" color: #fff;"></i>';
                                            message = `Great job! you have got ₱${notification.savings} peso(s) savings on your recent cycle from <strong>${formatDate(startDate)}</strong> to <strong>${formatDate(endDate)}</strong>.`;
                                            break;
                                        case "no_overspend":
                                            circleColor = '#fff';
                                            border = '2px solid #589C1C';
                                            notificationIcon = '<i class="fas fa-check" style=" color: #589C1C;"></i>';
                                            message = `Nice, you have followed your category expenses accordingly on your recent cycle from <strong>${formatDate(startDate)}</strong> to <strong>${formatDate(endDate)}</strong>`;
                                            break;
                                        default:
                                    }*/

                                    const time_stamp = new Date(notification.created_at);
                                    const currentTime = new Date();
                                    const timeDifferenceInSeconds = (currentTime - time_stamp) / 1000;

                                    const timeAgoText = formatTimeDifference(timeDifferenceInSeconds);

                                    // Check is_unread property
                                    textColor = "#589C1C";
                                    markAsReadText = "Mark as read";
                                    fontWeight = "600";
                                    backgroundColor = "";
                                    if (notification.is_read == 1) {
                                        // Change styles for unread notifications
                                        textColor = "#606266";
                                        markAsReadText = "Mark as unread";
                                        fontWeight = "400";
                                        backgroundColor = "#F8F9FA";
                                    }

                                    // Create a new notification element for each notification
                                    let notificationElement = `
                    <div class="dropdown-item" style="background: ${backgroundColor}; white-space: normal; display: flex; justify-content: space-evenly; gap: .5rem; border-bottom: 1px solid #f1f2f4; padding: 20px;">
                        <div style="border: ${border}; width: 50px; height: 50px; background-color: ${circleColor}; opacity: 1; border-radius: 50%; display: flex; justify-content: center; align-items: center;">
                            ${notificationIcon}
                        </div>
                        <div style="width: 250px; display: flex; flex-direction: column; justify-content: center;">
                            <span style="font-size: 14px; font-weight: ${fontWeight}; white-space: normal;">${message}</span>
                            <div style="display: flex; justify-content: space-between; font-size: 12px;">
                                <p style="color: ${textColor}; font-weight: ${fontWeight};">${timeAgoText}</p>
                                <span class="mark_as_read" style="pointer-events: auto; cursor: pointer; color: ${textColor}; text-decoration: underline; z-index: 999999;"></span>
                            </div>
                        </div>
                    </div>`;
                                    // Append each notification to the container
                                    $('#notification-container').append(notificationElement);
                                });
                            } else {
                                let notificationElement = `
                <div class="dropdown-item" style="background: #F8F9FA; white-space: normal; display: flex; justify-content: space-evenly; gap: .5rem; border-bottom: 1px solid #f1f2f4; padding: 20px;">
                    <div style="width: 250px; display: flex; flex-direction: column; justify-content: center;">
                        <span style="font-size: 14px; font-weight: normal; white-space: normal;">You don't have any notifications.</span>
                    </div>
                </div>`;
                                // Display a message when there are no notifications
                                $('#notification-container').append(notificationElement);
                            }
                        },
                        error: function (xhr, status, error) {
                            console.error('AJAX request error: ' + error);
                        }
                    });
                }




                // Call TotalNotification every 5 seconds
    setInterval(TotalNotification, 10000); // 5000 milliseconds = 5 seconds
</script>