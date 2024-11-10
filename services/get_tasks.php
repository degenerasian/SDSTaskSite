
<?php
    function get_tasks($con, $proj_id) { 
?>
    <div class="container-fluid">
        <div class="row row-cols-auto px-2 pb-1 mx-auto">
            
<?php 
    $labels = array('In Progress', 'For Testing', 'For Publish', 'For Checking', 'Reopened', 'QA Passed', 'QA Failed');
    foreach ($labels as $label) { 
?>
            <div class="col hstack align-items-start my-3" style="width:15rem;">
                <div class="row row-cols-1">
                    <div class="col">
                        <h5><strong><?= $label?></strong></h5>
                    </div>
<?php
        $query = "SELECT * 
                    FROM tasks 
                    WHERE label = ?
                    AND tasks.projectid = ?";
        
        $stmt = $con->prepare($query);
        $stmt->bind_param('si', $label, $proj_id);
        $stmt->execute();
        $results = $stmt->get_result();
        $requests = array();

        while ($row = mysqli_fetch_assoc($results)) {
            $requests[] = $row;
        }
        $requests = array_map(fn($v) => $v === '' ? null : $v, $requests);
        if($requests) {
            foreach ($requests as $request) {
?>
                    <div class="col">
                        <a class="link-underline link-underline-opacity-0" href="SDSTaskSite-main/task.php?taskid=<?= $request['taskid'];?>">
                        <div class="card shadow my-1" style="width:13.5rem; border: none">
                            <h6 class="card-title px-3 py-2"><?= $request['task_name'];?></h6>
                            <div class="card-footer">
                                <p class="card-subtitle text-secondary">
                                    <?php 
                                    //  really stupid way of getting first assignee name in card footer
                                        $query = "SELECT u.userid, u.f_name, u.l_name
                                                    FROM users u, assignee a, tasks t
                                                    WHERE a.userid = u.userid
                                                    AND a.taskid = t.taskid
                                                    AND t.taskid = " . $request['taskid'] ."
                                                    LIMIT 1";
                                        $taskresult = mysqli_query($con, $query);
                                        $task = mysqli_fetch_assoc($taskresult);
                                        if (!is_null($task)){
                                            echo $task['f_name'] . $task['l_name'];
                                        } else echo "No Assignees";
                                    ?>
                                </p>
                            </div>
                        </div>
                        </a>
                    </div>
<?php }} else { ?><p>No tasks yet. <?php } ?>
                </div>
            </div>
<?php    
    }
    ?>
<?php }