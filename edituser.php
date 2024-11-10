<!DOCTYPE html>
<html lang="en-US">
<head>
    <title>Register User | SDS Task Site</title>
    <link rel="shortcut icon" type="image/png" href="img/favicon.png"><!-- Favicon|image in web browser -->
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.1/font/bootstrap-icons.css">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-GLhlTQ8iRABdZLl6O3oVMWSktQOp6b7In1Zl3/Jr59b6EGGoI1aFkw7cmDA6j6gD" crossorigin="anonymous">
</head>
<?php
    session_start();
    if($_SESSION['privilege'] != 'Admin') header('location: ./index.php');
    require_once "modules/nav.php";
    get_navbar();
    
    require_once "services/db_config.php";
    $userid = mysqli_escape_string($con, $_GET['userid']);
    $query = "SELECT *
                FROM users
                WHERE userid = ?";
                
    $stmt = $con->prepare($query);
    $stmt->bind_param('i', $userid);
    $stmt->execute();
    $result = $stmt->get_result();
    $user = mysqli_fetch_assoc($result);

?>
<body class="bg-body-tertiary">
    <div class="container mt-5">
        <div class="row g-5">
            <div class="col">
                <center><img src="img/logo.png" style="width:170px"></center>
                <h1 class="m-3">Editing <?php echo $user['f_name'] . " " . $user['l_name'];?></h1>
                <br>
                <form method="POST" action="services/edituser.php">
                <input type="hidden" name="userid" id="userid" value="<?php echo $user['userid']?>">
                <div class="row mb-3">
                    <div class="col-12 col-lg-4">
                        <label for="firstname" class="form-label">First Name</label>
                        <input type="text" class="form-control" id="firstname" placeholder="John" name="firstname" value="<?php echo $user['f_name'];?>" required>
                    </div>
                    <div class="col-12 col-lg-4">
                        <label for="lastname" class="form-label">Last Name</label>
                        <input type="text" class="form-control" id="lastname" placeholder="Smith" name="lastname" value="<?php echo $user['l_name'];?>" required>
                    </div>
                    <div class="col-12 col-lg-4">
                        <label for="privilege" class="form-label" aria-label="Role select">Account Role</label>
                        <select class="form-select" id="privilege" placeholder="Smith" name="privilege" required>
                            <option selected></option>
                            <option value="User" <?=$user['privilege'] == 'User' ? ' selected="selected"' : '';?>>User</option>
                            <option value="Admin" <?=$user['privilege'] == 'Admin' ? ' selected="selected"' : '';?>>Admin</option>
                        </select>
                    </div>
                </div>
                <hr class="my-4">
                <div class="row mb-3">
                    <div class="col-12 col-lg-6">
                        <label for="email" class="form-label">Email</label>
                        <input type="email" class="form-control" id="email" placeholder="example@email.com" name="email" value="<?php echo $user['email'];?>" required>
                    </div>
                    <div class="col-12 col-lg-6">
                        <label for="password" class="form-label">Password</label>
                        <input type="password" class="form-control" id="password" placeholder="Password" value="<?php echo $user['password'];?>" name="password" required>
                    </div>
                </div>
                <br>
                <div class="mb-4 col-auto" style="text-align:center;">
                    <button type="submit" name='editdata' class="btn btn-info w-auto">Edit User</button>
                </div>
                </form>
            </div>
        </div>
    </div>
</body>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js" integrity="sha384-YvpcrYf0tY3lHB60NNkmXc5s9fDVZLESaAA55NDzOxhy9GkcIdslK1eN7N6jIeHz" crossorigin="anonymous"></script>

</html>