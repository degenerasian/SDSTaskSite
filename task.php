<!doctype html>
<html lang="en">
    <head>
        <meta charset="utf-8">
        <title>SDSTaskSite</title>
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
        <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.1/font/bootstrap-icons.css">
        <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-QWTKZyjpPEjISv5WaRU9OFeRpok6YctnYmDr5pNlyT2bRjXh0JMhjY6hW+ALEwIH" crossorigin="anonymous">
    </head>


    <?php
        //  PHP code to get project details, associated users, etc.
        //  Check if user is logged in, else redirect to login page
        session_start();
        if(!isset($_SESSION['userid'])){
            header('location: ./login.php');
        }        
        require_once "services/db_config.php";
        require_once "modules/nav.php";
        require_once "services/getimages.php";
        
        get_navbar();
        // Get general task details
            $taskid = mysqli_escape_string($con, $_GET['taskid']);
            $query = "SELECT tasks.*, users.userid, users.f_name, users.l_name
                        FROM tasks, users
                        WHERE tasks.taskid = ?
                        AND users.userid = tasks.created_by";
                
                $stmt = $con->prepare($query);
                $stmt->bind_param('i', $taskid);
                $stmt->execute();
                $result = $stmt->get_result();
                $create = mysqli_fetch_assoc($result);

            
            $query = "SELECT tasks.*
                    FROM tasks
                    WHERE tasks.taskid = ?";
            
                $stmt = $con->prepare($query);
                $stmt->bind_param('i', $taskid);
                $stmt->execute();
                $result = $stmt->get_result();
                $task = mysqli_fetch_assoc($result);
                $created_on = date("F j, Y", strtotime($task['created_on']));

        // Get assignee based details
            $query = "SELECT users.userid, users.f_name, users.l_name
                        FROM tasks
                        INNER JOIN assignee ON assignee.taskid = tasks.taskid
                        INNER JOIN users ON assignee.userid = users.userid 
                        WHERE tasks.taskid = ?";

                $stmt = $con->prepare($query);
                $stmt->bind_param('i', $taskid);
                $stmt->execute();
                $results = $stmt->get_result();
                $assignees = array();
                while ($row = mysqli_fetch_assoc($results)) {
                    $assignees[] = $row;
                }

            $query = "SELECT users.userid, users.f_name, users.l_name
                        FROM users
                        EXCEPT
                        SELECT users.userid, users.f_name, users.l_name
                        FROM tasks
                        INNER JOIN assignee ON assignee.taskid = tasks.taskid
                        INNER JOIN users ON assignee.userid = users.userid 
                        WHERE tasks.taskid = ?";

                $stmt = $con->prepare($query);
                $stmt->bind_param('i', $taskid);
                $stmt->execute();
                $results = $stmt->get_result();
                $n_assignees = array();
                while ($row = mysqli_fetch_assoc($results)) {
                    $n_assignees[] = $row;
                }

        // Get comments
            $query = "SELECT c.*, u.f_name, u.l_name
                        FROM comments c
                        INNER JOIN users u ON u.userid = c.userid
                        WHERE c.taskid = ?";
                
                $stmt = $con->prepare($query);
                $stmt->bind_param('i', $taskid);
                $stmt->execute();
                $results = $stmt->get_result();
                $comments = array();
                while ($row = mysqli_fetch_assoc($results)) {
                    $comments[] = $row;
                }
    ?>

    <body class="bg-body-tertiary">
        <div class="container-fluid px-5 py-3">
            <div class="row-auto py-2">
                <a class="icon-link" href="project.php?projectid=<?= $task['projectid']?>">
                    <i class="bi bi-arrow-left"></i>    
                    <h5>Back to Tasks</h5>
                </a>
            </div>
            <div class="row">
                <div class="col-auto mt-4">
                    <h1><?= $task['task_name'] ?></h1>
                </div>
                <div class="col-auto mt-4 align-self-center">
                    <h5 class="
                        <?php if ($task['label']=='QA Failed'){ ?>         text-danger
                        <?php } elseif ($task['label']=='QA Passed'){ ?>   text-success
                        <?php } else { ?>                                  text-secondary
                        <?php } ?>">
                        &#x2022; <?= $task['label'] ?>
                    </h5>
                </div>
                <div class="col-auto mt-4 align-self-center">
                    <button class="btn py-0" type="button" data-bs-toggle="dropdown" aria-expanded="false"><i class="bi bi-three-dots text-secondary fs-2"></i></button>
                    <ul class="dropdown-menu">
                        <li><a class="dropdown-item" href="edittask.php?taskid=<?= $taskid?>">Edit Task</a></li>
                        <li><a class="dropdown-item" href="services/duplicatetask.php?taskid=<?= $taskid?>">Duplicate Task</a></li>
                        <hr>
                        <li><button class="dropdown-item text-danger" data-bs-toggle="modal" data-bs-target="#deleteModal">Delete Task</a></li>
                    </ul>
                </div>
            </div>
            <div class="row">
                <div class="col-auto p-2">
                    <?php if(!is_null($create)) { ?>
                        <p>Created by <?= $create['f_name'] . " " . $create['l_name'];?> on <?= $created_on?></p>
                    <?php } else { ?> <p>Created by a Deleted User on <?= $created_on?></p><?php } ?>
                </div>
            </div>
            <div class="row">
                <hr>
                <div class="col-12 col-lg-7 col-xl-7">
                    <div class="col shadow rounded bg-white mb-4 p-4">
                        <p><?= $task['task_desc']?></p>
                        <?php 
                        $task_img = array();
                        $task_img = get_images($con, 'task', $taskid);
                        if($task_img) { ?>
                            <div class="row">
                                <?php foreach ($task_img as $ti) { ?>
                                    <div class="col-auto py-2">
                                        <img src="uploads/<?= $ti['image']?>" class="img-fluid">
                                    </div>
                                <?php } ?>
                            </div>
                            <?php } ?>
                    </div>

                <!-- Comment Section -->
                    <div class="col shadow rounded bg-white mb-4 p-4">
                        <h4>Comments</h4>
                        <form method="POST" action="services/comment.php?taskid=<?= $taskid?>" enctype="multipart/form-data">
                        <div class="mb-3">
                            <textarea class="form-control" id="commentbody" name="commentbody" rows="5" style="resize:none;"></textarea>
                        </div>
                        <div class="row mb-3">
                            <div class="col-12 mb-3">
                                <label for="attachment" class="form-label">Attachments</label>
                                <input class="form-control" type="file" id="attachment" name="attachment[]" multiple>
                            </div> 
                            <div class="col-12 mb-2">
                                <button type="submit" name="comment" class="btn btn-success float-end">Comment</input>
                            </div> 
                        </div>
                        </form>
                        <?php if($comments) { ?>
                        <div class="row row-cols-1">
                            <?php foreach ($comments as $c) { 
                                $comment_img = array();
                                $comment_img = get_images($con, 'comment', $c['commentid']);
                                ?>
                            <div class="col shadow rounded bg-white mb-4 px-4 pt-4">
                                <div class="row">
                                    <div class="col">    
                                        <p><?= $c['f_name'] . " " . $c['l_name']?></p>
                                    </div>
                                    <div class="col-auto ms-auto">    
                                        <a class="btn" href="services/deletecomment.php?commentid=<?= $c['commentid'];?>&taskid=<?= $taskid?>"><img src="img/trash.png"></a>
                                    </div>
                                </div>
                                <hr>   
                                <p><?= nl2br(stripcslashes($c['comment_text']))?></p>
                                <?php if($comment_img) {?>
                                    <div class="row">
                                    <?php foreach ($comment_img as $i) { ?>
                                        <div class="col-12 py-2">
                                            <img src="uploads/<?= $i['image']?>" class="img-fluid" style="max-width:450px;">
                                        </div>
                                    <?php } ?>
                                    </div>
                                <?php } ?>
                            </div>
                            <?php } ?>
                        </div>
                        <?php } ?>
                    </div>
                </div>
            <!-- End of Comment Section -->

            <!-- Assignee Section -->
                <div class="col-12 col-lg-4 col-xl-3 px-4 mb-4 py-3 bg-white shadow rounded">
                    <h4>Assignees</h4>
                    <hr>
                    <?php if($assignees) { ?>
                        <table class="table table-hover table-borderless">
                            <tbody>
                            <?php foreach($assignees as $a) { ?>
                                <tr>
                                    <td style="display:none;"><?php echo $a['userid']?></td>
                                    <td><?php echo $a['f_name'] . " " . $a['l_name'];?></td>
                                    <td><button type="button" class="btn btn-danger removebtn" data-bs-toggle="modal" data-bs-target="#removeModal">Remove</button></td>
                                </tr>
                            <?php } ?>
                        </table>
                    <?php } else {?> 
                        <p>No users assigned to this task.</p>
                    <?php } ?>
                    <button type="button" class="btn btn-warning align-middle" data-bs-toggle="modal" data-bs-target="#inviteModal">Manage</button>
                    <hr>
                    <form method="POST" action="services/edittask.php?taskid=<?= $taskid?>">
                    <div class="row my-3">
                        <h5>Label</h5>
                        <div class="col py-2">
                            <select class="form-select" name="label" id="label" aria-label="Default select example">  
                                <option selected disabled>Select label</option>
                                <option value="In Progress"<?=$task['label'] == 'In Progress' ? ' selected="selected"' : '';?>>In Progress</option>
                                <option value="For Testing"<?=$task['label'] == 'For Testing' ? ' selected="selected"' : '';?>>For Testing</option>
                                <option value="For Publish"<?=$task['label'] == 'For Publish' ? ' selected="selected"' : '';?>>For Publish</option>
                                <option value="For Checking"<?=$task['label'] == 'For Checking' ? ' selected="selected"' : '';?>>For Checking</option>
                                <option value="Reopened"<?=$task['label'] == 'Reopened' ? ' selected="selected"' : '';?>>Reopened</option>
                                <option value="QA Passed"<?=$task['label'] == 'QA Passed' ? ' selected="selected"' : '';?>>QA Passed</option>
                                <option value="QA Failed"<?=$task['label'] == 'QA Failed' ? ' selected="selected"' : '';?>>QA Failed</option>
                            </select>
                        </div>
                    </div>
                    <div class="row my-3">
                        <h5>Due Date</h5>
                        <div class="col py-2">
                            <label for="start_date">Start</label>
                            <input id="start_date" name="start_date" class="form-control" type="date" value="<?= $task['start_date']?>">
                        </div>
                        <div class="col py-2">
                            <label for="due_date">End</label>
                            <input id="due_date" name="due_date" class="form-control" type="date" value="<?= $task['due_date']?>">
                        </div>
                    </div>
                    <div class="row">
                        <h5>Time Estimation</h5>
                        <div class="col py-2">
                            <label for="hours">Hours</label>
                            <input class="form-control" type="number" name="hours" id="hours" min="0" value="<?= intdiv($task['time_est'], 60)?>">
                        </div>
                        <div class="col py-2">
                            <label for="minutes">Minutes</label>
                            <input class="form-control" type="number" name="minutes" id="minutes" min="0" max="60" value="<?= $task['time_est']%60?>">
                        </div>
                    </div>
                    <button type="submit" name="detailedit" class="btn btn-success align-middle my-2">Save Changes</button>
                    </form>
                </div>
            <!-- End of Assignee Section -->
            </div>
        </div>  

        <!--    Delete Task    -->
        <div class="modal fade" id="deleteModal" tabindex="-1" aria-labelledby="deleteModalLabel" aria-hidden="true">
            <div class="modal-dialog">
                <div class="modal-content">
                <div class="modal-header">
                    <h1 class="modal-title fs-5" id="deleteModalLabel">Delete this task?</h1>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Cancel"></button>
                </div>
                <form action="services/deletetask.php?taskid=<?= $taskid?>&projectid=<?= $task['projectid'];?>" method="POST">
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

        <!--    Remove Modal    -->
        <div class="modal fade" id="removeModal" tabindex="-1" aria-labelledby="removeModalLabel" aria-hidden="true">
            <div class="modal-dialog">
                <div class="modal-content">
                <div class="modal-header">
                    <h1 class="modal-title fs-5" id="removeModalLabel">Remove this assignee?</h1>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Cancel"></button>
                </div>
                <form action="services/deleteassignee.php?taskid=<?= $taskid?>" method="POST">
                <div class="modal-body">
                    <input type="hidden" name="remove_id" id="remove_id">
                    This can't be undone.
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                    <button type="submit" name="removedata" class="btn btn-danger">Remove</button>
                    </form>
                </div>
                </div>
            </div>
        </div>

        <!--    Invite Modal    -->
        <div class="modal fade" id="inviteModal" tabindex="-1" aria-labelledby="inviteModalLabel" aria-hidden="true">
            <div class="modal-dialog">
                <div class="modal-content">
                <div class="modal-header">
                    <h1 class="modal-title fs-5" id="inviteModalLabel">Manage Assignees</h1>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <h5>Add to <?= $task['task_name'];?></h5>
                    <form method="POST" action="services/manageassignees.php?taskid=<?= $taskid?>">
                    <?php if($n_assignees){ ?>
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
                                <?php foreach ($n_assignees as $n) { ?>
                                    <tr>
                                        <td><input type="checkbox" name="addid[]" value="<?= $n['userid']?>"></td>
                                        <td><?= $n['f_name'] . " " . $n['l_name'];?></td>
                                    </tr>
                                <?php } ?>
                            </tbody>
                        </table>
                    <?php } else { ?>No assignees available. 
                    <?php } ?>
                    <hr>
                    <h5>Remove from <?= $task['task_name'];?></h5>
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
                        <?php 
                        if($assignees){
                            foreach ($assignees as $m) { ?>
                            <tr>
                                <td><input type="checkbox" name="removeid[]" value="<?= $m['userid']?>"></td>
                                <td><?= $m['f_name'] . " " . $m['l_name'];?></td>
                            </tr>
                        <?php } 
                            } else { ?>This project has no assignees. <?php } ?>
                        </tbody>
                    </table>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                    <button type="submit" class="btn btn-success" name="manageassignees">Save Changes</input>
                    </form>
                </div>
                </div>
            </div>
        </div>
        
        <!-- For assignee delete -->
        <script>
            $(document).ready(function (){
                $('.removebtn').on('click', function() {
                    $tr = $(this).closest('tr');

                    var data = $tr.children("td").map(function() {
                        return $(this).text();
                    }).get();

                    console.log(data);
                    $('#remove_id').val(data[0]);
                });
            });
        </script>
    </body>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js" integrity="sha384-YvpcrYf0tY3lHB60NNkmXc5s9fDVZLESaAA55NDzOxhy9GkcIdslK1eN7N6jIeHz" crossorigin="anonymous"></script>
</html>
