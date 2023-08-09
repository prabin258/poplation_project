
<?php
// Establish a database connection
$line = new mysqli("localhost", "root", "");
if (!$line) {
    die();
}

// Create the "population" database if it does not exist
$databasequery = "CREATE DATABASE IF NOT EXISTS population";
$result1 = $line->query($databasequery);
$line->select_db("population");

$cityData = array(
    "options" => array(),
    "content" => array(),
    "changing" => 0
);

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $country = $_POST["country"];
    $city = $_POST["city"];
    $population_type = $_POST["population_type"];

    if (strlen($country) < 1) {
        $query3 = "SELECT country FROM countries_available";
        $result3 = $line->query($query3);
        if ($result3->num_rows > 0) {
            while ($row = $result3->fetch_assoc()) {
                $countryName = $row["country"];
                $cityData["options"][] = $countryName;
                $cityData["changing"] = 'country';
            }
            $query2 = "SELECT old, young, child FROM countries_available WHERE country='$country'";
            $result2 = $line->query($query2);
            $row2 = $result2->fetch_assoc();
            $cityData["content"][] = $row2['old'];
            $cityData["content"][] = $row2['young'];
            $cityData["content"][] = $row2['child'];
    
        }
    } elseif (strlen($city) < 1) {
        $query = "SELECT '$city' FROM '$country'";
        $result4 = $line->query($query);

        if ($result4->num_rows > 0) {
            while ($row = $result4->fetch_assoc()) {
                $cityName = $row["city_name"];
                $cityData["options"][] = $cityName;
            }
        }

        $query2 = "SELECT old, young, child FROM '$country' WHERE city='$city'";
        $result2 = $line->query($query2);
        $row = $result2->fetch_assoc();

        $cityData["content"][] = $row['old'];
        $cityData["content"][] = $row['young'];
        $cityData["content"][] = $row['child'];
        $cityData["changing"] = 'city';
    } else {
        $query5 = "SELECT old, young, child FROM '$country' WHERE city='$city'";
        $result5 = $line->query($query5);
        $row = $result5->fetch_assoc();

        $cityData["content"][] = $row['old'];
        $cityData["content"][] = $row['young'];
        $cityData["content"][] = $row['child'];
        $cityData["changing"] = 'population_type';
    }
}

header("Content-Type: application/json");

// Encode the array to JSON and send it
echo json_encode($cityData);

// Close the database connection
$line->close();
?>