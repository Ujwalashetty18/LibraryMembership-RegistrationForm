<?php
// submit.php
header('Content-Type: text/html; charset=utf-8');

// helper to sanitize
function s($k){
    return isset($_POST[$k]) ? trim($_POST[$k]) : '';
}
$fullname = htmlspecialchars(s('fullname'), ENT_QUOTES, 'UTF-8');
$email    = htmlspecialchars(s('email'), ENT_QUOTES, 'UTF-8');
$phone    = htmlspecialchars(s('phone'), ENT_QUOTES, 'UTF-8');
$gender   = htmlspecialchars(s('gender'), ENT_QUOTES, 'UTF-8');
$course   = htmlspecialchars(s('course'), ENT_QUOTES, 'UTF-8');
$comments = nl2br(htmlspecialchars(s('comments'), ENT_QUOTES, 'UTF-8'));

// basic server-side validation
if(strlen($fullname) < 2 || !filter_var($email, FILTER_VALIDATE_EMAIL)){
    http_response_code(400);
    echo "<div class='result-card'><strong>Error:</strong> Invalid submission.</div>";
    exit;
}

// Save to CSV (append)
$csvFile = __DIR__ . '/registrations.csv';
$fp = fopen($csvFile, 'a');
if($fp){
    // Add BOM on first creation? (not necessary)
    fputcsv($fp, [date('Y-m-d H:i:s'), $fullname, $email, $phone, $gender, $course, strip_tags($comments)]);
    fclose($fp);
}

// create a badge based on some rule (example: if course contains "Advanced" mark special)
$badgeText = "Registered";
$badgeClass = "badge";
if (stripos($course, 'advanced') !== false) {
    $badgeText = "Advanced interest";
}

$now = date('d M Y, H:i');
?>
<div class="result-card" role="status" aria-live="polite">
  <div class="result-header">
    <div>
      <h2 style="margin:0;font-size:18px"><?= $fullname ?></h2>
      <div style="font-size:13px;color:#666"><?= $now ?> • <?= htmlspecialchars($course) ?></div>
    </div>
    <div class="<?= $badgeClass ?>"><?= $badgeText ?></div>
  </div>

  <div class="result-field"><strong>Email:</strong> <?= $email ?></div>
  <div class="result-field"><strong>Phone:</strong> <?= $phone ?></div>
  <div class="result-field"><strong>Gender:</strong> <?= $gender ?></div>
  <div class="result-field"><strong>Comments:</strong> <?= $comments ?></div>

  <button id="printBtn" class="print-btn" type="button">Print</button>
</div>
