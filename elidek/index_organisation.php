<?php
// Include config file
require_once "config.php";
 
// Define variables and initialize with empty values
$type = "";
$type_err = "";
 
// Processing form data when form is submitted
if($_SERVER["REQUEST_METHOD"] == "POST"){
    
    // Validate organisation_type
    $input_type = trim($_POST["organisation_type"]);
    if(empty($input_type)){
        $type_err = "Please enter the type.";
    } else{
        $type = $input_type;
    }
    
    // Check input errors before inserting in database
    if(empty($type_err)){
        if($type=="Firm"){
                // Records created successfully. Redirect to landing page
                header("location: index_firm.php");
                exit();
            } 
        else if($type=="University"){
                // Records created successfully. Redirect to landing page
                header("location: index_university.php");
                exit();
            }
        else if($type=="Research Center"){
                // Records created successfully. Redirect to landing page
                header("location: index_research_center.php");
                exit();
            }
        else if($type=="All"){
                // Records created successfully. Redirect to landing page
                header("location: index_all_organisations.php");
                exit();
            }
        else{
                echo "Oops! Something went wrong. Please try again later.";
            }
        
    
    // Close connection
    mysqli_close($link);
    }
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
                    <h2 class="mt-5">Choose type</h2>
                    <p>Please select the type of the organisation.</p>
                    <form action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>" method="post">
                        <div class="form-group">
                            <label for="firm">Firm</label>
                            <input type="radio" name="organisation_type" id="firm" class="form-control <?php echo (!empty($type_err)) ? 'is-invalid' : ''; ?>" value="Firm" />
                            <label for="university">University</label>
                            <input type="radio" name="organisation_type" id="university" class="form-control <?php echo (!empty($type_err)) ? 'is-invalid' : ''; ?>" value="University" />
                            <label for="center">Research Center</label>
                            <input type="radio" name="organisation_type" id="center" class="form-control <?php echo (!empty($type_err)) ? 'is-invalid' : ''; ?>" value="Research Center" />
                            <label for="center">View all (only for viewing)</label>
                            <input type="radio" name="organisation_type" id="center" class="form-control <?php echo (!empty($type_err)) ? 'is-invalid' : ''; ?>" value="All" />
                            <span class="invalid-feedback"><?php echo $type_err;?></span>
                        </div>
                        <input type="submit" class="btn btn-primary" value="Submit">
                    </form>
                </div>
            </div>        
        </div>
    </div>
</body>
</html>