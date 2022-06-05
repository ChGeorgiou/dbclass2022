<?php
// Include config file
require_once "config.php";
 
// Define variables and initialize with empty values
$f_name = $l_name = "";
$f_name_err = $l_name_err = "";
 
// Processing form data when form is submitted
if(isset($_POST["elidek_ex_id"]) && !empty($_POST["elidek_ex_id"])){
    // Get hidden input value
    $id = $_POST["elidek_ex_id"];
    
    $input_f_name = trim($_POST["first_name"]);
    if(empty($input_f_name)){
        $f_name_err = "Please enter First Name.";
    } else{
        $f_name = $input_f_name;
    }
    
    $input_l_name = trim($_POST["last_name"]);
    if(empty($input_l_name)){
        $l_name_err = "Please enter Last Name.";     
    } else{
        $l_name = $input_l_name;
    }
    
    // Check input errors before inserting in database
    if(empty($f_name_err) && empty($l_name_err)){
        // Prepare an update statement
        $sql = "UPDATE elidek_ex SET first_name=?, last_name=? WHERE elidek_ex_id=?";
         
        if($stmt = mysqli_prepare($link, $sql)){
            // Bind variables to the prepared statement as parameters
            mysqli_stmt_bind_param($stmt, "ssi", $param_f_name, $param_l_name, $param_id);
            
            // Set parameters
            $param_f_name = $f_name;
            $param_l_name = $l_name;
            $param_id = $id;
            
            // Attempt to execute the prepared statement
            if(mysqli_stmt_execute($stmt)){
                // Records updated successfully. Redirect to landing page
                header("location: index_elidek_ex.php");
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
    if(isset($_GET["elidek_ex_id"]) && !empty(trim($_GET["elidek_ex_id"]))){
        // Get URL parameter
        $id =  trim($_GET["elidek_ex_id"]);
        
        // Prepare a select statement
        $sql = "SELECT * FROM elidek_ex WHERE elidek_ex_id = ?";
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
                    $f_name = $row["first_name"];
                    $l_name = $row["last_name"];
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
                            <input type="text" name="first_name" class="form-control <?php echo (!empty($f_name_err)) ? 'is-invalid' : ''; ?>" value="<?php echo $f_name; ?>">
                            <span class="invalid-feedback"><?php echo $f_name_err;?></span>
                        </div>
                        <div class="form-group">
                            <label>ELIDEK Department</label>
                            <input type="text" name="last_name" class="form-control <?php echo (!empty($l_name_err)) ? 'is-invalid' : ''; ?>" value="<?php echo $l_name; ?>">
                            <span class="invalid-feedback"><?php echo $l_name_err;?></span>
                        </div>
                        <input type="hidden" name="elidek_ex_id" value="<?php echo $id; ?>"/>
                        <input type="submit" class="btn btn-primary" value="Submit">
                        <a href="index_elidek_ex.php" class="btn btn-secondary ml-2">Cancel</a>
                    </form>
                </div>
            </div>        
        </div>
    </div>
</body>
</html>