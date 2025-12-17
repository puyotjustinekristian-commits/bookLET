<?php
session_start();

$airline  = $_GET['airline'] ?? '';
$duration = $_GET['duration'] ?? '';
$price    = $_GET['price'] ?? '';

$_SESSION['BOOKING'] = [
  'airline' => $airline,
  'duration' => $duration,
  'price' => $price
];
?>

<!DOCTYPE html>
<html>
<head>
  <title>Payment</title>
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.8/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body class="bg-light">

<div class="container mt-5">
  <div class="card shadow">
    <div class="card-body">
      <h4 class="fw-bold mb-3">Flight Summary</h4>

      <p><strong>Airline:</strong> <?= $airline ?></p>
      <p><strong>Duration:</strong> <?= $duration ?></p>
      <p><strong>Price:</strong> ₱<?= number_format($price) ?></p>

      <form method="POST" action="paymongo_checkout.php">
        <button class="btn btn-success w-100 mt-3">
          Pay with PayMongo
        </button>
      </form>
    </div>
  </div>
</div>

</body>
</html>
