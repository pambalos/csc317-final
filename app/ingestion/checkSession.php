<?php
include('lib/common.php');
include('lib/show_queries.php');
include('lib/error.php');

console_log("Testing console log");

session_start();
if (empty($_SESSION['Username']) ) {
    console_log("No current username");
} else {
    console_log("username found");
    console_log($_SESSION['Username']);
}
