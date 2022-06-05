<?php
// Include config file
require_once "config.php";
 
// Define variables and initialize with empty values
$f_name = $l_name = "";
$f_name_err = $l_name_err = "";
 
// Processing form data when form is submitted
if($_SERVER["REQUEST_METHOD"] == "POST"){

    $input_f_name = trim($_POST["first_name"]);
    if(empty($input_f_name)){
        $f_name_err = "Please enter first name.";
    } else{
        $f_name = $input_f_name;
    }
    
    $input_l_name = trim($_POST["last_name"]);
    if(empty($input_l_name)){
        $l_name_err = "Please enter last name.";     
    } else{
        $l_name = $input_l_name;
    }
    
    // Check input errors before inserting in database
    if(empty($f_name_err) && empty($l_name_err)){
        // Prepare an insert statement
        $sql = "INSERT INTO elidek_ex (first_name, last_name) VALUES (?, ?)";
         
        if($stmt = mysqli_prepare($link, $sql)){
            // Bind variables to the prepared statement as parameters
            mysqli_stmt_bind_param($stmt, "ss", $param_f_name, $param_l_name);
            
            // Set parameters
            $param_f_name = $f_name;
            $param_l_name = $l_name;
            
            // Attempt to execute the prepared statement
            if(mysqli_stmt_execute($stmt)){
                // Records created successfully. Redirect to landing page
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
                            <label>First Name</label>
                            <input type="text" name="first_name" class="form-control <?php echo (!empty($f_name_err)) ? 'is-invalid' : ''; ?>" value="<?php echo $f_name; ?>">
                            <span class="invalid-feedback"><?php echo $f_name_err;?></span>
                        </div>
                        <div class="form-group">
                            <label>Last Name</label>
                            <input type="text" name="last_name" class="form-control <?php echo (!empty($l_name_err)) ? 'is-invalid' : ''; ?>" value="<?php echo $l_name; ?>">
                            <span class="invalid-feedback"><?php echo $l_name_err;?></span>
                        </div>
                        <input type="submit" class="btn btn-primary" value="Submit">
                        <a href="index_elidek_ex.php" class="btn btn-secondary ml-2">Cancel</a>
                    </form>
                </div>
            </div>        
        </div>
    </div>
</body>
</html>