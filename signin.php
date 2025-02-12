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

    public function registerUser($name, $email, $password) {
        // Check if email already exists
        $query = "SELECT * FROM user WHERE email = :email";
        $stmt = $this->link->prepare($query);
        $stmt->bindParam(':email', $email);
        $stmt->execute();

        if ($stmt->rowCount() > 0) {
            return "Email already exists!";
        }

		 // Hash the password for security
		$hashed_password = password_hash($password, PASSWORD_DEFAULT);

        // Insert new user into the database
        $query = "INSERT INTO user (name, email, password) VALUES (:name, :email, :password)";
        $stmt = $this->link->prepare($query);
        $stmt->bindParam(':name', $name);
        $stmt->bindParam(':email', $email);
        $stmt->bindParam(':password', $password);

        if ($stmt->execute()) {
            return true;
        } else {
            return "Registration failed!";
        }
    }
}

$app = new App;

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    // Fetch data from POST request
    $name = $_POST['name'];
    $email = $_POST['email'];
    $password = $_POST['password'];

    // Call registerUser function to register the user
    $result = $app->registerUser($name, $email, $password);

    if ($result === true) {
        // Redirect to login page after successful registration
        header("Location: login.php");
        exit();
    } else {
        echo $result; // Display error message if registration fails
    }
}
?>




<!doctype html>
<html lang="en">
  <head>
  	<title>Pastelco Sign In</title>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">

	<link rel="preconnect" href="https://fonts.googleapis.com">
	<link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
	<link href="https://fonts.googleapis.com/css2?family=Averia+Serif+Libre:ital,wght@0,300;0,400;0,700;1,300;1,400;1,700&display=swap" rel="stylesheet">

	<link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/font-awesome/4.7.0/css/font-awesome.min.css">
	
	<link rel="stylesheet" href="css/login.css">
	<link rel="stylesheet" href="css/bootstrap.min.css">

	</head>
	<body class="img js-fullheight" style="background-image: url(img/login-bg-2.jpg);">
	<section class="ftco-section">
		<div class="container">
			<div class="row justify-content-center">
				<div class="col-md-6 text-center mb-5">
					<h2 class="heading-section">Sign In as a new user to Pastelco!</h2>
				</div>
			</div>
			<div class="row justify-content-center">
				<div class="col-md-6 col-lg-4">
					<div class="login-wrap p-0">
		      	<form action="signin.php" method ="POST" class="signin-form">
					<div class="form-group">
						<input type="text" name="name" class="form-control" placeholder="Name" required>
					</div>
		      		<div class="form-group">
		      			<input type="email" name="email" class="form-control" placeholder="Email" required>
		      		</div>
	            <div class="form-group">
	              <input id="password-field" name="password" type="password" class="form-control" placeholder="Password" required>
	              <span toggle="#password-field" class="fa fa-fw fa-eye field-icon toggle-password"></span>
	            </div>
	            <div class="form-group">
	            	<button type="submit" class="form-control btn btn-primary submit px-3">Sign Up</button>
	            </div>
	          </form>
	          <div class="social d-flex text-center">
	          	<a href="login.php" class="px-2 py-2 mr-md-1 rounded"><span class="ion-logo-facebook mr-2"></span> Go Back! </a>
	          </div>
		      </div>
				</div>
			</div>
		</div>
	</section>

  <script src="js/jquery.min.js"></script>
  <script src="js/popper.js"></script>
  <script src="js/bootstrap.min.js"></script>
  <script src="js/login-main.js"></script>
  
	</body>
</html>

