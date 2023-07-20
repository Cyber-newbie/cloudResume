<?php
ini_set('log_errors', 1);
ini_set('error_log', '/path/to/error.log');
// Database configuration
$servername = "localhost";
$username = "user1";
$password = "123";
$dbname = "tester"; // Replace with your actual database name

// Create connection
$conn = new mysqli($servername, $username, $password, $dbname);

// Check connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Function to sanitize user input
function sanitize_input($data) {
    $data = trim($data);
    $data = stripslashes($data);
    $data = htmlspecialchars($data);
    return $data;
}

// Create operation
if (isset($_POST['create'])) {
    $personID = sanitize_input($_POST['personID']);
    $lastName = sanitize_input($_POST['lastName']);
    $firstName = sanitize_input($_POST['firstName']);
    $address = sanitize_input($_POST['address']);
    $city = sanitize_input($_POST['city']);

    $sql = "INSERT INTO Persons (PersonID, LastName, FirstName, Address, City) VALUES ('$personID', '$lastName', '$firstName', '$address', '$city')";
    
    if ($conn->query($sql) === TRUE) {
        echo "New record created successfully.";
    } else {
        echo "Error: " . $sql . "<br>" . $conn->error;
    }
}

// Read operation
if (isset($_POST['read'])) {
    $sql = "SELECT * FROM Persons";
    $result = $conn->query($sql);

    if ($result->num_rows > 0) {
        echo "<table><tr><th>PersonID</th><th>LastName</th><th>FirstName</th><th>Address</th><th>City</th></tr>";
        while ($row = $result->fetch_assoc()) {
            echo "<tr><td>" . $row["PersonID"] . "</td><td>" . $row["LastName"] . "</td><td>" . $row["FirstName"] . "</td><td>" . $row["Address"] . "</td><td>" . $row["City"] . "</td></tr>";
        }
        echo "</table>";
    } else {
        echo "No records found.";
    }
}

// Update operation
if (isset($_POST['update'])) {
    $personID = sanitize_input($_POST['personID']);
    $lastName = sanitize_input($_POST['lastName']);
    $firstName = sanitize_input($_POST['firstName']);
    $address = sanitize_input($_POST['address']);
    $city = sanitize_input($_POST['city']);

    $sql = "UPDATE Persons SET LastName='$lastName', FirstName='$firstName', Address='$address', City='$city' WHERE PersonID='$personID'";
    
    if ($conn->query($sql) === TRUE) {
        echo "Record updated successfully.";
    } else {
        echo "Error updating record: " . $conn->error;
    }
}

// Delete operation
if (isset($_POST['delete'])) {
    $personID = sanitize_input($_POST['personID']);

    $sql = "DELETE FROM Persons WHERE PersonID='$personID'";
    
    if ($conn->query($sql) === TRUE) {
        echo "Record deleted successfully.";
    } else {
        echo "Error deleting record: " . $conn->error;
    }
}

$conn->close();
?>

<!DOCTYPE html>
<html>
<head>
    <title>CRUD Operations</title>
</head>
<body>
    <!-- Create form -->
    <h2>Create a new record:</h2>
    <form method="post">
        PersonID: <input type="text" name="personID" required><br>
        LastName: <input type="text" name="lastName" required><br>
        FirstName: <input type="text" name="firstName" required><br>
        Address: <input type="text" name="address" required><br>
        City: <input type="text" name="city" required><br>
        <input type="submit" name="create" value="Create">
    </form>

    <!-- Read records -->
    <h2>Records in the database:</h2>
    <form method="post">
        <input type="submit" name="read" value="Read">
    </form>

    <!-- Update form -->
    <h2>Update a record:</h2>
    <form method="post">
        PersonID: <input type="text" name="personID" required><br>
        LastName: <input type="text" name="lastName" required><br>
        FirstName: <input type="text" name="firstName" required><br>
        Address: <input type="text" name="address" required><br>
        City: <input type="text" name="city" required><br>
        <input type="submit" name="update" value="Update">
    </form>

    <!-- Delete form -->
    <h2>Delete a record:</h2>
    <form method="post">
        PersonID: <input type="text" name="personID" required><br>
        <input type="submit" name="delete" value="Delete">
    </form>
</body>
</html>
