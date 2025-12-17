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
    $_SESSION['signup_error'] = "Database connection failed.";
    header("Location: welcome.php");
    exit();
}

$firstname = trim($_POST["firstname"] ?? "");
$surname   = trim($_POST["surname"] ?? "");
$email     = trim($_POST["signinEmail"] ?? "");
$password  = trim($_POST["signinPassword"] ?? "");
$confirm   = trim($_POST["confirmPassword"] ?? "");

if ($firstname == "" || $surname == "" || $email == "" || $password == "" || $confirm == "") {
    $_SESSION['signup_error'] = "All fields are required.";
    header("Location: welcome.php");
    exit();
}

if ($password !== $confirm) {
    $_SESSION['signup_error'] = "Passwords do not match.";
    header("Location: welcome.php");
    exit();
}

$checkEmailSql = "SELECT EMAIL FROM USERS WHERE EMAIL = '$email'";
$checkEmailRes = sqlsrv_query($conn, $checkEmailSql);

if ($checkEmailRes && sqlsrv_fetch_array($checkEmailRes)) {
    $_SESSION['signup_error'] = "Email is already registered.";
    header("Location: welcome.php");
    exit();
}

$checkPassSql = "SELECT PASSWORD FROM USERS WHERE PASSWORD = '$password'";
$checkPassRes = sqlsrv_query($conn, $checkPassSql);

if ($checkPassRes && sqlsrv_fetch_array($checkPassRes)) {
    $_SESSION['signup_error'] = "Password already exists. Choose a different one.";
    header("Location: welcome.php");
    exit();
}

$insertSql = "
    INSERT INTO USERS (FIRST_NAME, SURNAME, EMAIL, PASSWORD)
    OUTPUT INSERTED.USER_ID
    VALUES ('$firstname', '$surname', '$email', '$password')
";

$insertRes = sqlsrv_query($conn, $insertSql);

if ($insertRes) {
    $row = sqlsrv_fetch_array($insertRes, SQLSRV_FETCH_ASSOC);
    $_SESSION['USER_ID'] = $row['USER_ID'];
    $_SESSION['user_email'] = $email; 

    $_SESSION['signup_success'] = "Account created successfully.";
    header("Location: ../homepage/homepage.html"); 
    exit();
}

$_SESSION['signup_error'] = "Registration failed. Please try again.";
header("Location: welcome.php");
exit();
