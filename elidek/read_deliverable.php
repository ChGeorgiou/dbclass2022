<?php
// Check existence of id parameter before processing further
if(isset($_GET["del_id"]) && !empty(trim($_GET["del_id"]))){
    // Include config file
    require_once "config.php";
    
    // Prepare a select statement
    $sql = "SELECT d.del_id as del_id, p.project_title as project_title, p.project_id as project_id, d.title as title, d.summary as summary, d.delivery_date as delivery_date FROM deliverable d INNER JOIN project p ON d.project_id = p.project_id WHERE del_id = ?";
    
    if($stmt = mysqli_prepare($link, $sql)){
        // Bind variables to the prepared statement as parameters
        mysqli_stmt_bind_param($stmt, "i", $param_id);
        
        // Set parameters
        $param_id = trim($_GET["del_id"]);
        
        // Attempt to execute the prepared statement
        if(mysqli_stmt_execute($stmt)){
            $result = mysqli_stmt_get_result($stmt);
    
            if(mysqli_num_rows($result) == 1){
                /* Fetch result row as an associative array. Since the result set
                contains only one row, we don't need to use while loop */
                $row = mysqli_fetch_array($result, MYSQLI_ASSOC);
                
                // Retrieve individual field value
                $p_title = $row['project_title'];
                $p_id = $row['project_id'];
                $title = $row['title'];
                $summary = $row['summary'];
                $date = $row['delivery_date'];
                $id = $row['del_id'];
                
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
                        <label>Deliverable ID</label>
                        <p><b><?php echo $row["del_id"]; ?></b></p>
                    </div>
                    <div class="form-group">
                        <label>Project Title</label>
                        <p><b><?php echo $row["project_title"]; ?></b></p>
                    </div>
                    <div class="form-group">
                        <label>Project ID</label>
                        <p><b><?php echo $row["project_id"]; ?></b></p>
                    </div>
                    <div class="form-group">
                        <label>Deliverable Title</label>
                        <p><b><?php echo $row["title"]; ?></b></p>
                    </div>
                    <div class="form-group">
                        <label>Summary</label>
                        <p><b><?php echo $row["summary"]; ?></b></p>
                    </div>
                    <div class="form-group">
                        <label>Delivery Date</label>
                        <p><b><?php echo $row["delivery_date"]; ?></b></p>
                    </div>
                    <p><a href="index_deliverable.php" class="btn btn-primary">Back</a></p>
                </div>
            </div>        
        </div>
    </div>
</body>
</html>