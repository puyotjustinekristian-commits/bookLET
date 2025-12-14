<?php
$serverName = "JustinePuyot\\SQLEXPRESS";
$connectionOptions = [
    "Database" => "BOOKLET",
    "Uid" => "",
    "PWD" => ""
];

$conn = sqlsrv_connect($serverName, $connectionOptions);

if ($conn === false) {
    die(print_r(sqlsrv_errors(), true));
}

$email = $_POST["LoginEmail"];
$password = $_POST["LoginPassword"];

if ($email == "" || $password == "") {
    header("Location: welcome.html");
    exit();
}

$sql = "SELECT EMAIL, PASSWORD FROM USERS WHERE EMAIL = '$email'";
$result = sqlsrv_query($conn, $sql);

if ($result === false) {
    die(print_r(sqlsrv_errors(), true));
}

$row = sqlsrv_fetch_array($result);

if ($row) {
    if ($password == $row["PASSWORD"]) {
        header("Location: homepage.html");
        exit();
    }
}

header("Location: welcome.html");
exit();
?>
