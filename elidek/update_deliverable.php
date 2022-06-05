<?php
// Include config file
require_once "config.php";
 
// Define variables and initialize with empty values
$proj_id = $title = $summary = $date = "";
$proj_id_err = $title_err = $summary_err = $date_err = "";
 
// Processing form data when form is submitted
if(isset($_POST["del_id"]) && !empty($_POST["del_id"])){
    // Get hidden input value
    $id = $_POST["del_id"];
    
    $input_proj_id = trim($_POST["project_id"]);
    if(empty($input_proj_id)){
        $proj_id_err = "Please enter project id.";
    } else{
        $proj_id = $input_proj_id;
    }
    
    $input_title = trim($_POST["title"]);
    if(empty($input_title)){
        $title_err = "Please enter title.";
    } else{
        $title = $input_title;
    }
    
    $input_summary = trim($_POST["summary"]);
    if(empty($input_summary)){
        $summary_err = "Please enter summary.";
    } else{
        $summary = $input_summary;
    }
    
    $input_date = trim($_POST["delivery_date"]);
    if(empty($input_date)){
        $date_err = "Please enter the delivery date.";
    } else{
        $date = $input_date;
    }
    
    // Check input errors before inserting in database
    if(empty($proj_id_err) && empty($title_err) && empty($summary_err) && empty($date_err)){
        // Prepare an update statement
        $sql = "UPDATE deliverable SET project_id=?, title=?, summary=?, delivery_date=? WHERE del_id=?";
         
        if($stmt = mysqli_prepare($link, $sql)){
            // Bind variables to the prepared statement as parameters
            mysqli_stmt_bind_param($stmt, "issss", $param_proj_id, $param_title, $param_summary, $param_date, $param_id);
            
            // Set parameters
            $param_proj_id = $proj_id;
            $param_title = $title;
            $param_summary = $summary;
            $param_date = $date;
            $param_id = $id;
            
            // Attempt to execute the prepared statement
            if(mysqli_stmt_execute($stmt)){
                // Records updated successfully. Redirect to landing page
                header("location: index_deliverable.php");
                exit();
            } else{
                echo "Oops! Something went wrong. Please try again later.";
            }
        }
         
        // Close statement
        mysqli_stmt_close($stmt);
    }
    
    // Close connection
    mysqli_close($link);
} else{
    // Check existence of id parameter before processing further
    if(isset($_GET["del_id"]) && !empty(trim($_GET["del_id"]))){
        // Get URL parameter
        $id =  trim($_GET["del_id"]);
        
        // Prepare a select statement
        $sql = "SELECT * FROM deliverable WHERE del_id = ?";
        if($stmt = mysqli_prepare($link, $sql)){
            // Bind variables to the prepared statement as parameters
            mysqli_stmt_bind_param($stmt, "i", $param_id);
            
            // Set parameters
            $param_id = $id;
            
            // Attempt to execute the prepared statement
            if(mysqli_stmt_execute($stmt)){
                $result = mysqli_stmt_get_result($stmt);
    
                if(mysqli_num_rows($result) == 1){
                    /* Fetch result row as an associative array. Since the result set
                    contains only one row, we don't need to use while loop */
                    $row = mysqli_fetch_array($result, MYSQLI_ASSOC);
                    
                   // Retrieve individual field value
                $proj_id = $row['project_id'];
                $title = $row['title'];
                $summary = $row['summary'];
                $date = $row['delivery_date'];
                } else{
                    // URL doesn't contain valid id. Redirect to error page
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
    }  else{
        // URL doesn't contain id parameter. Redirect to error page
        header("location: error.php");
        exit();
    }
}
?>
 
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Update Record</title>
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
                    <h2 class="mt-5">Update Record</h2>
                    <p>Please edit the input values and submit to update the record.</p>
                    <form action="<?php echo htmlspecialchars(basename($_SERVER['REQUEST_URI'])); ?>" method="post">
                        <div class="form-group">
                            <label>Project ID</label>
                            <input type="text" name="project_id" class="form-control <?php echo (!empty($proj_id_err)) ? 'is-invalid' : ''; ?>" value="<?php echo $proj_id; ?>">
                            <span class="invalid-feedback"><?php echo $proj_id_err;?></span>
                        </div>
                        <div class="form-group">
                            <label>Deliverable Title</label>
                            <input type="text" name="title" class="form-control <?php echo (!empty($title_err)) ? 'is-invalid' : ''; ?>" value="<?php echo $title; ?>">
                            <span class="invalid-feedback"><?php echo $title_err;?></span>
                        </div>
                        <div class="form-group">
                            <label>Summary</label>
                            <input type="text" name="summary" class="form-control <?php echo (!empty($summary_err)) ? 'is-invalid' : ''; ?>" value="<?php echo $summary; ?>">
                            <span class="invalid-feedback"><?php echo $summary_err;?></span>
                        </div>
                        <div class="form-group">
                            <label>Delivery Date</label>
                            <input type="text" name="delivery_date" class="form-control <?php echo (!empty($date_err)) ? 'is-invalid' : ''; ?>" value="<?php echo $date; ?>">
                            <span class="invalid-feedback"><?php echo $date_err;?></span>
                        </div>
                        <input type="hidden" name="del_id" value="<?php echo $id; ?>"/>
                        <input type="submit" class="btn btn-primary" value="Submit">
                        <a href="index_deliverable.php" class="btn btn-secondary ml-2">Cancel</a>
                    </form>
                </div>
            </div>        
        </div>
    </div>
</body>
</html>