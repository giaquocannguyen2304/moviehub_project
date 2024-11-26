-- -- moviehub.sql
USE moviehub;

-- Table for storing movies
CREATE TABLE IF NOT EXISTS movies (
    id INT AUTO_INCREMENT PRIMARY KEY,
    title VARCHAR(255) NOT NULL,
    rating FLOAT NOT NULL,
    poster_url VARCHAR(255),
    trailer_url VARCHAR(255)
);

-- Table for storing user information
CREATE TABLE IF NOT EXISTS users (
    id INT AUTO_INCREMENT PRIMARY KEY,
    username VARCHAR(50) NOT NULL UNIQUE,
    password VARCHAR(255) NOT NULL, -- Store hashed passwords
    email VARCHAR(100) NOT NULL UNIQUE,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
);

-- Table for storing contact form submissions
CREATE TABLE IF NOT EXISTS contact_form (
    id INT AUTO_INCREMENT PRIMARY KEY,  
    username VARCHAR(255) NOT NULL,        
    email VARCHAR(255) NOT NULL,        
    message TEXT NOT NULL,              
    submitted_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP 
);


CREATE TABLE survey_responses (
    id INT AUTO_INCREMENT PRIMARY KEY,
    username VARCHAR(255),
    ratings VARCHAR(255), -- Save ratings
    genres VARCHAR(255), -- Save genres
    movie_age_preference VARCHAR(255), -- Save movie age
    submitted_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
);