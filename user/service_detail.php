<?php
session_start();
include('../config/db_connect.php');

if (!isset($_SESSION['user_id']) || $_SESSION['user_role'] != 'user') {
    header('Location: ../auth/login.php');
    exit();
}

if (!isset($_GET['id'])) {
    header('Location: dashboard.php');
    exit();
}

$service_id = $_GET['id'];
$user_id = $_SESSION['user_id'];

// Fetch service details with vendor
$service_result = $conn->query("SELECT s.*, c.title AS category_name, v.name AS vendor_name, v.username, v.profile_image, v.id AS vendor_id, v.email, v.phone, v.address, v.skills, v.description AS vendor_description, v.experience FROM services s JOIN categories c ON s.category_id = c.id JOIN vendors v ON s.vendor_id = v.id WHERE s.id = '$service_id'");

if ($service_result->num_rows == 0) {
    echo "<script>alert('Service not found'); window.location.href='dashboard.php';</script>";
    exit();
}

$service = $service_result->fetch_assoc();
$images = json_decode($service['image'], true);

// 🔒 Null Safety for Images
if (!is_array($images)) {
    $images = [];
}

// Fetch vendor average rating
$vendor_id = $service['vendor_id'];
$vendor_avg_result = $conn->query("SELECT AVG(sr.rating) AS avg_vendor_rating FROM service_ratings sr JOIN services s ON sr.service_id = s.id WHERE s.vendor_id = '$vendor_id'");
$vendor_avg_rating = round($vendor_avg_result->fetch_assoc()['avg_vendor_rating'], 1);

$page_title = "Service Details";
include('layout.php');
?>

<!DOCTYPE html>
<html lang="en" data-theme="dark">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width,initial-scale=1.0">
  <title><?= htmlspecialchars($page_title) ?></title>
  <link rel="shortcut icon" href="../image/logo.png" type="image/x-icon">
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
  <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css" rel="stylesheet">
  <style>
      /* ---- THEME ROOT ---- */
      :root[data-theme="dark"]  { --bg:#0f172a; --tx:#f1f5f9; --surf:#1e293b; --primary-from:#7f5cff; --primary-to:#d946ef; --btn-success:#10b981; --btn-danger:#ef4444; }
      :root[data-theme="light"] { --bg:#f8fafc; --tx:#1e293b; --surf:#ffffff; --primary-from:#6366f1; --primary-to:#ec4899; --btn-success:#059669; --btn-danger:#dc2626; }
      body{background:var(--bg);color:var(--tx);font-family:'Poppins',sans-serif;transition:.4s}
      a{color:var(--primary-from)}
      .surface{background:var(--surf);border-radius:1rem;box-shadow:0 4px 30px rgba(0,0,0,.15);transition:.4s;padding:2rem}
      .btn-gradient{background:linear-gradient(90deg,var(--primary-from),var(--primary-to));border:none;color:#fff;font-weight:600}
      .btn-gradient:hover{opacity:.9}
      .btn-success{background:var(--btn-success);border:none}
      .btn-danger{background:var(--btn-danger);border:none}
      /* Carousel */
      #serviceCarousel .carousel-item{text-align:center}
      #serviceCarousel img{max-height:420px;object-fit:contain;border-radius:1rem}
      /* Toggle */
      .theme-toggle{position:fixed;top:1rem;right:1rem;width:42px;height:42px;border-radius:50%;display:flex;align-items:center;justify-content:center;background:var(--surf);color:var(--tx);border:none;cursor:pointer;z-index:1000;transition:.4s}
  </style>
</head>
<body>
<button id="themeToggle" class="theme-toggle" aria-label="toggle-theme"><i class="fa-solid fa-moon"></i></button>
<div class="container py-5">
  <h2 class="mb-4 text-center fw-bold">Service Details</h2>
  <div class="row g-4 align-items-start">
    <!-- Carousel -->
    <div class="col-lg-6">
      <div id="serviceCarousel" class="carousel slide" data-bs-ride="carousel" data-bs-interval="3000">
        <div class="carousel-inner">
          <?php foreach($images as $idx=>$img): ?>
            <div class="carousel-item <?= $idx===0?'active':'' ?>">
              <img src="../uploads/<?= htmlspecialchars($img) ?>" onerror="this.onerror=null;this.src='../assets/default.png';" class="d-block w-100">
            </div>
          <?php endforeach; ?>
        </div>
        <?php if(count($images)>1): ?>
          <button class="carousel-control-prev" type="button" data-bs-target="#serviceCarousel" data-bs-slide="prev"><span class="carousel-control-prev-icon"></span></button>
          <button class="carousel-control-next" type="button" data-bs-target="#serviceCarousel" data-bs-slide="next"><span class="carousel-control-next-icon"></span></button>
        <?php endif; ?>
      </div>
    </div>

    <!-- Details card -->
    <div class="col-lg-6">
      <div class="surface">
        <h4 class="fw-semibold mb-2"><?= htmlspecialchars($service['title']) ?></h4>
        <span class="badge bg-primary mb-3"><?= $service['category_name'] ?></span>
        <p><strong>Description:</strong> <?= nl2br(htmlspecialchars($service['description'])) ?></p>
        <p><strong>Price:</strong> ₹<?= $service['price'] ?></p>
        <p><strong>Keywords:</strong> <?= htmlspecialchars($service['keywords']) ?></p>
        <p><strong>Average Rating:</strong> <?= $service['average_rating']?round($service['average_rating'],1):'Not Rated' ?> ⭐</p>

        <hr>
        <h5 class="fw-semibold mb-3">Vendor</h5>
        <div class="d-flex align-items-center mb-3">
          <img src="../uploads/<?= htmlspecialchars($service['profile_image']) ?>" class="rounded-circle me-3" style="width:60px;height:60px;object-fit:cover" onerror="this.onerror=null;this.src='../assets/default.png';">
          <div>
            <p class="mb-1 fw-bold"><?= $service['vendor_name'] ?> (@<?= $service['username'] ?>)</p>
             <a href="vendor_profile.php?vendor_id=<?php echo $service['vendor_id']; ?>" class="btn btn-sm btn-outline-primary mt-1">View Vendor</a>

          </div>
        </div>
         <p><strong>Vendor Average Rating:</strong> <?php echo $vendor_avg_rating ? $vendor_avg_rating : 'Not Rated'; ?> ⭐</p>

        <form method="POST" action="add_to_cart.php" class="d-grid mb-3">
          <input type="hidden" name="service_id" value="<?= $service['id'] ?>">
          <button type="submit" class="btn btn-success">Book Now</button>
        </form>
      </div>
    </div>
  </div>

  <!-- Reviews -->
  <h3 class="mt-5 mb-4 text-center">Customer Reviews</h3>
  <?php
    $per=5;$page=isset($_GET['page'])?intval($_GET['page']):1;$off=($page-1)*$per;
    $rev=$conn->query("SELECT sr.*,u.name FROM service_ratings sr JOIN users u ON sr.user_id=u.id WHERE sr.service_id=$service_id ORDER BY sr.created_at DESC LIMIT $off,$per");
    $total=$conn->query("SELECT COUNT(*) total FROM service_ratings WHERE service_id=$service_id")->fetch_assoc()['total'];
    $pages=ceil($total/$per);
    if($rev->num_rows): while($r=$rev->fetch_assoc()): ?>
        <div class="surface mb-3">
            <strong><?= htmlspecialchars($r['name']) ?></strong> rated: <?= str_repeat('⭐',$r['rating']) ?><br>
            <p class="mb-0 mt-2"><?= nl2br(htmlspecialchars($r['review'])) ?></p>
        </div>
  <?php endwhile; else: echo "<p class='text-center'>No reviews yet. Be the first to rate!</p>"; endif; ?>
  <!-- Pagination -->
  <div class="d-flex justify-content-center mt-3 gap-2">
    <?php for($i=1;$i<=$pages;$i++): ?>
      <a href="service_detail.php?id=<?= $service_id ?>&page=<?= $i ?>" class="btn btn-sm <?= $i==$page?'btn-gradient':'btn-outline-primary' ?>"><?= $i ?></a>
    <?php endfor; ?>
  </div>

  <!-- Rating form if not rated -->
  <?php $rated=$conn->query("SELECT 1 FROM service_ratings WHERE service_id=$service_id AND user_id=$user_id")->num_rows;?>
  <?php if(!$rated): ?>
  <h4 class="mt-5 mb-3 text-center">Rate this Service</h4>
  <div id="rating-msg"></div>
  <form id="ratingForm" class="surface mx-auto" style="max-width:500px">
      <input type="hidden" name="service_id" value="<?= $service_id ?>">
      <div class="mb-3 text-center">
          <label class="form-label">Your Rating</label><br>
          <div class="d-inline-flex gap-2 star-wrap">
            <?php for($i=1;$i<=5;$i++): ?>
              <span class="star" data-val="<?= $i ?>">&#9734;</span>
            <?php endfor; ?>
          </div>
          <input type="hidden" name="rating" id="ratingVal" required>
      </div>
      <div class="mb-3">
          <label class="form-label">Your Review</label>
          <textarea name="review" rows="3" class="form-control" required></textarea>
      </div>
      <button class="btn btn-gradient w-100">Submit Rating</button>
  </form>
  <?php else: echo "<div class='alert alert-info mt-4 text-center'>You have already rated this service.</div>"; endif; ?>

  <div class="text-center mt-4">
      <a href="dashboard.php" class="btn btn-outline-secondary">Back to Dashboard</a>
  </div>
</div>

<style>
  .star{font-size:2rem;cursor:pointer;color:#ccc;transition:.3s}
  .star.selected{color:gold}
</style>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
<script>
  // Theme persistence
  (function(){const s=localStorage.getItem('lsh_theme');const p=window.matchMedia('(prefers-color-scheme:dark)').matches;const t=s?s:(p?'dark':'light');document.documentElement.setAttribute('data-theme',t);document.body.setAttribute('data-theme',t);})();
  const tbtn=document.getElementById('themeToggle'),icon=tbtn.querySelector('i');
  function upIcon(th){icon.className= th==='dark'?'fa-solid fa-moon':'fa-solid fa-sun';}
  upIcon(document.body.getAttribute('data-theme'));
  tbtn.onclick=()=>{const c=document.body.getAttribute('data-theme')||'dark';const n=c==='dark'?'light':'dark';document.documentElement.setAttribute('data-theme',n);document.body.setAttribute('data-theme',n);localStorage.setItem('lsh_theme',n);upIcon(n);};

  // Star rating control
  document.querySelectorAll('.star').forEach(st=>{
    st.addEventListener('click',()=>{const val=st.dataset.val;document.getElementById('ratingVal').value=val;document.querySelectorAll('.star').forEach(s=>s.classList.remove('selected'));for(let i=0;i<val;i++){document.querySelectorAll('.star')[i].classList.add('selected');}});
  });
  // AJAX rating submit
  document.getElementById('ratingForm')?.addEventListener('submit',e=>{
    e.preventDefault();const fd=new FormData(e.target);
    fetch('submit_rating.php',{method:'POST',body:fd}).then(r=>r.json()).then(d=>{
      const msg=document.getElementById('rating-msg');
      if(d.status==='success'){msg.innerHTML=`<div class='alert alert-success'>${d.message}</div>`;setTimeout(()=>location.reload(),1000);}else{msg.innerHTML=`<div class='alert alert-danger'>${d.message}</div>`;}
    });
  });
</script>
</body>
</html>