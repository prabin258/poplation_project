<?php
// Establish a database connection
$line = new mysqli("localhost", "root", "");

// Select the "population" database for further operations
$line->select_db("population");

// Initialize variables to store username and password from the form
$username = "";
$password = "";

// Check if the form was submitted via POST method
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Retrieve and sanitize the input data from the form
    if (isset($_POST["username"])) {
        $username = $_POST["username"];
        $password = md5($_POST["password"]); // Encrypt the password using md5 hash
    }
}

// Create an array to store the authentication result
$giver = array('result' => FALSE);

// Prepare and execute a SQL query to verify the username and password
$verifier = "SELECT * FROM allowed WHERE username='$username'";
$result = mysqli_query($line, $verifier);

// Fetch the user data from the result set
$user_data = mysqli_fetch_assoc($result);

// Check if the password matches the stored password in the database
if ($user_data['pasword'] == $password) {
    // Set the authentication result to TRUE
    $giver['result'] = TRUE;
}

// Set the response content type to JSON
header('Content-Type: application/json');

// Send the JSON response containing the authentication result
echo json_encode($giver);
?>
