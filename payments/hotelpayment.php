<?php
session_start();
$hotelName = $_GET['hotel_name'] ?? 'Hotel';
$price = $_GET['price'] ?? '0';
$checkin = $_GET['checkin'] ?? '';
$checkout = $_GET['checkout'] ?? '';
$rooms = $_GET['rooms'] ?? 1;
$adults = $_GET['adults'] ?? 1;
$children = $_GET['children'] ?? 0;
?>

<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1" />
    <title>Hotel Payment - bookLET</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.8/dist/css/bootstrap.min.css" rel="stylesheet" />
    <link rel="stylesheet" href="../css/style.css" />
    <style>
        body { background-color: #f7f7f7; font-family: "Gill Sans", sans-serif; }
        .payment-summary {
            background: white;
            padding: 20px;
            border-radius: 15px;
            box-shadow: 0 4px 12px rgba(0,0,0,0.05);
        }
        .form-control, .form-select {
            border-radius: 8px;
            padding: 10px 15px;
        }
    </style>
</head>
<body>

<div class="page-wrapper">

    <nav class="navbar navbar-expand navbar-light bg-white border-bottom mb-4">
        <div class="container">
          <a href="../homepage/homepage.html" class="logo me-2">
            <img src="../images/bookLET logo cropped.png" alt="bookLET" class="logo" style="height: 40px;" />
          </a>
          
          <div class="collapse navbar-collapse" id="navbarNav">
            <ul class="navbar-nav ms-auto">
                <li class="nav-item me-2">
                    <a class="nav-link fw-bold" href="../bookings/mybookings.php" style="color: #193764">My Bookings</a>
                </li>
                <li class="nav-item">
                    <a class="nav-link" href="../welcome/welcome.php" style="color: #dc3545">Logout</a>
                </li>
            </ul>
          </div>
        </div>
    </nav>

    <div class="container mb-5">
        <h2 class="mb-4 fw-bold" style="color: #193764;">Confirm Hotel Booking</h2>

        <div class="row">

            <div class="col-md-8">
                <div class="payment-summary">
                    <h4 class="mb-3">Guest Details</h4>
                    <form action="create_hotel_payment.php" method="POST">
                        <input type="hidden" name="hotel_name" value="<?= htmlspecialchars($hotelName) ?>">

                        <input type="hidden" name="price" id="formPrice" value="<?= htmlspecialchars($price) ?>">
                        <input type="hidden" name="checkin" value="<?= htmlspecialchars($checkin) ?>">
                        <input type="hidden" name="checkout" value="<?= htmlspecialchars($checkout) ?>">

                        <div class="row mb-3">
                            <div class="col-md-6">
                                <label class="form-label">First Name</label>
                                <input type="text" class="form-control" name="first_name" required>
                            </div>
                            <div class="col-md-6">
                                <label class="form-label">Last Name</label>
                                <input type="text" class="form-control" name="last_name" required>
                            </div>
                        </div>

                        <div class="mb-3">
                            <label class="form-label">Email Address</label>
                            <input type="email" class="form-control" name="email" required>
                        </div>

                        <div class="mb-3">
                            <label class="form-label">Contact Number</label>
                            <input type="tel" class="form-control" name="contact" required>
                        </div>
                        
                        <div class="row mb-3">
                            <div class="col-md-4">
                                <label class="form-label">Rooms</label>
                                <input type="number" class="form-control" name="rooms" id="roomsInput" value="<?= htmlspecialchars($rooms) ?>" min="1" required onchange="updatePrice()">
                            </div>
                            <div class="col-md-4">
                                <label class="form-label">Adults</label>
                                <input type="number" class="form-control" name="adults" value="<?= htmlspecialchars($adults) ?>" min="1" required>
                            </div>
                            <div class="col-md-4">
                                <label class="form-label">Children</label>
                                <input type="number" class="form-control" name="children" value="<?= htmlspecialchars($children) ?>" min="0" required>
                            </div>
                        </div>

                        <hr>
                        
                        <button type="submit" class="btn btn-primary w-100 py-3 fw-bold" style="background-color: #193764; border: none; font-size: 1.1rem;" id="payBtn">
                            Proceed to Payment (₱<?= number_format($price, 2) ?>)
                        </button>
                    </form>
                </div>
            </div>


            <div class="col-md-4">
                <div class="payment-summary bg-light">
                    <h5 class="fw-bold mb-3" style="color: #193764;">Booking Summary</h5>
                    <div class="d-flex justify-content-between mb-2">
                        <span class="text-muted">Hotel</span>
                        <span class="fw-bold text-end"><?= htmlspecialchars($hotelName) ?></span>
                    </div>
                    <?php if($checkin && $checkout): ?>
                    <div class="d-flex justify-content-between mb-2">
                        <span class="text-muted">Dates</span>
                        <span class="fw-bold text-end" style="font-size: 0.9rem;">
                            <?= htmlspecialchars($checkin) ?> <br>to<br> <?= htmlspecialchars($checkout) ?>
                        </span>
                    </div>
                    <?php endif; ?>
                    <hr>
                    <div class="d-flex justify-content-between align-items-center">
                        <span class="fw-bold text-muted">Total Price</span>
                        <span class="fs-4 fw-bold" style="color: #193764;" id="displayPrice">₱<?= number_format($price, 2) ?></span>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<script>

    const initialTotal = <?= floatval($price) ?>;
    const initialRooms = <?= intval($rooms) ?>;
    

    const pricePerRoomBundle = initialRooms > 0 ? initialTotal / initialRooms : 0;

    function updatePrice() {
        const roomsInput = document.getElementById('roomsInput');
        let newRooms = parseInt(roomsInput.value) || 1;
        if (newRooms < 1) newRooms = 1;

        const newTotal = pricePerRoomBundle * newRooms;


        document.getElementById('formPrice').value = newTotal.toFixed(2);


        const formatted = newTotal.toLocaleString('en-US', { minimumFractionDigits: 2, maximumFractionDigits: 2 });
        document.getElementById('displayPrice').innerText = '₱' + formatted;
        document.getElementById('payBtn').innerText = 'Proceed to Payment (₱' + formatted + ')';
    }
</script>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.8/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
