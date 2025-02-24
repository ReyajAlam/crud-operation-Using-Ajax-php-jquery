<?php 
include 'data.php';
$user = new User;
session_start();
$isLog = isset($_SESSION['user_id']);
if ($_SERVER["REQUEST_METHOD"] == "POST" && !$isLog) {
    $username = $_POST['username'];
    $password = $_POST['password'];
    if ($user->login($username, $password)) {
        $isLog = true;
    } else {
        $error = "Wrong usr and pass";
    }
}
?>
<h2>Login</h2>
<?php 
if ($isLog) {
    echo "<p>You are logged in</p>";
    echo "<a href='#' id='logout'>Logout</a>";
} else {
    if (isset($error)) {
        echo "<p style='color: red;'>$error</p>";
    }
?>
    <form method="POST" id="login">
        <label for="username">Username:</label>
        <input type="text" name="username" required><br><br>
        <label for="password">Password:</label>
        <input type="password" name="password" required><br><br>
        <input type="submit" value="Log In">
    </form>
<?php 
}
?>