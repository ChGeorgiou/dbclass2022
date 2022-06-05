<?php
// Include config file
require_once "config.php";
 
// Define variables and initialize with empty values
$f_name = $l_name = $sex = $birth = $hired = $org_id = "";
$f_name_err = $l_name_err = $sex_err = $birth_err = $hired_err = $org_id_err = "";
 
// Processing form data when form is submitted
if(isset($_POST["researcher_id"]) && !empty($_POST["researcher_id"])){
    // Get hidden input value
    $id = $_POST["researcher_id"];
    
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
    
    $input_sex = trim($_POST["sex"]);
    if(empty($input_sex)){
        $sex_err = "Please enter sex.";
    } else{
        $sex = $input_sex;
    }
    
    $input_birth = trim($_POST["date_of_birth"]);
    if(empty($input_birth)){
        $birth_err = "Please enter the date of birth.";
    } else{
        $birth = $input_birth;
    }
    
    $input_hired = trim($_POST["date_hired"]);
    if(empty($input_hired)){
        $hired_err = "Please enter the date they were hired.";
    } else{
        $hired = $input_hired;
    }
    
    $input_org_id = trim($_POST["organisation_id"]);
    if(empty($input_org_id)){
        $org_id_err = "Please enter organisation id.";
    } else{
        $org_id = $input_org_id;
    }
    
    // Check input errors before inserting in database
    if(empty($f_name_err) && empty($l_name_err) && empty($sex_err) && empty($birth_err) && empty($hired_err) && empty($org_id_err)){
        // Prepare an update statement
        $sql = "UPDATE researcher SET first_name=?, last_name=?, sex=?, date_of_birth=?, date_hired=?, organisation_id=? WHERE researcher_id=?";
         
        if($stmt = mysqli_prepare($link, $sql)){
            // Bind variables to the prepared statement as parameters
            mysqli_stmt_bind_param($stmt, "ssssssi", $param_f_name, $param_l_name, $param_sex, $param_birth, $param_hired, $param_org_id, $param_id);
            
            // Set parameters
            $param_f_name = $f_name;
            $param_l_name = $l_name;
            $param_sex = $sex;
            $param_birth = $birth;
            $param_hired = $hired;
            $param_org_id = $org_id;
            $param_id = $id;
            
            // Attempt to execute the prepared statement
            if(mysqli_stmt_execute($stmt)){
                // Records updated successfully. Redirect to landing page
                header("location: index_researcher.php");
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
    if(isset($_GET["researcher_id"]) && !empty(trim($_GET["researcher_id"]))){
        // Get URL parameter
        $id =  trim($_GET["researcher_id"]);
        
        // Prepare a select statement
        $sql = "SELECT * FROM researcher WHERE researcher_id = ?";
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
                $f_name = $row['first_name'];
                $l_name = $row['last_name'];
                $sex = $row['sex'];
                $birth = $row['date_of_birth'];
                $hired = $row['date_hired'];
                $org_id = $row['organisation_id'];
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
                            <label>First Name</label>
                            <input type="text" name="first_name" class="form-control <?php echo (!empty($f_name_err)) ? 'is-invalid' : ''; ?>" value="<?php echo $f_name; ?>">
                            <span class="invalid-feedback"><?php echo $f_name_err;?></span>
                        </div>
                        <div class="form-group">
                            <label>Last Name</label>
                            <input type="text" name="last_name" class="form-control <?php echo (!empty($l_name_err)) ? 'is-invalid' : ''; ?>" value="<?php echo $l_name; ?>">
                            <span class="invalid-feedback"><?php echo $l_name_err;?></span>
                        </div>
                        <div class="form-group">
                            <label>Sex</label>
                            <input type="text" name="sex" class="form-control <?php echo (!empty($sex_err)) ? 'is-invalid' : ''; ?>" value="<?php echo $sex; ?>">
                            <span class="invalid-feedback"><?php echo $sex_err;?></span>
                        </div>
                        <div class="form-group">
                            <label>Date of birth</label>
                            <input type="text" name="date_of_birth" class="form-control <?php echo (!empty($birth_err)) ? 'is-invalid' : ''; ?>" value="<?php echo $birth; ?>">
                            <span class="invalid-feedback"><?php echo $birth_err;?></span>
                        </div>
                        <div class="form-group">
                            <label>Date hired</label>
                            <input type="text" name="date_hired" class="form-control <?php echo (!empty($hired_err)) ? 'is-invalid' : ''; ?>" value="<?php echo $hired; ?>">
                            <span class="invalid-feedback"><?php echo $hired_err;?></span>
                        </div>
                        <div class="form-group">
                            <label>Organisation ID</label>
                            <input type="text" name="organisation_id" class="form-control <?php echo (!empty($org_id_err)) ? 'is-invalid' : ''; ?>" value="<?php echo $org_id; ?>">
                            <span class="invalid-feedback"><?php echo $org_id_err;?></span>
                        </div>
                        <input type="hidden" name="researcher_id" value="<?php echo $id; ?>"/>
                        <input type="submit" class="btn btn-primary" value="Submit">
                        <a href="index_researcher.php" class="btn btn-secondary ml-2">Cancel</a>
                    </form>
                </div>
            </div>        
        </div>
    </div>
</body>
</html>