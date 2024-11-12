<!doctype html>
<html lang="en">
    <head>
        <meta charset="utf-8">
        <title>SDSTaskSite</title>

        <meta name="viewport" content="width=device-width, initial-scale=1">
        <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-QWTKZyjpPEjISv5WaRU9OFeRpok6YctnYmDr5pNlyT2bRjXh0JMhjY6hW+ALEwIH" crossorigin="anonymous">
        <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.1/font/bootstrap-icons.css">
        <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    </head>

    <?php
        //  Check if user is logged in, else redirect to login page
        session_start();
        if(!isset($_SESSION['userid'])){
            header('location: ./login.php');
        }        
        require_once "modules/nav.php";
        get_navbar();
        
        require_once "services/db_config.php";
        $query = "SELECT u.userid, u.f_name, u.l_name, u.email, u.privilege
                    FROM users u";
                    
        $stmt = $con->prepare($query);
        $stmt->execute();
        $results = $stmt->get_result();
        $users = array();

        while ($row = mysqli_fetch_assoc($results)) {
            $users[] = $row;
        }
        
    ?>
    <body class="bg-body-tertiary">
        <!--main-->
        <div class="container-fluid px-5 py-3">
            <h1>SDS Users</h1>
            <button type="button" class="btn btn-primary adduserbtn" data-bs-toggle="modal" data-bs-target="#adduserModal">Add User</button>
        </div>
        <div class="col-lg-11 mx-auto">
            <table class="table table-striped scrollable table-bordered align-middle table-hover">
                <thead class="table-dark">
                    <tr>
                        <th>ID</th>
                        <th>Name</th>
                        <th>Privilege</th>
                        <th></th>
                        <th></th>
                    </tr>
                </thead>
                <?php foreach($users as $user) {?>
                <tr>
                    <td><?php echo $user['userid']?></td>
                    <td><?php echo $user['f_name'] . " " . $user['l_name']?></td>
                    <td><?php echo $user['privilege']?></td>
                    <td><a class="btn btn-primary" href="edituser.php?userid=<?php echo $user['userid']?>"><img class="pe-2" src="img/edit.png" style="width:30px;">Edit</a></td>
                    <td><button type="button" class="btn btn-danger deletebtn" data-bs-toggle="modal" data-bs-target="#deleteModal">Delete</button></td>
                </tr>
                <?php } ?>
            </table>
        </div>

<!--    Delete Modal    -->
<div class="modal fade" id="deleteModal" tabindex="-1" aria-labelledby="deleteModalLabel" aria-hidden="true">
  <div class="modal-dialog">
    <div class="modal-content">
      <div class="modal-header">
        <h1 class="modal-title fs-5" id="deleteModalLabel">Delete this user?</h1>
        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Cancel"></button>
      </div>
      <form action="services/deleteuser.php" method="POST">
      <div class="modal-body">
        <input type="hidden" name="delete_id" id="delete_id">
        This can't be undone.
      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
        <button type="submit" name="deletedata" class="btn btn-danger">Delete</button>
        </form>
      </div>
    </div>
  </div>
</div>

<!--    Add User Modal    -->
<div class="modal fade" id="adduserModal" tabindex="-1" aria-labelledby="adduserModalLabel" aria-hidden="true">
  <div class="modal-dialog">
    <div class="modal-content">
      <div class="modal-header bg-light">
        <h1 class="modal-title fs-5" id="adduserModalLabel">Add New User</h1>
        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Cancel"></button>
      </div>
      <form action="services/adduser.php" method="POST">
      <div class="modal-body">
      <form method="POST" action="services/edituser.php">
		<div class="row pt-2">
			<div class="col">
                <label for="firstname" class="form-label">First Name</label>
                <input type="text" class="form-control" id="firstname" placeholder="John" name="firstname" value="" required>
            </div>
        </div>
		<div class="row pt-2">
			<div class="col">
                <label for="lastname" class="form-label">Last Name</label>
                <input type="text" class="form-control" id="lastname" placeholder="Smith" name="lastname" value="" required>
            </div>
        </div>
		<div class="row pt-2">
            <div class="col">
                <label for="privilege" class="form-label" aria-label="Role select">Account Role</label>
                <select class="form-select" id="privilege" placeholder="Smith" name="privilege" required>
                    <option selected></option>
                    <option value="User">User</option>
                    <option value="Admin">Admin</option>
                </select>
            </div>
        </div>
		<hr>
        <div class="row pt-2">
			<div class="col">
                <label for="email" class="form-label">Email</label>
                <input type="email" class="form-control" id="email" placeholder="example@email.com" name="email" value="" required>
            </div>
        </div>
        <div class="row pt-2">
            <div class="col">
                <label for="password" class="form-label">Password</label>
                <input type="password" class="form-control" id="password" placeholder="Password" value="" name="password" required>
            </div>
        </div>
      </div>
      <div class="modal-footer bg-light">
        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
        <button type="submit" name="adduser" class="btn btn-primary">Add User</button>
        </form>
      </div>
    </div>
  </div>
</div>

<script>
$(document).ready(function (){
    $('.deletebtn').on('click', function() {
        $tr = $(this).closest('tr');

        var data = $tr.children("td").map(function() {
            return $(this).text();
        }).get();

        console.log(data);
        $('#delete_id').val(data[0]);
    });
});

</script>

</body>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js" integrity="sha384-YvpcrYf0tY3lHB60NNkmXc5s9fDVZLESaAA55NDzOxhy9GkcIdslK1eN7N6jIeHz" crossorigin="anonymous"></script>
</html>
