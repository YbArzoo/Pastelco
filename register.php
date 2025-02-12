<?php
session_start();

define("HOST", "localhost");
define("DBNAME", "pastelco");
define("USER", "root");
define("PASS", "");

class App {
    public $host = HOST;
    public $dbname = DBNAME;
    public $user = USER;
    public $pass = PASS;
    public $link;

    public function __construct() {
        $this->connect();
    }

    public function connect() {
        $this->link = new PDO("mysql:host=$this->host;dbname=$this->dbname", $this->user, $this->pass);
    }

    // Insert a new user into the database
    public function registerUser($name, $email, $password) {
        // Check if email already exists
        $checkQuery = "SELECT * FROM user WHERE email = :email";
        $stmt = $this->link->prepare($checkQuery);
        $stmt->bindParam(':email', $email);
        $stmt->execute();
        
        if ($stmt->rowCount() > 0) {
            return "Email already exists!";
        }

        // Hash password for security
        $hashed_password = password_hash($password, PASSWORD_DEFAULT);

        // Insert user into database
        $query = "INSERT INTO user (name, email, password) VALUES (:name, :email, :password)";
        $stmt = $this->link->prepare($query);
        $stmt->bindParam(':name', $name);
        $stmt->bindParam(':email', $email);
        $stmt->bindParam(':password', $hashed_password);

        if ($stmt->execute()) {
            return true; // Registration successful
        } else {
            return false; // Registration failed
        }
    }
}

$app = new App;

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $name = $_POST['name'];
    $email = $_POST['email'];
    $password = $_POST['password'];

    $result = $app->registerUser($name, $email, $password);

    if ($result === true) {
        $_SESSION['user_name'] = $name;
        header("Location: login.html"); // Redirect to login after successful registration
    } else {
        echo $result; // Display error message (e.g., "Email already exists!")
    }
}
?>
