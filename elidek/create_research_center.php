<?php
// Include config file
require_once "config.php";
 
// Define variables and initialize with empty values
$name = $city = $street = $number = $code = $abr = $equity = $budget = "";
$name_err = $city_err = $street_err = $number_err = $code_err = $abr_err = $equity_err = $budget_err = "";
 
// Processing form data when form is submitted
if($_SERVER["REQUEST_METHOD"] == "POST"){
    // Validate organisation_name
    $input_name = trim($_POST["organisation_name"]);
    if(empty($input_name)){
        $name_err = "Please enter a name.";
    } else{
        $name = $input_name;
    }
    
    // Validate city
    $input_city = trim($_POST["city"]);
    if(empty($input_city)){
        $city_err = "Please enter a city.";     
    } else{
        $city = $input_city;
    }
    
    // Validate street_name
    $input_street = trim($_POST["street_name"]);
    if(empty($input_street)){
        $street_err = "Please enter the street.";
    } else{
        $street = $input_street;
    }
    
    // Validate street_number
    $input_number = trim($_POST["street_number"]);
    if(empty($input_number)){
        $number_err = "Please enter the street number.";
    } elseif(!ctype_digit($input_number)){
        $number_err = "Please enter a positive integer value.";
    } else{
        $number = $input_number;
    }
    
     // Validate postal_code
    $input_code = trim($_POST["postal_code"]);
    if(empty($input_code)){
        $code_err = "Please enter the postal code.";
    } elseif(!ctype_digit($input_code)){
        $code_err = "Please enter a positive integer value.";
    } else{
        $code = $input_code;
    }
    
    // Validate abbreviation
    $input_abr = trim($_POST["abbreviation"]);
    if(empty($input_abr)){
        $abr_err = "Please enter the street.";
    } else{
        $abr = $input_abr;
    }
    
    // Validate organisation_type
    $input_equity = trim($_POST["budget1"]);
    if(empty($input_equity)){
        $equity_err = "Please enter the ministry budget.";
    } else{
        $equity = $input_equity;
    }
    
    // Validate organisation_type
    $input_budget = trim($_POST["budget2"]);
    if(empty($input_budget)){
        $budget_err = "Please enter the private actions budget.";
    } else{
        $budget = $input_budget;
    }
    
    // Check input errors before inserting in database
    if(empty($name_err) && empty($city_err) && empty($street_err) && empty($number_err) && empty($code_err) && empty($abr_err) && empty($equity_err) && empty($budget_err)){
        // Prepare an insert statement
        $sql = "INSERT INTO organisation (organisation_name, city, street_name, street_number, postal_code, abbreviation, organisation_type, budget1, budget2) VALUES (?, ?, ?, ?, ?, ?, 'Research Center', ?, ?)";
         
        if($stmt = mysqli_prepare($link, $sql)){
            // Bind variables to the prepared statement as parameters
            mysqli_stmt_bind_param($stmt, "sssiisss", $param_name, $param_city, $param_street, $param_number, $param_code, $param_abr, $param_equity, $param_budget);
            
            // Set parameters
            $param_name = $name;
            $param_city = $city;
            $param_street = $street;
            $param_number = $number;
            $param_code = $code;
            $param_abr = $abr;
            $param_equity = $equity;
            $param_budget = $budget
            
            // Attempt to execute the prepared statement
            if(mysqli_stmt_execute($stmt)){
                // Records created successfully. Redirect to landing page
                header("location: index_research_center.php");
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
                            <label>Name</label>
                            <input type="text" name="organisation_name" class="form-control <?php echo (!empty($name_err)) ? 'is-invalid' : ''; ?>" value="<?php echo $name; ?>">
                            <span class="invalid-feedback"><?php echo $name_err;?></span>
                        </div>
                        <div class="form-group">
                            <label>City</label>
                            <input type="text" name="city" class="form-control <?php echo (!empty($city_err)) ? 'is-invalid' : ''; ?>" value="<?php echo $city ?>">
                            <span class="invalid-feedback"><?php echo $city_err;?></span>
                        </div>
                        <div class="form-group">
                            <label>Street</label>
                            <input type="text" name="street_name" class="form-control <?php echo (!empty($street_err)) ? 'is-invalid' : ''; ?>" value="<?php echo $street; ?>">
                            <span class="invalid-feedback"><?php echo $street_err;?></span>
                        </div>
                        <div class="form-group">
                            <label>Number</label>
                            <input type="text" name="street_number" class="form-control <?php echo (!empty($number_err)) ? 'is-invalid' : ''; ?>" value="<?php echo $number; ?>">
                            <span class="invalid-feedback"><?php echo $number_err;?></span>
                        </div>
                        <div class="form-group">
                            <label>Postal Code</label>
                            <input type="text" name="postal_code" class="form-control <?php echo (!empty($code_err)) ? 'is-invalid' : ''; ?>" value="<?php echo $code; ?>">
                            <span class="invalid-feedback"><?php echo $code_err;?></span>
                        </div>
                        <div class="form-group">
                            <label>Abbreviation</label>
                            <input type="text" name="abbreviation" class="form-control <?php echo (!empty($abr_err)) ? 'is-invalid' : ''; ?>" value="<?php echo $abr; ?>">
                            <span class="invalid-feedback"><?php echo $abr_err;?></span>
                        </div>
                        <div class="form-group">
                            <label>Ministry Budget</label>
                            <input type="text" name="budget1" class="form-control <?php echo (!empty($equity_err)) ? 'is-invalid' : ''; ?>" value="<?php echo $equity; ?>">
                            <span class="invalid-feedback"><?php echo $equity_err;?></span>
                        </div>
                        <div class="form-group">
                            <label>Private Actions Budget</label>
                            <input type="text" name="budget2" class="form-control <?php echo (!empty($budget_err)) ? 'is-invalid' : ''; ?>" value="<?php echo $budget; ?>">
                            <span class="invalid-feedback"><?php echo $budget_err;?></span>
                        </div>
                        <input type="submit" class="btn btn-primary" value="Submit">
                        <a href="index_research_center.php" class="btn btn-secondary ml-2">Cancel</a>
                    </form>
                </div>
            </div>        
        </div>
    </div>
</body>
</html>