<?php
// Establish a database connection
$line = new mysqli("localhost", "root", "");

// Check for connection errors
if ($line->connect_error) {
    die("Connection failed: " . $line->connect_error);
}

// Select the "population" database for further operations
$line->select_db("population");

// Check if the form was submitted via POST method
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Sanitize and store the input data from the form
    $country_name = strtolower($_POST["country_name"]);
    $city_name = strtolower($_POST["city_name"]);
    $population_old = $_POST["old"];
    $population_young = $_POST["young"];
    $population_child = $_POST["child"];
}

// Store the name of countries in a separate table
$country_name_storer = "CREATE TABLE IF NOT EXISTS countries_available (
    country VARCHAR(200),
    old VARCHAR(300),
    young VARCHAR(300),
    child VARCHAR(300)
)";
$result3 = $line->query($country_name_storer);
if (!$result3) {
    die("Error creating countries_available table: " . $line->error);
}

$query = "SELECT country FROM countries_available WHERE country = '$country_name'";
$result_new = $line->query($query);

// Check if any rows are returned
if ($result_new->num_rows > 0) {
    $current_data_query = "SELECT old, young, child FROM countries_available WHERE country='$country_name'";
    $current_data_result = $line->query($current_data_query);
    if ($current_data_result) {
        $row = $current_data_result->fetch_assoc();
        $oldValue = $row['old'];
        $youngValue = $row['young'];
        $childValue = $row['child'];

        // Modify values as needed
        $oldValue += $population_old;
        $youngValue += $population_young;
        $childValue += $population_child;

        $updateQuery = "UPDATE countries_available SET old='$oldValue', young='$youngValue', child='$childValue' WHERE country='$country_name'";
        $line->query($updateQuery);
    } else {
        echo "Error fetching current data: " . $line->error;
    }
} else {
    $insertQuery = "INSERT INTO countries_available (country, old, young, child) VALUES ('$country_name', '$population_old', '$population_young', '$population_child')";
    if ($line->query($insertQuery) === TRUE) {
        echo "Data inserted into the table.";
    } else {
        echo "Error inserting data: " . $line->error;
    }
}

// Create the "country name" table if it does not exist
$createCountryTableQuery = "CREATE TABLE IF NOT EXISTS $country_name (
    id INT AUTO_INCREMENT PRIMARY KEY,
    city VARCHAR(800),
    old VARCHAR(225),
    young VARCHAR(225),
    child VARCHAR(225)
)";
$result2 = $line->query($createCountryTableQuery);
if ($result2) {
    echo "Successfully created the $country_name table.";
} else {
    echo "Failed to create the $country_name table: " . $line->error;
}

// Prepare and bind the statement to prevent SQL injection
$stmt = $line->prepare("INSERT INTO $country_name (city, old, young, child) VALUES (?, ?, ?, ?)");
$stmt->bind_param("ssss", $city_name, $population_old, $population_young, $population_child);

// Execute the prepared statement to insert data into the table
$result4 = $stmt->execute();

$stmt->close();

// Close the database connection
$line->close();


?>
