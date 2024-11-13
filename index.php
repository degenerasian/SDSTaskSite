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
        $projects = array();
        while ($row = mysqli_fetch_assoc($results)){
            $projects[] = $row;
        }

    //  get users for new project members
    $query = "SELECT * 
            FROM users";
                    
        $stmt = $con->prepare($query);
        $stmt->execute();
        $results = $stmt->get_result();
        $users = array();
        while ($row = mysqli_fetch_assoc($results)){
            $users[] = $row;
        }

?>

<!doctype html>
<html lang="en">
    <head>
        <meta charset="utf-8">
        <title>SDSTaskSite</title>

        <meta name="viewport" content="width=device-width, initial-scale=1">
        <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-QWTKZyjpPEjISv5WaRU9OFeRpok6YctnYmDr5pNlyT2bRjXh0JMhjY6hW+ALEwIH" crossorigin="anonymous">
        <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.1/font/bootstrap-icons.css">
    </head>

    <body class="bg-body-tertiary"> 
        <div class='container-fluid p-3'>
            <h1 class='display-6'>Projects</h1>
            <hr>
            <div class="row row-cols-auto">
                <?php foreach($projects as $project) { ?>
                <div class="col py-2 pe-3">
                    <a class="link-underline link-underline-opacity-0" href="project.php?projectid=<?= $project['projectid'];?>">
                        <div class="card shadow mb-5" style="width: 20rem; height: 20rem; border: none">
                            <div class="card-body">
                                <h5><?= $project['project_name'];?></h5>
                                <p class="card-text text-truncate fs-6" style='color: #484848'><?= nl2br(stripcslashes($project['project_desc']));?></p>
                            </div>
                        </div>
                    </a>
                </div>
                <?php } ?>
                <div class="col py-2 pe-3">
                        <div class="card shadow mb-5" style="width: 20rem; height: 20rem; border: none">
                            <div class="card-body">
                                <div style='font-size: 800%; text-align: center'> + </div>
                                <div style='font-size: 150%; text-align: center'>
                                    <h4>Add New Project</h4>
                                    <a href="" class="stretched-link" data-bs-toggle="modal" data-bs-target="#newprojectModal"></a>
                                </div> 
                            </div>
                        </div>
                </div>
        </div>
            </div>

        <!--    New Project Modal    -->
        <div class="modal fade" id="newprojectModal" tabindex="-1" aria-labelledby="newprojectModalLabel" aria-hidden="true">
            <div class="modal-dialog">
                <div class="modal-content">
                <div class="modal-header">
                    <h1 class="modal-title fs-5" id="newprojectModalLabel">New Project</h1>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <form method="POST" action="services/addproject.php">
                        <div class="col py-1">
                            <label for="project_name" class="form-label">Project Name</label>
                            <input type="text" class="form-control" name="project_name" id="project_name" placeholder="New Project" required>
                        </div>
                        <div class="col py-1">
                            <label for="project_desc" class="form-label">Description</label>
                            <textarea class="form-control" rows="5" name="project_desc" id="project_desc" placeholder="A brief project description" style="resize:none;"></textarea>
                        </div>
                        <hr>
                        <h5>Add to this project</h5>
                        <table class="table table-hover table-borderless">
                            <colgroup>
                            <col style="width:1%;">
                            <col>
                            <thead class="table-dark">
                                <tr>
                                    <th>Add</th>
                                    <th>Name</th>
                                </tr>
                            </thead>
                            <tbody>
                            <?php
                                foreach ($users as $u) { ?>
                                <tr>
                                    <td><input type="checkbox" name="addid[]" value="<?= $u['userid']?>"></td>
                                    <td><?= $u['f_name'] . " " . $u['l_name'];?></td>
                                </tr>
                                <?php } ?>
                            </tbody>
                        </table>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                    <button type="submit" class="btn btn-success" name="addproject">Create Project</input>
                    </form>
                </div>
                </div>
            </div>
        </div>
        <!--    END New Project Modal    -->
    </body>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js" integrity="sha384-YvpcrYf0tY3lHB60NNkmXc5s9fDVZLESaAA55NDzOxhy9GkcIdslK1eN7N6jIeHz" crossorigin="anonymous"></script>
</html>
