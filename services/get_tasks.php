
<?php
    function get_tasks($con, $proj_id) { 
?>
    <div class="container-fluid">
        <form action=" <?php echo htmlspecialchars($_SERVER["PHP_SELF"]);?> ">
        <div class="row row-cols-auto px-5 py-3 mx-auto">
            
<?php 
    $labels = array('In Progress', 'For Testing', 'For Publish', 'For Checking', 'Reopened', 'QA Passed', 'QA Failed');
    foreach ($labels as $label) { 
?>
            <div class="col hstack mx-2 align-items-start my-3" style="width:15rem;">
                <div class="row row-cols-1">
                    <div class="col">
                        <h5><strong><?php echo $label?></strong></h5>
                    </div>
<?php
        $query = "SELECT * FROM tasks 
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

        foreach ($requests as $request) {
?>
                    <div class="col py-2">
                        <div class="card btn" data-bs-toggle="offcanvas" href="#task-offcanvas" role="button" aria-controls="task-offcanvas">
                            <input type="hidden" name="<?php echo $request['taskid'];?>" value="">
                            Link with href
                        </a>
                        </div>
                    </div>
<?php } ?>
                </div>
            </div>
<?php    
    }
    ?>
        </form>
        </div>
        <div class="offcanvas offcanvas-end w-75" tabindex="1" id="task-offcanvas" aria-labelledby="task-offcanvasLabel">
            <div class="offcanvas-header">
                <h5 class="offcanvas-title" id="offcanvasExampleLabel">Offcanvas</h5>
                <button type="button" class="btn-close" data-bs-dismiss="offcanvas" aria-label="Close"></button>
            </div>
            <div class="offcanvas-body">
                <div>
                Some text as placeholder. In real life you can have the elements you have chosen. Like, text, images, lists, etc.
                </div>
                <div class="dropdown mt-3">
                <input type="text" name="taskid" id="taskid"/>
                <button class="btn btn-secondary dropdown-toggle" type="button" data-bs-toggle="dropdown">
                    Dropdown button
                </button>
                <ul class="dropdown-menu">
                    <li><a class="dropdown-item" href="#">Action</a></li>
                    <li><a class="dropdown-item" href="#">Another action</a></li>
                    <li><a class="dropdown-item" href="#">Something else here</a></li>
                </ul>
                </div>
            </div>
        </div>
    </div>

<script>
    $('#task-offcanvas').on('show.bs.offcanvas', function (event) {
        let taskId = $(event.relatedTarget).data('taskid') 
        console.log(taskId);
        $(this).find('.offcanvas-body input').val(taskId)
    })
</script>
<?php }