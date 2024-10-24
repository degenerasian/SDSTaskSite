<!DOCTYPE html>
<html lang="en-US">
<head>
    <title>Register User | SDS Task Site</title>
    <link rel="shortcut icon" type="image/png" href="img/favicon.png"><!-- Favicon|image in web browser -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-GLhlTQ8iRABdZLl6O3oVMWSktQOp6b7In1Zl3/Jr59b6EGGoI1aFkw7cmDA6j6gD" crossorigin="anonymous">
</head>
<?php
session_start();
if($_SESSION['privilege'] != 'Admin') header('location: ./index.php');
require_once "services/db_config.php";
require_once "modules/nav.php";
get_navbar();
?>
<body>
    <div class="container mt-5 w-50">
    <div class="row g-5">
    <div class="col logincontent"><!--Main Content w/ left & right margin = center-->
    <h1 class="m-3">Sign Up</h1>
        <center><img src="img/logo.png" style="width:170px"></center>
		<br>
        <form method="POST" action="services/register.php">
		<div class="row mb-3">
			<div class="col">
                <label for="firstname" class="form-label">First Name</label>
                <input type="text" class="form-control" id="firstname" name="firstname" value="" required>
                </div>
            <div class="col">
                <label for="middlename" class="form-label">Middle Name</label>
                <input type="text" class="form-control" id="middlename" name="middlename" value="" required>
            </div>
			<div class="col">
                <label for="lastname" class="form-label">Last Name</label>
                <input type="text" class="form-control" id="lastname" name="lastname" value="" required>
            </div>
        </div>
		<div class="row mb-3">
			<div class="col">
                <label for="gender" class="form-label">Sex</label>
                <select class="form-control" id="gender" name="gender" value="" required>
					<option value="Male">Male</option>
					<option value="Female">Female</option>
					<option value="Female">Non-binary</option>
				</select>
                </div>
            <div class="col">
                <label for="dateofbirth" class="form-label">Birthday</label>
                <input type="date" class="form-control" id="dateofbirth" name="dateofbirth" value="" required>
            </div>
			<div class="col">
                <label for="civilstatus" class="form-label">Civil Status</label>
                <select class="form-control" id="civilstatus" name="civilstatus" value="" required>
					<option value="Single">Single</option>
					<option value="Married">Married</option>
					<option value="Divorced">Divorced</option>
					<option value="Widowed">Widowed</option>
				</select>
            </div>
        </div>
		<div class="row mb-3">
			<div class="col">
                <label for="phonenumber" class="form-label">Phone No.</label>
                <input type="number" class="form-control" id="phonenumber" name="phonenumber" value="" required>
                </div>
            <div class="col">
                <label for="email" class="form-label">Email</label>
                <input type="email" class="form-control" id="email" name="email" value="" required>
            </div>
			<div class="col">
                <label for="telephonenumber" class="form-label">Telephone No.</label>
                <input type="number" class="form-control" id="telephonenumber" name="telephonenumber" value="" required>
            </div>
        </div>
		<div class="row mb-3">
			<div class="col">
                <label for="address" class="form-label">Address</label>
                <input type="text" class="form-control" id="address" name="address" value="" required>
            </div>
		</div>
		<br><hr><br>
		<div class="row mb-3">
            <div class="col">
                <label for="username" class="form-label">Username</label>
                <input type="text" class="form-control" id="username" placeholder="Username" name="username" required>
            </div>
            <div class="col">
                <label for="password" class="form-label">Password</label>
                <input type="password" class="form-control" id="password" placeholder="Password" name="password" required>
            </div>
		</div>
		<br>
		<div class="mb-4 col-auto" style="text-align:center;">
			<button type="submit" name='submit' class="btn btn-info w-auto">Create Account</button>
        </div>
        </form>
    </div>
</div>
</div>
<?php get_footer(); ?>
</body>
</html>