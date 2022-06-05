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
        $query_err = "Please choose a query.";
    } else{
        $query = $input_query;
    }
    
    // Check input errors before inserting in database
    if(empty($query_err)){
        if($query=="q3-1"){
                // Records created successfully. Redirect to landing page
                header("location: 3-1.php");
                exit();
            } 
        else if($query=="q3-2"){
                // Records created successfully. Redirect to landing page
                header("location: 3-2.php");
                exit();
            }
        else if($query=="q3-3"){
                // Records created successfully. Redirect to landing page
                header("location: 3-3.php");
                exit();
            }
        else if($query=="q3-4"){
                // Records created successfully. Redirect to landing page
                header("location: 3-4.php");
                exit();
            } 
        else if($query=="q3-5"){
                // Records created successfully. Redirect to landing page
                header("location: 3-5.php");
                exit();
            } 
        else if($query=="q3-6"){
                // Records created successfully. Redirect to landing page
                header("location: 3-6.php");
                exit();
            } 
        else if($query=="q3-7"){
                // Records created successfully. Redirect to landing page
                header("location: 3-7.php");
                exit();
            } 
        else if($query=="q3-8"){
                // Records created successfully. Redirect to landing page
                header("location: 3-8.php");
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
                    <h2 class="mt-5">Choose query</h2>
                    <p>Please select the query you want to execute.</p>
                    <form action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>" method="post">
                        <div class="form-group">
                            <label>Query 3.1</label>
                            <input type="radio" name="query" id="q3-1" class="form-control <?php echo (!empty($query_err)) ? 'is-invalid' : ''; ?>" value="q3-1" />
                            <label>Query 3.2</label>
                            <input type="radio" name="query" id="q3-2" class="form-control <?php echo (!empty($query_err)) ? 'is-invalid' : ''; ?>" value="q3-2" />
                            <label>Query 3.3</label>
                            <input type="radio" name="query" id="q3-3" class="form-control <?php echo (!empty($query_err)) ? 'is-invalid' : ''; ?>" value="q3-3" />
                            <label>Query 3.4</label>
                            <input type="radio" name="query" id="q3-4" class="form-control <?php echo (!empty($query_err)) ? 'is-invalid' : ''; ?>" value="q3-4" />
                            <label>Query 3.5</label>
                            <input type="radio" name="query" id="q3-5" class="form-control <?php echo (!empty($query_err)) ? 'is-invalid' : ''; ?>" value="q3-5" />
                            <label>Query 3.6</label>
                            <input type="radio" name="query" id="q3-6" class="form-control <?php echo (!empty($query_err)) ? 'is-invalid' : ''; ?>" value="q3-6" />
                            <label>Query 3.7</label>
                            <input type="radio" name="query" id="q3-7" class="form-control <?php echo (!empty($query_err)) ? 'is-invalid' : ''; ?>" value="q3-7" />
                            <label>Query 3.8</label>
                            <input type="radio" name="query" id="q3-8" class="form-control <?php echo (!empty($query_err)) ? 'is-invalid' : ''; ?>" value="q3-8" />
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