<?php
session_start();

define("HOST", "localhost");
define("DBNAME", "pastelco");
define("USER", "root");
define("PASS", "");
?>

<?php

class App {
public $host = HOST;
public $dbname = DBNAME;
public $user = USER;
public $pass = PASS;

public $link;

// create constract

public function __construct(){

    $this-> connect();
}

public function connect(){

    $this->link= new PDO("mysql:host=$this->host;dbname=$this->dbname",$this->user,$this->pass);

    //if($this->link) {
    //    echo "db connection is working";
    //}
}
// select all
public function selectAll($query,$arr){

    $rows= $this->link->prepare($query);
    $rows->execute($arr);

    $allRows= $rows->fetchAll(PDO::FETCH_ASSOC);

    if($allRows){
        return $allRows;
    }else {
        return false;
    }
}

// starting sessions
public function startingSession(){
    session_start();
}

//login


public function login($email,$password,$user){

    //email validate
    //user
    if ($user=="user"){
        $query="select * from user where email=:email";
    $login_user= $this->link->prepare($query);
    $login_user->execute(array(
        ":email"=>$email
        
    ));
    if($login_user->rowCount()>0){
        $fetch1=$login_user->fetch(PDO::FETCH_ASSOC);
        if($password==$fetch1['password']){
            $_SESSION['email']=$fetch1['email'];
            $_SESSION['name']=$fetch1['name'];
            $_SESSION['id']=$fetch1['user_id'];
            $_SESSION['user']='user';
            //header("Location:$path");
            echo "<script>window.location.href='http://localhost/pastelco/user.php'</script>";
        }else{
            echo "password is wrong";
        }
    }else{
        echo "username or password is incorrect";
    }

    }

    //admin
    if ($user=="admin"){
        $query="select * from admin where email=:email";
    $login_user= $this->link->prepare($query);
    $login_user->execute(array(
        ":email"=>$email
        
    ));
    if($login_user->rowCount()>0){
        $fetch1=$login_user->fetch(PDO::FETCH_ASSOC);
        if($password==$fetch1['password']){
            $_SESSION['email']=$fetch1['email'];
            $_SESSION['name']=$fetch1['name'];
            $_SESSION['id']=$fetch1['admin_id'];
            $_SESSION['user']='admin';
            //header("Location:$path");
            echo "<script>window.location.href='http://localhost/pastelco/admin.php'</script>";
        }else{
            echo "password is wrong";
        }
    }else{
        echo "username or password is incorrect";
    }

    }


    //raider
    if ($user=="rider"){
        $query="select * from rider where email=:email";
    $login_user= $this->link->prepare($query);
    $login_user->execute(array(
        ":email"=>$email
        
    ));
    if($login_user->rowCount()>0){
        $fetch1=$login_user->fetch(PDO::FETCH_ASSOC);
        if($password==$fetch1['password']){
            $_SESSION['email']=$fetch1['email'];
            $_SESSION['name']=$fetch1['name'];
            $_SESSION['id']=$fetch1['rider_id'];
            $_SESSION['user']='rider';
            //header("Location:$path");
            echo "<script>window.location.href='http://localhost/pastelco/riders_panel.php'</script>";
        }else{
            echo "password is wrong";
        }
    }else{
        echo "username or password is incorrect";
    }

    }
}

}
?>

<?php 

$app=new App;



if(isset($_POST["submit"])){
	$app->startingSession();

    $user=$_POST["user"];
    $email= $_POST["email"];
    $password= $_POST["password"];
    
    
    
    
    $app->login($email,$password,$user);
}

?>





<!doctype html>
<html lang="en">
  <head>
  	<title>Pastelco Login</title>
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
					<h2 class="heading-section">Login to Pastelco!</h2>
				</div>
			</div>
			<div class="row justify-content-center">
				<div class="col-md-6 col-lg-4">
					<div class="login-wrap p-0">
		      	<form action="login.php" method ="POST" class="signin-form">
				  <div class="">
				  <div class="form-group">
					  
					  <select name="user" class="form-control" placeholder="Select User">
						<option value="user">User</option>
						<option value="rider">Rider</option>
						<option value="admin">Admin</option>
					  </select>
				  </div>
			  </div>
		      		<div class="form-group">
		      			<input type="email" name= "email" class="form-control" placeholder="Email" required>
		      		</div>
	            	<div class="form-group">
	              		<input type="password" id="password-field" name="password" type="password" class="form-control" placeholder="Password" required>
	              		<span toggle="#password-field" class="fa fa-fw fa-eye field-icon toggle-password"></span>
	            	</div>
	            	<div class="form-group">
	            		<button name="submit" type="submit" class="form-control btn btn-primary submit px-3">Login</button>
	            	</div>
	          </form>
	          <p class="w-100 text-center">&mdash; Or Sign In as New User &mdash;</p>
	          <div class="social d-flex text-center">
	          	<a href="signin.php" class="px-2 py-2 mr-md-1 rounded"><span class="ion-logo-facebook mr-2"></span> New User </a>
	          </div>
			  <div class="social d-flex text-center mt-3">
				<a href="index.html" class="px-2 py-2 mr-md-1 rounded"><span class="ion-logo-facebook mr-2"></span> Go Back </a>
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

