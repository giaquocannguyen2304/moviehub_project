<?php
session_start(); // Initialize
// Your OMDb API key
$apiKey = 'e0972b7e';  // Replace this with your actual API key

// List of movie titles to fetch data for (more movies added)
$movieTitles = [
    "Inception", "Interstellar", "The Dark Knight", "Parasite", "Titanic", 
        "Gladiator", "The Matrix", "Avatar", "The Shawshank Redemption", 
        "The Godfather", "Pulp Fiction", "Forrest Gump", 
        "The Lord of the Rings: The Return of the King", "Fight Club", 
        "The Social Network", "Spider-Man: Across the Spider-Verse", "Batman v Superman: Dawn of Justice",
        "Man of Steel", "The Silence of the Lambs", "Spirited Away", "Avengers: Infinity War"
];

$movies = [];

// Fetch movie data from OMDb API
foreach ($movieTitles as $title) {
    $url = "http://www.omdbapi.com/?t=" . urlencode($title) . "&apikey=" . $apiKey;
    $json = file_get_contents($url);
    
    // Check if file_get_contents was successful
    if ($json === FALSE) {
        echo "Error fetching data for movie: $title";
        continue;  // Skip this movie and continue with the next one
    }

    $movieData = json_decode($json, true);

    // Check if the movie data is valid
    if (isset($movieData['Response']) && $movieData['Response'] === 'True') {
        // Adding the trailer URL to the movie data manually or from another source
        $trailerUrl = getTrailerUrl($movieData['Title']);  // Function to fetch trailer URL from YouTube or other source
        
        $movies[] = [
            'title' => $movieData['Title'],
            'poster' => $movieData['Poster'],
            'rating' => $movieData['imdbRating'],
            'year' => $movieData['Year'],
            'plot' => $movieData['Plot'],
            'genre' => $movieData['Genre'],
            'trailer' => $trailerUrl  // Add the trailer URL dynamically
        ];
    } else {
        echo "Error: " . $movieData['Error'] . " for movie: $title<br>";
    }
}

// Function to fetch trailer URL (can be customized)
function getTrailerUrl($movieTitle) {
    // Example: hardcoded YouTube trailer links, can be dynamic
    $trailerLinks = [
        'Inception' => 'https://www.youtube.com/watch?v=YoHD9XEInc0',
        'Interstellar' => 'https://www.youtube.com/watch?v=zSWdZVtXT7E',
        'The Dark Knight' => 'https://www.youtube.com/watch?v=EXeTwQWrcwY',
        'Parasite' => 'https://www.youtube.com/watch?v=5xH0HfJHsaY',
        'Titanic' => 'https://www.youtube.com/watch?v=2e-eXJ6HgkQ',
        'Gladiator' => 'https://www.youtube.com/watch?v=owK1qxDselE',
        'The Matrix' => 'https://www.youtube.com/watch?v=vKQi3bBA1y8',
        'Avatar' => 'https://www.youtube.com/watch?v=5PSNL1qE6VY',
        'The Shawshank Redemption' => 'https://www.youtube.com/watch?v=6hB3S9bIaco',
        'The Godfather' => 'https://www.youtube.com/watch?v=sY1S34973zA',
        'Pulp Fiction' => 'https://www.youtube.com/watch?v=s7EdQ4FqbhY',
        'Forrest Gump' => 'https://www.youtube.com/watch?v=bLvqoHBptjg',
        'The Lord of the Rings: The Return of the King' => 'https://www.youtube.com/watch?v=r5X-hFf6Bwo',
        'Fight Club' => 'https://www.youtube.com/watch?v=SUXWAEX2jlg',
        'The Social Network' => 'https://www.youtube.com/watch?v=lB95KLmpLR4',
        "Spider-Man: Across the Spider-Verse" => 'https://www.youtube.com/watch?v=cqGjhVJWtEg',
        "Batman v Superman: Dawn of Justice" => 'https://www.youtube.com/watch?v=0WWzgGyAH6Y',
        "Man of Steel" => 'https://www.youtube.com/watch?v=HstHJN8MJwo',
        "The Silence of the Lambs" => 'https://www.youtube.com/watch?v=6iB21hsprAQ',
        "Spirited Away" => 'https://www.youtube.com/watch?v=ByXuk9QqQkk', 
        "Avengers: Infinity War" => 'https://www.youtube.com/watch?v=6ZfuNTqbHE8'
    ];

    return $trailerLinks[$movieTitle] ?? '';  // Return the trailer URL or empty if not found
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Recommendations - MovieHub</title>
     
    <style>
       /* Insert your existing CSS here */
        /* Reset styles for consistency */
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }

        /* Navbar Styling */
        header {
            background-color: #222;
            color: #fff;
            display: flex;
            justify-content: space-between;
            align-items: center;
            padding: 1rem 2rem;
        }

        .logo {
            font-size: 1.5rem;
            font-weight: bold;
            color: #ff6600;
            text-decoration: none;
        }

        .nav-links {
            list-style: none;
            display: flex;
            gap: 1.5rem;
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

        /* Recommendations Page Styling */
        .recommendations-section {
            padding: 50px;
            text-align: center;
            background-color: #f9f9f9;
        }

        .recommendations-section h1 {
            font-size: 2.5em;
            color: #333;
            margin-bottom: 30px;
        }

        .movie-grid {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(250px, 1fr));
            gap: 20px;
            max-width: 1200px;
            margin: auto;
        }

        .movie-card img {
    width: 100%; /* Make the image responsive */
    height: 200px; /* Set a fixed height for uniformity */
    object-fit: cover; /* Ensures the image covers the area without stretching */
    border-bottom: 2px solid #ff6f00;
}

.movie-card {
    max-height: 350px; /* Set a max height for the card itself */
}


        .movie-card:hover {
            transform: scale(1.05);
        }

      

        .movie-card-content {
            padding: 15px;
            text-align: left;
        }

        .movie-title {
            font-size: 1.2em;
            font-weight: bold;
            color: #333;
            margin-bottom: 10px;
        }

        .movie-genre, .movie-rating {
            font-size: 0.9em;
            color: #666;
            margin-bottom: 8px;
        }

        .movie-rating {
            color: #ff6f00;
        }

        .view-trailer-btn {
            display: inline-block;
            background-color: #ff6f00;
            color: #fff;
            padding: 8px 15px;
            margin-top: 10px;
            border-radius: 5px;
            text-decoration: none;
            font-size: 0.9em;
        }

        .view-trailer-btn:hover {
            background-color: #e65c00;
        }

        /* Footer Styling */
        .footer {
            background-color: #333;
            color: #fff;
            padding: 20px 0;
            text-align: center;
            position: relative;
            bottom: 0;
            width: 100%;
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

        .footer-links li {
            display: inline;
        }

        .footer-links a {
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
        <a href="index.php" class="logo">MovieHub</a>
        <nav>
            <ul class="nav-links">
                <li><a href="index.php">Home</a></li>
                <li><a href="recommendations.php">Recommendations</a></li>
                <li><a href="features.php">Features</a></li>
                <li><a href="contact.php">Contact</a></li>
                 <?php if (isset($_SESSION['username'])): ?>
                    <li class="btn_login"><a href="#">Hello, <?php echo htmlspecialchars($_SESSION['username']); ?></a></li>
                    <li class="btn_logout"><a href="logout.php">Logout</a></li> 
                <?php else: ?>
                    <li class="btn_login"><a href="login.php">Login</a></li>
                <?php endif; ?>
            </ul>
        </nav>
    </header>

    <!-- Recommendations Section -->
    <section class="recommendations-section">
        <h1>Recommended Movies</h1>
        <div class="movie-grid">
            <?php foreach ($movies as $movie): ?>
                <div class="movie-card">
                    <img src="<?= $movie['poster']; ?>" alt="Movie Poster">
                    <div class="movie-card-content">
                        <h2 class="movie-title"><?= $movie['title']; ?></h2>
                        <p class="movie-genre">Genre: <?= $movie['genre']; ?></p>
                        <p class="movie-rating">Rating: <?= $movie['rating']; ?>/10</p>
                        <!-- View Trailer Button with Dynamic URL -->
                        <a href="<?= $movie['trailer']; ?>" class="view-trailer-btn" target="_blank">View Trailer</a>
                    </div>
                </div>
            <?php endforeach; ?>
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

    <script>
        // JavaScript for trailer modal functionality if needed
        function showTrailer(trailerUrl) {
            document.getElementById("trailerIframe").src = trailerUrl;
            document.getElementById("trailerModal").style.display = "block";
        }

        function closeTrailer() {
            document.getElementById("trailerModal").style.display = "none";
            document.getElementById("trailerIframe").src = "";
        }
    </script>
</body>
</html>
