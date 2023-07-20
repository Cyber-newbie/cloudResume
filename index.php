<?php
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

// Database connection
$servername = "localhost";
$username = "user1";
$password = "123";
$dbname = "tester";

$conn = new mysqli($servername, $username, $password, $dbname);

if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Create or Update operation
if (isset($_POST['submit'])) {
    $username = $_POST['username'];
    $email = $_POST['email'];
    $password = password_hash($_POST['password'], PASSWORD_BCRYPT);

    if (isset($_POST['id'])) {
        $id = $_POST['id'];
        $stmt = $conn->prepare("UPDATE users SET username=?, email=?, password=? WHERE id=?");
        $stmt->bind_param("sssi", $username, $email, $password, $id);
    } else {
        $stmt = $conn->prepare("INSERT INTO users (username, email, password) VALUES (?, ?, ?)");
        $stmt->bind_param("sss", $username, $email, $password);
    }

    if ($stmt->execute()) {
        header("Location: index.php");
        exit();
    } else {
        echo "Error: " . $stmt->error;
    }

    $stmt->close();
}

// Delete operation
if (isset($_GET['delete'])) {
    $id = $_GET['delete'];
    $stmt = $conn->prepare("DELETE FROM users WHERE id=?");
    $stmt->bind_param("i", $id);

    if ($stmt->execute()) {
        header("Location: index.php");
        exit();
    } else {
        echo "Error: " . $stmt->error;
    }

    $stmt->close();
}

// Fetch all users from the database
$sql = "SELECT * FROM users";
$result = $conn->query($sql);
$users = [];

if ($result->num_rows > 0) {
    while ($row = $result->fetch_assoc()) {
        $users[] = $row;
    }
}

$conn->close();
?>

<!-- The rest of your HTML code remains unchanged -->

<!DOCTYPE html>
<html>
<head>
    <title>CRUD Example</title>
</head>
<body>
    <h1>CRUD Example</h1>
<!-- Form to add or update user -->
<form method="post">
    <?php if (isset($_GET['edit'])) : ?>
        <input type="hidden" name="id" value="<?php echo $_GET['edit']; ?>">
    <?php endif; ?>
    <input type="text" name="username" placeholder="Username" value="<?php echo isset($_GET['edit']) ? $users[$_GET['edit']]['username'] : ''; ?>" required>
    <input type="email" name="email" placeholder="Email" value="<?php echo isset($_GET['edit']) ? $users[$_GET['edit']]['email'] : ''; ?>" required>
    <?php if (!isset($_GET['edit'])) : ?>
        <input type="password" name="password" placeholder="Password" required>
    <?php endif; ?>
    <button type="submit" name="submit"><?php echo isset($_GET['edit']) ? 'Update' : 'Add'; ?></button>
</form>

    <!-- Display list of users -->
    <h2>Users List</h2>
    <ul>
        <?php foreach ($users as $index => $user) : ?>
            <li>
                <?php echo $user['username']; ?> - <?php echo $user['email']; ?>
                <a href="?edit=<?php echo $index; ?>">Edit</a>
                <a href="?delete=<?php echo $user['id']; ?>">Delete</a>
            </li>
        <?php endforeach; ?>
    </ul>
</body>
</html>
