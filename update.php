<?php
require_once 'data.php';
session_start();
$user = new User();
if (!isset($_GET['id']) && !isset($_POST['id'])) {
    echo "No user ID provided";
    exit();
}

$userr = isset($_GET['id']) ? $user->readById($_GET['id']) : null;
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $id = $_POST['id'];
    if (!$userr) {
        $userr = $user->readById($id);
    }
    if (!$userr) {
        echo "Invalid user ID";
        exit();
    }
    if ($_FILES['image']['name']) {
        move_uploaded_file($_FILES['image']['tmp_name'], "uploads/" . $_FILES['image']['name']);
    }   
    $image = $_FILES['image']['name'] ? $_FILES['image']['name'] : $userr['image'];
    if ($user->update($id, $_POST['name'], $_POST['email'], $_POST['username'], $image, $_POST['country'], $_POST['state'])) {
        echo "User updated";
    } else {
        echo "Failed to update user";
    }
    exit();
}
?>
<h2>Update</h2>
<form method="POST" enctype="multipart-form-data" id="update">
    <input type="hidden" name="id" value="<?php echo $userr['id']; ?>">
    <input type="text" name="name" value="<?php echo $userr['name']; ?>" required><br><br>
    <input type="email" name="email" value="<?php echo $userr['email']; ?>" required><br><br>
    <input type="text" name="username" value="<?php echo $userr['username']; ?>" required><br><br>
    <input type="file" name="image"><br>
    <?php if ($userr['image']) echo "<img src='uploads/" . $userr['image'] . "' width='50'><br><br>"; ?>
    <select name="country" id="country" required>
        <option value="UK" 
        <?php if ($userr['country'] == 'UK') echo 'selected'; ?>>UK</option>
        <option value="India" 
        <?php if ($userr['country'] == 'India') echo 'selected'; ?>>India</option>
        <option value="USA" 
        <?php if ($userr['country'] == 'USA') echo 'selected'; ?>>USA</option>
        <option value="Canada" 
        <?php if ($userr['country'] == 'Canada') echo 'selected'; ?>>Canada</option>
        <option value="Australia" 
        <?php if ($userr['country'] == 'Australia') echo 'selected'; ?>>Australia</option>
    </select><br><br>
    <select name="state" id="state" >
        <option value="">Select State</option>
        <?php if ($userr['state']) { ?>
            <option value="<?php echo $userr['state']; ?>"selected><?php echo $userr['state']; ?></option>
        <?php } ?>
    </select><br><br>
    <input type="submit" value="Update">
</form>