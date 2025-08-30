<?php
ob_start(); // Start output buffering
session_start();
?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>ApiTickets</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css">
    <style>
        html,
        body {

            overflow-x: hidden;
            margin-top: 40px;
            padding: 0;
            font-family: Arial, sans-serif;
        }

        .headers {
            display: flex;
            align-items: center;
            justify-content: space-between;
            width: auto;
            color: white;

            position: fixed;
            top: 0;
            left:0;
            width: 100%;
            z-index: 1000;
        }

        /* Navbar Styles */
        .navbar {
            display: flex;
            align-items: center;
            justify-content: space-between;
            background-color: #070768;
            color: white;
            padding: 10px 20px;
            position: fixed;
            gap: 8px;
            top: 0;
            left: 0;
            width: 100%;
            z-index: 1000;
        }

        .hamburger-menu {
           
            text-decoration: none;
            cursor: pointer;
            font-size: 20px;
            color: white;
            display: none;
        }

        .logo {
            margin-left: 10%;
          
            font-size: 30px;
            font-weight: bold;
        }

        .nav-links {
    display: flex;  /* Apply flexbox layout */
    justify-content: center;  /* Centers the links */
    gap: 5px;  /* Spacing between the links */
    width: 60%;  /* Ensures it takes the full width of the container */
    
}

.nav-links a {
    color: #e6f9ff;
    text-decoration: none;
    font-size: 20px;
    width: 120px;
    display: inline-flex;  /* Use inline-flex for individual link flexibility */
    align-items: center;
    justify-content: center;  /* Centers the content inside each link */
    height: 50px;
    border-radius: 5px;
    transition: transform 0.3s ease, color 0.3s ease;
}

.nav-links a:hover {
    font-weight: bold;
    transform: scale(1.1);
    color: white;
}


        .user-logo {
    font-size: 38px;
    margin-right: 10%;
    width: 50px;
    height: 50px;
    background-color: #0078D7;
    border-radius: 50%;
    display: flex;
    align-items: center;
    justify-content: center;
    color: white;
    font-weight: bold;
    cursor: pointer;
    position: relative;
}
.nav-links a.active {
    font-weight: bold;
    color: white; /* Change the color when active */
    transform: scale(1.1); /* Slightly increase the size */
    transition: transform 0.3s ease, color 0.3s ease, text-shadow 0.3s ease; /* Add smooth text-shadow transition */
    border-bottom: 3px solid white; /* Add a subtle underline effect */
    padding-bottom: 5px; /* Adjust the padding to give space for the underline */
}


        .user-menu {
            display: none;
            position: absolute;
            top: 60px;
            right: 20px;
            background-color: white;
            color: black;
            border-radius: 5px;
            box-shadow: 0 2px 5px rgba(0, 0, 0, 0.2);
            z-index: 10;
        }

        .user-menu a {
            display: block;
            padding: 10px 20px;
            text-decoration: none;
            color: black;
        }

        .user-menu a:hover {
            background-color: #0078D7;
            color: white;
        }

      /* Sidebar Styles */
.sidebar {
    position: fixed;
    top: 0;
    left: -350px;
    width: 250px;
    height: 100%;
    background-color: #070768;
    color: white;
    display: flex;
    flex-direction: column;
    gap: 20px;
    padding: 20px;
    box-shadow: 2px 0 5px rgba(0, 0, 0, 0.2);
    transition: left 0.3s ease;
    z-index: 999;
}

.sidebar.open {
    left: 0;
}

.sidebar a {
    color: white;
    text-decoration: none;
    font-size: 16px;
    display: flex;
    align-items: center;
    gap: 8px;
    padding: 10px 0;
    transition: color 0.3s ease, transform 0.3s ease, text-shadow 0.3s ease;
}

.sidebar a.active {
    font-weight: bold;
    color: #0078d4; /* Active color */
    transform: scale(1.1); /* Slightly increase size */
    text-shadow: 0 0 5px rgba(0, 120, 212, 0.6); /* Glowing effect */
    border-bottom: 3px solid white; /* Add underline effect */
    padding-bottom: 10px; /* Ensure padding fits underline */
}

.sidebar a:hover {
    color: white;
    text-shadow: 0 0 5px rgba(0, 120, 212, 0.6); /* Glow on hover */
    transform: scale(1.05); /* Slightly increase size on hover */
}

/* Active link hover effect */
.sidebar a.active:hover {
    color: white; /* Darken color when hovered */
    transform: scale(1.15); /* Slightly enlarge when hovered for emphasis */
}


        /* Overlay Styles */
        .overlay {
            display: none;
            position: fixed;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            background: rgba(0, 0, 0, 0.5);
            z-index: 998;
        }

        .overlay.active {
            display: block;
        }

        /* Responsive Design */
        @media (max-width: 1038px) {
            .hamburger-menu {
                margin-left: 10px;
                display: block;
                font-size: 25px;
            }

            .logo {
                flex: 1;
                text-align: center;
            }
             .user-logo {
                  margin-right: 10px;
                  width: 40px;
                  height: 40px;
             }

            .nav-links {
                display: none;
            }

            .sidebar {
                width: 250px;
            }
            .logo {
        
        margin-left: 0px; /* Adjust margin */

            .nav-links a {
                font-size: 14px;
            }
        }
         @media (max-width: 1024px) { /* Tablet View */
    .logo {
        font-size: 22px; /* Reduce font size */
       
    }

    .user-logo {
        font-size: 22px;
        width: 35px;
        height: 35px;
        margin-right: 10px; /* Adjust margin */
    }

    .nav-links {
       
        gap: 4px; /* Reduce spacing */
    }

    .nav-links a {
        font-size: 15px; /* Reduce font size */
        height: 45px; /* Adjust height */
    }
        
        @media (max-width: 800px) { /* Tablet View */
    .logo {
        font-size: 28px; /* Reduce font size */
        
    }

    .user-logo {
        font-size: 25px;
        width: 35px;
        height: 35px;
        margin-right: 10px; /* Adjust margin */
    }

    .nav-links {
       
        gap: 4px; /* Reduce spacing */
    }

    .nav-links a {
        font-size: 18px; /* Reduce font size */
        height: 45px; /* Adjust height */
    }
}
    </style>
</head>

<body>
    <div class="headers">
        <div class="navbar">
    <div class="hamburger-menu" id="hamburger"><i class="fas fa-bars"></i></div>
  <div class="logo" id="logo">
    <a href="../index.php" style="text-decoration: none; color: inherit;">ApiTickets</a>
</div>

    
    <div class="nav-links" id="nav-links">
        <a href="../index.php" class="<?= (basename($_SERVER['PHP_SELF']) == 'index.php') ? 'active' : ''; ?>"><i class="fas fa-home"></i> Home</a>
        
        <a href="#" class="<?= (basename($_SERVER['PHP_SELF']) == 'contact.php') ? 'active' : ''; ?>"><i class="fas fa-phone"></i> Contact</a>
        
        <?php if (isset($_SESSION['user_role']) && $_SESSION['user_role'] == 'admin'): ?>
            <a href="../admin/user.php" class="<?= (basename($_SERVER['PHP_SELF']) == 'user.php') ? 'active' : ''; ?>"><i class="fas fa-users"></i> All Users</a>
        <?php endif; ?>
        
        <?php if (isset($_SESSION['user_role']) && ($_SESSION['user_role'] == 'staff' || $_SESSION['user_role'] == 'admin')): ?>
            <a href="../bus/view_buses.php" class="<?= (basename($_SERVER['PHP_SELF']) == 'view_buses.php') ? 'active' : ''; ?>"><i class="fas fa-tachometer-alt"></i> Buses</a>
        <?php endif; ?>
        
        <a href="../users/your_booking.php" class="<?= (basename($_SERVER['PHP_SELF']) == 'your_booking.php') ? 'active' : ''; ?>"><i class="fa fa-history"></i> History</a>
        
        <a href="../notifications/notificationss.php" class="<?= (basename($_SERVER['PHP_SELF']) == 'notificationss.php') ? 'active' : ''; ?>"><i class="fas fa-bell"></i> Notify</a>

        <?php if (isset($_SESSION['user_role']) && $_SESSION['user_role'] == 'staff'): ?>
            <a href="../bus/add_bus.php" class="<?= (basename($_SERVER['PHP_SELF']) == 'add_bus.php') ? 'active' : ''; ?>"><i class="fas fa-bus"></i> Add Bus</a>
        <?php endif; ?>
    </div>

    <div class="user-logo" id="user-logo">
        <?php echo isset($_SESSION['user_name']) ? strtoupper($_SESSION['user_name'][0]) : "?"; ?>
    </div>

    <div class="user-menu" id="user-menu">
        <?php if (isset($_SESSION['user_name'])): ?>
            <a href="../users/profile.php" class="<?= (basename($_SERVER['PHP_SELF']) == 'profile.php') ? 'active' : ''; ?>"><i class="fas fa-user"></i> Profile</a>
            <a href="../users/logout.php"><i class="fas fa-sign-out-alt"></i> Logout</a>
        <?php else: ?>
            <a href="../users/login.php" class="<?= (basename($_SERVER['PHP_SELF']) == 'login.php') ? 'active' : ''; ?>"><i class="fas fa-sign-in-alt"></i> Login</a>
        <?php endif; ?>
    </div>
</div>

<div class="sidebar" id="sidebar">
    <a href="#" class="active"><i class=""></i>br</a>

    <a href="../index.php" class="<?= (basename($_SERVER['PHP_SELF']) == 'index.php') ? 'active' : ''; ?>"><i class="fas fa-home"></i> Home</a>
    
    <a href="#" class="<?= (basename($_SERVER['PHP_SELF']) == 'contact.php') ? 'active' : ''; ?>"><i class="fas fa-phone"></i> Contact</a>
    
    <a href="../users/your_booking.php" class="<?= (basename($_SERVER['PHP_SELF']) == 'your_booking.php') ? 'active' : ''; ?>"><i class="fa fa-history"></i> History</a>
    
    <?php if (isset($_SESSION['user_role']) && ($_SESSION['user_role'] == 'staff' || $_SESSION['user_role'] == 'admin')): ?>
        <a href="../bus/view_buses.php" class="<?= (basename($_SERVER['PHP_SELF']) == 'view_buses.php') ? 'active' : ''; ?>"><i class="fas fa-tachometer-alt"></i> Buses</a>
    <?php endif; ?>
    
    <?php if (isset($_SESSION['user_role']) && $_SESSION['user_role'] == 'staff'): ?>
        <a href="../bus/add_bus.php" class="<?= (basename($_SERVER['PHP_SELF']) == 'add_bus.php') ? 'active' : ''; ?>"><i class="fas fa-bus"></i> Add Bus</a>
        
        <?php if (isset($_SESSION['user_role']) && $_SESSION['user_role'] == 'admin'): ?>
            <a href="../admin/users.php" class="<?= (basename($_SERVER['PHP_SELF']) == 'users.php') ? 'active' : ''; ?>"><i class="fas fa-users"></i> All Users</a>
        <?php endif; ?>
    <?php endif; ?>
    
    <a href="../notifications/notificationss.php" class="<?= (basename($_SERVER['PHP_SELF']) == 'notificationss.php') ? 'active' : ''; ?>"><i class="fas fa-bell"></i> Notify</a>
</div>


        <div class="overlay" id="overlay"></div>
    </div>

    <script>
        const hamburger = document.getElementById('hamburger');
        const sidebar = document.getElementById('sidebar');
        const overlay = document.getElementById('overlay');
        const userLogo = document.getElementById('user-logo');
        const userMenu = document.getElementById('user-menu');

        // Toggle sidebar and overlay visibility
        hamburger.addEventListener('click', () => {
            sidebar.classList.toggle('open');
            overlay.classList.toggle('active');
        });

        // Close sidebar when clicking on overlay
        overlay.addEventListener('click', () => {
            sidebar.classList.remove('open');
            overlay.classList.remove('active');
        });

        // Toggle user menu
        userLogo.addEventListener('click', () => {
            userMenu.style.display = userMenu.style.display === 'block' ? 'none' : 'block';
        });
    </script>

    <?php
    ob_end_flush(); // Send buffered output to the browser
    ?>
</body>

</html>