<?php
require_once 'data.php';
$user = new User();
if (isset($_GET['id'])) {
    if ($user->delete($_GET['id'])) {
        // header("Location: show.php");
    } else {
        echo "Failed to delete user!";
    }
}
?>