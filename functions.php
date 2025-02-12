<?php
require 'db_connection.php';
session_start();

// Function to get flavour details
function get_flavour_details($flavour_id, $app) {
    $query = "SELECT * FROM flavour WHERE flavour_id = :flavour_id";
    $stmt = $app->link->prepare($query);
    $stmt->execute(['flavour_id' => $flavour_id]);
    return $stmt->fetch(PDO::FETCH_ASSOC);
}

// Function to get frosting details
function get_frosting_details($frosting_id, $app) {
    $query = "SELECT * FROM frosting WHERE frost_id = :frost_id";
    $stmt = $app->link->prepare($query);
    $stmt->execute(['frost_id' => $frosting_id]);
    return $stmt->fetch(PDO::FETCH_ASSOC);
}

// Function to get topping details
function get_topping_details($topping_id, $app) {
    $query = "SELECT * FROM toppings WHERE topping_id = :topping_id";
    $stmt = $app->link->prepare($query);
    $stmt->execute(['topping_id' => $topping_id]);
    return $stmt->fetch(PDO::FETCH_ASSOC);
}

// Function to get beverage details
function get_beverage_details($beverage_id, $app) {
    $query = "SELECT * FROM beverage WHERE beverage_id = :beverage_id";
    $stmt = $app->link->prepare($query);
    $stmt->execute(['beverage_id' => $beverage_id]);
    return $stmt->fetch(PDO::FETCH_ASSOC);
}
?>
