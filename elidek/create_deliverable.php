<?php
// Include config file
require_once "config.php";
 
// Define variables and initialize with empty values
$proj_id = $title = $summary = $date = $proj_name = "";
$proj_id_err = $title_err = $summary_err = $date_err = "";
 
// Processing form data when form is submitted
if($_SERVER["REQUEST_METHOD"] == "POST" and !empty($_POST["project"])){
    
    //$input_proj_id = trim($_POST["project_id"]);
    //if(empty($input_proj_id)){
        //$proj_id_err = "Please enter project id.";
    //} else{
        //$proj_id = $input_proj_id;
    //}
    
    $proj_name = trim($_POST["project"]);
    
    $sql = "SELECT project_id FROM project 
                    WHERE project_title = '$proj_name';";
                    $query11 = mysqli_query($link, $sql);
                    $result = $query11->fetch_array();
                    $proj_id = intval($result[0]);
    
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
        // Prepare an insert statement
        $sql = "INSERT INTO deliverable (project_id, title, summary, delivery_date) VALUES (?, ?, ?, ?)";
         
        if($stmt = mysqli_prepare($link, $sql)){
            // Bind variables to the prepared statement as parameters
            mysqli_stmt_bind_param($stmt, "isss", $param_proj_id, $param_title, $param_summary, $param_date);
            
            // Set parameters
            $param_proj_id = $proj_id;
            $param_title = $title;
            $param_summary = $summary;
            $param_date = $date;
            
            // Attempt to execute the prepared statement
            if(mysqli_stmt_execute($stmt)){
                // Records created successfully. Redirect to landing page
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
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Create Record</title>
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
                    <h2 class="mt-5">Create Record</h2>
                    <p>Please fill this form and submit to add record to the database.</p>
                    <form action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>" method="post">
                        <div class="form-group"> 
                            <label>Project</label>
                            <select name="project">
                            <option disabled selected>-- Project --</option>
                            <?php
                                $sql = "SELECT * FROM project ORDER BY project_title;";
                                $records = mysqli_query($link, $sql);  
  
                                while($row = mysqli_fetch_array($records)) {
                                    echo "<option value='". $row['project_title'] ."'>" .$row['project_title'] ."</option>"; 
                                }	  
                            ?>  
                            </select>
                            <br>
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
                            <input type="date" name="delivery_date" class="form-control <?php echo (!empty($date_err)) ? 'is-invalid' : ''; ?>" value="<?php echo $date; ?>">
                            <span class="invalid-feedback"><?php echo $date_err;?></span>
                        </div>
                        <input type="submit" class="btn btn-primary" value="Submit">
                        <a href="index_deliverable.php" class="btn btn-secondary ml-2">Cancel</a>
                    </form>
                </div>
            </div>        
        </div>
    </div>
</body>
</html>