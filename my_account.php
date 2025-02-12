<?php

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

//select all just query
public function selectAll_query($query){
    $rows= $this->link->query($query);
    

    $allRows= $rows->fetchAll(PDO::FETCH_ASSOC);

    if($allRows){
        return $allRows;
    }else {
        return false;
    }
}
//select one row
public function selectOne($query,$arr){
    
    $row= $this->link->prepare($query);
    $row->execute($arr);

    $singleRow= $row->fetch(PDO::FETCH_ASSOC);

    if($singleRow){
        return $singleRow;
    }else {
        return false;
    }
}

public function update_password($user,$password,$new_pass,$id){
    

    if ($user=="user"){
        $query="select * from user where user_id=:user_id";
    $login_user= $this->link->prepare($query);
    $login_user->execute(array(
        ":user_id"=>$id
        
    ));
    if($login_user->rowCount()>0){
        $fetch1=$login_user->fetch(PDO::FETCH_ASSOC);
        $old_password_rider=$fetch1['password'];
        if($password==$old_password_rider){
            $query="UPDATE user SET password =:old_password1 WHERE user_id=:user_id";
            $update_location = $this->link->prepare($query);
            $update_location->execute(array(
                ":old_password1"=>$new_pass,
                ":user_id"=>$id
            
            ));
        }else{
            echo "password is wrong";
        }
    }


    }


    

    //raider
    if ($user=="rider"){
        $query="select * from rider where rider_id=:rider_id";
    $login_user= $this->link->prepare($query);
    $login_user->execute(array(
        ":rider_id"=>$id
        
    ));
    if($login_user->rowCount()>0){
        $fetch1=$login_user->fetch(PDO::FETCH_ASSOC);
        $old_password_rider=$fetch1['password'];
        if($password==$old_password_rider){
            $query="UPDATE rider SET password =:old_password1 WHERE rider_id=:rider_id";
            $update_location = $this->link->prepare($query);
            $update_location->execute(array(
                ":old_password1"=>$new_pass,
                ":rider_id"=>$id
            
            ));
        }else{
            echo "password is wrong";
        }
    }

    }
}
//update email
public function update_email($user,$password,$email,$id){
    $old_password1=$email;

    if ($user=="user"){
        $query="select * from user where user_id=:user_id";
    $login_user= $this->link->prepare($query);
    $login_user->execute(array(
        ":user_id"=>$id
        
    ));
    if($login_user->rowCount()>0){
        $fetch1=$login_user->fetch(PDO::FETCH_ASSOC);
        if($password==$fetch1['password']){
            $query="UPDATE user SET email =:old_password1 WHERE user_id=:user_id";
            $update_location = $this->link->prepare($query);
            $update_location->execute(array(
                ":old_password1"=>$old_password1,
                ":user_id"=>$id
            
            ));
        }else{
            echo "password is wrong";
        }
    }

    }

    

    

    //raider
    if ($user=="rider"){
        $query="select * from rider where rider_id=:rider_id";
    $login_user= $this->link->prepare($query);
    $login_user->execute(array(
        ":rider_id"=>$id
        
    ));
    if($login_user->rowCount()>0){
        $fetch1=$login_user->fetch(PDO::FETCH_ASSOC);
        if($password==$fetch1['password']){
            $query="UPDATE rider SET email =:old_password1 WHERE rider_id=:rider_id";
            $update_location = $this->link->prepare($query);
            $update_location->execute(array(
                ":old_password1"=>$old_password1,
                ":rider_id"=>$id
            
            ));
        }else{
            echo "password is wrong";
        }
    }

    }
}
//set location
public function set_location($id,$house,$road,$area,$city){
    $query= "update user set house_number=:house_number where user_id=:user_id";
    $location_user= $this->link->prepare($query);
    $location_user->execute(params: array(
        ":house_number"=>$house,
        ":user_id"=>$id
        
    ));

    $query2= "update user set road_number=:road_number where user_id=:user_id";
    $location_user2= $this->link->prepare($query2);
    $location_user2->execute(array(
        ":road_number"=>$road,
        ":user_id"=>$id
        
    ));

    $query3= "update user set area_block_sector=:area_block_sector where user_id=:user_id";
    $location_user3= $this->link->prepare($query3);
    $location_user3->execute(array(
        ":area_block_sector"=>$area,
        ":user_id"=>$id
        
    ));

    $query4= "update user set city=:city where user_id=:user_id";
    $location_user4= $this->link->prepare($query4);
    $location_user4->execute(array(
        ":city"=>$city,
        ":user_id"=>$id
        
    ));


}
//cashout
public function cash_out($id,$cash){
    $query="select * from rider where rider_id=:rider_id";
    $arr=array(
        ":rider_id"=>$id
    );
    
    $fetch=$this->selectOne($query,$arr);
    $balance=(int)$fetch["balance"];
    if ($balance>$cash){
        $cash1=(int)$cash;
        $new_balance=$balance-$cash1;
        $query1="update rider set balance=:balance where rider_id=:rider_id";
        $arr1=array(
            ":balance"=>$new_balance,
            ":rider_id"=>$id
        );
        
        $this->execute_command($query1,$arr1);
        
    }else{
        
    }

}

//place something in the table
public function execute_command($query,$arr){
    
    $temp= $this->link->prepare($query);
    $temp->execute($arr);

}
// starting sessions
public function startingSession(){
    session_start();
}




}
?>

<?php
$app=new App;
$app->startingSession();
if(isset($_SESSION['user'])){
$app= new App;
if (isset($_SESSION['user'])){
    $main_user=$_SESSION['user'];
    $main_id=$_SESSION['id'];

    
    
    if($main_user=='rider'){
        $query1="select * from rider where rider_id=:rider_id";
        $arr1=array(
            ":rider_id"=>$main_id
        );
        $row=$app->selectOne($query1,$arr1);
    }
    elseif($main_user=='user'){
        $query1="select * from user where user_id=:user_id";
        $arr1=array(
            ":user_id"=>$main_id
        );
        $row=$app->selectOne($query1,$arr1);
    }
}


if(isset($_POST['submit1'])){
    $old_pass=$_POST['old_pass'];
    $new_pass=$_POST['new_pass'];
    
    $app->update_password($main_user,$old_pass,$new_pass,$main_id);

    $int_value="http://localhost/pastelco/my_account.php";
    echo "<script>window.location.href='".$int_value."'</script>";
    //header("Location:http://localhost/pastelco/my_info.php");
    //exit;
}

if(isset($_POST['submit2'])){
    $old_pass=$_POST['pass'];
    $new_email=$_POST['email'];
    
    $app->update_email($main_user,$old_pass,$new_email,$main_id);

    $int_value2="http://localhost/pastelco/my_account.php";
    echo "<script>window.location.href='".$int_value2."'</script>";
    //header("Location:http://localhost/pastelco/my_info.php");
    //exit;
}
if(isset($_POST['submit3'])){
    $house=$_POST['house'];
    $road=$_POST['road'];
    $area=$_POST['zone'];
    $city=$_POST['dist'];

    
    
    $app->set_location($main_id,$house,$road,$area,$city);
    $int_value3="http://localhost/pastelco/my_account.php";
    echo "<script>window.location.href='".$int_value3."'</script>";
    //header("Location:http://localhost/pastelco/my_info.php");
    //exit;
}



if(isset($_POST['submit5'])){
    
    $cash=(int)$_POST['cash'];
    $app->cash_out($main_id,$cash);
    $int_value5="http://localhost/pastelco/my_account.php";
    echo "<script>window.location.href='".$int_value5."'</script>";

    
}
}else{
    $int_value="http://localhost/pastelco/index.php";
      echo "<script>window.location.href='".$int_value."'</script>";
  }
?>



<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="utf-8">
    <title>Pastelco</title>
    <meta content="width=device-width, initial-scale=1.0" name="viewport">
   

    <!-- Favicon -->
    <link href="img/favicon.ico" rel="icon">

    <!-- Google Web Fonts -->
    
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Averia+Serif+Libre:ital,wght@0,300;0,400;0,700;1,300;1,400;1,700&family=Modak&display=swap" rel="stylesheet">
    <!-- Icon Font Stylesheet -->
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.10.0/css/all.min.css" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.4.1/font/bootstrap-icons.css" rel="stylesheet">

    <!-- Libraries Stylesheet -->
    <link href="lib/owlcarousel/assets/owl.carousel.min.css" rel="stylesheet">

    <!-- Customized Bootstrap Stylesheet -->
    <link href="css/bootstrap.min.css" rel="stylesheet">

    <!-- Template Stylesheet -->
    <link href="css/style.css" rel="stylesheet">
</head>

<body>

<!-- Navbar Start -->
<nav class="navbar navbar-expand-lg bg-dark navbar-dark shadow-sm py-3 py-lg-0 px-3 px-lg-0">
    <a href="index.html" class="navbar-brand d-block d-lg-none">
        <h1 class="m-0 text-uppercase text-white">Pastelco</h1>
    </a>
    <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarCollapse">
        <span class="navbar-toggler-icon"></span>
    </button>
    <div class="collapse navbar-collapse" id="navbarCollapse">
        <div class="navbar-nav ms-auto mx-lg-auto py-0">
            <?php if($main_user=='user'): ?>
                <a href="user.php" class="nav-item nav-link active">Back to Home Page</a>
            <?php elseif($main_user=='rider'): ?>
                <a href="riders_panel.php" class="nav-item nav-link active">Back to Home Page</a>
            <?php endif; ?>
        </div>
    </div>
</nav>
<!-- Navbar End -->

<!-- Account panel start -->
<div class="container-fluid pt-5">
    <div class="section-title position-relative text-center mx-auto mb-5 pb-3" style="max-width: 600px;">
        <h1 class="display-4 text-uppercase">Welcome To Your Account</h1>
    </div>
</div>
<!-- Account panel end -->

<div class="container">
    <?php if($main_user=='user'): ?>
        <div class="row mb-4">
            <div class="col-md-6">
                <h4>Name: <?php echo $row['name'];?> </h4>
                <h4>ID: <?php echo $row['user_id'];?> </h4>
                <h4>Email: <?php echo $row['email'];?> </h4>
            </div>
        </div>

        <!-- Update Password Form -->
        <div class="row mb-4">
            <div class="col-md-6">
                <h4>Update Password</h4>
                <form action="my_account.php" method="POST" class="form-group">
                    <label for="old_pass">Old Password:</label>
                    <input type="password" id="old_pass" name="old_pass" class="form-control" required>
                    <label for="new_pass">New Password:</label>
                    <input type="password" id="new_pass" name="new_pass" class="form-control" required><br>
                    <button class="btn btn-primary" name="submit1" type="submit" value="<?php echo $row['user_id'];?>">Update</button>
                </form>
            </div>
        </div>

        <!-- Update Email Form -->
        <div class="row mb-4">
            <div class="col-md-6">
                <h4>Update Email</h4>
                <form action="my_account.php" method="POST" class="form-group">
                    <label for="pass">Password:</label>
                    <input type="password" id="pass" name="pass" class="form-control" required>
                    <label for="email">New Email:</label>
                    <input type="email" id="email" name="email" class="form-control" required><br>
                    <button class="btn btn-primary" name="submit2" type="submit" value="<?php echo $row['user_id'];?>">Update</button>
                </form>
            </div>
        </div>

        <!-- Update Location Form -->
        <div class="row mb-4">
            <div class="col-md-6">
                <h4>Update Location</h4>
                <form action="my_account.php" method="POST" class="form-group">
                    <label for="house">House No:</label>
                    <input type="text" id="house" name="house" class="form-control" required>
                    <label for="road">Road No:</label>
                    <input type="text" id="road" name="road" class="form-control" required>
                    <label for="zone">Area/block/sector:</label>
                    <input type="text" id="zone" name="zone" class="form-control" required>
                    <label for="dist">City:</label>
                    <input type="text" id="dist" name="dist" class="form-control" required><br>
                    <button class="btn btn-primary" name="submit3" type="submit" value="<?php echo $row['user_id'];?>">Update Now</button>
                </form>
            </div>
        </div>
    <?php elseif($main_user=='rider'): ?>
        <div class="row mb-4">
            <div class="col-md-6">
                <h4>Name: <?php echo $row['name'];?> </h4>
                <h4>ID: <?php echo $row['rider_id'];?> </h4>
                <h4>Email: <?php echo $row['email'];?> </h4>
                <h4>Balance: <?php echo $row['balance'];?> </h4>
            </div>
        </div>

        <!-- Update Password Form -->
        <div class="row mb-4">
            <div class="col-md-6">
                <h4>Update Password</h4>
                <form action="my_account.php" method="POST" class="form-group">
                    <label for="old_pass">Old Password:</label>
                    <input type="password" id="old_pass" name="old_pass" class="form-control" required>
                    <label for="new_pass">New Password:</label>
                    <input type="password" id="new_pass" name="new_pass" class="form-control" required><br>
                    <button class="btn btn-primary" name="submit1" type="submit" value="<?php echo $row['rider_id'];?>">Update</button>
                </form>
            </div>
        </div>

        <!-- Cash Out Form -->
        <div class="row mb-4">
            <div class="col-md-6">
                <h4>Cash Out</h4>
                <form action="my_account.php" method="POST" class="form-group">
                    <label for="cash">Enter Amount:</label>
                    <input type="number" id="cash" name="cash" class="form-control" required><br>
                    <button class="btn btn-success" name="submit5" type="submit" value="<?php echo $row['rider_id'];?>">Cash Out</button>
                </form>
            </div>
        </div>
    <?php endif; ?>
</div>



    
    

    <!-- Footer Start -->
    <div class="container-fluid bg-img text-secondary" style="margin-top: 90px">
        <div class="container">
            <div class="row gx-5">
                <div class="col-lg-4 col-md-6 mb-lg-n5">
                    <div class="d-flex flex-column align-items-center justify-content-center text-center h-90 bg-primary border-inner p-4">
                        <a href="index.html" class="navbar-brand">
                            <h1 class="m-0 text-uppercase text-white pastelco-text footer-modak-font">Pastelco</h1>
                        </a>
                        <p class="mt-3">Hoping for a good project mark🍰</p>
                    </div>
                </div>
                <div class="col-lg-8 col-md-6">
                    <div class="row gx-5">
                        <div class="col-lg-4 col-md-12 pt-5 mb-5">
                            <h4 class="text-primary text-uppercase mb-4">Get In Touch</h4>
                            <div class="d-flex mb-2">
                                <i class="bi bi-geo-alt text-primary me-2"></i>
                                <p class="mb-0">123 Uttara, Bangladesh 2.0</p>
                            </div>
                            <div class="d-flex mb-2">
                                <i class="bi bi-envelope-open text-primary me-2"></i>
                                <p class="mb-0">bracu@bracu.ac.bd</p>
                            </div>
                            <div class="d-flex mb-2">
                                <i class="bi bi-telephone text-primary me-2"></i>
                                <p class="mb-0">+0 440 370</p>
                            </div>
                            <div class="d-flex mt-4">
                                <a class="btn btn-lg btn-primary btn-lg-square border-inner rounded-0 me-2" href="#"><i class="fab fa-twitter fw-normal"></i></a>
                                <a class="btn btn-lg btn-primary btn-lg-square border-inner rounded-0 me-2" href="#"><i class="fab fa-facebook-f fw-normal"></i></a>
                                <a class="btn btn-lg btn-primary btn-lg-square border-inner rounded-0 me-2" href="#"><i class="fab fa-linkedin-in fw-normal"></i></a>
                            </div>
                        </div>
                        <div class="col-lg-4 col-md-12 pt-0 pt-lg-5 mb-5">
                            <h4 class="text-primary text-uppercase mb-4">Quick Links</h4>
                            <div class="d-flex flex-column justify-content-start">
                                <a class="text-secondary mb-2" href="#"><i class="bi bi-arrow-right text-primary me-2"></i>Home</a>
                                <a class="text-secondary mb-2" href="#"><i class="bi bi-arrow-right text-primary me-2"></i>About Us</a>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <div class="container-fluid text-secondary py-4" style="background: #111111;">
        <div class="container text-center">
            <p class="mb-0">&copy; <a class="text-white border-bottom" href="#">Pastelco</a>. All Rights Reserved. 
			
			<!--/*** This template is free as long as you keep the footer author’s credit link/attribution link/backlink. If you'd like to use the template without the footer author’s credit link/attribution link/backlink, you can purchase the Credit Removal License from "https://htmlcodex.com/credit-removal". Thank you for your support. ***/-->
			Designed by Y B Arzoo with Groupies</a></p>
        </div>
    </div>
    <!-- Footer End -->


    <!-- Back to Top -->
    <a href="#" class="btn btn-primary border-inner py-3 fs-4 back-to-top"><i class="bi bi-arrow-up"></i></a>


    <!-- JavaScript Libraries -->
    <script src="https://code.jquery.com/jquery-3.4.1.min.js"></script>
    <script src="js/jquery.min.js"></script>
    <script src="js/popper.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.0.0/dist/js/bootstrap.bundle.min.js"></script>
    <script src="js/bootstrap.min.js"></script>
    <script src="lib/easing/easing.min.js"></script>
    <script src="lib/waypoints/waypoints.min.js"></script>
    <script src="lib/counterup/counterup.min.js"></script>
    <script src="lib/owlcarousel/owl.carousel.min.js"></script>

    <!-- Template Javascript -->
    <script src="js/index-main.js"></script>
</body>

</html>