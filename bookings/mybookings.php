<?php
session_start();

$userId = $_SESSION['USER_ID'] ?? 0;

if (!$userId) {
    header("Location: welcome/welcome.php");
    exit();
}

$serverName = "JustinePuyot\SQLEXPRESS";
$connectionOptions = [
    "Database" => "BOOKLET",
    "Uid" => "",
    "PWD" => ""
];

$conn = sqlsrv_connect($serverName, $connectionOptions);
if (!$conn) {
    die("Database connection failed");
}

$sqlFlight = "SELECT *, 'FLIGHT' as BOOKING_TYPE FROM FLIGHT_BOOKING WHERE USER_ID = ? ORDER BY FLIGHT_ID DESC";
$params = [$userId];
$stmtFlight = sqlsrv_query($conn, $sqlFlight, $params);

$bookings = [];
if ($stmtFlight) {
    while ($row = sqlsrv_fetch_array($stmtFlight, SQLSRV_FETCH_ASSOC)) {
        $bookings[] = $row;
    }
}

$sqlHotel = "SELECT *, 'HOTEL' as BOOKING_TYPE FROM HOTEL_BOOKING WHERE USER_ID = ? ORDER BY HOTEL_ID DESC";
$stmtHotel = sqlsrv_query($conn, $sqlHotel, $params);

if ($stmtHotel) {
    while ($row = sqlsrv_fetch_array($stmtHotel, SQLSRV_FETCH_ASSOC)) {
        $bookings[] = $row;
    }
}
?>

<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1" />
    <title>My Bookings - bookLET</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.8/dist/css/bootstrap.min.css" rel="stylesheet" />
    <link rel="stylesheet" href="../css/style.css" />
    <style>
        body { background-color: #f7f7f7; font-family: "Gill Sans", sans-serif; }
        .booking-card {
            background: white;
            border-radius: 15px;
            border: none;
            box-shadow: 0 4px 12px rgba(0,0,0,0.05);
            margin-bottom: 20px;
            transition: transform 0.2s;
        }
        .booking-card:hover { transform: translateY(-3px); }
        .status-badge {
            padding: 5px 12px;
            border-radius: 20px;
            font-size: 0.85rem;
            font-weight: bold;
        }
        .status-paid { background-color: #d4edda; color: #155724; }
        .status-pending { background-color: #fff3cd; color: #856404; }
    </style>
</head>
<body>

<nav class="navbar navbar-expand navbar-light bg-white border-bottom mb-4">
    <div class="container">
      <a href="../homepage/homepage.html" class="logo me-2">
        <img src="../images/bookLET logo cropped.png" alt="bookLET" class="logo" style="height: 40px;" />
      </a>
      
      <div class="collapse navbar-collapse" id="navbarNav">
        <ul class="navbar-nav ms-auto">
            <li class="nav-item me-2">
                <a class="nav-link active fw-bold" href="mybookings.php" style="color: #193764">My Bookings</a>
            </li>
            <li class="nav-item">
                <a class="nav-link" href="../welcome/welcome.php" style="color: #dc3545">Logout</a>
            </li>
        </ul>
      </div>
    </div>
</nav>

<div class="container">
    <h2 class="mb-4 fw-bold" style="color: #193764;">My Bookings</h2>

    <?php if (empty($bookings)): ?>
        <div class="alert alert-light text-center p-5 shadow-sm">
            <h4>No bookings found</h4>
            <p class="text-muted">Looks like you haven't booked any flights or hotels yet.</p>
            <div class="d-flex justify-content-center gap-2 mt-3">
                 <a href="../flights/flights.html" class="btn btn-primary" style="background-color: #193764;">Book Flight</a>
                 <a href="../hotels/hotel.html" class="btn btn-outline-primary">Book Hotel</a>
            </div>
        </div>
    <?php else: ?>
        <div class="row g-4">
            <?php foreach ($bookings as $booking): ?>
                <div class="col-md-6 col-lg-4">
                    <div class="booking-card p-4 h-100 d-flex flex-column">
                        <div class="d-flex justify-content-between align-items-center mb-4">
                            <span class="badge <?= ($booking['BOOKING_TYPE'] === 'HOTEL') ? 'bg-success' : 'bg-primary' ?>">
                                <?= $booking['BOOKING_TYPE'] ?>
                            </span>
                            <?php 
                                $status = strtoupper($booking['STATUS'] ?? 'PENDING');
                                $badgeClass = ($status === 'PAID') ? 'status-paid' : 'status-pending';
                            ?>
                            <span class="status-badge <?= $badgeClass ?>"><?= htmlspecialchars($status) ?></span>
                        </div>
                        
                        <?php if ($booking['BOOKING_TYPE'] === 'FLIGHT'): ?>

                            <h5 class="fw-bold mb-4">
                                <?= htmlspecialchars($booking['DEPARTURE_FROM'] ?? 'Unknown') ?> 
                                <i class="bi bi-arrow-right mx-2 text-muted"></i> 
                                <?= htmlspecialchars($booking['ARRIVAL_TO'] ?? 'Unknown') ?>
                            </h5>

                            <div class="row mb-3">
                                <div class="col-6">
                                    <div class="text-muted small mb-1">Departure</div>
                                    <?php 
                                        $depDate = $booking['DEPARTURE_DATE'] ?? '-';
                                        if ($depDate instanceof DateTime) $depDate = $depDate->format('Y-m-d');
                                    ?>
                                    <p class="fw-bold"><?= htmlspecialchars($depDate) ?></p>
                                </div>
                                <?php if (!empty($booking['RETURN_DATE'])): ?>
                                <div class="col-6">
                                    <div class="text-muted small mb-1">Return</div>
                                    <?php 
                                        $retDate = $booking['RETURN_DATE'];
                                        if ($retDate instanceof DateTime) $retDate = $retDate->format('Y-m-d');
                                    ?>
                                    <p class="fw-bold"><?= htmlspecialchars($retDate) ?></p>
                                </div>
                                <?php endif; ?>
                            </div>

                        <?php else: ?>

                            <h5 class="fw-bold mb-4">
                                <i class="bi bi-building me-2"></i> Hotel Reservation
                            </h5>
                            
                            <div class="row mb-3">
                                <div class="col-6">
                                    <div class="text-muted small mb-1">Check-in</div>
                                    <?php 
                                        $ci = $booking['CHECK_IN'] ?? '-';
                                        if ($ci instanceof DateTime) $ci = $ci->format('Y-m-d');
                                    ?>
                                    <p class="fw-bold"><?= htmlspecialchars($ci) ?></p>
                                </div>
                                <div class="col-6">
                                    <div class="text-muted small mb-1">Check-out</div>
                                    <?php 
                                        $co = $booking['CHECK_OUT'] ?? '-';
                                        if ($co instanceof DateTime) $co = $co->format('Y-m-d');
                                    ?>
                                    <p class="fw-bold"><?= htmlspecialchars($co) ?></p>
                                </div>
                            </div>
                            
                            <div class="d-flex justify-content-between border-top pt-3 mb-3">
                                <small class="text-muted"><?= htmlspecialchars($booking['ROOMS']) ?> Room(s), <?= htmlspecialchars($booking['GUESTS']) ?> Guest(s)</small>
                                <span class="fw-bold text-primary">₱<?= number_format($booking['TOTAL_PRICE'] ?? 0) ?></span>
                            </div>

                        <?php endif; ?>

                        <div class="mt-auto pt-3 border-top">
                            <div class="text-muted small mb-1">Reference</div>
                            <p class="font-monospace mb-0 text-truncate" title="<?= htmlspecialchars($booking['PAYMENT_ID'] ?? '-') ?>">
                                <?= htmlspecialchars($booking['PAYMENT_ID'] ?? '-') ?>
                            </p>
                        </div>
                    </div>
                </div>
            <?php endforeach; ?>
        </div>
    <?php endif; ?>
</div>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.8/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
