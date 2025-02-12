<?php
// Check if there was a redirect due to missing selection
if (isset($_GET['error']) && $_GET['error'] == 'missing_selection') {
    echo "<p style='color:red; text-align:center;'>Please select one item from each category (flavour, frosting, topping).</p>";
}
?>


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
}
?>

<?php
$app= new App;
$query_flavour="select * from flavour";
$query_frosting = "select * from frosting";
$query_toppings = "select * from toppings";
$query_beverage = "select * from beverage";

$all_flavour= $app->selectAll_query($query_flavour);
$all_frosting= $app->selectAll_query($query_frosting);
$all_toppings= $app->selectAll_query($query_toppings); 
$all_beverage= $app->selectAll_query($query_beverage);

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
                <a href="user.php" class="nav-item nav-link active">Return to Home</a>
                <a href="logout.php" class="btn btn-square-login">Logout</a>

            </div>
        </div>
    </nav>
    <!-- Navbar End -->


    <!-- Page Header Start -->
    <div class="container-fluid bg-dark bg-img p-5 mb-5">
        <div class="row">
            <div class="col-12 text-center">
                <h1 class="display-4 text-uppercase text-white">Menu & Pricing</h1>
            </div>
        </div>
    </div>
    <!-- Page Header End -->


    <!-- Products Start -->
    <div class="container-fluid about py-5">
        <div class="container">
            <div class="section-title position-relative text-center mx-auto mb-5 pb-3" style="max-width: 600px;">
                <h1 class="display-4 text-uppercase">Explore Our Cakes</h1>
            </div>
            
            <div class="tab-class text-center">
                <ul class="nav nav-pills d-inline-flex justify-content-center bg-dark text-uppercase border-inner p-4 mb-5 mt-5">
                    <li class="nav-item">
                        <a class="nav-link text-white active" data-bs-toggle="pill" href="#flavours">Flavours</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link text-white" data-bs-toggle="pill" href="#frosting">Frosting</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link text-white" data-bs-toggle="pill" href="#toppings">Toppings</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link text-white" data-bs-toggle="pill" href="#beverages">Beverages</a>
                    </li>
                </ul>
                
                <form method="POST" action="checkout.php">
                    <!-- FOR FLAVOURS -->
                    <div class="tab-content">
                        <div id="flavours" class="tab-pane fade show p-0 active">
                            <div class="row g-3">
                                <?php foreach ($all_flavour as $row): ?>   
                                    <div class="col-lg-6">
                                        <div class="d-flex h-100">
                                            <div class="flex-shrink-0">
                                                <img class="img-fluid" src="img/Flavours/<?php echo $row["flavour_image"]; ?>" alt="" style="width: 200px; height: 200px;">
                                            </div>
                                            <div class="d-flex flex-column justify-content-center text-start bg-secondary border-inner px-4">
                                                <h5 class="text-uppercase"><?php echo $row["flavour_name"]; ?></h5>
                                                <span><?php echo $row["flavour_price"]; ?></span>
                                                <input type="radio" name="flavour" value="<?php echo $row['flavour_id']; ?>" data-price="<?php echo $row['flavour_price']; ?>" required>
                                            </div>
                                        </div>
                                    </div>
                                <?php endforeach ?> 
                            </div>
                        </div>

                        <!-- FOR FROSTING -->
                        <div id="frosting" class="tab-pane fade show p-0">
                            <div class="row g-3">
                                <?php foreach ($all_frosting as $row): ?>   
                                    <div class="col-lg-6">
                                        <div class="d-flex h-100">
                                            <div class="flex-shrink-0">
                                                <img class="img-fluid" src="img/Frosting/<?php echo $row["frost_image"]; ?>" alt="" style="width: 200px; height: 200px;">
                                            </div>
                                            <div class="d-flex flex-column justify-content-center text-start bg-secondary border-inner px-4">
                                                <h5 class="text-uppercase"><?php echo $row["frost_name"]; ?></h5>
                                                <span><?php echo $row["frost_price"]; ?></span>
                                                <input type="radio" name="frosting" value="<?php echo $row['frost_id']; ?>" data-price="<?php echo $row['frost_price']; ?>" required>
                                            </div>
                                        </div>
                                    </div>
                                <?php endforeach ?> 
                            </div>
                        </div>

                        <!-- FOR TOPPINGS -->
                        <div id="toppings" class="tab-pane fade show p-0">
                            <div class="row g-3">
                                <?php foreach ($all_toppings as $row): ?>   
                                    <div class="col-lg-6">
                                        <div class="d-flex h-100">
                                            <div class="flex-shrink-0">
                                                <img class="img-fluid" src="img/Toppings/<?php echo $row["topping_image"]; ?>" alt="" style="width: 200px; height: 200px;">
                                            </div>
                                            <div class="d-flex flex-column justify-content-center text-start bg-secondary border-inner px-4">
                                                <h5 class="text-uppercase"><?php echo $row["topping_name"]; ?></h5>
                                                <span><?php echo $row["topping_price"]; ?></span>
                                                <input type="radio" name="topping" value="<?php echo $row['topping_id']; ?>" data-price="<?php echo $row['topping_price']; ?>" required>
                                            </div>
                                        </div>
                                    </div>
                                <?php endforeach ?> 
                            </div>
                        </div>

                        <!-- FOR BEVERAGES -->
                        <div id="beverages" class="tab-pane fade show p-0">
                            <div class="row g-3">
                                <?php foreach ($all_beverage as $row): ?>   
                                    <div class="col-lg-6">
                                        <div class="d-flex h-100">
                                            <div class="flex-shrink-0">
                                                <img class="img-fluid" src="img/Baverage/<?php echo $row["bev_image"]; ?>" alt="" style="width: 200px; height: 200px;">
                                            </div>
                                            <div class="d-flex flex-column justify-content-center text-start bg-secondary border-inner px-4">
                                                <h5 class="text-uppercase"><?php echo $row["bev_name"]; ?></h5>
                                                <span><?php echo $row["bev_price"]; ?></span>
                                                <input type="radio" name="beverage" value="<?php echo $row['beverage_id']; ?>" data-price="<?php echo $row['bev_price']; ?>">
                                            </div>
                                        </div>
                                    </div>
                                <?php endforeach ?> 
                            </div>
                        </div>
                    </div>
                
                    <!-- Submit Button -->
                    <form action="checkout.php" method="POST">
    
                        <!-- Submit Button -->
                        <button type="submit" class="btn btn-primary mt-4">Proceed to Checkout</button>
                    </form>

                </form>
            </div>
        </div>
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

    <script>
        // Function to calculate total price
        function calculateTotalPrice() {
            const flavourSelect = document.getElementById('flavourSelect');
            const frostingSelect = document.getElementById('frostingSelect');
            const toppingSelect = document.getElementById('toppingSelect');
            const beverageSelect = document.getElementById('beverageSelect');

            // Get the prices of the selected items
            const flavourPrice = parseFloat(flavourSelect.options[flavourSelect.selectedIndex].getAttribute('data-price')) || 0;
            const frostingPrice = parseFloat(frostingSelect.options[frostingSelect.selectedIndex].getAttribute('data-price')) || 0;
            const toppingPrice = parseFloat(toppingSelect.options[toppingSelect.selectedIndex].getAttribute('data-price')) || 0;
            const beveragePrice = parseFloat(beverageSelect.options[beverageSelect.selectedIndex].getAttribute('data-price')) || 0;

            // Calculate the total price
            const totalPrice = flavourPrice + frostingPrice + toppingPrice + beveragePrice;

            // Display the total price
            document.getElementById('totalPrice').innerText = 'Total Price: $' + totalPrice.toFixed(2);

            // Set the total price in the hidden input to send to checkout.php
            document.getElementById('totalPriceInput').value = totalPrice.toFixed(2);
        }

        // Ensure user selects one item from flavour, frosting, and topping
        document.querySelector('form').addEventListener('submit', function(event) {
            // Get all selected radio button values
            var flavour = document.querySelector('input[name="flavour"]:checked');
            var frosting = document.querySelector('input[name="frosting"]:checked');
            var topping = document.querySelector('input[name="topping"]:checked');

            // Check if each required item is selected
            if (!flavour || !frosting || !topping) {
                event.preventDefault(); // Prevent form submission
                alert('Please select one item from each category (flavour, frosting, topping).');
            }
        });
    </script>



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






