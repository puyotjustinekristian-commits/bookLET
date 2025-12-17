<?php
$linkId = $_GET['link_id'] ?? '';
$ref = $_GET['ref'] ?? '';
$checkoutUrl = $_GET['checkout_url'] ?? '';
?>
<!DOCTYPE html>
<html>
<head>
    <title>Processing Payment...</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <script>

        window.open("<?= $checkoutUrl ?>", "_blank");


        function checkStatus() {
            fetch(`api_check_status.php?type=hotel&link_id=<?= $linkId ?>&ref=<?= $ref ?>`)
                .then(res => res.json())
                .then(data => {
                    if (data.status === 'paid') {
                        window.location.href = "payment_success_hotel.php?ref=<?= $ref ?>";
                    }
                })
                .catch(err => console.error(err));
        }

        setInterval(checkStatus, 2000);
    </script>
    <style>
        body { background-color: #f8f9fa; font-family: 'Gill Sans', sans-serif; }
    </style>
</head>
<body class="d-flex justify-content-center align-items-center vh-100 flex-column">
    <div class="spinner-border text-primary mb-3" role="status" style="width: 3rem; height: 3rem;">
        <span class="visually-hidden">Loading...</span>
    </div>
    <h3 class="fw-bold" style="color: #193764;">Completing your payment...</h3>
    <p class="text-muted">A new tab has opened for you to pay securely via PayMongo.</p>
    <p class="small text-muted">Once paid, this page will automatically update.</p>
    <a href="<?= $checkoutUrl ?>" target="_blank" class="btn btn-outline-primary btn-sm mt-3">Click here to pay</a>
</body>
</html>
