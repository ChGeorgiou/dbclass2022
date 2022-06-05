<?php
// Check existence of id parameter before processing further
if(isset($_GET["researcher_id"]) && !empty(trim($_GET["researcher_id"]))){
    // Include config file
    require_once "config.php";
    
    // Prepare a select statement
    $sql = "SELECT * FROM researcher r
    INNER JOIN organisation o ON o.organisation_id = r.organisation_id
    WHERE researcher_id = ?";
    
    if($stmt = mysqli_prepare($link, $sql)){
        // Bind variables to the prepared statement as parameters
        mysqli_stmt_bind_param($stmt, "i", $param_id);
        
        // Set parameters
        $param_id = trim($_GET["researcher_id"]);
        
        // Attempt to execute the prepared statement
        if(mysqli_stmt_execute($stmt)){
            $result = mysqli_stmt_get_result($stmt);
    
            if(mysqli_num_rows($result) == 1){
                /* Fetch result row as an associative array. Since the result set
                contains only one row, we don't need to use while loop */
                $row = mysqli_fetch_array($result, MYSQLI_ASSOC);
                
                // Retrieve individual field value
                $f_name = $row['first_name'];
                $l_name = $row['last_name'];
                $sex = $row['sex'];
                $birth = $row['date_of_birth'];
                $hired = $row['date_hired'];
                $org_id = $row['organisation_name'];
                
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
                        <label>First Name</label>
                        <p><b><?php echo $row["first_name"]; ?></b></p>
                    </div>
                    <div class="form-group">
                        <label>Last Name</label>
                        <p><b><?php echo $row["last_name"]; ?></b></p>
                    </div>
                    <div class="form-group">
                        <label>Sex</label>
                        <p><b><?php echo $row["sex"]; ?></b></p>
                    </div>
                    <div class="form-group">
                        <label>Date of birth</label>
                        <p><b><?php echo $row["date_of_birth"]; ?></b></p>
                    </div>
                    <div class="form-group">
                        <label>Date hired</label>
                        <p><b><?php echo $row["date_hired"]; ?></b></p>
                    </div>
                    <div class="form-group">
                        <label>Organisation</label>
                        <p><b><?php echo $row["organisation_name"]; ?></b></p>
                    </div>
                    <p><a href="index_researcher.php" class="btn btn-primary">Back</a></p>
                </div>
            </div>        
        </div>
    </div>
</body>
</html>