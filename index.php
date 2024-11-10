<?php
    //  Check if user is logged in, else redirect to login page
    session_start();
    if(!isset($_SESSION['userid'])){
        header('location: ./login.php');
    }  
    
    //get navbar
    require_once "modules/nav.php";
    get_navbar(); //get navigation bar
        
    //get projects
    require_once "services/db_config.php";
    $sess_id = mysqli_escape_string($con, $_SESSION['userid']);
    $query = "SELECT * 
            FROM projects 
            INNER JOIN p_members 
            ON projects.projectid = p_members.projectid 
            WHERE userid = ?";
                    
    $stmt = $con->prepare($query);
    $stmt->bind_param('i', $sess_id);
    $stmt->execute();
    $results = $stmt->get_result();
    $requests = array();
    while ($row = mysqli_fetch_assoc($results)){
        $requests[] = $row;
    }
?>

<!doctype html>
<html lang="en">
    <head>
        <meta charset="utf-8">
        <title>SDSTaskSite</title>
        <base href="/">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-QWTKZyjpPEjISv5WaRU9OFeRpok6YctnYmDr5pNlyT2bRjXh0JMhjY6hW+ALEwIH" crossorigin="anonymous">
        <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.1/font/bootstrap-icons.css">
    </head>

    <body> 
        <div class='container-fluid p-3'>
            <h1 class='display-6'>Projects</h1>
            <hr>
            <div class="row row-cols-auto">
                <?php foreach($requests as $request) { ?>
                <div class="col py-2 pe-3">
                    <a class="link-underline link-underline-opacity-0" href="project.php?projectid=<?= $request['projectid'];?>">
                        <div class="card shadow mb-5" style="width: 20rem; height: 20rem; border: none">
                            <div class="card-body">
                                <h5><?= $request['project_name'];?></h5>
                                <p class="card-text text-truncate fs-6" style='color: #484848'><?= $request['project_desc'];?></p>
                            </div>
                        </div>
                    </a>
                </div>
                <?php } ?>
                <div class="col py-2 pe-3">
                    <a class="link-underline link-underline-opacity-0" href="../register.php">
                        <div class="card shadow mb-5" style="width: 20rem; height: 20rem; border: none">
                            <div class="card-body">
                                <div style='font-size: 800%; text-align: center'> + </div>
                                <div style='font-size: 150%; text-align: center'>Add New Project</div> 
                            </div>
                        </div>
                    </a>
                </div>
        </div>
            </div>
    </body>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js" integrity="sha384-YvpcrYf0tY3lHB60NNkmXc5s9fDVZLESaAA55NDzOxhy9GkcIdslK1eN7N6jIeHz" crossorigin="anonymous"></script>
</html>
