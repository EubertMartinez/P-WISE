<?php
session_start();

// Determine the base directory of your script
$baseDirectory = __DIR__;

require_once  '../../helpers/ApiIndex.php';
require_once  '../../data/notifications/NotificationEndedCycleAutoRunner.php';

$data = new NotificationEndedCycleAutoRunner();
$run = $data->AutoRunner();
echo $run;
error_log("Script started.", 3, "debug.log");

// Output a message to the console
echo "Debug message: Script started.\n";

// Output a variable's value
$debugVar = "Hello, World!";
echo "Debug variable: $debugVar\n";
// Log a message to a specific log file
error_log("Script started.", 3, "debug.log");

// Log variable values
error_log("Debug variable: $debugVar", 3, "debug.log");

?>