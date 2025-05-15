<?php
session_start();
require_once 'php/db_connect.php';

// Fetch reviews from the database (simplified query for debugging)
try {
    $stmt = $conn->query("SELECT * FROM reviews ORDER BY created_at DESC");
    $reviews = $stmt->fetchAll(PDO::FETCH_ASSOC);
    // Debug: Log the number of reviews fetched
    error_log("Fetched " . count($reviews) . " reviews from the database.");
    // Debug: Log the reviews array
    error_log("Reviews: " . print_r($reviews, true));
} catch (PDOException $e) {
    $reviews = [];
    error_log("Error fetching reviews: " . $e->getMessage());
    echo "<!-- Error fetching reviews: " . htmlspecialchars($e->getMessage()) . " -->";
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Utsav</title>
    <link rel="stylesheet" href="https://unpkg.com/swiper@11.2.6/swiper-bundle.min.css" />
    <link rel="stylesheet" href="css/style.css">
</head>
<body>
    
<header class="header">
  <div class="logo-wrapper">
    <a href="index.php" class="logo"><span>UTSAV</span></a>
    <div class="logo-subtitle"><span style="color:#eae2b7; align:center">CELEBRATE EVERY MOMENT</span></div>

  </div>

  <nav class="navbar">
    <a href="#home">home</a>
    <a href="#service">service</a>
    <a href="#about">about</a>
    <a href="#gallery">gallery</a>
    <a href="#price">price</a>
    <a href="#review">review</a>
    <a href="#contact">contact</a>
    <div class="auth-links">
      <?php if (isset($_SESSION['user_id'])): ?>
          <a href="php/logout.php">logout</a>
      <?php else: ?>
          <a href="php/login.php">login</a>
      <?php endif; ?>
    </div>
  </nav>

  <div id="menu-bars" class="fas fa-bars"></div>
</header>


<section class="home" id="home">

    <div class="content">
        <h3>its time to celebrate! the best <span> event organizers </span></h3>
        <a href="#" class="btn">contact us</a>
    </div>

    <div class="swiper-container home-slider">
        <div class="swiper-wrapper">
            <div class="swiper-slide"><img src="images/slider-1.png" alt=""></div>
            <div class="swiper-slide"><img src="images/slider-2.png" alt=""></div>
            <div class="swiper-slide"><img src="images/slider-3.png" alt=""></div>
            <div class="swiper-slide"><img src="images/slider-4.png" alt=""></div>
            <div class="swiper-slide"><img src="images/slider-5.png" alt=""></div>
        </div>
    </div>

</section>




<!-- service section starts  -->

<section class="service" id="service">

    <h1 class="heading"> our <span>services</span> </h1>

    <div class="box-container">

        <div class="box">
            <i class="fas fa-map-marker-alt"></i>
            <h3>venue selection</h3>
            <p>Choose from a curated list of top-rated venues based on your event type, guest size, and location preferences. Browse photos, check availability, and find the perfect space to make your event unforgettable..</p>
        </div>

        <div class="box">
            <i class="fas fa-envelope"></i>
            <h3>invitation card</h3>
            <p>Design elegant and personalized invitation cards that match your event theme. Choose from a variety of templates or upload your own design.</p>
        </div>

        <div class="box">
            <i class="fas fa-music"></i>
            <h3>entertainment</h3>
            <p>From live music to DJs and cultural performances, select entertainment options that will keep your guests engaged and energized throughout the event.</p>
        </div>

        <div class="box">
            <i class="fas fa-utensils"></i>
            <h3>food and drinks</h3>
            <p>Explore catering services offering a wide variety of cuisines, beverages, and dining styles—from buffets to plated meals—to suit your guests' tastes.</p>
        </div>

        <div class="box">
            <i class="fas fa-photo-video"></i>
            <h3>photos and videos</h3>
            <p>Hire professional photographers and videographers to capture every special moment. Relive your event through beautifully edited albums and highlight reels.

</p>
        </div>

        <div class="box">
            <i class="fas fa-birthday-cake"></i>
            <h3>custom food</h3>
            <p>Create a personalized menu that fits your theme, dietary preferences, or cultural tastes. Work with chefs to craft dishes that make your event unique and memorable.

</p>
        </div>

    </div>

</section>

<section class="about" id="about">
    <h1 class="heading"> <span>about</span> us </h1>
    <div class="row">
        <div class="image">
            <img src="images/aboutus.png" alt="">
        </div>
        <div class="content">
            <h3>we will give a very special celebration for you</h3>
            <p>Welcome to Utsav, where every celebration becomes a cherished memory. As a proud Indian event management company, we specialize in crafting unforgettable experiences—be it weddings, corporate gatherings, or festive functions. With a passion for perfection and deep cultural roots, our team handles everything from venue selection and custom invitations to entertainment, catering, and photography. At Utsav, we don’t just plan events we create moments that last a lifetime.

</p>
            <p>Utsav – Crafting unforgettable moments, one celebration at a time.</p>
            <a href="#contact" class="btn">contact us</a>
        </div>
    </div>
</section>

<section class="gallery" id="gallery">
    <h1 class="heading"> our <span>gallery</span> </h1>
    <div class="box-container">
        <div class="box">
            <img src="images/tra-1.png" alt="">
            <div class="title">Traditional wedding</div>
            <div class="icons">
                <a href="#" class="fas fa-eye"></a>
                <a href="#" class="fas fa-heart"></a>
                <a href="#" class="fas fa-share-alt"></a>
            </div>
        </div>
        <div class="box">
            <img src="images/gallery-2.png" alt="">
            <div class="title">Western wedding</div>
            <div class="icons">
                <a href="#" class="fas fa-eye"></a>
                <a href="#" class="fas fa-heart"></a>
                <a href="#" class="fas fa-share-alt"></a>
            </div>
        </div>
        <div class="box">
            <img src="images/gallery-3.png" alt="">
            <div class="title">Birthday party </div>
            <div class="icons">
                <a href="#" class="fas fa-eye"></a>
                <a href="#" class="fas fa-heart"></a>
                <a href="#" class="fas fa-share-alt"></a>
            </div>
        </div>
        <div class="box">
            <img src="images/gallery-4.png" alt="">
            <div class="title">Concert</div>
            <div class="icons">
                <a href="#" class="fas fa-eye"></a>
                <a href="#" class="fas fa-heart"></a>
                <a href="#" class="fas fa-share-alt"></a>
            </div>
        </div>
        <div class="box">
            <img src="images/gallery-5png.png" alt="">
            <div class="title">Corparate Event</div>
            <div class="icons">
                <a href="#" class="fas fa-eye"></a>
                <a href="#" class="fas fa-heart"></a>
                <a href="#" class="fas fa-share-alt"></a>
            </div>
        </div>
        <div class="box">
            <img src="images/gallery-6.png" alt="">
            <div class="title">Open Ground </div>
            <div class="icons">
                <a href="#" class="fas fa-eye"></a>
                <a href="#" class="fas fa-heart"></a>
                <a href="#" class="fas fa-share-alt"></a>
            </div>
        </div>
        <div class="box">
            <img src="images/gallery-7.png" alt="">
            <div class="title">college Fest</div>
            <div class="icons">
                <a href="#" class="fas fa-eye"></a>
                <a href="#" class="fas fa-heart"></a>
                <a href="#" class="fas fa-share-alt"></a>
            </div>
        </div>
        <div class="box">
            <img src="images/gallery-8.png" alt="">
            <div class="title">Cultural Fest</div>
            <div class="icons">
                <a href="#" class="fas fa-eye"></a>
                <a href="#" class="fas fa-heart"></a>
                <a href="#" class="fas fa-share-alt"></a>
            </div>
        </div>
    </div>
</section>

<section class="price" id="price">
    <h1 class="heading"> our <span>price</span> </h1>
    <div class="box-container">
        <div class="box">
            <h3 class="title">for birthdays</h3>
            <h3 class="amount">₹3,50,000</h3>
            <ul>
                <li><i class="fas fa-check"></i>Cake</li>
                <li><i class="fas fa-check"></i>decorations</li>
                <li><i class="fas fa-check"></i>music and photos</li>
                <li><i class="fas fa-check"></i>food and drinks</li>
                <li><i class="fas fa-check"></i>invitation card</li>
            </ul>
            <a href="php/book_event.php?event=Birthdays" class="btn open-booking" data-event="Birthdays">book now</a>
        </div>
        <div class="box">
            <h3 class="title">for weddings</h3>
            <h3 class="amount">₹10,50,000</h3>
            <ul>
                <li><i class="fas fa-check"></i>full services</li>
                <li><i class="fas fa-check"></i>decorations</li>
                <li><i class="fas fa-check"></i>music and photos</li>
                <li><i class="fas fa-check"></i>food and drinks</li>
                <li><i class="fas fa-check"></i>invitation card</li>
            </ul>
            <a href="php/book_event.php?event=Weddings" class="btn open-booking" data-event="Weddings">book now</a>
        </div>
        <div class="box">
            <h3 class="title">for concerts</h3>
            <h3 class="amount">₹40,50,000</h3>
            <ul>
                <li><i class="fas fa-check"></i>artist(selected)</li>
                <li><i class="fas fa-check"></i>Sounds and light</li>
                <li><i class="fas fa-check"></i>music and photos</li>
                <li><i class="fas fa-check"></i>food and drinks</li>
                <li><i class="fas fa-check"></i>Security</li>
            </ul>
            <a href="php/book_event.php?event=Concerts" class="btn open-booking" data-event="Concerts">book now</a>
        </div>
        <div class="box">
            <h3 class="title">for others</h3>
            <h3 class="amount">₹15,50,000</h3>
            <ul>
                <li><i class="fas fa-check"></i>Event type</li>
                <li><i class="fas fa-check"></i>decorations</li>
                <li><i class="fas fa-check"></i>music and photos</li>
                <li><i class="fas fa-check"></i>food and drinks</li>
                <li><i class="fas fa-check"></i>invitation card</li>
            </ul>
            <a href="php/book_event.php?event=Others" class="btn open-booking" data-event="Others">book now</a>
        </div>
    </div>
</section>



<!-- Review Submission Section -->
    <section class="review-submit" id="review-submit">
        <h1 class="heading"> submit your <span>review</span> </h1>
        <?php if (isset($_SESSION['user_id'])): ?>
            <form action="php/submit_review.php" method="POST">
                <div class="input-group">
                    <label>First Name</label>
                    <input type="text" name="name" placeholder="Enter your first name" required>
                    <input type="hidden" name="user_id" value="<?php echo $_SESSION['user_id']; ?>">
                </div>
                <div class="input-group">
                    <label>Subject</label>
                    <input type="text" name="subject" placeholder="Enter the subject of your review" required>
                </div>
                <div class="input-group">
                    <label>Description</label>
                    <textarea name="description" placeholder="Write your review here" cols="30" rows="5" required></textarea>
                </div>
                <input type="submit" value="submit review" class="btn">
            </form>
        <?php else: ?>
            <p style="text-align: center; color: #fff;">Please <a href="php/login.php" style="color: #f5c518;">login</a> to submit a review.</p>
        <?php endif; ?>
    </section>

    <!-- Review Display Section -->
    <section class="review" id="review">
        <h1 class="heading"> client's <span>review</span> </h1>
        <!-- Debug: Output the reviews array -->
        <div style="display: none;">
            <?php echo "Debug Reviews: " . print_r($reviews, true); ?>
        </div>
        <div class="swiper review-slider">
            <div class="swiper-wrapper">
                <!-- Display user-submitted reviews -->
                <?php if (empty($reviews)): ?>
                    <div class="swiper-slide box">
                        <p style="text-align: center; color: #fff;">No reviews submitted yet.</p>
                    </div>
                <?php else: ?>
                    <?php foreach ($reviews as $index => $review): ?>
                        <div class="swiper-slide box">
                            <i class="fas fa-quote-right"></i>
                            <div class="user">
                                <img src="images/pic-<?php echo ($index % 4) + 1; ?>.png" alt="">
                                <div class="user-info">
                                    <h3><?php echo htmlspecialchars($review['name']); ?></h3>
                                    <span>happy clients</span>
                                </div>
                            </div>
                            <p><strong><?php echo htmlspecialchars($review['subject']); ?>:</strong> <?php echo htmlspecialchars($review['description']); ?></p>
                        </div>
                    <?php endforeach; ?>
                <?php endif; ?>
            </div>
            <div class="swiper-pagination"></div>
        </div>
    </section>
<!-- Contact Section -->
<!-- Contact Section -->
    <section class="contact" id="contact">
        <h1 class="heading"> <span>contact</span> us </h1>
        <form action="php/submit_contact.php" method="POST">
            <div class="inputBox">
                <input type="text" name="name" placeholder="name" required>
                <input type="email" name="email" placeholder="email" required>
            </div>
            <div class="inputBox">
                <input type="tel" name="phone" placeholder="number" required>
                <input type="text" name="subject" placeholder="subject" required>
            </div>
            <textarea name="message" placeholder="message" cols="30" rows="10" required></textarea>
            <input type="submit" value="send message" class="btn">
        </form>
    </section>

<section class="footer">
    <div class="box-container">
        <div class="box">
            <h3>branches</h3>
            <a href="#"> <i class="fas fa-map-marker-alt"></i> Udupi(head Office) </a>
            <a href="#"> <i class="fas fa-map-marker-alt"></i> Mangalore </a>
            <a href="#"> <i class="fas fa-map-marker-alt"></i> bangalore </a>
            <a href="#"> <i class="fas fa-map-marker-alt"></i> navi mumbai </a>
            <a href="#"> <i class="fas fa-map-marker-alt"></i> Kochi </a>
        </div>
        <div class="box">
            <h3>quick links</h3>
            <a href="#home"> <i class="fas fa-arrow-right"></i> home </a>
            <a href="#service"> <i class="fas fa-arrow-right"></i> service </a>
            <a href="#about"> <i class="fas fa-arrow-right"></i> about </a>
            <a href="#gallery"> <i class="fas fa-arrow-right"></i> gallery </a>
            <a href="#price"> <i class="fas fa-arrow-right"></i> price </a>
            <a href="#review"> <i class="fas fa-arrow-right"></i> review </a>
            <a href="#contact"> <i class="fas fa-arrow-right"></i> contact </a>
        </div>
        <div class="box">
            <h3>contact info</h3>
            <a href="#"> <i class="fas fa-phone"></i> 8970931564 </a>
            <a href="#"> <i class="fas fa-phone"></i> +98970563 </a>
            <a href="#"> <i class="fas fa-envelope"></i> utsavevent@example.com </a>
            <a href="#"> <i class="fas fa-envelope"></i> adminutsavacontact@example.com </a>
            <a href="#"> <i class="fas fa-map-marker-alt"></i> Udupi, Karnatak,india - 5741115 </a>
        </div>
        <div class="box">
            <h3>follow us</h3>
            <a href="#footer"> <i class="fab fa-facebook-f"></i> facebook </a>
            <a href="#footer"> <i class="fab fa-twitter"></i> twitter </a>
            <a href="#footer"> <i class="fab fa-instagram"></i> instagram </a>
            <a href="#footer"> <i class="fab fa-linkedin"></i> linkedin </a>
        </div>
    </div>
    <div class="credit"> created by <span>Utsav_Event</span> |2025@all rights reserved </div>
</section>

<!-- Add this part inside your index.php file, just before the closing </body> tag -->



<script src="https://unpkg.com/swiper@11.2.6/swiper-bundle.min.js"></script>

<!-- custom js file link  -->
<script src="js/script.js"></script>


</body>
</html>