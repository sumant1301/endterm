<?php

// Function to connect to the database
function connectToDatabase() {
    $servername = "your_mysql_server";
    $username = "your_mysql_username";
    $password = "your_mysql_password";
    $dbname = "mydatabase";

    // Create connection
    $conn = new mysqli($servername, $username, $password, $dbname);

    // Check connection
    if ($conn->connect_error) {
        die("Connection failed: " . $conn->connect_error);
    }

    return $conn;
}

// Function to check if the user with the same name already exists
function doesUserExist($conn, $name) {
    $sql = "SELECT * FROM users WHERE name = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("s", $name);
    $stmt->execute();
    $result = $stmt->get_result();

    return $result->num_rows > 0;
}

// Function to insert user into the database
function insertUser($conn, $name, $email, $phone, $userClass) {
    $sql = "INSERT INTO users (name, email, phone, user_class) VALUES (?, ?, ?, ?)";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("ssss", $name, $email, $phone, $userClass);
    $stmt->execute();
}

// Main logic
if ($_SERVER["REQUEST_METHOD"] === "POST") {
    $name = $_POST["name"];
    $email = $_POST["email"];
    $phone = $_POST["phone"];
    $userClass = $_POST["class"];

    $conn = connectToDatabase();

    if (doesUserExist($conn, $name)) {
        echo "User with the same name already exists";
    } else {
        insertUser($conn, $name, $email, $phone, $userClass);
        echo "User successfully registered";
    }

    $conn->close();
}

?>

