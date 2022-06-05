<?php
// Include config file
require_once "config.php";
 
// Define variables and initialize with empty values
$f_name = $l_name = $sex = $birth = $hired = $org_id = "";
$f_name_err = $l_name_err = $sex_err = $birth_err = $hired_err = $org_id_err = "";
 
// Processing form data when form is submitted
if($_SERVER["REQUEST_METHOD"] == "POST" and !empty($_POST["organisation_name"])){
    
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
    
    $org_name = trim($_POST["organisation_name"]);
    
    $sql = "SELECT organisation_id FROM organisation 
                    WHERE organisation_name = '$org_name';";
                    $query11 = mysqli_query($link, $sql);
                    $result = $query11->fetch_array();
                    $org_id = intval($result[0]);
    
    //$input_org_id = trim($_POST["organisation_id"]);
    //if(empty($input_org_id)){
        //$org_id_err = "Please enter organisation id.";
    //} else{
    //    $org_id = $input_org_id;
    //}
    
    // Check input errors before inserting in database
    if(empty($f_name_err) && empty($l_name_err) && empty($sex_err) && empty($birth_err) && empty($hired_err) && empty($org_id_err)){
        // Prepare an insert statement
        $sql = "INSERT INTO researcher (first_name, last_name, sex, date_of_birth, date_hired, organisation_id) VALUES (?, ?, ?, ?, ?, ?)";
         
        if($stmt = mysqli_prepare($link, $sql)){
            // Bind variables to the prepared statement as parameters
            mysqli_stmt_bind_param($stmt, "ssssss", $param_f_name, $param_l_name, $param_sex, $param_birth, $param_hired, $param_org_id);
            
            // Set parameters
            $param_f_name = $f_name;
            $param_l_name = $l_name;
            $param_sex = $sex;
            $param_birth = $birth;
            $param_hired = $hired;
            $param_org_id = $org_id;
            
            // Attempt to execute the prepared statement
            if(mysqli_stmt_execute($stmt)){
                // Records created successfully. Redirect to landing page
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
                        <div class="form-group">
                            Sex: 
                            <select name="sex">
                                <option value=0 selected>-- Sex --</option>
                                <option value="male">Male</option>
                                <option value="female">Female</option> 
                            </select>
                    </div>
                        <div class="form-group">
                            <label>Date of birth</label>
                            <input type="date" name="date_of_birth" class="form-control <?php echo (!empty($birth_err)) ? 'is-invalid' : ''; ?>" value="<?php echo $birth; ?>">
                            <span class="invalid-feedback"><?php echo $birth_err;?></span>
                        </div>
                        <div class="form-group">
                            <label>Date hired</label>
                            <input type="date" name="date_hired" class="form-control <?php echo (!empty($hired_err)) ? 'is-invalid' : ''; ?>" value="<?php echo $hired; ?>">
                            <span class="invalid-feedback"><?php echo $hired_err;?></span>
                        </div>
                        <div class="form-group"> 
                            <label>Organisation</label>
                            <select name="organisation_name">
                            <option disabled selected>-- Organisation --</option>
                            <?php
                                $sql = "SELECT * FROM organisation ORDER BY organisation_name;";
                                $records = mysqli_query($link, $sql);  
  
                                while($row = mysqli_fetch_array($records)) {
                                    echo "<option value='". $row['organisation_name'] ."'>" .$row['organisation_name'] ."</option>"; 
                                }	  
                            ?>  
                            </select>
                            <br>
                        </div>
                        <input type="submit" class="btn btn-primary" value="Submit">
                        <a href="index_researcher.php" class="btn btn-secondary ml-2">Cancel</a>
                    </form>
                </div>
            </div>        
        </div>
    </div>
</body>
</html>