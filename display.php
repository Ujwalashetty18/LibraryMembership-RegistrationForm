<?php
function safe($v){ return htmlspecialchars(trim($v)); }

if($_SERVER['REQUEST_METHOD'] !== 'POST'){
    header('Location: index.html');
    exit;
}

// collect
$first = safe($_POST['first'] ?? '');
$last = safe($_POST['last'] ?? '');
$dob = safe($_POST['dob'] ?? '');
$email = safe($_POST['email'] ?? '');
$phone = safe($_POST['phone'] ?? '');
$street = safe($_POST['street'] ?? '');
$city = safe($_POST['city'] ?? '');
$zip = safe($_POST['zip'] ?? '');
$membership = safe($_POST['membership'] ?? '');

$fullname = trim($first . ' ' . $last);

// save to CSV
$filename = 'registrations.csv';
$is_new = !file_exists($filename);
$fp = fopen($filename, 'a');
if($fp){
    if($is_new){
        fputcsv($fp, ['MemberID','Full Name','DOB','Email','Phone','Street','City','Zip','Membership','IssueDate']);
    }
    // generate a member id
    $memberId = 'LIB' . strtoupper(substr(md5(uniqid('',true)),0,8));
    $issueDate = date('Y-m-d');
    fputcsv($fp, [$memberId, $fullname, $dob, $email, $phone, $street, $city, $zip, $membership, $issueDate]);
    fclose($fp);
} else {
    // cannot write - proceed anyway
    $memberId = 'LIB' . strtoupper(substr(md5(uniqid('',true)),0,8));
    $issueDate = date('Y-m-d');
}

$validUntil = date('Y-m-d', strtotime('+1 year'));

?>
<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="utf-8" />
<meta name="viewport" content="width=device-width, initial-scale=1" />
<title>Registration Complete</title>
<link rel="stylesheet" href="style.css">
<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/html2canvas/1.4.1/html2canvas.min.js"></script>
</head>
<body>
  <header class="hero">
    <div class="hero-inner">
      <h1>Welcome to Our Library</h1>
      <p>Begin your journey with thousands of books, resources, and a vibrant community</p>
    </div>
  </header>

  <main class="main-wrap">
    <div class="success-wrap">
      <h2>Registration Complete!</h2>
      <p class="small-muted">Welcome to our library community. Here is your digital membership card.</p>

      <div id="membership-card" class="card-membership" role="region" aria-label="Membership Card">
        <div style="display:flex; justify-content:space-between; align-items:flex-start;">
          <div>
            <div class="label">Community Library</div>
            <h4><?php echo $fullname ? $fullname : 'Member'; ?></h4>
            <div class="meta">
              <div>
                <div style="font-size:12px; opacity:0.9;">Member ID</div>
                <strong><?php echo $memberId; ?></strong>
              </div>
              <div style="text-align:right">
                <div style="font-size:12px; opacity:0.9;">Membership Type</div>
                <strong><?php echo $membership; ?></strong>
              </div>
            </div>
          </div>
          <div style="text-align:right;">
            <div style="font-size:12px; opacity:0.9;">Issue Date</div>
            <strong><?php echo date('M j, Y', strtotime($issueDate)); ?></strong>
            <div style="height:8px"></div>
            <div style="font-size:12px; opacity:0.9;">Valid Until</div>
            <strong><?php echo date('M j, Y', strtotime($validUntil)); ?></strong>
          </div>
        </div>
      </div>

      <div style="margin-top:10px;">
        <a id="downloadBtn" class="download-btn" href="#" onclick="return false;">Download Membership Card</a>
        <button onclick="window.location.href='index.html'" style="margin-left:10px; padding:8px 12px; border-radius:6px;">Register Another Member</button>
      </div>

      <p class="small-muted">You can also pick up a physical card at the front desk during your next visit.</p>
    </div>
  </main>

<script>
document.getElementById('downloadBtn').addEventListener('click', function(){
  var el = document.getElementById('membership-card');
  if(window.html2canvas){
    html2canvas(el, {scale:2}).then(function(canvas){
      var link = document.createElement('a');
      link.download = '<?php echo $memberId; ?>_membership.png';
      link.href = canvas.toDataURL('image/png');
      link.click();
    }).catch(function(){
      window.print();
    });
  } else {
    window.print();
  }
});
</script>
</body>
</html>
