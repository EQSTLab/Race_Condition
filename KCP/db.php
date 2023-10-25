<?php
session_start();

// Connect to Database
$hostname = "localhost";
$username = "root";
$password = "";
$database = "shop";

$conn = new mysqli($hostname, $username, $password, $database);

if (mysqli_connect_errno()) {
    die("Connection Failed, try again!");
}