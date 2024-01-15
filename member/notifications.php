<?php

require_once '../shared/header.php';
require_once './validation.php';
require_once './../data/notifications/Notification.php';

$data = new Notification();
$account_type = $_SESSION['account_type'];
$id = $_SESSION['user_id'];
$parent_id = $_SESSION['parent_parent_id'];
$result = $data->GetNotifications2($id, $account_type, $parent_id);

/*var_dump($result);*/
?>
    <style>
        .notification {
            min-width: 50%;
        }

        @media(max-width: 750px) {
            .notification {
                max-width: 100%;
            }
        }

        .notification-icon {
            display: none;
        }
    </style>
    <div class="container-fluid">
        <div class="col-lg-12 mb-4 d-flex justify-content-center" style="margin-top: 30px;">
            <div class="border shadow tile-container notification" style="padding: 20px 0 0 0;">
                <div class="title" style="font-weight: 700; font-size: 20px; color: #589C1C; margin: 0 20px;">Notifications</div>
                <br>
                <?php

                function formatTimeDifference($timeDifferenceInSeconds) {
                    if ($timeDifferenceInSeconds < 60) {
                        return "Just now";
                    } elseif ($timeDifferenceInSeconds < 3600) {
                        $minutes = floor($timeDifferenceInSeconds / 60);
                        return $minutes . ($minutes == 1 ? " minute ago" : " minutes ago");
                    } elseif ($timeDifferenceInSeconds < 86400) {
                        $hours = floor($timeDifferenceInSeconds / 3600);
                        return $hours . ($hours == 1 ? " hour ago" : " hours ago");
                    } elseif ($timeDifferenceInSeconds < 604800) {
                        $days = floor($timeDifferenceInSeconds / 86400);
                        return $days . ($days == 1 ? " day ago" : " days ago");
                    } elseif ($timeDifferenceInSeconds < 2419200) {
                        $weeks = floor($timeDifferenceInSeconds / 604800);
                        return $weeks . ($weeks == 1 ? " week ago" : " weeks ago");
                    } elseif ($timeDifferenceInSeconds < 29030400) {
                        $months = floor($timeDifferenceInSeconds / 2419200);
                        return $months . ($months == 1 ? " month ago" : " months ago");
                    } else {
                        $years = floor($timeDifferenceInSeconds / 29030400);
                        return $years . ($years == 1 ? " year ago" : " years ago");
                    }
                }

                while($row = mysqli_fetch_assoc($result)) {
                        date_default_timezone_set('Asia/Manila');
                        $dateTime = $row['created_at'];
                        $createdTimestamp = strtotime($dateTime);
                        $currentTimestamp = time();
                        $timeDifferenceInSeconds = $currentTimestamp - $createdTimestamp;

                        $created_at = formatTimeDifference($timeDifferenceInSeconds);


                        $circleColor = '';
                        $rowIcon = '';
                        $message = '';
                        $markAsRead = ($row['is_read'] == 0) ? 'Mark as read' : 'Mark as unread';
                        $backgroundColor = ($row['is_read'] == 0) ? '#fff' : '#f3f5f7';
                        $markAsReadColor = ($row['is_read'] == 0) ? '#589C1C' : '#000';
                        $fontWeight = ($row['is_read'] == 0) ? 'bold' : 'normal';

                        switch ($row['notification_type']) {
                            case "overspent":
                                $border = '';
                                $circleColor = '#F7BB07';
                                $rowIcon = '<i class="fas fa-exclamation-triangle" style="color: #fff;"></i>';
                                $fullname = ucwords($row['firstname'] . ' ' . $row['lastname']);
                                $message4 = ($row['account_type'] == 1 && $row['sender_id'] != $row['user_id']) ? '<strong>[Member]</strong> ' : ' ';
                                $message5 = ($row['account_type'] == 3 && $row['sender_id'] != $row['user_id'] && $row['parent_id'] == $row['sender_id']) ? '<strong>[Household]</strong> ' : '<strong>[Co-member]</strong> ';

                                if($row['sender_id'] == $row['user_id']) {
                                    $message5 = '';
                                }
                                $message2 = ($row['user_id'] == $row['sender_id']) ? 'You have' : $fullname . ' has';

                                if ($row['account_type'] == 1) {
                                    $message = $message4 . $message2 . " overspent in the " . $row['expenses_name_name'] . " expenses, add more budget on <strong>" . $row['expenses_category_name'] . "</strong> category.";
                                } elseif ($row['account_type'] == 2) {
                                    $message = "You have overspent in the " . $row['expenses_name_name'] . " expenses, add more budget on <strong>" . $row['expenses_category_name'] . "</strong> category.";
                                } elseif ($row['account_type'] == 3) {
                                    $message = $message5 . $message2 . " overspent in the " . $row['expenses_name_name'] . " expenses, add more budget on <strong>" . $row['expenses_category_name'] . "</strong> category.";
                                } else {
                                    $message = "You have overspent in the " . $row['expenses_name_name'] . " expenses, add more budget on <strong>" . $row['expenses_category_name'] . "</strong> category.";
                                }
                                break;
                            case "cycle_overspent":
                                $circleColor = '#FB4D24';
                                $border = '';
                                $rowIcon = '<svg xmlns="http://www.w3.org/2000/svg" height="1em" viewBox="0 0 512 512" style="fill: #fff;">
                                                         <path d="M192 96H320l47.4-71.1C374.5 14.2 366.9 0 354.1 0H157.9c-12.8 0-20.4 14.2-13.3 24.9L192 96zm128 32H192c-3.8 2.5-8.1 5.3-13 8.4l0 0 0 0C122.3 172.7 0 250.9 0 416c0 53 43 96 96 96H416c53 0 96-43 96-96c0-165.1-122.3-243.3-179-279.6c-4.8-3.1-9.2-5.9-13-8.4zM289.9 336l47 47c9.4 9.4 9.4 24.6 0 33.9s-24.6 9.4-33.9 0l-47-47-47 47c-9.4 9.4-24.6 9.4-33.9 0s-9.4-24.6 0-33.9l47-47-47-47c-9.4-9.4-9.4-24.6 0-33.9s24.6-9.4 33.9 0l47 47 47-47c9.4-9.4 24.6-9.4 33.9 0s9.4 24.6 0 33.9l-47 47z"/>
                                                    </svg>';
                                $fullname = ucwords($row['firstname'] . ' ' . $row['lastname']);
                                $message4 = ($row['account_type'] == 1 && $row['sender_id'] != $row['user_id']) ? '<strong>[Member]</strong> ' : ' ';
                                $message5 = ($row['account_type'] == 3 && $row['sender_id'] != $row['user_id'] && $row['parent_id'] == $row['sender_id']) ? '<strong>[Household]</strong> ' : '<strong>[Co-member]</strong> ';

                                if($row['sender_id'] == $row['user_id']) {
                                    $message5 = '';
                                }
                                $message2 = ($row['user_id'] == $row['sender_id']) ? 'You have' : $fullname . ' has';

                                if ($row['account_type'] == 1) {
                                    $message = $message4 . $message2 . " overspent, having ₱" .$row['savings']. " on your recent cycle budget from <strong>" . date("F jS, Y", strtotime($row['start'])) ."</strong> to <strong>" .date("F jS, Y", strtotime($row['end'])). "</strong> , add more budget on your next cycle to prevent over spending.";
                                } elseif ($row['account_type'] == 2) {
                                    $message = "You have overspent in the " . $row['expenses_name_name'] . " expenses, add more budget on <strong>" . $row['expenses_category_name'] . "</strong> category.";
                                } elseif ($row['account_type'] == 3) {
                                    $message = $message5 . $message2 . " overspent on your recent cycle, having ₱" .$row['savings']. "  budget from <strong>" . date("F jS, Y", strtotime($row['start'])) ."</strong> to <strong>" .date("F jS, Y", strtotime($row['end'])). "</strong> , add more budget on your next cycle to prevent over spending.";
                                } else {
                                    $message = "You have overspent in the " . $row['expenses_name_name'] . " expenses, add more budget on <strong>" . $row['expenses_category_name'] . "</strong> category.";
                                }
                                break;
                            case "savings":
                                $border = '';
                                $circleColor = '#589C1C';
                                $rowIcon = '<i class="fas fa-coins" style="color: #fff;"></i>';
                                $message = "Great job! you have got ₱" . $row['savings'] . " peso(s) savings on your recent cycle from <strong>" . date("F jS, Y", strtotime($row['start'])) . "</strong> to <strong>" . date("F jS, Y", strtotime($row['end'])) . "</strong>.";
                                break;
                            case "no_overspend":
                                $circleColor = '#fff';
                                $border = '2px solid #589C1C';
                                $rowIcon = '<i class="fas fa-check" style="color: #589C1C;"></i>';
                                $message = "Nice, you have followed your category expenses accordingly on your recent cycle from <strong>" . date("F jS, Y", strtotime($row['start'])) . "</strong> to <strong>" . date("F jS, Y", strtotime($row['end'])) . " </strong> cycle.";
                                break;
                            default:
                                // code block
                        } ?>
                        <!--Display the notification HTML content-->
                        <div style="display: flex; gap: 1rem; background: <?=$backgroundColor?>; padding: 20px 20px 17px 20px; ">
                            <div style="background: <?=$circleColor; ?>; border: <?=$border;?>; min-width: 50px; height: 50px; opacity: 1; border-radius: 50%; display: flex; justify-content: center; align-items: center;">
                                <?=$rowIcon;?>
                            </div>
                            <div style="display: flex; flex-direction: column; flex-grow: 1; gap: 0.5rem;">
                                <span style="font-size: 14px; font-weight: 400; white-space: normal;"><?=$message;?></span>
                                <div style="display: flex; justify-content: space-between; font-size: 12px; width: 100%;">
                                    <p style="color: <?= $markAsReadColor ?>; font-weight: <?= $fontWeight ?>; flex: 1;"><?= $created_at ?></p>
                                    <span class="mark_as_read" id="mark_as_read" data-id="<?= $row['notification_id'] ?>" data-read="<?= $row['is_read'] ?>" style="pointer-events: auto; cursor: pointer; color: <?= $markAsReadColor ?>; text-decoration: underline; justify-content: flex-end;"><?= $markAsRead ?></span>
                                </div>
                            </div>
                        </div>
                        <?php
                    }

                ?>
            </div>
        </div>
    </div>

    <script>


        let markAsRead = document.getElementsByClassName('mark_as_read');

        for (let i = 0; i < markAsRead.length; i++) {
            markAsRead[i].addEventListener('click', function () {
                let dataId = this.getAttribute('data-id'); // Get the data-id attribute of the clicked element
                let dataIsRead = this.getAttribute('data-read'); // Get the data-read attribute of the clicked element

                $.ajax({
                    type: 'POST',
                    url: '../api/notifications/notificationUpdate.php',
                    data: {
                        id: dataId,
                        is_read: dataIsRead
                    },
                    dataType: 'json',
                    success: function (response) {
                        location.reload();
                    },
                    error: function (xhr, status, error) {
                        console.error('AJAX request error: ' + error);
                    }
                });

            });
        }


    </script>
<?php require_once '../shared/note.php'; ?>
<?php require_once '../shared/footer.php'; ?>