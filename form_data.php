<?php 
include 'data.php';
session_start();
$images = 'uploads/';
if (!file_exists($images)) {
    mkdir($images, 0755, true);
}
$user = new User();
$submitt = isset($_SESSION['submitt']) ? $_SESSION['submitt'] : false;
$errors = [];
if ($_SERVER["REQUEST_METHOD"] == "POST" && !$submitt) {
    $name = $_POST['name'];
    $email = $_POST['email'];
    $password = $_POST['password'];
    $username = $_POST['username'];
    $image = '';
    $country = $_POST['country'];
    $state = $_POST['state'];
    if (!preg_match("/^[a-zA-Z\s]{3,}$/", $name)) {
        $errors[] = "Name atleast 3 char";
    }
    if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        $errors[] = "Please enter a valid email";
    }
    if (!empty($_FILES['image']['name'])) {
        $image = basename($_FILES['image']['name']);
        $showfile = $images . $image;
        move_uploaded_file($_FILES['image']['tmp_name'], $showfile);
    }
    if (empty($errors)) {
        $user->create($name, $email, $password, $username, $image, $country,$state);
        $submitt = true;
        $_SESSION['submitt'] = true;
    }
}
?>