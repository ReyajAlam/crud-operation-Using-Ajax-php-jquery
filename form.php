<?php 
include 'form_data.php';
?>
<!DOCTYPE html>
<html>
<head>
    <title>Document</title>
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.7.1/jquery.min.js"></script>
    <script>
        function valida() {
            let email = document.getElementsByName("email")[0].value;
            let emailPattern = /^[a-zA-Z0-9._-]+@[a-zA-Z0-9.-]+\.[a-zA-Z]{2,4}$/;
            let emailError = document.getElementById("emailError");
            emailError.innerHTML = "";
            if (email === "") {
                emailError.innerHTML = "Please enter your email address.";
                return false;
            }
            if (!emailPattern.test(email)) {
                emailError.innerHTML = "Please enter a valid email address.";
                return false;
            }
            return true;
        }
    </script>
</head>
<body>
    <h2>Create User</h2>
    <div>
        <?php 
        if ($submitt) {
            echo "<p style='color: black;'>Your form is submitted</p>";
            unset($_SESSION['submitt']);
        } else {
            if (!empty($errors)) {
                echo "<ul>";
                foreach ($errors as $error) {
                    echo "<li style='color: red;'>$error</li>";
                }
                echo "</ul>";
            } 
        ?>
            <form method="POST" enctype="multipart/form-data" id="form">
                <input type="text" name="name" placeholder="Name" required><br><br>
                <input type="email" name="email" placeholder="Email" required><br><br>
                <span id="emailError" style="color:red; font-size: 20px;"></span><br>
                <input type="password" name="password" placeholder="Password" required><br><br>
                <input type="text" name="username" placeholder="Username" required><br><br>
                <input type="file" name="image"><br><br>
                <select name="country" id="country" required>
                    <option value="">Select Country</option>
                    <option value="UK">UK</option>
                    <option value="India">India</option>
                    <option value="USA">USA</option>
                    <option value="Canada">Canada</option>
                    <option value="Australia">Australia</option>
                </select><br><br>
                <select name="state" id="state" style="display:none;">
                    <option value="">Select State</option>
                </select><br><br>
                <input type="submit" value="Create User">
            </form>
        <?php 
        } 
        ?>
    </div>
</body>
</html>