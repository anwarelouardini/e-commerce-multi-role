<?php
// 1. Define the path to your .env file
// __DIR__ ensures it always looks in the same folder as db.php
$envPath = __DIR__ . '/.env';

// 2. Check if the .env file exists to prevent silent crashes
if (file_exists($envPath)) {
    
    // Parse the .env file into a readable array
    $env = parse_ini_file($envPath);
    
    // 3. Assign Anwar's specific variables
    $host = $env['DB_HOST'];
    $port = $env['DB_PORT'];
    $dbname = $env['DB_NAME'];
    $user = $env['DB_USER'];
    $password = $env['DB_PASSWORD'];

    // 4. Create the MySQLi connection (Notice we added the $port variable at the end!)
    $conn = new mysqli($host, $user, $password, $dbname, $port);

    // 5. Catch and display any connection errors
    if ($conn->connect_error) {
        die("Database connection failed: " . $conn->connect_error);
    }
    
} else {
    // Stop the script if the .env file is missing
    die("Error: The .env file was not found. Please create it in the root directory.");
}
?>