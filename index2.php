<?php session_start();
$estate = json_decode(file_get_contents('states.json'), true); 
// if ($estate === null) {
//     $estate = [];
//     echo "<script>console.error('eooe show.');</script>";
// }
?>
<!DOCTYPE html>
<html>
<head>
    <title>Document</title>
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.7.1/jquery.min.js"></script>
    <style>
    .container{
        margin-top:20px
    }
    nav a{
        margin-right:10px;cursor:pointer
    }
    table{
        width:100%;margin:20px auto;border-collapse:collapse;font-family:Arial,sans-serif
    }
    th,td{
        padding:10px;text-align:center;border:1px solid #333
    }
    img{
        max-width:100px;height:auto
    }
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
    const estate = <?php echo json_encode($estate); ?>;
    console.log('satate name', estate);

    $(document).ready(function(){
        $('nav a').click(function(e){
            e.preventDefault();
            loaded($(this).data('page'));
        });
        $(document).on('submit', '#form', function(e){
            e.preventDefault();
            if (valida()) {
                $.ajax({
                    url: 'form_data.php',
                    type: 'POST',
                    data: new FormData(this),
                    contentType: false,
                    processData: false,
                    success: function(response){
                        $('#content').html('<p style="color: black;">Your form is submitted</p>');
                        // setTimeout(function(){ loaded('show.php'); }, 1000); 
                    },
                    error: function(){
                        $('#content').html('<p style="color: red;">Error submitting form</p>');
                    }
                });
            }
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
                   $('#content').html('<p style="color: black;">User Update</p>');
                   setTimeout(function(){ loaded('show.php'); },1000); 
                },
                error: function(){
                    $('#content').html('<p style="color: red;">Failed to update user</p>');
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
                    $('#content').html('<p style="color: red;">Error logging out</p>');
                }
            });
        });
        $(document).on('change', '#country', function() {
        let country = $(this).val();
        if (country) {
          let fine = estate.filter(state => state.country === country);
          let statedrop = $('#state');
          statedrop.empty();
          statedrop.append('<option value="">Select State</option>');
           $.each(fine, function(index, state) {
            statedrop.append('<option value="' + state.name.en + '">' + state.name.en + '</option>');
        });
        statedrop.show();
    } else {
        $('#state').hide().empty().append('<option value="">Select State</option>');
    }
});
        function loaded(page){
            $.ajax({
                url: page,
                type: 'GET',
                success: function(response){
                    $('#content').html(response);
                },
                error: function(){
                    $('#content').html('<p>Error loading page</p>');
                }
            });
        }
        loaded('form.php');
    });
    </script>
</body>
</html>