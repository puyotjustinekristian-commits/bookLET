<?php
session_start();

$serverName = "JustinePuyot\\SQLEXPRESS";
$connectionOptions = [
    "Database" => "BOOKLET",
    "Uid" => "",
    "PWD" => ""
];

$conn = sqlsrv_connect($serverName, $connectionOptions);
if ($conn === false) {
    $_SESSION['login_error'] = "Database connection failed.";
    header("Location: welcome.php");
    exit();
}

$email    = trim($_POST["LoginEmail"] ?? "");
$password = trim($_POST["LoginPassword"] ?? "");

if ($email == "" || $password == "") {
    $_SESSION['login_error'] = "Email and password are required.";
    header("Location: welcome.php");
    exit();
}
$sql = "SELECT USER_ID, EMAIL, PASSWORD FROM USERS WHERE EMAIL = '$email'";
$result = sqlsrv_query($conn, $sql);

if ($result === false) {
    $_SESSION['login_error'] = "Login error.";
    header("Location: welcome.php");
    exit();
}

$row = sqlsrv_fetch_array($result);

if (!$row) {
    $_SESSION['login_error'] = "Account does not exist.";
    header("Location: welcome.php");
    exit();
}

if ($password !== $row["PASSWORD"]) {
    $_SESSION['login_error'] = "Incorrect password.";
    header("Location: welcome.php");
    exit();
}

$_SESSION['user_email'] = $row["EMAIL"];
$_SESSION['USER_ID'] = $row["USER_ID"];
header("Location: ../homepage/homepage.html");
exit();
