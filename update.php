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
        echo "User updated successfully!";
    } else {
        echo "Failed to update user.";
    }
    exit();
}
?>

<h2>Update User</h2>
<form method="POST" enctype="multipart/form-data" id="update">
    <input type="hidden" name="id" value="<?php echo $userr['id']; ?>">
    <input type="text" name="name" value="<?php echo $userr['name']; ?>" required><br><br>
    <input type="email" name="email" value="<?php echo $userr['email']; ?>" required><br><br>
    <input type="text" name="username" value="<?php echo $userr['username']; ?>" required><br><br>
    <input type="file" name="image"><br>
    <?php if ($userr['image']) echo "<img src='uploads/" . $userr['image'] . "' width='50'><br><br>"; ?>

    <select name="country" id="country" required>
        <option value="">Select Country</option>
    </select><br><br>

    <select name="state" id="state">
        <option value="">Select State</option>
    </select><br><br>

    <input type="submit" value="Update">
</form>

<script>
    $(document).ready(function () {
        let ecountry= "<?php echo $userr['country']; ?>";
        let estate = "<?php echo $userr['state']; ?>";

        function countring() {
            $.ajax({
                url: 'https://countriesnow.space/api/v0.1/countries',
                type: 'GET',
                success: function(response) {
                    let countrydp = $('#country');
                    response.data.forEach(country => {
                        let selected = country.country === ecountry? "selected" : "";
                        countrydp.append(`<option value="${country.country}" ${selected}>${country.country}</option>`);
                    });
                    if (countselect) {
                        $('#country').val(countselect).trigger('change');
                    }
                },
                error: function() {
                    console.error('Error fetching countries');
                }
            });
        }

        function loading(country) {
            if (country) {
                $.ajax({
                    url: 'https://countriesnow.space/api/v0.1/countries/states',
                    type: 'POST',
                    contentType: 'application/json',
                    data: JSON.stringify({ country: country }),
                    success: function(response) {
                        let stateDrop = $('#state');
                        stateDrop.empty().append('<option value="">Select State</option>');
                        response.data.states.forEach(state => {
                            let selected = state.name === estate ? "selected" : "";
                            stateDrop.append(`<option value="${state.name}" ${selected}>${state.name}</option>`);
                        });
                    },
                    error: function() {
                        console.error('Error fetching states');
                    }
                });
            } else {
                $('#state').empty().append('<option value="">Select State</option>');
            }
        }

        $('#country').change(function() {
            loading($(this).val());
        });

        countring();
    });
</script>
