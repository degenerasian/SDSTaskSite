<?php
    session_start();
    //  Check if user is logged in, else redirect to login page
    if(!isset($_SESSION['userid'])){
        header('location: ./login.php');
    }
    require_once "services/db_config.php";
    require_once "modules/nav.php";
    require_once "services/get_tasks.php";
    get_navbar();

    $projectid = mysqli_escape_string($con, $_GET['projectid']);

    if(!isset($_COOKIE['taskview'])){
        setcookie('taskview', 'grid', time() + (86400 * 30), '/');   //  view cookie expires in 30 days
        header('location: project.php?projectid=' . mysqli_escape_string($con, $_GET['projectid']));
    }
    
    // Setting cookie for task view
    if ($_SERVER['REQUEST_METHOD'] == 'POST') {
        if (isset($_POST['list'])) {
            setcookie('taskview', 'list', time() + (86400 * 30), '/');
            header('location: project.php?projectid=' . $projectid);
        } elseif (isset($_POST['grid'])) {
            setcookie('taskview', 'grid', time() + (86400 * 30), '/');
            header('location: project.php?projectid=' . $projectid);
        }
    }
    $viewcookie = $_COOKIE['taskview'];
    // echo $viewcookie;
       
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

    <!--  PHP code to get project details, associated users, etc.  -->
    <?php
        //  Check if user is logged in, else redirect to login page
        if(!isset($_SESSION['userid'])){
            header('location: ./login.php');
        }        
        require_once "services/db_config.php";
        require_once "modules/nav.php";
        require_once "services/get_tasks.php";
        $projectid = mysqli_escape_string($con, $_GET['projectid']);
        $query = "SELECT *
                    FROM projects
                    WHERE projectid = ?";
        
        $stmt = $con->prepare($query);
        $stmt->bind_param('i', $projectid);
        $stmt->execute();
        $result = $stmt->get_result();
        $project = mysqli_fetch_assoc($result);

        // formatted project description for showing line breaks in project description edit
        $f_project_desc = stripcslashes($project['project_desc']);

        // echo $project['project_name'];
        // create a query to get project and user details
        $query = "SELECT p.*, u.userid, u.f_name, u.l_name, u.email
                    FROM projects p
                    INNER JOIN p_members pm on p.projectid = pm.projectid
                    INNER JOIN users u on u.userid = pm.userid
                    WHERE p.projectid = ?";

        $stmt = $con->prepare($query);
        $stmt->bind_param('i', $projectid);
        $stmt->execute();
        $results = $stmt->get_result();
        $members = array();
    
        while ($row = mysqli_fetch_assoc($results)) {
            $members[] = $row;
        }

        //  Query to get non-members for invite
        $query = "SELECT u.userid, u.f_name, u.l_name
                    FROM users u
                    EXCEPT
                    SELECT u.userid, u.f_name, u.l_name
                    FROM projects p
                    INNER JOIN p_members pm on p.projectid = pm.projectid
                    INNER JOIN users u on u.userid = pm.userid
                    WHERE p.projectid = ?";
        
        $stmt = $con->prepare($query);
        $stmt->bind_param('i', $projectid);
        $stmt->execute();
        $results = $stmt->get_result();
        $n_members = array();

        while ($row = mysqli_fetch_assoc($results)) {
            $n_members[] = $row;
        }

    ?>

    <body class="bg-body-tertiary">
        <div class="container-fluid px-5 py-3">
            <div class="row-auto py-2">
                <a class="icon-link" href="index.php">
                    <i class="bi bi-arrow-left"></i>    
                    <h5>Back to Projects</h5>
                </a>
            </div>
            <div class="row row-cols-auto">
                <div class='col p-2'>
                    <h1 class='display-6 pb-2'><?= $project['project_name'] ?>: Manage Tasks</h1>
                    <div class="row">
                        <div class="col-auto">
                            <a href="" data-bs-toggle="modal" data-bs-target="#newtaskModal"><i class="bi bi-file-plus fs-3"></i></a>
                        </div>
                        <?php if($_SESSION['privilege'] == 'Admin'){ ?>
                        <div class="col-auto">
                                <a href="" data-bs-toggle="modal" data-bs-target="#inviteModal"><i class="bi bi-people fs-3"></i></a>
                        </div>
                        <div class="col-auto">
                            <a href="" data-bs-toggle="modal" data-bs-target="#editprojectModal"><i class="bi bi-gear fs-3"></i></a>
                        </div>
                        <?php } ?>
                    </div>
                </div>
                <div class="col p-3">
                    <form method="POST" action="<?= $_SERVER['PHP_SELF'] . '?' . $_SERVER['QUERY_STRING'] ?>">
                        <button class="btn <?php if($_COOKIE['taskview'] == 'grid'){ ?>border border-primary"<?php } ?> type="submit" name="grid"><i class="bi bi-columns-gap fs-2"></i></button>
                        <button class="btn <?php if($_COOKIE['taskview'] == 'list'){ ?>border border-primary"<?php } ?> type="submit" name="list"><i class="bi bi-card-list fs-2"></i></button>
                    </form>
                </div>
                <!-- CHANGE THIS BUTTON FRONTEND, TEMPORARY FOR PROJECT DELETE -->
                <div class="col p-3">
                    <a class="btn btn-danger" data-bs-toggle="modal" data-bs-target="#deleteModal">Delete Project</a>
                </div>
            
            <br>
        <?php get_tasks($con, $projectid);?>


    <!--    Invite Modal    -->
        <div class="modal fade" id="inviteModal" tabindex="-1" aria-labelledby="inviteModalLabel" aria-hidden="true">
            <div class="modal-dialog">
                <div class="modal-content">
                <div class="modal-header">
                    <h1 class="modal-title fs-5" id="inviteModalLabel">Manage Members</h1>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <h5>Add to <?= $project['project_name'];?></h5>
                    <form method="POST" action="services/manageusers.php?projectid=<?= $projectid?>">
                    <?php if($n_members){ ?>
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
                            foreach ($n_members as $n) { ?>
                            <tr>
                                <td><input type="checkbox" name="addid[]" value="<?= $n['userid']?>"></td>
                                <td><?= $n['f_name'] . " " . $n['l_name'];?></td>
                            </tr>
                            <?php } ?>
                        </tbody>
                    </table>
                    <?php } else { ?>No members available. <?php } ?>
                    <hr>
                    <h5>Remove from <?= $project['project_name'];?></h5>
                    <?php if($members) { ?>
                        <table class="table table-hover table-borderless">
                            <colgroup>
                            <col style="width:1%;">
                            <col>
                            <thead class="table-dark">
                                <tr>
                                    <th>Remove</th>
                                    <th>Name</th>
                                </tr>
                            </thead>
                            <tbody>

                                <?php foreach ($members as $m) { ?>
                                <tr>
                                    <td><input type="checkbox" name="removeid[]" value="<?= $m['userid']?>"></td>
                                    <td><?= $m['f_name'] . " " . $m['l_name'];?></td>
                                </tr>
                            <?php } ?>
                            </tbody>
                        </table>
                    <?php } else { ?>This project has no members. <?php } ?>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                    <button type="submit" class="btn btn-success" name="manageusers">Save Changes</input>
                    </form>
                </div>
                </div>
            </div>
        </div>
    <!--    END Invite Modal    -->

    <!--    New Task Modal    -->
        <div class="modal fade" id="newtaskModal" tabindex="-1" aria-labelledby="newtaskModalLabel" aria-hidden="true">
            <div class="modal-dialog">
                <div class="modal-content">
                <div class="modal-header">
                    <h1 class="modal-title fs-5" id="newtaskModalLabel">New Task</h1>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <form method="POST" action="services/addtask.php?projectid=<?= $projectid?>" enctype="multipart/form-data">
                        <div class="col py-1">
                            <label for="task_name" class="form-label">Task Name</label>
                            <input type="text" class="form-control" name="task_name" id="task_name" placeholder="New Task">
                        </div>
                        <div class="col py-1">
                            <label for="task_desc" class="form-label">Description</label>
                            <textarea class="form-control" rows="5" name="task_desc" id="task_desc" placeholder="A brief task description" style="resize:none;"></textarea>
                        </div>
                            <label for="attachment" class="form-label">Attachments</label>
                            <input class="form-control" type="file" id="attachment" name="attachment[]" multiple>
                        <hr>
                        <h6>Label</h6>
                        <div class="col py-2">
                            <select class="form-select" name="label" id="label" aria-label="Default select example">  
                                <option disabled>Select label</option>
                                <option value="In Progress">In Progress</option>
                                <option value="For Testing">For Testing</option>
                                <option value="For Publish">For Publish</option>
                                <option value="For Checking">For Checking</option>
                                <option value="Reopened">Reopened</option>
                                <option value="QA Passed">QA Passed</option>
                                <option value="QA Failed">QA Failed</option>
                            </select>
                        </div>
                        <div class="row my-3">
                            <h5>Due Date</h5>
                            <div class="col py-2">
                                <label for="start_date">Start</label>
                                <input id="start_date" name="start_date" class="form-control" type="date" value="">
                            </div>
                            <div class="col py-2">
                                <label for="due_date">End</label>
                                <input id="due_date" name="due_date" class="form-control" type="date" value="">
                            </div>
                        </div>
                        <div class="row">
                            <h5>Time Estimation</h5>
                            <div class="col py-2">
                                <label for="hours">Hours</label>
                                <input class="form-control" type="number" name="hours" id="hours" min="0" value="">
                            </div>
                            <div class="col py-2">
                                <label for="minutes">Minutes</label>
                                <input class="form-control" type="number" name="minutes" id="minutes" min="0" max="60" value="">
                            </div>
                        </div>
                        <hr>
                        <h5>Add to New Task</h5>
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
                                foreach ($members as $m) { ?>
                                <tr>
                                    <td><input type="checkbox" name="addid[]" value="<?= $m['userid']?>"></td>
                                    <td><?= $m['f_name'] . " " . $m['l_name'];?></td>
                                </tr>
                                <?php } ?>
                            </tbody>
                        </table>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                    <button type="submit" class="btn btn-success" name="addtask">Create Task</input>
                    </form>
                </div>
                </div>
            </div>
        </div>
    <!--    END New Task Modal    -->

    <!--    Delete Project    -->
        <div class="modal fade" id="deleteModal" tabindex="-1" aria-labelledby="deleteModalLabel" aria-hidden="true">
            <div class="modal-dialog">
                <div class="modal-content">
                <div class="modal-header">
                    <h1 class="modal-title fs-5" id="deleteModalLabel">Delete <?= $project['project_name']?>?</h1>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Cancel"></button>
                </div>
                <form action="services/deleteproject.php?projectid=<?= $projectid;?>" method="POST">
                <div class="modal-body">
                    <input type="hidden" name="delete_id" id="delete_id">
                    This can't be undone.
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                    <button type="submit" name="delete" class="btn btn-danger">Delete</button>
                    </form>
                </div>
                </div>
            </div>
        </div>
    
    <!--    Edit Project Modal    -->
        <div class="modal fade" id="editprojectModal" tabindex="-1" aria-labelledby="editprojectModalLabel" aria-hidden="true">
            <div class="modal-dialog">
                <div class="modal-content">
                <div class="modal-header">
                    <h1 class="modal-title fs-5" id="editprojectModalLabel">Edit <?= $project['project_name']?> Details</h1>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <form method="POST" action="services/editproject.php?projectid=<?= $projectid?>">
                        <div class="col py-1">
                            <label for="project_name" class="form-label">Project Name</label>
                            <input type="text" class="form-control" name="project_name" id="project_name" placeholder="New Project" value="<?= $project['project_name']?>">
                        </div>
                        <div class="col py-1">
                            <label for="project_desc" class="form-label">Description</label>
                            <textarea class="form-control" rows="5" name="project_desc" id="project_desc" placeholder="A brief project description" style="resize:none;"><?= $f_project_desc?></textarea>
                        </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                    <button type="submit" class="btn btn-success" name="editproject">Edit Project</input>
                    </form>
                </div>
                </div>
            </div>
        </div>
    <!--    END New Project Modal    -->
    </body>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js" integrity="sha384-YvpcrYf0tY3lHB60NNkmXc5s9fDVZLESaAA55NDzOxhy9GkcIdslK1eN7N6jIeHz" crossorigin="anonymous"></script>
</html>
