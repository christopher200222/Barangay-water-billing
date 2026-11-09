<?php
// =====================================================================
// @@HOME
// HOME > Landing page with the Admin / Consumer buttons.
// FILE: index.php
// FIND: press Ctrl+Shift+F and type a tag (example: @@HOME) to see every related place.
// =====================================================================
require_once __DIR__ . '/includes/bootstrap.php'; ?>
<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<title>Barangay Tigbao Water District</title>
<link rel="icon" type="image/png" href="assets/images/logo-seal.png">
<?php require __DIR__ . '/includes/pwa_head.php'; ?><!-- @@PWA  makes this page installable on a phone -->
<!-- @@FONTS  Poppins loads AFTER the page is shown. If the computer has no internet / DNS, pages open instantly with the normal system font instead of waiting for Google. -->
<script>
window.addEventListener('load', function () {
  setTimeout(function () {
    var l = document.createElement('link');
    l.rel = 'stylesheet';
    l.href = 'https://fonts.googleapis.com/css2?family=Poppins:wght@400;500;600;700&display=swap';
    document.head.appendChild(l);
  }, 400);
});
</script>
<link rel="stylesheet" href="assets/css/style.css">
<link rel="stylesheet" href="assets/css/theme.css">
</head>
<body>
<!-- @@HOME-PAGE  landing page content -->
<div class="landing">
  <!-- @@LANDING-WATER  moving water background (see theme.css) -->
  <div class="water-bg" aria-hidden="true"><span></span><span></span></div>
  <div class="landing-card">
    <img class="landing-seal" src="assets/images/logo-seal.png" alt="Municipality of Tigbao seal">
    <h1>BARANGAY TIGBAO WATER DISTRICT</h1>
    <p class="subtitle">Water Billing &amp; Online Payment System</p>
    <div class="btn-row">
      <a class="btn btn-primary" href="customer/login.php"><span class="btn-icon">👤</span> Consumer Login</a>
      <a class="btn btn-outline" href="admin/login.php"><span class="btn-icon">🛡️</span> Admin Login</a>
      <a class="btn btn-outline" href="treasurer/login.php"><span class="btn-icon">💰</span> Treasurer Login</a>
      <a class="btn btn-outline" href="meter-reader/login.php"><span class="btn-icon">🔢</span> Water Reader Login</a>
    </div>
    <p class="landing-foot">Province of Zamboanga del Sur</p>
  </div>
</div>
</body>
</html>
