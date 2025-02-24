<?php
require_once 'data.php';
$user = new User();
$users = $user->show();
?>
<h2>All Users</h2>
<table> 
    <tr>
        <th>ID</th>
        <th>Name</th>
        <th>Email</th>
        <th>Username</th>
        <th>Image</th>
        <th>Password</th>
        <th>Country</th>
        <th>State</th>
        <th>Edit</th>
        <th>Delete</th>
    </tr>
    <?php 
    foreach ($users as $uff): 
    ?>
    <tr>
        <td><?php echo $uff['id']; ?></td>
        <td><?php echo $uff['name']; ?></td>
        <td><?php echo $uff['email']; ?></td>
        <td><?php echo $uff['username']; ?></td>
        <td>
            <?php 
            echo $uff['image'] ? "<img src='uploads/{$uff['image']}' alt='User Image'>" : 'No Image';
            ?>
        </td>
        <td><?php echo $uff['password']; ?></td>
        <td><?php echo $uff['country']; ?></td>
        <td><?php echo $uff['state']; ?></td>
        <td><a href="#" class="edit-link" data-id="<?php echo $uff['id']; ?>">Edit</a></td>
        <td><a href="#" class="delete-link" data-id="<?php echo $uff['id']; ?>">Delete</a></td>
    </tr>
    <?php 
    endforeach; 
    ?>
</table>