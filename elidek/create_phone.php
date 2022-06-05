<?php
// Include config file
require_once "config.php";
 
// Define variables and initialize with empty values
$org_id = $phone = $org_name = "";
$org_id_err = $phone_err = "";
 
// Processing form data when form is submitted
if($_SERVER["REQUEST_METHOD"] == "POST" and !empty($_POST["organisation_name"])){
    
    //$input_org_id = trim($_POST["organisation_id"]);
    //if(empty($input_org_id)){
        //$org_id_err = "Please enter organisation id.";
    //} else{
        //$org_id = $input_org_id;
    //}
    
    $org_name = trim($_POST["organisation_name"]);
    
    $sql = "SELECT organisation_id FROM organisation 
                    WHERE organisation_name = '$org_name';";
                    $query11 = mysqli_query($link, $sql);
                    $result = $query11->fetch_array();
                    $org_id = intval($result[0]);
    
    $input_phone = trim($_POST["phone"]);
    if(empty($input_phone)){
        $phone_err = "Please enter phone number.";
    } else{
        $phone = $input_phone;
    }
    
    // Check input errors before inserting in database
    if(empty($org_id_err) && empty($phone_err)){
        // Prepare an insert statement
        $sql = "INSERT INTO phone_number (organisation_id, phone) VALUES (?, ?)";
         
        if($stmt = mysqli_prepare($link, $sql)){
            // Bind variables to the prepared statement as parameters
            mysqli_stmt_bind_param($stmt, "ii", $param_org_id, $param_phone);
            
            // Set parameters
            $param_org_id = $org_id;
            $param_phone = $phone;
            
            // Attempt to execute the prepared statement
            if(mysqli_stmt_execute($stmt)){
                // Records created successfully. Redirect to landing page
                header("location: index_phone.php");
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
                        <div class="form-group">
                            <label>Phone Number</label>
                            <input type="text" name="phone" class="form-control <?php echo (!empty($phone_err)) ? 'is-invalid' : ''; ?>" value="<?php echo $phone; ?>">
                            <span class="invalid-feedback"><?php echo $phone_err;?></span>
                        </div>
                        <input type="submit" class="btn btn-primary" value="Submit">
                        <a href="index_phone.php" class="btn btn-secondary ml-2">Cancel</a>
                    </form>
                </div>
            </div>        
        </div>
    </div>
</body>
</html>