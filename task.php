<!doctype html>
<html lang="en">
    <head>
        <meta charset="utf-8">
        <title>SDSTaskSite</title>
        <base href="/">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-QWTKZyjpPEjISv5WaRU9OFeRpok6YctnYmDr5pNlyT2bRjXh0JMhjY6hW+ALEwIH" crossorigin="anonymous">
    </head>

    <!--  PHP code to get project details, associated users, etc.  -->
    <?php
        //  Check if user is logged in, else redirect to login page
        session_start();
        if(!isset($_SESSION['userid'])){
            header('location: ./login.php');
        }        
        require_once "services/db_config.php";
        require_once "modules/nav.php";
        
        get_navbar();
        $taskid = mysqli_escape_string($con, $_GET['taskid']);
        $query = "SELECT tasks.*, users.userid, users.f_name, users.l_name
                    FROM tasks
                    INNER JOIN assignee ON assignee.taskid = tasks.taskid
                    INNER JOIN users ON assignee.userid = users.userid 
                    WHERE tasks.taskid = ?
                    AND users.userid = tasks.created_by";
        
        $stmt = $con->prepare($query);
        $stmt->bind_param('i', $taskid);
        $stmt->execute();
        $result = $stmt->get_result();
        $task = mysqli_fetch_assoc($result);
        $created_on = date("F j, Y", strtotime($task['created_on']));
    ?>

    <body class="bg-body-tertiary">
        <div class="container-fluid px-5 py-3">
            <div class="row">
                <div class="col mt-4">
                    <h1><strong><?= $task['task_name'] ?></strong></h1>
                </div>
            </div>
            <div class="row">
                <div class="col-auto">
                    <p>Created by <?= $task['f_name'] . " " . $task['l_name'];?> on <?= $created_on?></p>
                </div>
            </div>
            <div class="row">
                <hr>
                <div class="col-12 col-lg-10 col-xl-7 bg-white p-4">
                    <h6><?= $task['task_desc']?></h6>
                </div>    
            </div>
        </div>  
    </body>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js" integrity="sha384-YvpcrYf0tY3lHB60NNkmXc5s9fDVZLESaAA55NDzOxhy9GkcIdslK1eN7N6jIeHz" crossorigin="anonymous"></script>
</html>
