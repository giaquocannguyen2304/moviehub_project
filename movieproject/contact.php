<?php
session_start();
include 'db_connection.php';

// Ensure database connection is established
if (!isset($conn)) {
    die("Database connection failed. Please try again later.");
}

// Handle form submission
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $name = trim($_POST['name']);
    $email = trim($_POST['email']);
    $messageContent = trim($_POST['message']);

    try {
        // Prepare the SQL statement
        $stmt = $conn->prepare("INSERT INTO contact_form (name, email, message) VALUES (:name, :email, :message)");

        // Bind parameters
        $stmt->bindParam(':name', $name);
        $stmt->bindParam(':email', $email);
        $stmt->bindParam(':message', $messageContent);

        // Execute the query
        if ($stmt->execute()) {
            $feedback = "Message sent successfully!";
        } else {
            $feedback = "An error occurred. Please try again later.";
        }
    } catch (PDOException $e) {
        $feedback = "An error occurred while processing your request. Please try again later.";
        // Optionally log the error: error_log($e->getMessage());
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Contact - MovieHub</title>
    <style>
        /* Reset styles for consistency */
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
        }

        .logo {
            font-size: 1.5rem;
            font-weight: bold;
            color: #ff6600;
            text-decoration: none;
        }

        .nav-links {
            list-style: none;
            display: flex; /* Ensure items are in a row */
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
        }

        .nav-links li a:hover {
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
        }

        .login-btn:hover {
            background-color: #ff4500;
        }

        /* Contact Section Styling */
        .contact-section {
            padding: 50px;
            text-align: center;
        }

        .contact-form {
            max-width: 600px;
            margin: auto;
            display: flex;
            flex-direction: column;
            gap: 15px;
        }

        .contact-form label {
            text-align: left;
        }

        .contact-form input, .contact-form textarea {
            width: 100%;
            padding: 10px;
            border: 1px solid #ccc;
            border-radius: 5px;
        }

        .contact-form button {
            background-color: #ff6f00;
            color: #fff;
            padding: 10px;
            border: none;
            border-radius: 5px;
            cursor: pointer;
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

    <!-- Contact Section -->
    <section class="contact-section">
        <h1>Contact Us</h1>
        <p>Have questions or feedback? Get in touch with us!</p>

        <!-- Display success/error message -->
        <?php if (isset($message)) { echo "<p>$message</p>"; } ?>

        <!-- Contact Form -->
        <form class="contact-form" method="POST" action="">
            <label for="name">Name:</label>
            <input type="text" id="name" name="name" required>

            <label for="email">Email:</label>
            <input type="email" id="email" name="email" required>

            <label for="message">Message:</label>
            <textarea id="message" name="message" rows="4" required></textarea>

            <button type="submit">Send Message</button>
        </form>
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

<?php
// Close the connection to the database
$conn= null;
?>