<?php
// Include config file
require_once "config.php";
 
// Define variables and initialize with empty values
$title = $dept = "";
$title_err = $dept_err = "";
 
// Processing form data when form is submitted
if(isset($_POST["program_id"]) && !empty($_POST["program_id"])){
    // Get hidden input value
    $id = $_POST["program_id"];
    
    $input_title = trim($_POST["title"]);
    if(empty($input_title)){
        $title_err = "Please enter the title.";
    } else{
        $title = $input_title;
    }
    
    $input_dept = trim($_POST["elidek_dep"]);
    if(empty($input_dept)){
        $dept_err = "Please enter an address.";     
    } else{
        $dept = $input_dept;
    }
    
    // Check input errors before inserting in database
    if(empty($title_err) && empty($dept_err)){
        // Prepare an update statement
        $sql = "UPDATE program SET title=?, elidek_dep=? WHERE program_id=?";
         
        if($stmt = mysqli_prepare($link, $sql)){
            // Bind variables to the prepared statement as parameters
            mysqli_stmt_bind_param($stmt, "ssi", $param_title, $param_dept, $param_id);
            
            // Set parameters
            $param_title = $title;
            $param_dept = $dept;
            $param_id = $id;
            
            // Attempt to execute the prepared statement
            if(mysqli_stmt_execute($stmt)){
                // Records updated successfully. Redirect to landing page
                header("location: index_program.php");
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
    if(isset($_GET["program_id"]) && !empty(trim($_GET["program_id"]))){
        // Get URL parameter
        $id =  trim($_GET["program_id"]);
        
        // Prepare a select statement
        $sql = "SELECT * FROM program WHERE program_id = ?";
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
                    $title = $row["title"];
                    $dept = $row["elidek_dep"];
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
                            <label>Title</label>
                            <input type="text" name="title" class="form-control <?php echo (!empty($title_err)) ? 'is-invalid' : ''; ?>" value="<?php echo $title; ?>">
                            <span class="invalid-feedback"><?php echo $title_err;?></span>
                        </div>
                        <div class="form-group">
                            <label>ELIDEK Department</label>
                            <input type="text" name="elidek_dep" class="form-control <?php echo (!empty($dept_err)) ? 'is-invalid' : ''; ?>" value="<?php echo $dept; ?>">
                            <span class="invalid-feedback"><?php echo $dept_err;?></span>
                        </div>
                        <input type="hidden" name="program_id" value="<?php echo $id; ?>"/>
                        <input type="submit" class="btn btn-primary" value="Submit">
                        <a href="index_program.php" class="btn btn-secondary ml-2">Cancel</a>
                    </form>
                </div>
            </div>        
        </div>
    </div>
</body>
</html>