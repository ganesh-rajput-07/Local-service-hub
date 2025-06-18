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

<div class="container mt-4">
    <h2 class="mb-4">Service Details</h2>

    <div class="row mb-4 align-items-start">
        <div class="col-md-6">
            <div id="serviceCarousel" class="carousel slide" data-bs-ride="carousel">
                <div class="carousel-inner">
                    <?php foreach ($images as $index => $img) { ?>
                        <div class="carousel-item <?php if ($index == 0) echo 'active'; ?> text-center">
                            <div class="zoom-container">
                                <img src="../uploads/<?php echo $img; ?>" class="img-fluid zoom-image" style="max-height: 400px; object-fit: contain;">
                            </div>
                        </div>
                    <?php } ?>
                </div>

                <?php if (count($images) > 1) { ?>
                    <button class="carousel-control-prev" type="button" data-bs-target="#serviceCarousel" data-bs-slide="prev">
                        <span class="carousel-control-prev-icon" aria-hidden="true"></span>
                    </button>
                    <button class="carousel-control-next" type="button" data-bs-target="#serviceCarousel" data-bs-slide="next">
                        <span class="carousel-control-next-icon" aria-hidden="true"></span>
                    </button>
                <?php } ?>
            </div>
        </div>

        <div class="col-md-6">
            <h4><?php echo $service['title']; ?></h4>
            <span class="badge bg-primary mb-2"><?php echo $service['category_name']; ?></span>
            <p><strong>Description:</strong> <?php echo $service['description']; ?></p>
            <p><strong>Price:</strong> ₹<?php echo $service['price']; ?></p>
            <p><strong>Keywords:</strong> <?php echo $service['keywords']; ?></p>
            <p><strong>Average Rating:</strong> <?php echo $service['average_rating']; ?> ⭐</p>

            <h5 class="mt-4">Vendor</h5>
            <div class="d-flex align-items-center mb-3">
                <img src="../uploads/<?php echo $service['profile_image']; ?>" class="rounded-circle me-3" style="width: 60px; height: 60px; object-fit: cover;">
                <div>
                    <p class="mb-0 fw-bold"><?php echo $service['vendor_name']; ?> (@<?php echo $service['username']; ?>)</p>
                    <a href="vendor_profile.php?vendor_id=<?php echo $service['vendor_id']; ?>" class="btn btn-sm btn-outline-primary mt-1">View Vendor</a>
                </div>
            </div>

            <p><strong>Vendor Average Rating:</strong> <?php echo $vendor_avg_rating ? $vendor_avg_rating : 'Not Rated'; ?> ⭐</p>

            <form method="POST" action="add_to_cart.php" style="display: inline;">
    <input type="hidden" name="service_id" value="<?php echo $service['id']; ?>">
    <button type="submit" class="btn btn-success">Book Now</button>
</form>

        </div>
    </div>

    <!-- Reviews Section with Pagination -->
    <h4 class="mb-4">Customer Reviews</h4>
    <?php
    $reviews_per_page = 5;
    $page = isset($_GET['page']) ? $_GET['page'] : 1;
    $offset = ($page - 1) * $reviews_per_page;

    $review_query = "SELECT sr.*, u.name FROM service_ratings sr JOIN users u ON sr.user_id = u.id WHERE sr.service_id='$service_id' ORDER BY sr.created_at DESC LIMIT $offset, $reviews_per_page";
    $reviews = $conn->query($review_query);

    $total_reviews = $conn->query("SELECT COUNT(*) AS total FROM service_ratings WHERE service_id='$service_id'")->fetch_assoc()['total'];
    $total_pages = ceil($total_reviews / $reviews_per_page);

    if ($reviews->num_rows > 0) {
        while ($review = $reviews->fetch_assoc()) {
            $stars = str_repeat('⭐', $review['rating']);
            echo "<div class='card p-3 mb-3'>";
            echo "<strong>" . $review['name'] . "</strong> rated it: " . $stars;
            echo "<p class='mt-2'>" . $review['review'] . "</p>";
            echo "</div>";
        }

        // Pagination Buttons
        echo "<div class='mt-4'>";
        for ($i = 1; $i <= $total_pages; $i++) {
            echo "<a href='service_detail.php?id=$service_id&page=$i' class='btn btn-sm " . ($i == $page ? 'btn-primary' : 'btn-outline-primary') . " me-1'>$i</a>";
        }
        echo "</div>";
    } else {
        echo "<p>No reviews yet. Be the first to rate!</p>";
    }
    ?>

    <!-- Rating Form -->
    <h4 class="mt-4">Rate this Service</h4>
    <div id="rating-message"></div>

    <?php
    $check_rating = $conn->query("SELECT * FROM service_ratings WHERE service_id='$service_id' AND user_id='$user_id'");

    if ($check_rating->num_rows > 0) {
        echo "<div class='alert alert-info'>You have already rated this service.</div>";
    } else { ?>
        <form id="ratingForm" class="mb-4">
            <input type="hidden" name="service_id" value="<?php echo $service_id; ?>">
            <div class="mb-3">
                <label>Your Rating:</label>
                <div class="star-rating mb-3">
                    <input type="hidden" name="rating" id="ratingInput" required>
                    <span class="star" data-value="1">&#9734;</span>
                    <span class="star" data-value="2">&#9734;</span>
                    <span class="star" data-value="3">&#9734;</span>
                    <span class="star" data-value="4">&#9734;</span>
                    <span class="star" data-value="5">&#9734;</span>
                </div>
            </div>
            <div class="mb-3">
                <label>Your Review:</label>
                <textarea name="review" class="form-control" rows="3"></textarea>
            </div>
            <button type="submit" class="btn btn-success">Submit Rating</button>
        </form>
    <?php } ?>

    <a href="dashboard.php" class="btn btn-secondary mt-4">Back to Dashboard</a>
</div>

<style>
    .star {
        font-size: 2rem;
        cursor: pointer;
        color: #ccc;
    }
    .star.selected {
        color: gold;
    }
</style>


<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
<script>
    // AJAX Rating Submit
    document.getElementById('ratingForm')?.addEventListener('submit', function (e) {
        e.preventDefault();
        var formData = new FormData(this);

        fetch('submit_rating.php', {
            method: 'POST',
            body: formData
        })
        .then(response => response.json())
        .then(data => {
            if (data.status == 'success') {
                document.getElementById('rating-message').innerHTML = `<div class='alert alert-success'>${data.message}</div>`;
                setTimeout(() => { window.location.reload(); }, 1000);
            } else {
                document.getElementById('rating-message').innerHTML = `<div class='alert alert-danger'>${data.message}</div>`;
            }
        });
    });

    // Star Selection Logic
    const stars = document.querySelectorAll('.star');
    const ratingInput = document.getElementById('ratingInput');

    stars.forEach(star => {
        star.addEventListener('click', () => {
            const value = star.getAttribute('data-value');
            ratingInput.value = value;

            stars.forEach(s => s.classList.remove('selected'));
            for (let i = 0; i < value; i++) {
                stars[i].classList.add('selected');
            }
        });
    });
</script>

<script>
    // Direct inline styling setup
    const stars = document.querySelectorAll('.star');
    const ratingInput = document.getElementById('ratingInput');

    // Set default style
    stars.forEach(star => {
        star.style.fontSize = '2rem';
        star.style.cursor = 'pointer';
        star.style.color = '#ccc'; // Empty star color
    });

    stars.forEach(star => {
        star.addEventListener('click', () => {
            const value = parseInt(star.getAttribute('data-value'));
            ratingInput.value = value;

            // Reset all stars
            stars.forEach(s => {
                s.innerHTML = '&#9734;'; // Empty star symbol
                s.style.color = '#ccc'; // Empty star color
            });

            // Fill selected stars
            for (let i = 0; i < value; i++) {
                stars[i].innerHTML = '&#9733;'; // Filled star symbol
                stars[i].style.color = 'gold'; // Filled star color
            }
        });
    });
</script>
