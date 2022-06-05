<?php
// Include config file
require_once "config.php";
 
// Define variables and initialize with empty values
$org_id = $phone = "";
$org_id_err = $phone_err = "";
 
// Processing form data when form is submitted
if(isset($_POST["phone_id"]) && !empty($_POST["phone_id"])){
    // Get hidden input value
    $id = $_POST["phone_id"];
    
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
        // Prepare an update statement
        $sql = "UPDATE phone_number SET organisation_id=?, phone=? WHERE phone_id=?";
         
        if($stmt = mysqli_prepare($link, $sql)){
            // Bind variables to the prepared statement as parameters
            mysqli_stmt_bind_param($stmt, "iii", $param_org_id, $param_phone, $param_id);
            
            // Set parameters
            $param_org_id = $org_id;
            $param_phone = $phone;
            $param_id = $id;
            
            // Attempt to execute the prepared statement
            if(mysqli_stmt_execute($stmt)){
                // Records updated successfully. Redirect to landing page
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
} else{
    // Check existence of id parameter before processing further
    if(isset($_GET["phone_id"]) && !empty(trim($_GET["phone_id"]))){
        // Get URL parameter
        $id =  trim($_GET["phone_id"]);
        
        // Prepare a select statement
        $sql = "SELECT * FROM phone_number WHERE phone_id = ?";
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
                $org_id = $row['organisation_id'];
                    $sql = "SELECT organisation_name FROM organisation 
                    WHERE organisation_id = '$org_id';";
                    $query1 = mysqli_query($link, $sql);
                    $result = $query1->fetch_array();
                    $org_name = $result[0];
                $phone = $row['phone'];
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
                            <label>Organisation</label>
                            <select name="organisation_name">
                            <option selected class="form-control <?php echo (!empty($org_name_err)) ? 'is-invalid' : ''; ?>" value="<?php echo $org_name; ?>"><?php echo $org_name?></option>
                            <?php
                                include "config.php";
                                $sqlr = "SELECT * FROM organisation ORDER BY organisation_name;";
                                $records = mysqli_query($link, $sqlr);  
 
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
                        <input type="hidden" name="phone_id" value="<?php echo $id; ?>"/>
                        <input type="submit" class="btn btn-primary" value="Submit">
                        <a href="index_phone.php" class="btn btn-secondary ml-2">Cancel</a>
                    </form>
                </div>
            </div>        
        </div>
    </div>
</body>
</html>