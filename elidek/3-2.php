<?php
// Include config file
require_once "config.php";
 
// Define variables and initialize with empty values
$query = "";
$query_err = "";
 
// Processing form data when form is submitted
if($_SERVER["REQUEST_METHOD"] == "POST"){
    
    // Validate organisation_type
    $input_query = trim($_POST["query"]);
    if(empty($input_query)){
        $query_err = "Please choose a view.";
    } else{
        $query = $input_query;
    }
    
    // Check input errors before inserting in database
    if(empty($query_err)){
        if($query=="q3-1"){
                // Records created successfully. Redirect to landing page
                header("location: 3-2a.php");
                exit();
            } 
        else if($query=="q3-2"){
                // Records created successfully. Redirect to landing page
                header("location: 3-2b.php");
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
                    <h2>Views: Projects per Researcher, Projects per Organisation</h2>
                    <h3 class="mt-5">Choose view</h3>
                    <p>Please select the view you want to check.</p>
                    <form action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>" method="post">
                        <div class="form-group">
                            <label>View Projects per Researcher</label>
                            <input type="radio" name="query" id="q3-1" class="form-control <?php echo (!empty($query_err)) ? 'is-invalid' : ''; ?>" value="q3-1" />
                            <label>View Projects per Organisation</label>
                            <input type="radio" name="query" id="q3-2" class="form-control <?php echo (!empty($query_err)) ? 'is-invalid' : ''; ?>" value="q3-2" />
                            <span class="invalid-feedback"><?php echo $query_err;?></span>
                        </div>
                        <input type="submit" class="btn btn-primary" value="Submit">
                    </form>
                </div>
            </div>        
        </div>
    </div>
</body>
</html>