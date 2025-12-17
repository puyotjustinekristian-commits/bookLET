<?php
session_start();

$userId = $_SESSION['USER_ID'] ?? 0;

$airline  = $_GET['airline'] ?? '';
$duration = $_GET['duration'] ?? '';
$price    = $_GET['price'] ?? 0;
?>

<!DOCTYPE html>
<html>
<head>
  <meta charset="utf-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1" />
  <title>Payment - bookLET</title>

  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.8/dist/css/bootstrap.min.css" rel="stylesheet" />
  
  <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.1/font/bootstrap-icons.css" />

  <link rel="stylesheet" href="../css/style.css" />

  <style>
    body {
      background-color: #f7f7f7;
      font-family: "Gill Sans", "Gill Sans MT", Calibri, "Trebuchet MS", sans-serif;
    }

    .payment-card {
      border: none;
      border-radius: 20px;
      box-shadow: 0 10px 30px rgba(0,0,0,0.1);
      overflow: hidden;
    }

    .payment-header {
      background-color: #193764;
      color: white;
      padding: 2rem;
      text-align: center;
    }

    .section-title {
      color: #193764;
      font-weight: bold;
      border-bottom: 2px solid #f0f0f0;
      padding-bottom: 10px;
      margin-bottom: 20px;
    }

    .btn-pay {
      background-color: #193764;
      color: white;
      font-size: 1.2rem;
      padding: 12px;
      border-radius: 12px;
      font-weight: bold;
      transition: all 0.3s ease;
    }

    .btn-pay:hover {
      background-color: #0d2a5c;
      color: white;
      transform: translateY(-2px);
      box-shadow: 0 5px 15px rgba(25, 55, 100, 0.3);
    }

    .form-control {
      border-radius: 10px;
      padding: 12px;
      border: 1px solid #e0e0e0;
    }

    .form-control:focus {
      border-color: #193764;
      box-shadow: 0 0 0 0.2rem rgba(25, 55, 100, 0.25);
    }

    .summary-item {
      display: flex;
      justify-content: space-between;
      margin-bottom: 10px;
      font-size: 1.1rem;
    }
  </style>
</head>
<body>

  <nav class="navbar navbar-expand navbar-light bg-white border-bottom mb-4">
    <div class="container">
      <a href="../homepage/homepage.html" class="logo me-2">
        <img src="../images/bookLET logo cropped.png" alt="bookLET" style="height: 40px;" />
      </a>
    </div>
  </nav>

  <div class="container pb-5">
    <div class="row justify-content-center">
      <div class="col-lg-8">
        
        <form action="create_payment.php" method="POST">
          <input type="hidden" name="airline" value="<?= htmlspecialchars($airline) ?>">
          <input type="hidden" name="duration" value="<?= htmlspecialchars($duration) ?>">
          <input type="hidden" name="price" value="<?= htmlspecialchars($price) ?>">
          <input type="hidden" name="user_id" value="<?= htmlspecialchars($userId) ?>">
          <input type="hidden" name="from_city" value="<?= htmlspecialchars($_GET['from'] ?? '') ?>">
          <input type="hidden" name="to_city" value="<?= htmlspecialchars($_GET['to'] ?? '') ?>">
          <input type="hidden" name="departure_date" value="<?= htmlspecialchars($_GET['depart'] ?? '') ?>">
          <input type="hidden" name="return_date" value="<?= htmlspecialchars($_GET['return'] ?? '') ?>">
          <input type="hidden" name="adults" value="<?= htmlspecialchars($_GET['adults'] ?? 1) ?>">

          <div class="payment-card bg-white">
            <div class="payment-header">
              <h2 class="mb-0 fw-bold">Confirm Your Booking</h2>
              <p class="mb-0 opacity-75">Review your flight details and enter passenger info</p>
            </div>

            <div class="p-4 p-md-5">
              
              <h4 class="section-title">
                <i class="bi bi-airplane-fill me-2"></i> Flight Summary
              </h4>
              
              <div class="bg-light p-4 rounded-4 mb-4">
                <div class="row">
                  <div class="col-md-6 mb-3 mb-md-0">
                    <small class="text-muted text-uppercase fw-bold">Airline</small>
                    <div class="fs-5 fw-bold text-dark"><?= htmlspecialchars($airline) ?></div>
                  </div>
                  <div class="col-md-6 text-md-end">
                    <small class="text-muted text-uppercase fw-bold">Total Price</small>
                    <div class="fs-4 fw-bold text-success">₱<?= htmlspecialchars($price) ?></div>
                  </div>
                </div>
                <hr class="my-3">
                <div class="row g-3">
                  <div class="col-6 col-md-3">
                    <small class="text-muted">From</small><br>
                    <strong><?= htmlspecialchars($_GET['from'] ?? '-') ?></strong>
                  </div>
                  <div class="col-6 col-md-3">
                    <small class="text-muted">To</small><br>
                    <strong><?= htmlspecialchars($_GET['to'] ?? '-') ?></strong>
                  </div>
                  <div class="col-6 col-md-3">
                    <small class="text-muted">Departure</small><br>
                    <strong><?= htmlspecialchars($_GET['depart'] ?? '-') ?></strong>
                  </div>
                  <div class="col-6 col-md-3">
                    <small class="text-muted">Duration</small><br>
                    <strong><?= htmlspecialchars($duration) ?></strong>
                  </div>
                </div>
              </div>

              <h4 class="section-title">
                <i class="bi bi-person-lines-fill me-2"></i> Passenger Details
              </h4>

              <div class="row g-3">
                <div class="col-md-6">
                  <label class="form-label text-muted fw-bold">First Name</label>
                  <input type="text" name="first_name" class="form-control" placeholder="e.g. Juan" required>
                </div>
                <div class="col-md-6">
                  <label class="form-label text-muted fw-bold">Last Name</label>
                  <input type="text" name="last_name" class="form-control" placeholder="e.g. Dela Cruz" required>
                </div>
                <div class="col-md-6">
                  <label class="form-label text-muted fw-bold">Email Address</label>
                  <input type="email" name="email" class="form-control" placeholder="juan@example.com" required>
                </div>
                <div class="col-md-6">
                  <label class="form-label text-muted fw-bold">Contact Number</label>
                  <input type="text" name="contact_number" class="form-control" placeholder="0912 345 6789" required>
                </div>
              </div>

              <div class="mt-5">
                <button type="submit" class="btn btn-pay w-100 shadow-sm">
                  <i class="bi bi-credit-card-2-front me-2"></i> Proceed to PayMongo
                </button>
                <div class="text-center mt-3">
                  <a href="../flights/flights.html" class="text-decoration-none text-muted small">
                    <i class="bi bi-arrow-left"></i> Cancel and return to flights
                  </a>
                </div>
              </div>

            </div>
          </div>

        </form>

      </div>
    </div>
  </div>

  <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.8/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
