
<?php
    function get_images($con, $table, $id) { 
        if($table == 'comment'){
            $query = "SELECT * 
                        FROM img 
                        WHERE commentid = ?";
                
        } elseif ($table == 'task') {
            $query = "SELECT *
                        FROM img
                        WHERE taskid = ?";
        }
        
        $stmt = $con->prepare($query);
        $stmt->bind_param('i', $id);
        $stmt->execute();
        $results = $stmt->get_result();
        $requests = array();
        while ($row = mysqli_fetch_assoc($results)) {
            $requests[] = $row;
        }
        
        return $requests;
    }