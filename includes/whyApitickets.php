<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Why ApiTickets</title>
  <script src="https://kit.fontawesome.com/a076d05399.js"></script>
  <style>
    * {
      margin: 0;
      padding: 0;
      box-sizing: border-box;
    }

    body {
      font-family: 'Arial', sans-serif;
      background-color: #f8f9fa;
    }

    #why-apitickets {
      padding: 40px 10px;
      text-align: center;
      color: #333;
    }

    .container {
      width: 90%;
      max-width: 1200px;
      margin: 0 auto;
    }

    h2 {
      font-size: 28px;
      margin-bottom: 25px;
      font-weight: bold;
    }

    .carousel-container {
      position: relative;
      overflow: hidden;
      width: 100%;
    }

    .reasons-wrapper {
      display: flex;
      gap: 20px;
      transition: transform 0.6s ease-in-out;
    }

    .reason {
      background: linear-gradient(135deg, #0056b3, #004099);
      color: #fff;
      padding: 25px;
      border-radius: 15px;
      box-shadow: 0 6px 12px rgba(0, 0, 0, 0.2);
      text-align: center;
      transition: transform 0.4s ease-in-out;
      flex: 1;
      min-width: 250px;
      max-width: 300px;
    }

    .reason:hover {
      transform: scale(1.02);
    }

    .reason .icon {
      font-size: 45px;
      margin-bottom: 10px;
    }

    .reason h3 {
      font-size: 20px;
      margin-bottom: 10px;
      font-weight: bold;
    }

    .reason p {
      font-size: 14px;
      margin-bottom: 10px;
    }

    /* Mobile View */
    @media screen and (max-width: 768px) {
      .reasons-wrapper {
        flex-wrap: nowrap;
        overflow-x: auto;
        scroll-snap-type: x mandatory;
        -webkit-overflow-scrolling: touch;
      }

      .reason {
        min-width: 90%;
        scroll-snap-align: center;
      }
    }

    /* Tablet View */
    @media screen and (min-width: 769px) and (max-width: 1024px) {
      .reasons-wrapper {
        display: flex;
        overflow-x: auto;
        scroll-snap-type: x mandatory;
        -webkit-overflow-scrolling: touch;
        justify-content: flex-start;
      }
      .reason {
        min-width: 48%; /* Show two templates at once */
        scroll-snap-align: center;
      }
    }

    /* Desktop View */
    @media screen and (min-width: 1025px) {
      .reasons-wrapper {
        display: flex;
        overflow-x: auto;
        scroll-snap-type: x mandatory;
        -webkit-overflow-scrolling: touch;
        justify-content: flex-start;
      }
      .reason {
        min-width: 32%; /* Show three templates at once */
        scroll-snap-align: center;
      }
    }
  </style>
</head>
<body>

  <section id="why-apitickets">
    <div class="container">
      <h2>Why Choose ApiTickets?</h2>
      <div class="carousel-container">
        <div class="reasons-wrapper">
          <div class="reason">
            <div class="icon">
              <i class="fas fa-ticket-alt"></i>
            </div>
            <h3>Easy Booking</h3>
            <p>Book your tickets quickly with just a few clicks.</p>
          </div>
          <div class="reason">
            <div class="icon">
              <i class="fas fa-thumbs-up"></i>
            </div>
            <h3>Reliable Service</h3>
            <p>24/7 customer support ensures hassle-free experience.</p>
          </div>
          <div class="reason">
            <div class="icon">
              <i class="fas fa-credit-card"></i>
            </div>
            <h3>Flexible Payments</h3>
            <p>Supports eSewa and Pay Later options.</p>
          </div>
          <div class="reason">
            <div class="icon">
              <i class="fas fa-dollar-sign"></i>
            </div>
            <h3>Affordable Prices</h3>
            <p>Get the best deals and discounts on your ticket bookings.</p>
          </div>
          <div class="reason">
            <div class="icon">
              <i class="fas fa-clock"></i>
            </div>
            <h3>Time Saving</h3>
            <p>Skip the long queues and book tickets in seconds.</p>
          </div>
        </div>
      </div>
    </div>
  </section>

</body>
</html>
