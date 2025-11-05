<?php
// submit.php
header('Content-Type: text/html; charset=utf-8');
date_default_timezone_set('Asia/Kolkata');

// simple sanitizer helper: returns trimmed POST value or empty string
function s($k){
    return isset($_POST[$k]) ? trim($_POST[$k]) : '';
}

// Read and sanitize inputs
$fullname = htmlspecialchars(s('fullname'), ENT_QUOTES, 'UTF-8');
$email    = htmlspecialchars(s('email'), ENT_QUOTES, 'UTF-8');
$phone    = htmlspecialchars(s('phone'), ENT_QUOTES, 'UTF-8');
$dob      = htmlspecialchars(s('dob'), ENT_QUOTES, 'UTF-8');
$address  = htmlspecialchars(s('address'), ENT_QUOTES, 'UTF-8');
$duration = (int)s('membership_duration');
$genres   = htmlspecialchars(s('preferred_genres'), ENT_QUOTES, 'UTF-8');
$membership_id = htmlspecialchars(s('membership_id'), ENT_QUOTES, 'UTF-8');

// Basic server-side validation
if(strlen($fullname) < 2 || !filter_var($email, FILTER_VALIDATE_EMAIL)){
    http_response_code(400);
    echo "<div class='result-card'><strong>Error:</strong> Invalid submission. Please check name and email.</div>";
    exit;
}

// compute expiry date based on duration (months)
$start = new DateTime();
$expiry = (clone $start)->modify("+{$duration} months");
$startStr = $start->format('d M Y');
$expiryStr = $expiry->format('d M Y');

// save to CSV file
$csvFile = __DIR__ . '/library_members.csv';
$fp = fopen($csvFile, 'a');
if($fp){
    // Save: timestamp, membership_id, fullname, email, phone, dob, address, genres, duration, expiry-date
    fputcsv($fp, [date('Y-m-d H:i:s'), $membership_id, $fullname, $email, $phone, $dob, $address, $genres, $duration, $expiry->format('Y-m-d')]);
    fclose($fp);
} else {
    // If file can't be opened, respond with error
    http_response_code(500);
    echo "<div class='result-card'><strong>Error:</strong> Could not save registration (file write error).</div>";
    exit;
}

// return a formatted card (HTML)
?>
<div class="result-card" role="status" aria-live="polite">
  <div class="result-header">
    <div>
      <h2 style="margin:0;font-size:18px"><?= $fullname ?></h2>
      <div style="font-size:13px;color:#666">Member ID: <strong><?= $membership_id ?></strong></div>
    </div>
    <div class="badge">Member</div>
  </div>

  <div class="result-field"><strong>Email:</strong> <?= $email ?></div>
  <div class="result-field"><strong>Phone:</strong> <?= $phone ?></div>
  <div class="result-field"><strong>DOB:</strong> <?= $dob ? $dob : '—' ?></div>
  <div class="result-field"><strong>Address:</strong> <?= $address ? nl2br($address) : '—' ?></div>
  <div class="result-field"><strong>Preferred Genres:</strong> <?= $genres ? $genres : '—' ?></div>
  <div class="result-field"><strong>Membership Start:</strong> <?= $startStr ?></div>
  <div class="result-field"><strong>Membership Expires:</strong> <?= $expiryStr ?></div>

  <button id="printBtn" class="print-btn" type="button">Print Membership</button>
</div>
