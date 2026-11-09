<?php
// =====================================================================
// @@SETUP
// SETUP > One-time script that creates the first admin and consumer. DELETE it afterwards.
// FILE: setup.php
// FIND: press Ctrl+Shift+F and type a tag (example: @@SETUP) to see every related place.
// =====================================================================
/**
 * One-time setup script.
 * Run this once after importing database/database.sql, then DELETE this file
 * (or rename it) and change the development passwords before real use.
 */
// @@SETUP-GUARD  creates accounts with KNOWN passwords, so it is closed unless you open it on purpose:
// on your own computer (localhost) it works; online it only works when the host has TIGBAO_ALLOW_SETUP=1,
// and you must remove that setting (and delete this file) right after.
$__local = in_array($_SERVER['REMOTE_ADDR'] ?? '', ['127.0.0.1', '::1'], true);
if (!$__local && getenv('TIGBAO_ALLOW_SETUP') !== '1') {
    http_response_code(403);
    exit('Setup is closed.');
}
require_once __DIR__ . '/includes/bootstrap.php';
require_once __DIR__ . '/includes/auth.php';   // @@STAFF-ROLES  for the Treasurer / Water Reader demo accounts

$conn = get_db_connection();
$messages = [];

// @@SETUP-SEED-USER  reusable helper: creates one user account if that email doesn't exist yet
function seed_user(mysqli $conn, string $name, string $email, string $password, string $role, ?string $accountNo, string $phone, string $address): int
{
    $stmt = $conn->prepare('SELECT id FROM users WHERE email = ?');
    $stmt->bind_param('s', $email);
    $stmt->execute();
    $existing = $stmt->get_result()->fetch_assoc();
    $stmt->close();

    if ($existing) {
        return (int)$existing['id'];
    }

    $hash = password_hash($password, PASSWORD_DEFAULT);
    // @@SQL-users-INSERT  database query: adds a row to table users
    $stmt = $conn->prepare(
        'INSERT INTO users (full_name, email, password_hash, role, account_no, phone, address, status)
         VALUES (?, ?, ?, ?, ?, ?, ?, "active")'
    );
    $stmt->bind_param('sssssss', $name, $email, $hash, $role, $accountNo, $phone, $address);
    $stmt->execute();
    $id = $stmt->insert_id;
    $stmt->close();
    return $id;
}

// @@SETUP-ADMIN  --- Seed admin account ---
$adminId = seed_user($conn, 'Water District Administrator', 'admin@tigbao.local', 'Admin@12345', 'admin', null, '', '');
$messages[] = "Admin account ready: admin@tigbao.local / Admin@12345 (user #$adminId)";

// @@SETUP-STAFF  --- Seed the Treasurer and Water Reader demo accounts ---
ensure_staff_roles_schema($conn);
$treasurerId = seed_user($conn, 'Water District Treasurer', 'treasurer@tigbao.local', 'Treasurer@12345', 'treasurer', null, '', '');
$messages[] = "Treasurer account ready: treasurer@tigbao.local / Treasurer@12345 (user #$treasurerId)";
$readerId = seed_user($conn, 'Water Reader', 'reader@tigbao.local', 'Reader@12345', 'meter_reader', null, '', '');
$messages[] = "Water Reader account ready: reader@tigbao.local / Reader@12345 (user #$readerId)";

// @@SETUP-CUSTOMER  --- Seed sample consumer ---
$customerId = seed_user($conn, 'Juan Dela Cruz', 'juan@tigbao.local', 'Customer@12345', 'customer', '2024-10023', '09171234567', 'Tigbao, Zamboanga del Sur');
$messages[] = "Consumer account ready: juan@tigbao.local / Customer@12345 (user #$customerId)";

// @@SETUP-SAMPLE-BILL  --- Seed a sample meter reading + bill so dashboards aren't empty ---
$hasReading = $conn->query("SELECT id FROM meter_readings WHERE customer_id = $customerId LIMIT 1")->fetch_assoc();
if (!$hasReading) {
    $stmt = $conn->prepare(
        'INSERT INTO meter_readings (customer_id, reading_date, previous_reading, current_reading, consumption_m3, recorded_by)
         VALUES (?, CURDATE(), 117, 129, 12, ?)'
    );
    $stmt->bind_param('ii', $customerId, $adminId);
    $stmt->execute();
    $readingId = $stmt->insert_id;
    $stmt->close();

    require_once __DIR__ . '/includes/billing.php';
    $amount = calculate_water_charge(12);
    $dueDate = date('Y-m-d', strtotime('+15 days'));
    $period = date('F Y');

    // @@SQL-bills-INSERT  database query: adds a row to table bills
    $stmt = $conn->prepare(
        'INSERT INTO bills (customer_id, meter_reading_id, billing_period, consumption_m3, amount_due, due_date, status)
         VALUES (?, ?, ?, 12, ?, ?, "unpaid")'
    );
    $stmt->bind_param('iisds', $customerId, $readingId, $period, $amount, $dueDate);
    $stmt->execute();
    $stmt->close();

    $messages[] = "Sample meter reading and bill ({$period}, " . peso($amount) . ") created for Juan Dela Cruz.";
}
// @@SETUP-RESULT-PAGE  the simple "Setup Complete" screen shown after the above runs
?>
<!DOCTYPE html>
<html lang="en" data-section="admin-login">
<head>
<meta charset="UTF-8">
<title>Setup - Barangay Tigbao Water District</title>
<link rel="stylesheet" href="assets/css/style.css">
<link rel="stylesheet" href="assets/css/theme.css">
</head>
<body>
<div class="auth-wrapper">
  <div class="auth-box" style="max-width:520px;">
    <h2>Setup Complete</h2>
    <?php foreach ($messages as $m): ?>
      <div class="alert alert-success"><?= e($m) ?></div>
    <?php endforeach; ?>
    <p style="font-size:.85rem; color:var(--text-muted);">
      Delete or rename <code>setup.php</code> now, and change these development
      passwords before this system is used for real.
    </p>
    <a class="muted-link" href="index.php">&larr; Go to the login page</a>
  </div>
</div>
</body>
</html>
