<?php
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

if (!isset($_SESSION['user_id']) || $_SESSION['user_role'] != 'user') {
    header('Location: ../auth/login.php');
    exit();
}

include('../config/db_connect.php');

// Cart count calculation
$cart_count = isset($_SESSION['cart']) ? count($_SESSION['cart']) : 0;
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="shortcut icon" href="../image/logo.png" type="image/x-icon">
    <title>Local Service HUb</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <style>
             :root[data-theme="dark"] {
            --bg-color: linear-gradient(135deg, #0f0c29, #302b63, #24243e);
            --text-color: #ffffff;
            --card-bg: #1e1e2f;
            --link-color: #8a2be2;
        }
        :root[data-theme="light"] {
            --bg-color: #ffffff;
            --text-color: #000000;
            --card-bg: #f8f9fa;
            --link-color: #5b2eff;
        }

        body {
            margin: 0;
            font-family: 'Poppins', sans-serif;
            background: var(--bg-color);
            color: var(--text-color);
            overflow-x: hidden;
            transition: background 0.3s, color 0.3s;
        }
        .navbar {
            background: rgba(12, 10, 50, 0.95);
        }
        .hero {
            height: 100vh;
            display: flex;
            align-items: center;
            justify-content: center;
            flex-direction: column;
            text-align: center;
            background: radial-gradient(circle at top left, #5b2eff, #8a2be2);
            color: #fff;
        }
        .hero h1 {
            font-size: 3.5rem;
            font-weight: 700;
        }
        .hero p {
            font-size: 1.2rem;
        }
        .hero .btn {
            margin-top: 20px;
            background-color: #8a2be2;
            border: none;
            color: #fff;
        }
        .section {
            padding: 60px 20px;
        }
        .card {
            background: var(--card-bg);
            border: none;
            transition: transform 0.3s ease;
        }
        .card:hover {
            transform: translateY(-10px);
        }
        .card i {
            font-size: 2rem;
            color: var(--link-color);
        }
        .footer {
            background: #0f0c29;
            padding: 30px;
            text-align: center;
        }
        a {
            color: var(--link-color);
            text-decoration: none;
        }
        .theme-toggle {
            position: fixed;
            bottom: 20px;
            right: 20px;
            background: var(--link-color);
            color: white;
            border: none;
            padding: 10px 15px;
            border-radius: 30px;
            cursor: pointer;
            z-index: 1000;
        }
        /* Fix text color in all modes */
       
     
        .card h5,
        p.card-text,
        .theme-toggle {
            color: var(--text-color) !important;
        }
        .search-bar {
            margin-left: 20px;
        }
       
/* --- Card Container --- */
.card1 {
  display: flex;
  flex-direction: row;
  background-color: #212529;
  color: white;
  border-radius: 12px;
  overflow: hidden;
  box-shadow: 0 4px 12px rgba(0, 0, 0, 0.4);
  margin: 20px auto;
  max-width: 900px;
}

/* --- Mobile Stacking --- */
@media (max-width: 768px) {
  .card1 {
    flex-direction: column;
  }
}

/* --- Slider Section --- */
.slider {
  flex: 1;
  position: relative;
  max-width: 100%;
  overflow: hidden;
}
.slides {
  display: flex;
  width: 300%;
  transition: transform 1s ease;
}
.slide {
  width: 100%;
  flex-shrink: 0;
  height: 100%;
}
.slide img {
  width: 100%;
  height: 100%;
  object-fit: cover;
  border-right: 2px solid #111;
}

/* --- Navigation --- */
.navigation-manual, .navigation-auto {
  position: absolute;
  width: 100%;
  bottom: 15px;
  display: flex;
  justify-content: center;
  gap: 10px;
  z-index: 10;
}
.manual-btn, .auto-btn1, .auto-btn2, .auto-btn3 {
  border: 2px solid white;
  padding: 5px;
  border-radius: 50%;
  cursor: pointer;
  background-color: #6f42c1;
}

input[type="radio"] {
  display: none;
}

/* --- Card Body --- */
.card-body {
  flex: 1;
  padding: 20px;
}
.card-title {
  font-size: 24px;
  color: #fff;
  font-weight: bold;
}
.card-text {
  margin: 8px 0;
  color: #ccc;
}
.badge {
  background-color: #6f42c1;
  color: white;
  padding: 4px 10px;
  border-radius: 20px;
  font-size: 12px;
  margin-right: 6px;
}
.rating {
  color: gold;
  font-size: 16px;
  margin-right: 10px;
}
.heart {
  color: #6f42c1;
  font-size: 16px;
}
.book-btn {
  background-color: #6f42c1;
  color: white;
  padding: 10px 20px;
  border: none;
  border-radius: 10px;
  margin-top: 15px;
  transition: transform 0.3s ease, box-shadow 0.3s ease;
}
.book-btn:hover {
  transform: scale(1.05);
  box-shadow: 0 0 10px #6f42c1;
}

/* ====================  SERVICE‑CARD  ==================== */
.lsh-sc-card{
    position:relative;
    height:400px;
    width:100%;
    margin:10px 0;
    perspective:1200px;
    transition:ease all 2.3s;
    overflow:hidden;
    box-shadow:20px 20px 60px #00000041, inset -20px -20px 60px #ffffff40;
}

.lsh-sc-inner{
    position:relative;
    height:100%;
    width:100%;
    transition:transform 1.2s ease;
    transform-style:preserve-3d;
    box-shadow:inherit;
}

/* flip on hover */
.lsh-sc-card:hover .lsh-sc-inner{ transform:rotateY(180deg); }

/* -------- Front -------- */
.lsh-sc-front{
    position:absolute;
    inset:0;
    display:flex;
    flex-direction:column;
    justify-content:center;
    align-items:center;
    backface-visibility:hidden;
    background:#000;
    box-shadow:inherit;
}

.lsh-sc-img{
    height:100%;
    width:100%;
    object-fit:cover;
}

.lsh-sc-title{
    position:absolute;
    bottom:10px;
    left:10px;
    width:calc(100% - 20px);
    margin:0;
    padding:5px 10px;
    font-size:1.8em;
    font-weight:600;
    text-align:center;
    color:rgba(0,0,0,0.8);
    background:#fff;
}

.lsh-sc-card:hover .lsh-sc-title{ visibility:hidden; }

/* -------- Back -------- */
.lsh-sc-back{
    position:absolute;
    inset:0;
    padding:20px;
    display:flex;
    flex-direction:column;
    justify-content:center;
    align-items:center;
    background:#232323;
    color:#fff;
    transform:rotateY(180deg);
    backface-visibility:visible;
    opacity:0;
    transition:opacity .5s ease;
    box-shadow:inherit;
}

.lsh-sc-card:hover .lsh-sc-back{ opacity:1; }

.lsh-sc-text{
    margin:10px 0;
    font-size:1.1em;
    font-weight:200;
}

/* optional – unify btn look inside card */
.lsh-sc-back a.btn{
    background:transparent;
    border:1px solid #fff;
    font-weight:200;
    font-size:1.1em;
    color:#fff;
    padding:10px 20px;
    margin-top:10px;
    transition:background .5s ease,color .5s ease;
}

.lsh-sc-back a.btn:hover{
    background:#fff;
    color:#000;
}


        .service-card {
            transition: transform 0.2s;
        }
        .service-card:hover {
            transform: scale(1.02);
        }


        @import url("https://fonts.googleapis.com/css2?family=Comfortaa:wght@300;400;500;600;700&display=swap");

/* ---------- Section Layout ---------- */
.mn-section{
  position:relative;
  display:flex;
  justify-content:center;
  align-items:center;
  background:linear-gradient(135deg, #0f0c29, #302b63, #24243e);;
  min-height:100vh;
  overflow:hidden;
  padding:40px 15px;
  
}

/* Glass card */
.mn-content{
  display:flex;
  gap:30px;
  background:linear-gradient(180deg,rgba(255,255,255,0.28) 0%,rgba(255,255,255,0) 100%);
  backdrop-filter:blur(30px);
  border-radius:20px;
  width:min(900px,100%);
  box-shadow:0 1px 0 rgba(255,255,255,0.66) inset,0 4px 16px rgba(0,0,0,0.12);
  z-index:10;
}

/* Text box */
.mn-info{
  max-width:450px;
  padding:35px;
  text-align:justify;
  display:flex;
  flex-direction:column;
  justify-content:center;
  align-items:center;
}
.mn-info p{color:#fff;line-height:1.5;margin-bottom:20px;}
.mn-movie-night{background:linear-gradient(225deg,#ff3cac 0%,#784ba0 50%,#2b86c5 100%);-webkit-background-clip:text;color:transparent;font-weight:600;}
.mn-btn{
  padding:10px 40px;
  font-size:1.1rem;
  font-weight:700;
  border-radius:4px;
  background:rgba(255,255,255,0.9);
  color:#784ba0;
  border:1px solid rgba(255,255,255,0.3);
  cursor:pointer;
  transition:transform .2s;
}
.mn-btn:hover{transform:scale(1.08);}

/* ---------- Swiper ---------- */
.mn-swiper{width:250px;height:450px;padding:50px 0;}
.swiper-slide{position:relative;border-radius:10px;box-shadow:0 15px 50px rgba(0,0,0,0.2);overflow:hidden;}
.swiper-slide img{width:100%;height:100%;object-fit:cover;}
.mn-img-position{object-position:50% 0%;}
.mn-overlay{
  position:absolute;inset:0;
  background:linear-gradient(to top,#0f2027 0%,transparent 60%);
}
.mn-overlay span{
  position:absolute;top:10px;right:10px;
  padding:7px 18px;font-size:0.8rem;font-weight:700;letter-spacing:2px;
  background:rgba(255,255,255,0.095);color:#fff;border-radius:20px;
  backdrop-filter:blur(74px);
}
.mn-overlay h2{position:absolute;left:20px;bottom:20px;color:#fff;font-weight:400;font-size:1.1rem;}

/* ---------- Floating Bubbles ---------- */
.mn-circles{position:absolute;inset:0;overflow:hidden;}
.mn-circles li{
  position:absolute;display:block;list-style:none;width:20px;height:20px;
  background:linear-gradient(225deg,#ff3cac 0%,#784ba0 50%,#2b86c5 100%);
  animation:mn-float 25s linear infinite;bottom:-150px;
}
/* size‑&‑delay variations */
.mn-circles li:nth-child(1){left:25%;width:80px;height:80px;}
.mn-circles li:nth-child(2){left:10%;animation-delay:2s;animation-duration:12s;}
.mn-circles li:nth-child(3){left:70%;animation-delay:4s;}
.mn-circles li:nth-child(4){left:40%;width:60px;height:60px;animation-duration:18s;}
.mn-circles li:nth-child(5){left:65%;}
.mn-circles li:nth-child(6){left:75%;width:110px;height:110px;animation-delay:3s;}
.mn-circles li:nth-child(7){left:35%;width:150px;height:150px;animation-delay:7s;}
.mn-circles li:nth-child(8){left:50%;width:25px;height:25px;animation-delay:15s;animation-duration:45s;}
.mn-circles li:nth-child(9){left:20%;width:15px;height:15px;animation-delay:2s;animation-duration:35s;}
.mn-circles li:nth-child(10){left:85%;width:150px;height:150px;animation-duration:11s;}

@keyframes mn-float{
  0%{transform:translateY(0) rotate(0deg);opacity:1;border-radius:0;}
  100%{transform:translateY(-1000px) rotate(720deg);opacity:0;border-radius:50%;}
}

/* ---------- Responsive ---------- */
@media (max-width:750px){
  .mn-content{flex-direction:column-reverse;padding:20px;}
  .mn-swiper{width:80vw;height:70vh;}
  .mn-btn{margin:10px auto 30px;}
}

    </style>
</head>
<body>

<!-- Navbar -->
<nav class="navbar navbar-expand-lg navbar-dark">
    <div class="container">
        <a class="navbar-brand" href="index.php">Local Service Hub</a>
        <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav">
            <span class="navbar-toggler-icon"></span>
        </button>
        <div class="collapse navbar-collapse justify-content-end" id="navbarNav">
            <ul class="navbar-nav">
                <li class="nav-item"><a href="dashboard.php" class="nav-link">Dashboard</a></li>
                <li class="nav-item"><a href="my_bookings.php" class="nav-link">My Bookings</a></li>
                <li class="nav-item"><a href="chat.php" class="nav-link">Chat</a></li>
                <li class="nav-item position-relative">
                    <a href="cart.php" class="nav-link">
                        Cart
                        <?php if ($cart_count > 0) { ?>
                            <span class="position-absolute top-0 start-100 translate-middle badge rounded-pill bg-danger">
                                <?php echo $cart_count; ?>
                            </span>
                        <?php } ?>
                    </a>
                </li>
                <li class="nav-item"><a href="my_following.php" class="nav-link">Followings</a></li>
                <li class="nav-item"><a href="raise_query.php" class="nav-link">Support</a></li>
                <li class="nav-item"><a href="../auth/logout.php" class="nav-link">Logout</a></li>
            </ul>
        </div>
    </div>
</nav>

    <!-- Hero Section -->
    <section class="hero">
        <h1>Connecting You to Trusted Local Services</h1>
        <p>Plumbers, Electricians, Carpenters & more — just a tap away</p>
        <a href="dashboard.php" class="btn btn-lg">Get Started</a>
    </section>
 <!-- Top Services Carousel -->
<section class="section text-center bg-dark text-white">
    <div class="container">
        <h2 class="mb-4">Top Rated Services</h2>
        <div id="topServicesCarousel" class="carousel slide" data-bs-ride="carousel">
            <div class="carousel-inner">
                <div class="carousel-item active">
                    <h5>Electrician Services</h5>
                    <p>Trusted professionals for wiring, lighting, and repairs.</p>
                </div>
                <div class="carousel-item">
                    <h5>Plumbing Services</h5>
                    <p>Quick fixes, installations, and emergency plumbing support.</p>
                </div>
                <div class="carousel-item">
                    <h5>Home Cleaning</h5>
                    <p>Deep cleaning, sanitization & maintenance with professionals.</p>
                </div>
            </div>
            <button class="carousel-control-prev" type="button" data-bs-target="#topServicesCarousel" data-bs-slide="prev">
                <span class="carousel-control-prev-icon"></span>
            </button>
            <button class="carousel-control-next" type="button" data-bs-target="#topServicesCarousel" data-bs-slide="next">
                <span class="carousel-control-next-icon"></span>
            </button>
        </div>
    </div>
</section>

    <!-- About Section -->
    <section class="section text-center">
        <div class="container">
            <h2>About Us</h2>
            <p>Our mission is to bridge the gap between local vendors and customers through a seamless, reliable platform.</p>
        </div>
    </section>

     
<!-- card Section  -->
<section class="mn-section">
  <div class="mn-content">
    <div class="mn-info">
<p style="font-family: 'Poppins', sans-serif; font-size: 1rem; line-height: 1.6;  text-align: justify; margin-bottom: 20px;">
  Join us for a best near services movie night filled with popcorn, laughter, and great company!
  Whether you're a fan of thrilling action, heart‑warming dramas, or side‑splitting comedies,
  we've got a film line‑up to cater to all tastes. Save the date and bring your favourite
  snacks to make it a memorable evening.
</p>

<!-- Button with hover and transition -->
<button 
  style="
    padding: 10px 25px;
    font-family: 'Poppins', sans-serif;
    font-size: 1rem;
    font-weight: 600;
    background-color: #7a5fff;
    color: white;
    border: none;
    border-radius: 6px;
    cursor: pointer;
    transition: background-color 0.3s ease, transform 0.2s ease;
  "
  onmouseover="this.style.backgroundColor='#5c40d1'; this.style.transform='scale(1.05)'"
  onmouseout="this.style.backgroundColor='#7a5fff'; this.style.transform='scale(1)'"
  onclick="location.href='dashboard.php'"
>
  Join
</button>


    </div>

    <!-- Swiper container -->
    <div class="mn-swiper">
      <div class="swiper-wrapper">
        <!-- ❶ Slide -->
        <div class="swiper-slide">
          <img src="../image/work1.jpg" alt="">
          <div class="mn-overlay"><span style="  background-color: #7a5fff;">2200 rs.</span><h2>Home Deep Cleaning</h2></div>
        </div>
        <!-- ❷ Slide -->
        <div class="swiper-slide">
          <img class="mn-img-position" src="../image/electrician1.jpg" alt="">
          <div class="mn-overlay"><span style="  background-color: #7a5fff;">350 rs.</span><h2>Ceiling Fan Installation</h2></div>
        </div>
        <!-- … बाकी स्लाइड्स तशाच ठेवा … -->
      </div>
    </div>
  </div>

  <!-- Animated bubbles -->
  <ul class="mn-circles">
    <li></li><li></li><li></li><li></li><li></li>
    <li></li><li></li><li></li><li></li><li></li>
  </ul>
</section>


    
 <!-- Why Choose Us Section -->
    <section class="section text-center">
        <div class="container">
            <h2>Why Choose Us</h2>
            <div class="row g-4">
                <div class="col-md-4">
                    <div class="card p-4">
                        <i class="fas fa-check-circle"></i>
                        <h5 class="mt-3">Verified Vendors</h5>
                    </div>
                </div>
                <div class="col-md-4">
                    <div class="card p-4">
                        <i class="fas fa-lock"></i>
                        <h5 class="mt-3">Secure Payments</h5>
                    </div>
                </div>
                <div class="col-md-4">
                    <div class="card p-4">
                        <i class="fas fa-bolt"></i>
                        <h5 class="mt-3">Fast Booking</h5>
                    </div>
                </div>
            </div>
        </div>
    </section>
<button class="theme-toggle" onclick="toggleTheme()">Switch Theme</button>
    

    <!-- Footer Section -->
    <footer class="footer">
        <p>&copy; 2025 Local Service Hub | <a href="./users_area/user_registration.php">Register</a> | <a href="./users_area/user_login.php">Login</a></p>
        <div>
            <a href="#"><i class="fab fa-facebook mx-2"></i></a>
            <a href="#"><i class="fab fa-instagram mx-2"></i></a>
            <a href="#"><i class="fab fa-twitter mx-2"></i></a>
        </div>
    </footer>

    <script>
        function toggleTheme() {
            const html = document.documentElement;
            const current = html.getAttribute('data-theme');
            html.setAttribute('data-theme', current === 'dark' ? 'light' : 'dark');
        }
    </script>
    <script>
let counter = 0;
let slideInterval = setInterval(() => {
  const slides = document.querySelector('.slides');
  counter = (counter + 1) % 3;
  slides.style.transform = `translateX(-${counter * 100}%)`;
}, 5000);
</script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
    <!-- SwiperJS CDN (जर आधी include नसेल तर) -->
<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/swiper@11/swiper-bundle.min.css" />
<script src="https://cdn.jsdelivr.net/npm/swiper@11/swiper-bundle.min.js"></script>

<script>
document.addEventListener("DOMContentLoaded", () => {
  new Swiper(".mn-swiper", {
    effect: "cards",
    grabCursor: true,
    initialSlide: 2,
    speed: 500,
    loop: true,
    mousewheel: { invert: false },
    /* rotate:true आता Swiper v11 मध्ये cards.effect.rotate वापरा (optional) */
    cardsEffect: { rotate: true }
  });
});
</script>

</body>
</html>
