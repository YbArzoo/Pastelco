<?php
require 'db_connection.php';
session_start();

// Function to get flavour details
function get_flavour_details($flavour_id) {
    global $app; // Use the global $app variable
    $query = "SELECT * FROM flavour WHERE flavour_id = :flavour_id";
    $stmt = $app->link->prepare($query);
    $stmt->execute(['flavour_id' => $flavour_id]);
    return $stmt->fetch(PDO::FETCH_ASSOC);
}

// Function to get frosting details
function get_frosting_details($frosting_id) {
    global $app;
    $query = "SELECT * FROM frosting WHERE frost_id = :frost_id";
    $stmt = $app->link->prepare($query);
    $stmt->execute(['frost_id' => $frosting_id]);
    return $stmt->fetch(PDO::FETCH_ASSOC);
}

// Function to get topping details
function get_topping_details($topping_id) {
    global $app;
    $query = "SELECT * FROM toppings WHERE topping_id = :topping_id";
    $stmt = $app->link->prepare($query);
    $stmt->execute(['topping_id' => $topping_id]);
    return $stmt->fetch(PDO::FETCH_ASSOC);
}

// Function to get beverage details
function get_beverage_details($beverage_id) {
    global $app;
    $query = "SELECT * FROM beverage WHERE beverage_id = :beverage_id";
    $stmt = $app->link->prepare($query);
    $stmt->execute(['beverage_id' => $beverage_id]);
    return $stmt->fetch(PDO::FETCH_ASSOC);
}

// Check if the form has been submitted and data is available
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    // Ensure the form was submitted with a selection for flavour, frosting, and topping
    if (!isset($_POST['flavour']) || !isset($_POST['frosting']) || !isset($_POST['topping'])) {
        // If any selection is missing, redirect back to order.php with an error message
        header("Location: order.php?error=missing_selection");
        exit;
    }

    // Capture selected items from POST request
    $flavour_id = $_POST['flavour'];
    $frosting_id = $_POST['frosting'];
    $topping_id = $_POST['topping'];
    $beverage_id = isset($_POST['beverage']) ? $_POST['beverage'] : null;

    // Fetch selected items from the database using the selected IDs
    $app = new App();
    $flavour = get_flavour_details($flavour_id);
    $frosting = get_frosting_details($frosting_id);
    $topping = get_topping_details($topping_id);
    $beverage = $beverage_id ? get_beverage_details($beverage_id) : null;

    // Add fixed delivery charge
    $delivery_charge = 200;
    $total_price = $flavour['flavour_price'] + $frosting['frost_price'] + $topping['topping_price'] + ($beverage ? $beverage['bev_price'] : 0);
    $total_price_with_delivery = $total_price + $delivery_charge;


    //cake table
    $query_cart1 = "INSERT INTO cake (flavour_id, frost_id,topping_id,user_id) VALUES (:flavour_id,:frost_id,:topping_id,:user_id)";
        $stmt_cart1 = $app->link->prepare($query_cart1);
        $stmt_cart1->execute([
            ':flavour_id' => $flavour['flavour_id'], // Modify as needed
            ':frost_id' => $frosting['frost_id'],
            ':topping_id'=>$topping['topping_id'],
            ':user_id'=>$_SESSION['id']
        ]);

        $cake_id = $app->link->lastInsertId();




   
    // Handle order confirmation

    if (isset($_POST['confirm_order'])) {
        // Generate a new cart entry in the cart table
        $query_cart = "INSERT INTO cart (cost, cake_id) VALUES (:cost, :cake_id)";
        $stmt_cart = $app->link->prepare($query_cart);
        $stmt_cart->execute([
            ':cost' => $total_price_with_delivery,
            ':cake_id' => $cake_id
        ]);

        // Get the last inserted cart_id
        $cart_id = $app->link->lastInsertId();

        // Fetch the cake details using cake_id to get flavour_id, frost_id, and topping_id
        $cake_query = "SELECT flavour_id, frost_id, topping_id FROM cake WHERE cake_id = :cake_id";
        $cake_stmt = $app->link->prepare($cake_query);
        $cake_stmt->execute(['cake_id' => $cake_id]);
        $cake_details = $cake_stmt->fetch(PDO::FETCH_ASSOC);

        // Insert details into the received_order table
        $query = "INSERT INTO received_order (admin_id, cart_id, flavour_id, frost_id, topping_id, beverage_id, total_cost) VALUES (:admin_id, :cart_id, :flavour_id, :frost_id, :topping_id, :beverage_id, :total_cost)";
        $stmt = $app->link->prepare($query);
        $stmt->execute([
            ':admin_id' => 1, // Replace with your logic to get actual admin ID
            ':cart_id' => $cart_id,
            ':flavour_id' => $flavour['flavour_id'], // Use the retrieved flavour_id
            ':frost_id' => $frosting['frost_id'], // Use the retrieved frost_id
            ':topping_id' => $topping['topping_id'], // Use the retrieved topping_id
            ':beverage_id' => $beverage ? $beverage['beverage_id'] : null, // Optional
            ':total_cost' => $total_price_with_delivery,
        ]);

        // Redirect to user.php after successful order confirmation
        header("Location: user.php");
        exit; // Ensure to exit after redirection

        
    }

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
                <a href="user.php" class="nav-item nav-link active">Return Home</a>
                <a href="logout.php" class="btn btn-square-login">Logout</a>

            </div>
        </div>
    </nav>
    <!-- Navbar End -->


    <!-- Page Header Start -->
    <div class="container-fluid bg-dark bg-img p-5 mb-5">
        <div class="row">
            <div class="col-12 text-center">
                <h1 class="display-4 text-uppercase text-white">Confirm Your Orders</h1>
            </div>
        </div>
    </div>
    <!-- Page Header End -->


    <!-- Products Start -->
    <div class="container">
        <h1 class="display-4 text-uppercase text-center">Checkout</h1>

        <table class="table table-bordered">
            <thead>
                <tr>
                    <th>Item</th>
                    <th>Name</th>
                    <th>Price</th>
                </tr>
            </thead>
            <!-- Display the selected flavour -->
            <?php if ($flavour): ?>
            <tr>
                <td>Flavour</td>
                <td><?php echo htmlspecialchars($flavour['flavour_name']); ?></td>
                <td><?php echo htmlspecialchars($flavour['flavour_price']); ?></td>
            </tr>
            <?php endif; ?>

             <!-- Display the selected frosting -->
            <?php if ($frosting): ?>
            <tr>
                <td>Frosting</td>
                <td><?php echo htmlspecialchars($frosting['frost_name']); ?></td>
                <td><?php echo htmlspecialchars($frosting['frost_price']); ?></td>
            </tr>
            <?php endif; ?>

            <!-- Display the selected topping -->
            <?php if ($topping): ?>
            <tr>
                <td>Topping</td>
                <td><?php echo htmlspecialchars($topping['topping_name']); ?></td>
                <td><?php echo htmlspecialchars($topping['topping_price']); ?></td>
            </tr>
            <?php endif; ?>

            <!-- Display the selected beverage if available -->
            <?php if ($beverage): ?>
            <tr>
                <td>Beverage</td>
                <td><?php echo htmlspecialchars($beverage['bev_name']); ?></td>
                <td><?php echo htmlspecialchars($beverage['bev_price']); ?></td>
            </tr>
            <?php endif; ?>
        </table>

        <!-- Display delivery charge and total price including delivery -->
        <h3>Delivery Charge: ৳<?php echo $delivery_charge; ?></h3>
        <h3>Total Price (including delivery): ৳<?php echo htmlspecialchars($total_price_with_delivery); ?></h3>


        <form method="POST" action="checkout.php">
            <input type="hidden" name="flavour" value="<?php echo $flavour_id; ?>">
            <input type="hidden" name="frosting" value="<?php echo $frosting_id; ?>">
            <input type="hidden" name="topping" value="<?php echo $topping_id; ?>">
            <?php if ($beverage_id): ?>
                <input type="hidden" name="beverage" value="<?php echo $beverage_id; ?>">
            <?php endif; ?>
            
            <button type="submit" name="confirm_order" class="btn btn-primary">Confirm My Order</button>
        </form>



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