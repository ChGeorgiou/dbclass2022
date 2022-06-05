<?php
// Include config file
require_once "config.php";
 
// Define variables and initialize with empty values
$title = $dept = "";
$title_err = $dept_err = "";
 
// Processing form data when form is submitted
if($_SERVER["REQUEST_METHOD"] == "POST"){

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
        // Prepare an insert statement
        $sql = "INSERT INTO program (title, elidek_dep) VALUES (?, ?)";
         
        if($stmt = mysqli_prepare($link, $sql)){
            // Bind variables to the prepared statement as parameters
            mysqli_stmt_bind_param($stmt, "ss", $param_title, $param_dept);
            
            // Set parameters
            $param_title = $title;
            $param_dept = $dept;
            
            // Attempt to execute the prepared statement
            if(mysqli_stmt_execute($stmt)){
                // Records created successfully. Redirect to landing page
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
                            <label>Title</label>
                            <input type="text" name="title" class="form-control <?php echo (!empty($title_err)) ? 'is-invalid' : ''; ?>" value="<?php echo $title; ?>">
                            <span class="invalid-feedback"><?php echo $title_err;?></span>
                        </div>
                        <div class="form-group">
                            <label>ELIDEK Department</label>
                            <input type="text" name="elidek_dep" class="form-control <?php echo (!empty($dept_err)) ? 'is-invalid' : ''; ?>" value="<?php echo $dept; ?>">
                            <span class="invalid-feedback"><?php echo $dept_err;?></span>
                        </div>
                        <input type="submit" class="btn btn-primary" value="Submit">
                        <a href="index_program.php" class="btn btn-secondary ml-2">Cancel</a>
                    </form>
                </div>
            </div>        
        </div>
    </div>
</body>
</html>