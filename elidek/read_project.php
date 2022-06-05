<?php
// Check existence of id parameter before processing further
if(isset($_GET["project_id"]) && !empty(trim($_GET["project_id"]))){
    // Include config file
    require_once "config.php";
    
    // Prepare a select statement
    $sql = "SELECT p.project_title, p.start_date, p.end_date, p.duration, p.fund, p.eval_grade, p.eval_date, o.organisation_name AS name, r1.first_name AS sup_f_name, r1.last_name AS sup_l_name, r2.first_name AS ev_f_name, r2.last_name AS ev_l_name, e.first_name AS ex_f_name, e.last_name AS ex_l_name, pr.title AS program_title, p.project_description
    FROM project p
    INNER JOIN organisation o ON o.organisation_id = p.organisation_id
    INNER JOIN researcher r1 ON p.supervisor_id = r1.researcher_id
    INNER JOIN researcher r2 ON p.evaluator_id = r2.researcher_id
    INNER JOIN elidek_ex e ON p.elidek_ex_id = e.elidek_ex_id
    INNER JOIN program pr ON pr.program_id = p.program_id
    WHERE project_id = ?";
    
    if($stmt = mysqli_prepare($link, $sql)){
        // Bind variables to the prepared statement as parameters
        mysqli_stmt_bind_param($stmt, "i", $param_id);
        
        // Set parameters
        $param_id = trim($_GET["project_id"]);
        
        // Attempt to execute the prepared statement
        if(mysqli_stmt_execute($stmt)){
            $result = mysqli_stmt_get_result($stmt);
    
            if(mysqli_num_rows($result) == 1){
                /* Fetch result row as an associative array. Since the result set
                contains only one row, we don't need to use while loop */
                $row = mysqli_fetch_array($result, MYSQLI_ASSOC);
                
                // Retrieve individual field value
                $title = $row['project_title'];
                $s_date = $row['start_date'];
                $e_date = $row['end_date'];
                $duration = $row['duration'];
                $fund = $row['fund'];
                $desc = $row['project_description'];
                $ex_id = $row['ex_f_name']." ".$row['ex_l_name'];
                $prog_id = $row['program_title'];
                $org_id = $row['name'];
                $super_id = $row['sup_f_name']." ".$row['sup_l_name'];
                $eval_id = $row['ev_f_name']." ".$row['ev_l_name'];
                $grade = $row['eval_grade'];
                $date = $row['eval_date'];
            } else{
                // URL doesn't contain valid id parameter. Redirect to error page
                header("location: error.php");
                exit();
            }
            
        } else{
            echo "Oops! Something went wrong. Please try again later.";
        }
    }
     
    // Close statement
    mysqli_stmt_close($stmt);
    
    // Close connection
    mysqli_close($link);
} else{
    // URL doesn't contain id parameter. Redirect to error page
    header("location: error.php");
    exit();
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>View Record</title>
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
    <style>
        .wrapper{
            width: 1000px;
            margin: 0 auto;
        }
    </style>
</head>
<body>
    <div class="wrapper">
        <div class="container-fluid">
            <div class="row">
                <div class="col-md-12">
                    <h1 class="mt-5 mb-3">View Record</h1>
                    <div class="form-group">
                        <label>Starting Date</label>
                        <p><b><?php echo $row["start_date"]; ?></b></p>
                    </div>
                    <div class="form-group">
                        <label>Ending Date</label>
                        <p><b><?php echo $row["end_date"]; ?></b></p>
                    </div>
                    <div class="form-group">
                        <label>Duration</label>
                        <p><b><?php echo $row["duration"]; ?></b></p>
                    </div>
                    <div class="form-group">
                        <label>Funding</label>
                        <p><b><?php echo $row["fund"]; ?></b></p>
                    </div>
                    <div class="form-group">
                        <label>Title</label>
                        <p><b><?php echo $row["project_title"]; ?></b></p>
                    </div>
                    <div class="form-group">
                        <label>Description</label>
                        <p><b><?php echo $row["project_description"]; ?></b></p>
                    </div>
                    <div class="form-group">
                        <label>Executive</label>
                        <p><b><?php echo $row['ex_f_name']." ".$row['ex_l_name']; ?></b></p>
                    </div>
                    <div class="form-group">
                        <label>Program</label>
                        <p><b><?php echo $row["program_title"]; ?></b></p>
                    </div>
                    <div class="form-group">
                        <label>Organisation</label>
                        <p><b><?php echo $row["name"]; ?></b></p>
                    </div>
                    <div class="form-group">
                        <label>Supervisor</label>
                        <p><b><?php echo $row['sup_f_name']." ".$row['sup_l_name']; ?></b></p>
                    </div>
                    <div class="form-group">
                        <label>Evaluator</label>
                        <p><b><?php echo $row['ev_f_name']." ".$row['ev_l_name']; ?></b></p>
                    </div>
                    <div class="form-group">
                        <label>Grade</label>
                        <p><b><?php echo $row["eval_grade"]; ?></b></p>
                    </div>
                    <div class="form-group">
                        <label>Evaluation Date</label>
                        <p><b><?php echo $row["eval_date"]; ?></b></p>
                    </div>
                    <p><a href="index_project.php" class="btn btn-primary">Back</a></p>
                </div>
            </div>        
        </div>
    </div>
</body>
</html>