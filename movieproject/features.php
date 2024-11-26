<?php
session_start(); 
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Features - MovieHub</title>
    <style>
        /* Reset styles */
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }

        /* Body Styling */
        body {
            font-family: 'Arial', sans-serif;
            line-height: 1.6;
            background-color: #f4f4f4;
            min-height: 100vh; 
            display: flex; 
            flex-direction: column; 
        }

        /* Modify your main content area */
        main {
            flex: 1;  /* This pushes the footer down */
        }

        /* Navbar Styling */
        nav {
            background-color: #222;
            color: #fff;
            display: flex;
            justify-content: space-between;
            padding: 1rem 2rem;
            align-items: center;
            position: sticky;
            top: 0;
            z-index: 1000;
        }

        .logo {
            font-size: 1.8rem;
            font-weight: bold;
            color: #ff6600;
            text-decoration: none;
        }

        .nav-links {
            list-style: none;
            display: flex;
            gap: 1.5rem;
            margin-left: auto;
        }

        .nav-links li {
            display: inline;
        }

        .nav-links li a {
            color: #fff;
            text-decoration: none;
            padding: 0.5rem;
            transition: color 0.3s ease;
            font-size: 1rem;
        }

        .nav-links li a:hover, .nav-links li a.active {
            color: #ff6600;
        }

        .login-btn {
            background-color: #ff6600;
            color: #fff;
            border: none;
            padding: 0.5rem 1rem;
            cursor: pointer;
            border-radius: 5px;
            transition: background-color 0.3s ease;
            font-size: 1rem;
        }

        .login-btn:hover {
            background-color: #ff4500;
        }

        /* Features Section Styling */
        .features-section {
            padding: 60px 20px;
            text-align: center;
            background-color: #fff;
        }

        .features-section h1 {
            font-size: 2.5rem;
            color: #333;
            margin-bottom: 40px;
        }

        .feature-list {
            display: flex;
            flex-wrap: wrap;
            gap: 20px;
            justify-content: center;
            margin-top: 20px;
        }

        .feature {
            background-color: #f3f3f3;
            padding: 30px;
            border-radius: 10px;
            width: 280px;
            box-shadow: 0 4px 10px rgba(0, 0, 0, 0.1);
            transition: transform 0.3s ease, box-shadow 0.3s ease;
        }

        .feature:hover {
            transform: translateY(-10px);
            box-shadow: 0 6px 20px rgba(0, 0, 0, 0.15);
        }

        .feature h2 {
            color: #ff6f00;
            margin-bottom: 15px;
            font-size: 1.5rem;
        }

        .feature p {
            color: #555;
            font-size: 1rem;
        }

        /* Footer Styling */
        .footer {
            background-color: #333;
            color: #fff;
            padding: 20px 0;
            text-align: center;
            width: 100%;
            margin-top: auto;
        }

        .footer-content {
            max-width: 1200px;
            margin: auto;
            padding: 0 20px;
            display: flex;
            justify-content: space-between;
            align-items: center;
            flex-wrap: wrap;
        }

        .footer-links {
            list-style-type: none;
            display: flex;
            gap: 15px;
        }

        .footer-links li a {
            color: #ff6f00;
            text-decoration: none;
            font-size: 14px;
        }

        .footer-links a:hover {
            text-decoration: underline;
        }

        /* Responsive Styling */
        @media (max-width: 768px) {
            .nav-links {
                flex-direction: column;
                gap: 1rem;
            }

            .feature-list {
                flex-direction: column;
            }

            .feature {
                width: 100%;
            }
        }
        .login-btn a{
            color : #fff;
            
        }
    </style>
</head>
<body>
    <!-- Navbar -->
    <header>
        <nav>
            <a href="index.php" class="logo">MovieHub</a>
            <ul class="nav-links">
            <li><a href="index.php">Home</a></li>
                <li><a href="recommendations.php">Recommendations</a></li>
                <li><a href="features.php">Features</a></li>
                <li><a href="contact.php">Contact</a></li>
            </ul>
          <?php if (isset($_SESSION['username'])): ?>
                    <li class="btn_login"><a href="#">Hello, <?php echo htmlspecialchars($_SESSION['username']); ?></a></li>
                    <li class="btn_logout"><a href="logout.php">Logout</a></li>
                <?php else: ?>
                    <li class="btn_login"><a href="login.php">Login</a></li>
                <?php endif; ?>
        </nav>
    </header>

    <!-- Features Section -->
    <section class="features-section">
        <h1>Our Features</h1>
        <div class="feature-list">
            <div class="feature">
                <h2>Personalized Recommendations</h2>
                <p>Get tailored movie suggestions based on your viewing preferences, ensuring the perfect watch every time.</p>
            </div>
            <div class="feature">
                <h2>Top Picks and Trending Movies</h2>
                <p>Stay up-to-date with the latest trending movies or discover hidden gems that match your taste.</p>
            </div>
            <div class="feature">
                <h2>Watch Trailers</h2>
                <p>Watch trailers before making your decision. Get a sneak peek of the movie to see if it’s what you’re looking for.</p>
            </div>
            <div class="feature">
                <h2>Advanced Search Filters</h2>
                <p>Filter movies by genre, release date, rating, and more to quickly find exactly what you want to watch.</p>
            </div>
        </div>
    </section>

    <!-- Footer -->
    <footer class="footer">
        <div class="footer-content">
            <p>&copy; 2024 MovieHub. All rights reserved.</p>
            <ul class="footer-links">
            <li><a href="index.php">Home</a></li>
                <li><a href="recommendations.php">Recommendations</a></li>
                <li><a href="features.php">Features</a></li>
                <li><a href="contact.php">Contact</a></li>
            </ul>
        </div>
    </footer>
</body>
</html>
