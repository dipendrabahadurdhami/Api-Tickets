<!-- Footer Section -->
<footer id="ticketsea-footer" class="footer">
    <div class="footer-container">
        <div class="footer-row">
            <!-- Left Section: Developer Details -->
            <div class="footer-column">
                <p class="developer-text">Developer</p>
                <a href="https://www.facebook.com/deepcan34" target="_blank">
                    <img src="../dev.png" alt="Developer Logo" class="developer-logo">
                </a>
            </div>

            <!-- Center Section: About Us -->
            <div class="footer-column">
                <h5>About Us</h5>
                <p class="about-text">Welcome to ApiTickets - Your one-stop solution for online cab bookings. We provide seamless and secure ticketing services.</p>
            </div>

            <!-- Right Section: Connect With Us (Social Links) -->
            <div class="footer-column">
                <h5>Connect With Us</h5>
                <div class="social-links">
                    <a href="https://facebook.com" class="social-icon" target="_blank"><i class="fab fa-facebook-f"></i></a>
                    <a href="https://twitter.com" class="social-icon" target="_blank"><i class="fab fa-twitter"></i></a>
                    <a href="https://linkedin.com" class="social-icon" target="_blank"><i class="fab fa-linkedin-in"></i></a>
                    <a href="https://instagram" class="social-icon" target="_blank"><i class="fab fa-instagram"></i></a>
                </div>
            </div>
        </div>

        <!-- Copyright Info -->
        <div class="footer-row copyright-row">
            <p class="copyright-text">&copy; 2024 ApiTickets. All rights reserved.</p>
        </div>
    </div>
</footer>

<!-- External CSS for Social Icons -->
<link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.4/css/all.min.css" rel="stylesheet">

<style>
    #ticketsea-footer {
    background-color: #2c3e50;
    color: white;
    padding: 20px 0px;
    font-family: Arial, sans-serif;
    
    bottom: 0;
    left: 0;
    width: 100%;
    z-index: 10;
}

.footer-container {
    max-width: 1200px;
    margin: 0 auto;
    display: flex;
    flex-direction: column;
    align-items: center;
}

.footer-row {
    display: flex;
    flex-wrap: wrap;
    justify-content: space-between;
    width: 100%;
    margin-bottom: 20px;
}

.footer-column {
    flex: 1;
    min-width: 250px;
    max-width: 300px;
    text-align: center;
    margin: 10px;
}

.developer-logo {
    width: 55px;
    height: 55px;
    border-radius: 50%;
    margin-top: -0px;
}

.developer-text {
    margin: auto;
    font-size: 17px;
    color: #f8f9fa;
}

h5 {
    font-size: 18px;
    margin-bottom: 10px;
    color: #f1f1f1;
}

.about-text {
    font-size: 14px;
    color: #dcdcdc;
}

.social-links {
    margin-top: 10px;
}

.social-icon {
    display: inline-block;
    margin: 5px;
    font-size: 20px;
    background-color: #495057;
    padding: 10px;
    border-radius: -38%;
    color: white;
    transition: background-color 0.3s, transform 0.3s;
}

.social-icon:hover {
    background-color: #6c757d;
    transform: scale(1.1);
}

.copyright-row {
    justify-content: center;
    border-top: 1px solid #495057;
    padding-top: 10px;
    width: 100%;
}

.copyright-text {
    font-size: 14px;
    color: #aaa;
}

/* Mobile Responsiveness */
@media (max-width: 768px) {
    .footer-row {
        flex-direction: column;
        align-items: center;
    }

    .footer-column {
        max-width: 100%;
    }

    .social-icon {
        font-size: 18px;
        padding: 8px;
    }

    .developer-logo {
        width: 50px;
        height: 50px;
    }
}

@media (max-width: 576px) {
    h5 {
        font-size: 16px;
    }

    .social-icon {
        font-size: 16px;
        padding: 6px;
    }

    .developer-logo {
        width: 40px;
        height: 40px;
    }

    .copyright-text {
        font-size: 12px;
    }
}


</style>




