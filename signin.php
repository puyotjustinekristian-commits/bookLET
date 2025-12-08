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

$email = $_POST["signinEmail"];
$password = $_POST["signinPassword"];
$confirm = $_POST["confirmPassword"];

if ($password !== $confirm) {
    $error = "Passwords do not match!";
} else {
    $checkSql = "SELECT 1 FROM USERS WHERE EMAIL = '$email'";
    $checkRes = sqlsrv_query($conn, $checkSql);

    if ($checkRes && sqlsrv_fetch_array($checkRes)) {
        $error = "Email is already registered!";
    } else {
        $sql = "INSERT INTO USERS (EMAIL, PASSWORD) VALUES ('$email', '$password')";
        $result = sqlsrv_query($conn, $sql);
        if ($result) {
            header('Location: homepage.html');
            exit();
        } else {
            $error = "Insert error";
        }
    }
}
?>
<!doctype html>
<html>
<body>
<?php if (!empty($error)): ?>
  <div style="color:red;"><?php echo $error; ?></div>
<?php endif; ?>
</body>
</html>

