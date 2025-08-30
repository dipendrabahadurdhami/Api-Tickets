<?php include('includes/header.php'); ?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>ApiTickets</title>
    <!-- Bootstrap CSS -->
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css">
    <style>
        body {
            background-color: white;
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
            margin: 0;
        }

        /* Full-Screen Responsive Image */
        .image-container {
            position: relative;
            width: 100%;
            height: 65vh;
            background: url('image3.png') no-repeat center center;
            background-size: cover;
            background-position: center center; 
        }

        /* Adjust for Tablets */
        @media (max-width: 992px) {
            .image-container {
                height: 46vh; 
                background-size: cover;
                background-position: center center;
            }
        }

        /* Adjust for Mobile */
        @media (max-width: 768px) { 
            .image-container {
                height: 30vh; 
                background-size: cover;
                background-position: center center;
            }
        }

        /* Book Now Button */
        .book-now-btn {
            position: absolute;
            bottom: 10%; 
            left: 50%;
            transform: translateX(-50%); 
            padding: 15px 40px;
            background-color: #0078d4; 
            color: white;
            font-size: 1.2rem;
            font-weight: bold;
            border: none;
            border-radius: 5px;
            transition: background-color 0.3s ease;
            text-decoration: none;
        }

        /* Hover Effect for Button */
        .book-now-btn:hover {
            background-color: #005a9e; 
            text-decoration: none;
        }

     
        @media (max-width: 992px) { 
            .book-now-btn {
                padding: 12px 30px;
                font-size: 1rem;
            }
        }

        @media (max-width: 768px) { /* Mobile */
            .book-now-btn {
                padding: 10px 25px;
                font-size: 0.9rem;
            }
        }

     
        .content-section {
            padding-top: 20px;
            padding-bottom: 50px;
        }
    </style>
</head>

<body>

 
    <div class="image-container">
        <!-- Book Now Button -->
        <a href="#booking-form" class="book-now-btn">
            Book Now
        </a>
    </div>

    <!-- Content Section -->
    <div class="content-section">
        <?php include('includes/whyApitickets.php'); ?>
        <!-- Booking Form Section -->
        <div id="booking-form">
            <?php include('booking/book.php'); ?>
        </div>
        <?php include('includes/aboutus.php'); ?>
        
        
    </div>
    

    <!-- Footer Section -->
    <?php include('includes/footer.php'); ?>

    <!-- Bootstrap JS -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
</body>

</html>
