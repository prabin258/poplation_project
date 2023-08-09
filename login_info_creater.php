<?php
// Establish a database connection
$line = new mysqli("localhost", "root", "");
if(! $line){
    die();
}

// Create the "exam" database if it does not exist
$databasequery = "CREATE DATABASE IF NOT EXISTS population";
$result1 = $line->query($databasequery);


// Create the "allowed" table if it does not exist
$createlogindata = "CREATE TABLE IF NOT EXISTS allowed(
    username VARCHAR(225) NOT NULL UNIQUE,
    pasword VARCHAR(225) NOT NULL
)";
$line->select_db("population");
$result2 = $line->query($createlogindata);
// Check if the table creation was successful
if (! $result2) {
    die();
}

$username = "admin";
$password = md5("root");

// Insert the admin credentials into the "allowed" table
$result3 = $line->query("INSERT INTO allowed (username, pasword) VALUES ('$username','$password')");

// Check if the data insertion was successful
if ($result3) {
    echo 'Successfully inserted data into the "allowed" table';
} else {
    echo 'Failed to insert data into the "allowed" table';
}

// Close the database connection
$line->close();
?>
