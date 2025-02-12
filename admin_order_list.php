

<?php

// Include your database constants and App class definition
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

    public function __construct(){
        $this->connect();
    }

    public function connect(){
        $this->link = new PDO("mysql:host=$this->host;dbname=$this->dbname", $this->user, $this->pass);
    }

    public function selectAll($query, $arr){
        $stmt = $this->link->prepare($query);
        $stmt->execute($arr);
        $allRows = $stmt->fetchAll(PDO::FETCH_ASSOC);
        return $allRows ? $allRows : false;
    }

    public function selectAll_query($query){
        $stmt = $this->link->query($query);
        $allRows = $stmt->fetchAll(PDO::FETCH_ASSOC);
        return $allRows ? $allRows : false;
    }

    public function execute_command($query, $arr){
        $stmt = $this->link->prepare($query);
        $stmt->execute($arr);
    }
}

// Instantiate the App class
$app = new App();

// Fetch all orders with details for a specific admin (assuming admin_id=1)
$query = "
    SELECT ro.*, c.*, 
           f.flavour_name, f.flavour_price, 
           fro.frost_name, fro.frost_price, 
           t.topping_name, t.topping_price, 
           b.bev_name, b.bev_price
    FROM received_order ro
    JOIN cart c ON ro.cart_id = c.cart_id
    LEFT JOIN cake k ON c.cake_id = k.cake_id
    LEFT JOIN flavour f ON k.flavour_id = f.flavour_id
    LEFT JOIN frosting fro ON k.frost_id = fro.frost_id
    LEFT JOIN toppings t ON k.topping_id = t.topping_id
    LEFT JOIN beverage b ON ro.beverage_id = b.beverage_id
    WHERE ro.admin_id = 1
";

$rows = $app->selectAll_query($query);

// Continue with the rest of your code for displaying the orders...

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
                <div class="navbar-nav ms-auto mx-lg-auto py-0">
                <a href="admin.php" class="nav-item nav-link active">Back to Admin Panel</a>
                <a href="rider_list.php" class="nav-item nav-link">Riders</a>
                <a href="user_list.php" class="nav-item nav-link">Users</a>
                <a href="admin_order_list.php" class="nav-item nav-link">Order List</a>
                <a href="hire_rider.php" class="nav-item nav-link">Hire Riders</a>
                <a href="login.php" class="btn btn-square-login">Logout</a>
                </div>

            </div>
        </div>
    </nav>
    <!-- Navbar End -->


    <!-- Page Header Start -->
    <div class="container-fluid bg-dark bg-img p-5 mb-5">
        <div class="row">
            <div class="col-12 text-center">
                <h1 class="display-4 text-uppercase text-white">Orders from Users</h1>
            </div>
        </div>
    </div>
    <!-- Page Header End -->


    <!-- Order List Start -->
    <div class="container-fluid about py-5">
        <div class="container">
            <div class="section-title position-relative text-center mx-auto mb-5 pb-3" style="max-width: 600px;">
            </div>

            <div class="tab-content">
                <div id="tab-1" class="tab-pane fade show p-0 active">
                    <div class="row g-3">

                    <?php if (!$rows): ?>
                        <p>No orders found.</p>
                    <?php else: ?>
                        <?php foreach ($rows as $row): ?>
                            <div class="col-lg-6">
                                <div class="d-flex h-100">
                                    <div class="flex-shrink-0">
                                        <img class="img-fluid" src="img/map.png" alt="" style="width: 150px; height: 150px;">
                                    </div>
                                    <div class="d-flex flex-column justify-content-center text-start bg-secondary border-inner px-4">
                                        <h5 class="text-uppercase"><?php echo htmlspecialchars($row['cart_id']); ?></h5>
                                        <span>Flavour: <?php echo htmlspecialchars($row['flavour_name']); ?> - ৳<?php echo htmlspecialchars($row['flavour_price']); ?></span>
                                        <span>Frosting: <?php echo htmlspecialchars($row['frost_name']); ?> - ৳<?php echo htmlspecialchars($row['frost_price']); ?></span>
                                        <span>Topping: <?php echo htmlspecialchars($row['topping_name']); ?> - ৳<?php echo htmlspecialchars($row['topping_price']); ?></span>
                                        <?php if ($row['bev_name']): ?>
                                            <span>Beverage: <?php echo htmlspecialchars($row['bev_name']); ?> - ৳<?php echo htmlspecialchars($row['bev_price']); ?></span>
                                        <?php endif; ?>
                                        <span>Total Cost: ৳<?php echo htmlspecialchars($row['total_cost']); ?></span>
                                        <form action="admin_order_list.php" method="POST">
                                            <button name="submit" type="submit" class="form-control btn btn-primary submit px-3" value="<?php echo htmlspecialchars($row['cart_id']); ?>">Forward to rider</button>
                                        </form>
                                    </div>
                                </div>
                            </div>
                        <?php endforeach; ?>
                    <?php endif; ?>

                    </div>
                </div>
            </div>
        </div>
    </div>
    <!-- Order List End -->
    
    

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