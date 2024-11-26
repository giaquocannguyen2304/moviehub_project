<?php
session_start();
$movies = include('fetch_movies.php');  // Changed this line

if (!is_array($movies)) {
    $movies = [];  // Fallback if fetch fails
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Movie Recommendation System</title>
    <link rel="stylesheet" href="styles.css">
</head>
<body>


<nav>
    <div><a href="index.php" class="logo">MovieHub</a></div>
    <ul class="nav-links">
    <li><a href="index.php">Home</a></li>
                <li><a href="recommendations.php">Recommendations</a></li>
                <li><a href="features.php">Features</a></li>
                <li><a href="contact.php">Contact</a></li>
    </ul>
    <div class="search-bar">
        <input type="text" id="search-input" placeholder="Search movies..." onkeyup="showSuggestions()">
        <div class="suggestions" id="suggestions"></div>
    </div>
    <a href="login.php">
        <button class="login-btn">Login</button>
    </a>
</nav>

<section id="home" class="hero">
    <div class="hero-content">
        <h1>Discover Your Next Favorite Movie</h1>
        <p>Unlock a world of personalized movie recommendations just for you.</p>
        <a href='home.php' class="primary-btn">Get Started</a>
        <a href='features.php' class="secondary-btn">Learn More</button>
    </div>
</section>

 <!-- Popular Movies Section -->
 <section class="movie-cards-section">
        <h1>Popular Movies</h1>
        <div class="movie-cards">
            <?php
            // Loop through each movie and display its data
            foreach ($movies as $movie) {
                echo '
                <div class="movie-card">
                    <img src="' . htmlspecialchars($movie['poster']) . '" alt="' . htmlspecialchars($movie['title']) . ' Poster">
                    <h3>' . htmlspecialchars($movie['title']) . ' (' . htmlspecialchars($movie['year']) . ')</h3>
                    <p>Rating: ' . htmlspecialchars($movie['rating']) . '/10</p>
                    <p>' . htmlspecialchars($movie['plot']) . '</p>
                </div>';
            }
            ?>
        </div>
    </section>

<div id="trailerModal" class="modal">
    <div class="modal-content">
        <span class="close" onclick="closeTrailer()">&times;</span>
        <iframe id="trailerIframe" width="100%" height="315" src="" frameborder="0" allowfullscreen></iframe>
    </div>
</div>

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


       <script src="script.js">
        // Sample list of movie titles
const movieTitles = ["Inception", "Interstellar", "The Dark Knight", "Parasite", "Titanic", 
        "Gladiator", "The Matrix", "Avatar", "The Shawshank Redemption", 
        "The Godfather", "Pulp Fiction", "Forrest Gump", 
        "The Lord of the Rings: The Return of the King", "Fight Club", 
        "The Social Network", "Spider-Man: Across the Spider-Verse", "Batman v Superman: Dawn of Justice",
        "Man of Steel", "The Silence of the Lambs", "Spirited Away", "Avengers: Infinity War"];

function showSuggestions() {
    const input = document.getElementById('search-input').value.toLowerCase();
    const suggestionsBox = document.getElementById('suggestions');
    suggestionsBox.innerHTML = ''; // Clear previous suggestions

    if (input) {
        const suggestions = movieTitles.filter(title => title.toLowerCase().includes(input));
        suggestionsBox.style.display = suggestions.length ? 'block' : 'none';
        suggestions.forEach(title => {
            const div = document.createElement('div');
            div.innerHTML = title;
            div.onclick = () => { document.getElementById('search-input').value = title; };
            suggestionsBox.appendChild(div);
        });
    } else {
        suggestionsBox.style.display = 'none';
    }
}

/*trailer*/
function showTrailer(url) {
    const modal = document.getElementById('trailerModal');
    const iframe = document.getElementById('trailerIframe');
    iframe.src = url;
    modal.style.display = 'flex';
}

function closeTrailer() {
    const modal = document.getElementById('trailerModal');
    const iframe = document.getElementById('trailerIframe');
    iframe.src = ''; // Stop video playback
    modal.style.display = 'none';
}

/*loader*/
let currentMovies = 4;

function loadMoreMovies() {
    const movieSection = document.querySelector('.movie-cards');
    for (let i = currentMovies; i < currentMovies + 4; i++) {
        // Add movie cards dynamically
        const movieCard = document.createElement('div');
        movieCard.classList.add('movie-card');
        movieCard.innerHTML = `
            <img src="path/to/movie-poster-${i}.jpg" alt="Movie Poster">
            <h3>Movie Title ${i + 1}</h3>
            <p>Rating: ${Math.random() * 10}</p>
        `;
        movieSection.appendChild(movieCard);
    }
    currentMovies += 4;
}

    </script>

</body>
</html>

