<?php
// Database connection settings for XAMPP default
$host = 'localhost';
$dbname = 'pastelco';   
$username = 'root';
$password = '';  // Empty password for XAMPP

try {
    // Create a new PDO instance and set error mode to exception
    $db = new PDO("mysql:host=$host;dbname=$dbname;charset=utf8", $username, $password);

    // Set the PDO error mode to exception
    $db->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
} catch (PDOException $e) {
    // If the connection fails, output the error message
    die("Database connection failed: " . $e->getMessage());
}

// Define the App class only if it doesn't already exist
if (!class_exists('App')) {
    class App {
        public $link;

        public function __construct(){
            global $db; // Use the global $db connection
            $this->link = $db;
        }

        // Method to select all with query
        public function selectAll_query($query){
            $rows = $this->link->query($query);
            $allRows = $rows->fetchAll(PDO::FETCH_ASSOC);
            return $allRows ?: false;
        }
    }
}

// Create an instance of App and define $conn
$app = new App();
$conn = $app->link; // Assign the link to $conn for use in other files
?>
