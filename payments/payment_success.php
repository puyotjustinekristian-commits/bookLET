<?php
session_start();

$serverName = "JustinePuyot\SQLEXPRESS";
$connectionOptions = [
    "Database" => "BOOKLET",
    "Uid" => "",
    "PWD" => ""
];
$conn = sqlsrv_connect($serverName, $connectionOptions);

if (!$conn) {
    die("Connection failed: " . print_r(sqlsrv_errors(), true));
}

$reference = $_GET['ref'] ?? '';

if (!$reference) {
    die("Invalid reference.");
}

if (!$reference) {
    die("Invalid reference.");
}

if (!$reference) {
    die("Invalid reference.");
}

$sql = "UPDATE FLIGHT_BOOKING SET STATUS = 'PAID' WHERE PAYMENT_ID = ?";
$params = [$reference];
$stmt = sqlsrv_query($conn, $sql, $params);

if ($stmt === false) {
    die(print_r(sqlsrv_errors(), true));
}
?>

<!DOCTYPE html>
<html>
<head>
  <meta charset="utf-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1" />
  <title>Payment Successful</title>
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.8/dist/css/bootstrap.min.css" rel="stylesheet" />
  <link rel="stylesheet" href="style.css" />
  <style>
    body { background-color: #f7f7f7; font-family: "Gill Sans", sans-serif; }
    .success-card { background: white; border-radius: 20px; padding: 3rem; text-align: center; margin-top: 5rem; }
    .icon-box { color: #28a745; font-size: 5rem; }
  </style>
</head>
<body>
  <nav class="navbar navbar-expand navbar-light bg-white border-bottom mb-4">
    <div class="container">
      <a href="homepage/homepage.html" class="logo me-2">
        <img src="images/bookLET logo cropped.png" alt="bookLET" style="height: 40px;" />
      </a>
      <div class="ms-auto">
        <ul class="navbar-nav">
             <li class="nav-item me-2">
                <a class="nav-link" href="mybookings.php" style="color: #193764">My Bookings</a>
            </li>
            <li class="nav-item">
                <a class="nav-link" href="welcome/welcome.php" style="color: #dc3545">Logout</a>
            </li>
        </ul>
      </div>
    </div>
  </nav>

  <div class="container">
    <div class="row justify-content-center">
      <div class="col-md-8">
        <div class="success-card shadow">
          <div class="icon-box">
            <i class="bi bi-check-circle-fill">✔</i>
          </div>
          <h1 class="fw-bold mt-3" style="color: #193764;">Payment Successful!</h1>
          <p class="lead text-muted">Your flight has been successfully booked.</p>
          <p>Reference: <strong><?= htmlspecialchars($reference) ?></strong></p>
          <hr>
          <a href="../homepage/homepage.html" class="btn btn-primary btn-lg mt-3" style="background-color: #193764;">Return to Home</a>
        </div>
      </div>
    </div>
  </div>
</body>
</html>
