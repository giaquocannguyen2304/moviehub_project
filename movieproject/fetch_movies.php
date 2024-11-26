<?php
// Define your OMDb API key
$apiKey = 'e0972b7e'; // Your actual OMDb API key
 // Replace with your actual API key

 $movieTitles = [ //Choose random movies
    "Inception", 
    "Interstellar", 
    "The Dark Knight", 
    "Parasite", 
    "Titanic", 
    "Gladiator",
    "The Matrix", 
    "Avatar", 
    "The Shawshank Redemption", 
    "The Godfather", 
    "Pulp Fiction", 
    "Forrest Gump", 
    "The Lord of the Rings: The Return of the King",
    "Fight Club",
    "The Social Network",
    "Spider-Man: Across the Spider-Verse",
    "Batman v Superman: Dawn of Justice",
    "Man of Steel",
    "The Silence of the Lambs",
    "Spirited Away",
    "Avengers: Infinity War"

];

$movies = [];

// Fetch movie data from OMDb API
foreach ($movieTitles as $title) {
    $url = "http://www.omdbapi.com/?t=" . urlencode($title) . "&apikey=" . $apiKey;
    $json = file_get_contents($url);
    $movieData = json_decode($json, true);

    // Check if the movie data is valid
    if ($movieData['Response'] === 'True') {
        array_push($movies, array(
            'title' => $movieData['Title'],
            'poster' => $movieData['Poster'],
            'rating' => $movieData['imdbRating'],
            'year' => $movieData['Year'],
            'plot' => $movieData['Plot'],
            'genre' => $movieData['Genre']
        ));
    }
}

return $movies;
?>
