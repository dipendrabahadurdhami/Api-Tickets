<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>About Us | ApiTickets</title>
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
      color: #333;
    }

    /* About Us Section */
    #about-us {
      
      color: #260161;
      padding: 60px 10px;
      text-align: center;
    }

    #about-us .about-container {
      width: 90%;
      max-width: 1200px;
      margin: 0 auto;
    }

    #about-us h2 {
      font-size: 36px;
      font-weight: bold;
      margin-bottom: 20px;
    }

    #about-us p {
      font-size: 18px;
      line-height: 1.6;
      margin-bottom: 20px;
    }

    #about-us .about-image {
      max-width: 100%;
      height: auto;
      border-radius: 10px;
    }

    #about-us .team {
      display: flex;
      justify-content: space-between;
      gap: 20px;
      flex-wrap: wrap;
      margin-top: 40px;
    }

    #about-us .team-member {
      background: linear-gradient(135deg, #0056b3, #004099);
      border-radius: 10px;
      box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
      width: 100%;
      max-width: 300px;
      text-align: center;
      padding: 20px;
    }

    #about-us .team-member img {
      width: 80px;
      height: 80px;
      border-radius: 50%;
      margin-bottom: 15px;
    }

    #about-us .team-member h3 {
      font-size: 22px;
      margin-bottom: 10px;
    }

    #about-us .team-member p {
      font-size: 16px;
      color: #666;
    }

    /* Mobile Layout */
    @media screen and (max-width: 768px) {
      #about-us h2 {
        font-size: 28px;
      }

      #about-us .team {
        flex-direction: column;
        align-items: center;
      }

      #about-us .team-member {
        margin-bottom: 20px;
        width: 80%;
      }
    }
  </style>
</head>
<body>

  <!-- About Us Section -->
  <section id="about-us">
    <div class="about-container">
      <h2>About Us</h2>
      <p>ApiTickets is a leading platform for easy and quick ticket bookings. Our mission is to provide users with the most reliable and affordable ticketing service while ensuring a smooth and hassle-free experience across all devices.</p>
      <img src="logo123.png" alt="ApiTickets Team" class="about-image" style="max-width: 350px; height: auto; display: block; margin: auto;">
        </div>
      </div>
    </div>
  </section>

</body>
</html>
