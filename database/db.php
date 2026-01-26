<?php

$host = "localhost";
$username = "np03cs4a240047";
$password = "e0IcRXFbkZ";
$dbname = "np03cs4a240047";

// PDO options to make database handling safer and cleaner
$options = [

    // Show errors as exceptions (helps in debugging)
    PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,

    // Disable prepared statement emulation (more secure)
    PDO::ATTR_EMULATE_PREPARES => false,

    // Fetch data as associative arrays by default
    PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC
];

try {
    // Create a new PDO database connection
    $pdo = new PDO(
        "mysql:host=$host;dbname=$dbname;charset=utf8mb4", // Connection string
        $username,  
        $password,   
        $options    
    );
} catch (PDOException $e) {

    // Stop the script if the database connection fails
    // (Do not show real error details for security)
    die('Database connection failed.');
}
