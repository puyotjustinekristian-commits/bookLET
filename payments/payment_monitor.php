<!DOCTYPE html>
<html>
<head>
  <meta charset="utf-8" />
  <title>Processing Payment...</title>
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.8/dist/css/bootstrap.min.css" rel="stylesheet" />
  <link rel="stylesheet" href="style.css" />
  <style>
    body {
      background-color: #f7f7f7;
      font-family: "Gill Sans", sans-serif;
      text-align: center;
      padding-top: 100px;
    }
    .spinner-border { width: 3rem; height: 3rem; color: #193764; }
  </style>
</head>
<body>

  <div class="container">
    <div class="spinner-border mb-4" role="status"></div>
    <h2 class="fw-bold" style="color: #193764;">Completing your payment...</h2>
    <p class="text-muted">A new tab has opened for you to pay via PayMongo.</p>
    <p>We are waiting for confirmation. Do not close this window.</p>
    
    <div class="mt-4">
      <small class="text-muted">If the payment tab didn't open,</small><br>
      <a href="<?= htmlspecialchars($_GET['checkout_url'] ?? '#') ?>" target="_blank" class="btn btn-sm btn-outline-primary mt-2">Click here to pay</a>
    </div>
  </div>

  <script>
    const checkoutUrl = "<?= htmlspecialchars($_GET['checkout_url'] ?? '') ?>";
    if(checkoutUrl) {
      window.open(checkoutUrl, '_blank');
    }

    const linkId = "<?= htmlspecialchars($_GET['link_id'] ?? '') ?>";
    const ref = "<?= htmlspecialchars($_GET['ref'] ?? '') ?>";

    const interval = setInterval(() => {
      fetch(`api_check_status.php?link_id=${linkId}&ref=${ref}`)
        .then(response => response.json())
        .then(data => {
          if (data.status === 'paid') {
            clearInterval(interval);
            window.location.href = `payment_success.php?ref=${ref}`;
          }
        })
        .catch(err => console.error(err));
    }, 3000);
  </script>
</body>
</html>
