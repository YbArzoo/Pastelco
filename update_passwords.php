<?php
define("HOST", "localhost");
define("DBNAME", "pastelco");
define("USER", "root");
define("PASS", "");

try {
    $pdo = new PDO("mysql:host=" . HOST . ";dbname=" . DBNAME, USER, PASS);
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

    $query = "SELECT user_id, password FROM user";
    $stmt = $pdo->prepare($query);
    $stmt->execute();
    $users = $stmt->fetchAll(PDO::FETCH_ASSOC);

    foreach ($users as $user) {
        $user_id = $user['user_id'];
        $hashed_password = password_hash($user['password'], PASSWORD_DEFAULT);
        
        $updateQuery = "UPDATE user SET password = :password WHERE user_id = :user_id";
        $updateStmt = $pdo->prepare($updateQuery);
        $updateStmt->bindParam(':password', $hashed_password);
        $updateStmt->bindParam(':user_id', $user_id);
        $updateStmt->execute();
    }

    echo "Passwords updated successfully!";
} catch (PDOException $e) {
    echo "Error: " . $e->getMessage();
}
?>
