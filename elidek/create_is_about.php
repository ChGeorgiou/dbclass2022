<?php
// Include config file
require_once "config.php";
 
// Define variables and initialize with empty values
$id = $field = $title = "";
$id_err = $field_err = $title_err = "";
 
// Processing form data when form is submitted
if($_SERVER["REQUEST_METHOD"] == "POST" and (trim($_POST["title"]) != "") and (trim($_POST["field"]) != "")){

    $title = trim($_POST["title"]);   
    
    $sql = "SELECT project_id FROM project 
    WHERE project_title = '$title';";
    $query = mysqli_query($link, $sql);
    $result = $query->fetch_array();
    $id = intval($result[0]);
    
    $field = trim($_POST["field"]);
    
    // Check input errors before inserting in database
    if(!empty($field) && !empty($id)){
        // Prepare an insert statement
        $sql = "INSERT INTO is_about (project_id, field_name) VALUES (?, ?)";
         
        if($stmt = mysqli_prepare($link, $sql)){
            // Bind variables to the prepared statement as parameters
            mysqli_stmt_bind_param($stmt, "is", $param_id, $param_field);
            
            // Set parameters
            $param_id = $id;
            $param_field = $field;
            
            // Attempt to execute the prepared statement
            if(mysqli_stmt_execute($stmt)){
                // Records created successfully. Redirect to landing page
                header("location: index_is_about.php");
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
                            <label>Project Title</label>
                            <select name="title">
                            <option value="" selected>-- Project --</option>
                            <?php
                                $sql = "SELECT * FROM project;";
                                $records = mysqli_query($link, $sql);  
  
                                while($row = mysqli_fetch_array($records)) {
                                    echo "<option value='". $row['project_title'] ."'>" .$row['project_title'] ."</option>"; 
                                }	  
                            ?>  
                            </select>
                            <br>
                        </div>
                        <div class="form-group"> 
                            <label>Research Field</label>
                            <select name="field">
                            <option value="" selected>-- Research Field --</option>
                            <?php
                                $sql = "SELECT * FROM research_field;";
                                $records = mysqli_query($link, $sql);  
  
                                while($row = mysqli_fetch_array($records)) {
                                    echo "<option value='". $row['field_name'] ."'>" .$row['field_name'] ."</option>"; 
                                }	  
                            ?>  
                            </select>
                            <br>
                        </div>
                        <input type="submit" class="btn btn-primary" value="Submit">
                        <a href="index_is_about.php" class="btn btn-secondary ml-2">Cancel</a>
                    </form>
                </div>
            </div>        
        </div>
    </div>
</body>
</html>