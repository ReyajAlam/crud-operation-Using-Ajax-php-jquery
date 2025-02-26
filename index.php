<?php session_start(); 
//++++ json file include her 
// $estate = json_decode(file_get_contents('states.json'), true); 
// if ($estate === null) {
//     $estate = [];
//     echo "<script>console.error('eooe show.');</script>";
// }
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Application</title>
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.7.1/jquery.min.js"></script>
    <style>
        .container { margin-top: 20px; }
        nav a { margin-right: 10px; cursor: pointer; }
        table { width: 100%; margin: 20px auto; border-collapse: collapse; font-family: Arial, sans-serif; }
        th, td { padding: 10px; text-align: center; border: 1px solid #333; }
        img { max-width: 100px; height: auto; }
    </style>
</head>
<body>
    <h2>Application</h2>
    <nav>
        <a data-page="form.php">Create</a>
        <a data-page="show.php">View Data</a>
        <a data-page="login.php">Login</a>
    </nav>
    <div id="content" class="container"></div>

    <script>
        //++ json file  variable staore in javascript file
        $(document).ready(function(){
            $('nav a').click(function(e){
                e.preventDefault();
                loaded($(this).data('page'));
            });

            function loaded(page) {
                $.ajax({
                    url: page,
                    type: 'GET',
                    success: function(response) {
                        $('#content').html(response);
                    },
                    error: function() {
                        $('#content').html('<p>Error loading page</p>');
                    }
                });
            }
            loaded('form.php');

            $(document).on('submit', '#form', function(e){
                e.preventDefault();
                $.ajax({
                    url: 'form_data.php',
                    type: 'POST',
                    data: new FormData(this),
                    contentType: false,
                    processData: false,
                    success: function(response){
                        $('#content').html('<p style="color: black;">Your form is submitted</p>');
                        
                    },
                    error: function(){
                        $('#content').html('<p style="color: red;">Error submitting form</p>');
                    }
                });
            });

            $(document).on('submit', '#login', function(e){
                e.preventDefault();
                $.ajax({
                    url: 'login.php',
                    type: 'POST',
                    data: new FormData(this),
                    contentType: false,
                    processData: false,
                    success: function(response){
                        $('#content').html(response);
                    },
                    error: function(){
                        $('#content').html('<p style="color: red;">Error logging in</p>');
                    }
                });
            });

            $(document).on('submit', '#update', function(e){
                e.preventDefault();
                $.ajax({
                    url: 'update.php',
                    type: 'POST',
                    data: new FormData(this),
                    contentType: false,
                    processData: false,
                    success: function(response){
                        $('#content').html('<p style="color: black;">User Updated</p>');
                        setTimeout(function(){ loaded('show.php'); }, 1000);
                    },
                    error: function(){
                        $('#content').html('<p style="color: red;">Failed to user </p>');
                    }
                });
            });
            $(document).on('click', '.edit-link', function(e){
            e.preventDefault();
            loaded('update.php?id=' + $(this).data('id'));
            });

            $(document).on('click', '.delete-link', function(e){
                e.preventDefault();
                if (confirm('Are you sure?')) {
                    $.ajax({
                        url: 'delete.php?id=' + $(this).data('id'),
                        type: 'GET',
                        success: function(){
                            loaded('show.php');
                        },
                        error: function(){
                            $('#content').html('<p style="color: red;">Failed to delete user</p>');
                        }
                    });
                }
            });

            $(document).on('click', '#logout', function(e){
                e.preventDefault();
                $.ajax({
                    url: 'logout.php',
                    type: 'GET',
                    success: function(){
                        loaded('login.php');
                    },
                    error: function(){
                        $('#content').html('<p style="color: red;">Error login</p>');
                    }
                });
            });

            $.ajax({
                url: 'https://countriesnow.space/api/v0.1/countries',
                type: 'GET',
                success: function(response) {
                    if (!response.error) {
                        let countrydrop = $('#country');
                        countrydrop.append('<option value="">Select Country</option>');
                        response.data.forEach(country => {
                            countrydrop.append('<option value="' + country.country + '">' + country.country + '</option>');
                        });
                    }
                },
                error: function() {
                    console.error('Error show data');
                }
            });

            $(document).on('change', '#country', function(){
                let country = $(this).val();
                if (country) {
                    $.ajax({
                        url: 'https://countriesnow.space/api/v0.1/countries/states',
                        type: 'POST',
                        contentType: 'application/json',
                        data: JSON.stringify({ country: country }),
                        success: function(response) {
                            let statedrop = $('#state');
                            statedrop.empty();
                            statedrop.append('<option value="">Select State</option>');
                            if (!response.error) {
                                response.data.states.forEach(state => {
                                    statedrop.append('<option value="' + state.name + '">' + state.name + '</option>');
                                });
                            }
                        },
                        error: function() {
                            console.error('Error states');
                        }
                    });
                } else {
                    $('#state').empty().append('<option value="">Select State</option>');
                }
            });
        });
    </script>
</body>
</html>
