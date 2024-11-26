<?php
session_start(); // Initializes
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    include 'db_connection.php'; // Includes database connection file

    // Check if user is logged in by verifying session
    if (!isset($_SESSION['username'])) {
        echo json_encode(['success' => false, 'error' => 'You need to login to take the survey.']);
        exit();
    }

    // Get form data
    $username = $_SESSION['username']; // Get usernamme from session
    $ratings = isset($_POST['ratings']) ? implode(', ', $_POST['ratings']) : ''; // Convert ratings array to string
    $genres = isset($_POST['genres']) ? implode(', ', $_POST['genres']) : ''; // Convert genres array to string
    $movie_age = $_POST['movie_age'] ?? ''; // Get movie age preference

    $response = ['success' => false, 'movie' => null]; // Initialize response array

    try {
        // Prepare and execute SQL to save survey response
       $stmt = $conn->prepare("INSERT INTO survey_responses (username, ratings, genres, movie_age_preference) VALUES (:username, :ratings, :genres, :movie_age)");
        $stmt->execute([
        ':username' => $username,
        ':ratings' => $ratings,
        ':genres' => $genres,
        ':movie_age' => $movie_age
        ]);
        // Get movie recommendation from OMDB
        $recommendedMovie = getRecommendedMovie($genres, $movie_age);
        if ($recommendedMovie) {
            $response['success'] = true;
            $response['movie'] = $recommendedMovie;
        } else {
             // If no match found, get random movie
            $response['success'] = true;
            $response['movie'] = getRandomMovie();
        }
    } catch (PDOException $e) {
        $response['error'] = "Error saving survey data: " . $e->getMessage();
    }

    echo json_encode($response);
    exit();
}

function getRecommendedMovie($genres, $movie_age) {
    $apiKey = 'e0972b7e';  // OMDB API KEY (Change based on your own API key)
    $movieTitles = [
        "Inception", "Interstellar", "The Dark Knight", "Parasite", "Titanic", 
        "Gladiator", "The Matrix", "Avatar", "The Shawshank Redemption", 
        "The Godfather", "Pulp Fiction", "Forrest Gump", 
        "The Lord of the Rings: The Return of the King", "Fight Club", 
        "The Social Network", "Spider-Man: Across the Spider-Verse", "Batman v Superman: Dawn of Justice",
        "Man of Steel", "The Silence of the Lambs", "Spirited Away", "Avengers: Infinity War"
    ];

    $recommendedMovies = [];

    foreach ($movieTitles as $title) {
        // Create API URL for each movie
        $url = "http://www.omdbapi.com/?t=" . urlencode($title) . "&apikey=" . $apiKey;
        // Get movie data from API
        $json = file_get_contents($url);
        $movieData = json_decode($json, true);

         // Check if movie matches user preferences
        if (isset($movieData['Response']) && $movieData['Response'] === 'True') {
            if ((stripos($movieData['Genre'], $genres) !== false || empty($genres)) && checkMovieAge($movieData['Year'], $movie_age)) {
                $recommendedMovies[] = [
                    'title' => $movieData['Title'],
                    'poster' => $movieData['Poster'],
                    'rating' => $movieData['imdbRating'],
                    'year' => $movieData['Year'],
                    'plot' => $movieData['Plot']
                ];
            }
        }
    }

        // Return random movie from matched movies
    return !empty($recommendedMovies) ? $recommendedMovies[array_rand($recommendedMovies)] : null;
}

function checkMovieAge($year, $agePreference) {
    $currentYear = intval(date("Y")); //Get current year
    $movieYear = intval($year);
    switch ($agePreference) {
        case 'Published in the last 3 years':
            return ($currentYear - $movieYear) <= 3;
        case 'Published in the last 5 years':
            return ($currentYear - $movieYear) <= 5;
        case 'Published in the last 10 years':
            return ($currentYear - $movieYear) <= 10;
        case 'Does not matter':
            return true;
        default:
            return true;
    }
}

function getRandomMovie() {
    $apiKey = 'e0972b7e';  // Change it based on your API
    $movieTitles = [
        "Inception", "Interstellar", "The Dark Knight", "Parasite", "Titanic", 
        "Gladiator", "The Matrix", "Avatar", "The Shawshank Redemption", 
        "The Godfather", "Pulp Fiction", "Forrest Gump", 
        "The Lord of the Rings: The Return of the King", "Fight Club", 
        "The Social Network", "Spider-Man: Across the Spider-Verse", "Batman v Superman: Dawn of Justice",
        "Man of Steel", "The Silence of the Lambs", "Spirited Away", "Avengers: Infinity War"
    ];

    $title = $movieTitles[array_rand($movieTitles)];
    $url = "http://www.omdbapi.com/?t=" . urlencode($title) . "&apikey=" . $apiKey;
    $json = file_get_contents($url); // Gets raw JSON from OMDB API
    $movieData = json_decode($json, true); // Converts JSON to PHP array

    if (isset($movieData['Response']) && $movieData['Response'] === 'True') {
        return [
            'title' => $movieData['Title'],
            'poster' => $movieData['Poster'],
            'rating' => $movieData['imdbRating'],
            'year' => $movieData['Year'],
            'plot' => $movieData['Plot']
        ];
    }

    return null;
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Survey - MovieHub</title>
    <link rel="stylesheet" href="style.css">
    
    <style>
        /* Modal styles */
       .modal-content {
    background-color: #fefefe;
    margin: 10% auto;
    padding: 20px;
    border-radius: 8px; /* Rounded corners */
    width: 90%;
    max-width: 600px; /* Maximum width limit */
    box-shadow: 0 4px 8px rgba(0, 0, 0, 0.2); /* Shadow effect */
    overflow: hidden;
}

.modal h2 {
    font-size: 24px;
    margin-bottom: 15px;
}

.modal img {
    width: 100%;
    max-width: 400px; /* Maximum width limit */
    margin: 0 auto;
    display: block; /* Makes margin auto work */
    border-radius: 5px; /* Rounded corners on image */
}

.modal p {
    font-size: 16px;
    margin: 10px 0;
    line-height: 1.5;
}

.close {
    color: #333;
    float: right;
    font-size: 28px;
    font-weight: bold;
    cursor: pointer;
}

.close:hover {
    color: #d9534f;
}

.modal {
    display: none;
    position: fixed;
    z-index: 1;
    left: 0;
    top: 0;
    width: 100%;
    height: 100%;
    overflow: auto;
    background-color: rgba(0, 0, 0, 0.7); /* Semi-transparent dark background */
}
#newSuggestionBtn {
    background-color: #ff6f00;
    color: white;
    padding: 10px 15px;
    margin-top: 15px;
    border: none;
    border-radius: 5px;
    cursor: pointer;
    transition: background-color 0.3s ease;
}

#newSuggestionBtn:hover {
    background-color: #e65c00;
}

#newSuggestionBtn:disabled {
    background-color: #ccc;
    cursor: not-allowed;
}

    </style>
</head>
<body>
    <!-- Navbar -->
    <header>
        <a href="index.php" class="logo">MovieHub</a>
        <nav>
            <ul class="nav-links">
                <li><a href="home.php">Home</a></li>
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

    

    <!-- Survey Form -->
    <div class="survey-container">
        
        <div class="survey-box">
            <h1>Movie Preferences Survey</h1>
            <form id="surveyForm">
                <!-- Questions -->
            <p>Please select the Australian Film Classifications rating system that you prefer (multiple answers are allowed):</p>
                <label><input type="checkbox" name="ratings[]" value="G-Rated"> G-Rated: General audience</label><br>
                <label><input type="checkbox" name="ratings[]" value="PG-Rated"> PG-Rated: requires the guidance of a parent or guardian</label><br>
                <label><input type="checkbox" name="ratings[]" value="M-Rated"> M-Rated: have content such as violence and themes that require a mature outlook</label><br>
                <label><input type="checkbox" name="ratings[]" value="R18+-Rated"> R18+-Rated: for a person 18 years old and over</label><br><br>

                <!-- More questions... -->
                <!-- Genres -->
               <p>Please choose any genre you are interested in (multiple answers are allowed):</p>
                <label><input type="checkbox" name="genres[]" value="Action"> Action</label><br>
                <label><input type="checkbox" name="genres[]" value="Comedy"> Comedy</label><br>
                <label><input type="checkbox" name="genres[]" value="Drama"> Drama</label><br>
                <label><input type="checkbox" name="genres[]" value="Thriller"> Thriller</label><br>
                <label><input type="checkbox" name="genres[]" value="Adventure"> Adventure</label><br>
                <label><input type="checkbox" name="genres[]" value="Romance"> Romance</label><br>
                <label><input type="checkbox" name="genres[]" value="Crime"> Crime</label><br>
                <label><input type="checkbox" name="genres[]" value="Science Fiction"> Science Fiction</label><br>
                <label><input type="checkbox" name="genres[]" value="Fantasy"> Fantasy</label><br><br>

                <!-- Movie Age -->
              <p>How old would you like the movie to be?</p>
                <label><input type="radio" name="movie_age" value="Does not matter"> Does not matter</label><br>
                <label><input type="radio" name="movie_age" value="Published in the last 3 years"> Published in the last 3 years</label><br>
                <label><input type="radio" name="movie_age" value="Published in the last 5 years"> Published in the last 5 years</label><br>
                <label><input type="radio" name="movie_age" value="Published in the last 10 years"> Published in the last 10 years</label><br><br>

                <!-- Submit Button -->
                <button class="btn_submit_survey" type="submit">Submit Survey</button>
            </form>
        </div>
    </div>

    <!-- Modal for Movie Recommendation -->
  <div id="recommendationModal" class="modal">
    <div class="modal-content">
        <span class="close" onclick="closeModal()">&times;</span>

        <!-- Movie information display -->
        <h2 id="movieTitle"></h2>
        <img id="moviePoster" src="" alt="Movie Poster">
        <p id="moviePlot"></p>
        <p><strong>Rating:</strong> <span id="movieRating"></span></p>
        <p><strong>Year:</strong> <span id="movieYear"></span></p>
        <button id="newSuggestionBtn" onclick="resubmitForm()">Get Another Suggestion</button> 
    </div>
</div>

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
        // Add submit event listener to the survey form
        document.getElementById('surveyForm').addEventListener('submit', function(e) {
             // Prevent default form submission to avoid page reload
            e.preventDefault();

            // Get the submit button element
            const submitButton = document.querySelector('.btn_submit_survey');
            const formData = new FormData(this);
              submitButton.disabled = true;
            
              submitButton.textContent = 'Submitting...';
             
              // Send data to current file using fetch API
              fetch('', { 
                method: 'POST',
                body: formData
            })
            .then(response => response.json())  // Parse JSON response
            .then(data => {
                 submitButton.disabled = false;
                 submitButton.textContent = 'Submit Survey';
                if (data.success && data.movie) {
                    showModal(data.movie);
                } else {
                    alert('Survey submitted successfully but no matching movies found!');
                }
            })
            .catch(error => {
                 submitButton.disabled = false;
                 submitButton.textContent = 'Submit Survey';
                 console.error('Error:', error);
            });
        });

        // show modal
        function showModal(movie) {
            document.getElementById("movieTitle").textContent = movie.title;
            document.getElementById("moviePoster").src = movie.poster;
            document.getElementById("moviePlot").textContent = movie.plot;
            document.getElementById("movieRating").textContent = movie.rating;
            document.getElementById("movieYear").textContent = movie.year;
            document.getElementById("recommendationModal").style.display = "block";
        }

        // close modal
        function closeModal() {
            document.getElementById("recommendationModal").style.display = "none";
        }

    function resubmitForm() {
    const btn = document.getElementById('newSuggestionBtn');
    btn.disabled = true;  // Disable button during processing
    btn.textContent = 'Loading...'; // Change button text to loading state

    const formData = new FormData(document.getElementById('surveyForm'));
    fetch('', { // Send request to current file for new suggestion
        method: 'POST',
        body: formData
    })
    .then(response => response.json())
    .then(data => {
        btn.disabled = false;    // Re-enable button
        btn.textContent = 'Get Another Suggestion';    // Reset button text
        if (data.success && data.movie) {
            showModal(data.movie); // Show new movie recommendation
        } else {
            alert('No new movie suggestions found.');
        }
    })
    .catch(error => { 
         // Handle any errors
        btn.disabled = false; 
        btn.textContent = 'Get Another Suggestion';
        console.error('Error:', error);
    });
}
    </script>

</body>
</html>

