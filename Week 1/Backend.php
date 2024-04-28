<?php
error_reporting(E_ALL);
require_once('php_backend/inc_libraries/inc_session.php');
session_set_save_handler('_open','_close','_read','_write','_destroy','_clean');
session_start();
require_once('php_backend/inc_libraries/inc_db_conn_san.php');

if (!isset($_SESSION["login"])) {
    echo "Error: You are not logged in.";
    die();
}

// Sanitize the $_POST array
foreach ($_POST as $key => $value) {
    $value = dbTrimSanitizeStandard($value);
}

// var_dump the $_POST array
var_dump($_POST);

$dbc = dbConnectInitial();//connecting to db
$lemail=dbTrimSanitizeStandard($_SESSION["login"]);

$sql = "SELECT count(challenge_user_email) AS user_count 
        FROM unadat_data.challenges_user_data 
        WHERE challenge_key=? AND challenge_user_email=?";



if ($user_count == 0) {
    echo "Error: You are not authorized to edit this challenge.";
    die();
}
?>
