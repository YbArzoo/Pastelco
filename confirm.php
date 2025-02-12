
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
//place something in the table
public function execute_command($query,$arr){
    
    $temp= $this->link->prepare($query);
    $temp->execute($arr);

}


}
?>

<?php
session_start();
?>

<?php

$app= new App;

$query="select * from forward_order where rider_id=:rider_id";
$arr=array(
    ":rider_id"=>$_SESSION['id']
);

$rows=$app->selectAll($query,$arr);

?>

<?php 

if(isset($_POST['select'])){

    $temp_cart_id=$_POST['select'];
    $temp_rider_id=$_SESSION['id'];
    $query1="delete from forward_order where cart_id=:cart_id";
    $arr1=array(
        
        ":cart_id"=>$temp_cart_id
    );
    $app->execute_command($query1,$arr1);


    $query2="update parcel set rider_id=:rider_id where cart_id=:cart_id";
    $arr2=array(
        ":rider_id"=>$temp_rider_id,
        ":cart_id"=>$temp_cart_id
    );
    $app->execute_command($query2,$arr2);

    $query3="select * from parcel where cart_id=:cart_id";
    $arr3=array(
        
        ":cart_id"=>$temp_cart_id
    );
    $a_row=$app->selectAll($query3,$arr3);

    $temp_user_id=$a_row['user_id'];
    $temp_notification="order completed";
    $query4="update place_order set notification=:notification where cart_id=:cart_id";
    $arr4=array(
        ":notification"=>$temp_notification,
        ":cart_id"=>$temp_cart_id
    );
    $app->execute_command($query4,$arr4);

    
    $query5="select * from rider where rider_id=:rider_id";
    $arr5=array(
        
        ":rider_id"=>$_SESSION['id']
    );
    $rider=$app->selectAll($query5,$arr5);

    $rider_cost=$rider['cost'];
    $rider_dele=$rider['delivery_completed'];
    $query6="update rider set cost=:cost where rider_id=:rider_id";
    $rider_cost=$rider['cost'];

    $arr6=array(
        ":cost"=>$rider_cost,
        ":rider_id"=>$_SESSION['id']
    );
    $app->execute_command($query6,$arr6);

    $rider_dele = $rider['delivery_completed'];
    $query7="update rider set delivery_completed=:delivery_completed where rider_id=:rider_id";
    $arr7 = array(
        ":delivery_completed" => $rider_dele, // Use the variable inside the array
        ":rider_id" => $_SESSION['id']
    );
    $app->execute_command($query7,$arr7);

    




    echo "<script>window.location.href='http://localhost/pastelco/confirm.php'</script>";
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
                <a href="riders_panel.php" class="nav-item nav-link active">Return to Rider Panel</a>
                <a href="login.html" class="btn btn-square-login">Login</a>

            </div>
        </div>
    </nav>
    <!-- Navbar End -->


    <!-- Page Header Start -->
    <div class="container-fluid bg-dark bg-img p-5 mb-5">
        <div class="row">
            <div class="col-12 text-center">
                <h1 class="display-4 text-uppercase text-white">Order List</h1>
            </div>
        </div>
    </div>
    <!-- Page Header End -->


    <!-- Products Start -->
    <div class="container-fluid about py-5">
        <div class="container">
            <div class="section-title position-relative text-center mx-auto mb-5 pb-3" style="max-width: 600px;">
                
            </div>
            
                <div class="tab-content">
                    <div id="tab-1" class="tab-pane fade show p-0 active">
                        <div class="row g-3">

                            
                            
                            
                        

                            
                            <?php foreach ($rows as $row): ?>   

                                <?php 
                                    $cart_id=$row['cart_id'];
                                    $query8="select * from cart where cart_id=:cart_id";
                                    $arr8=array(
                                        ":cart_id"=>$cart_id
                                    );
                                    $new_row=$app->selectAll($query8,$arr8);




                                ?>

                                <div class="col-lg-6">
                                        <div class="d-flex h-100">
                                            <div class="flex-shrink-0">
                                            <img class="img-fluid" src="img/map.png" alt="" style="width: 150px; height: 150px;">
                                </div>
                                            <div class="d-flex flex-column justify-content-center text-start bg-secondary border-inner px-4">
                                                <h4> price:<?php echo $new_row['cost'];?>
                                <h5 class="text-uppercase"><?php echo $new_row["cart_id"]; ?></h5>
                                                <span><?php echo $new_row["house_number"]; ?></span>
                                                <span><?php echo $new_row["road_number"]; ?></span>
                                                <span><?php echo $new_row["area_block_sector"]; ?></span>
                                                <span><?php echo $new_row["city"]; ?></span>
                                                <form action="confirm.php.php" method="POST">
                                                <button name="select" value="<?php echo $new_row['cart_id'];?>" class="btn btn-primary w-100 py-3" type="submit">Hand Over</button>
                                </form>
                                            </div>
                                        </div>
                                </div>


                                <?php endforeach ?> 

                        
                        </div>
                    </div>
                    
                </div>
            </div>
        </div>
    </div>
    <!-- Products End -->
    

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
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.0.0/dist/js/bootstrap.bundle.min.js"></script>
    <script src="lib/easing/easing.min.js"></script>
    <script src="lib/waypoints/waypoints.min.js"></script>
    <script src="lib/counterup/counterup.min.js"></script>
    <script src="lib/owlcarousel/owl.carousel.min.js"></script>

    <!-- Template Javascript -->
    <script src="js/main.js"></script>
</body>

</html>