<?php
// Include config file
require_once "config.php";
 
// Define variables and initialize with empty values
$s_date = $e_date = $fund = $title = $desc = $ex_id = $prog_id = $org_id = $super_id = $ev_id = $grade = $eval_date = "";
$s_date_err = $e_date_err = $fund_err = $title_err = $desc_err = $ex_id_err = $prog_id_err = $org_id_err = $super_id_err = $ev_id_err = $grade_err = $eval_date_err = "";
 
// Processing form data when form is submitted
if($_SERVER["REQUEST_METHOD"] == "POST" and !empty($_POST["elidek_ex"]) and !empty($_POST["program_title"]) and !empty($_POST["organisation_name"]) and !empty($_POST["evaluator"]) and !empty($_POST["supervisor"])){
    
    $input_s_date = trim($_POST["start_date"]);
    if(empty($input_s_date)){
        $s_date_err = "Please enter the starting date.";     
    } else{
        $s_date = $input_s_date;
    }
    
    $input_e_date = trim($_POST["end_date"]);
    if(empty($input_e_date)){
        $e_date_err = "Please enter the ending date.";
    } else{
        $e_date = $input_e_date;
    }
    
    $input_fund = trim($_POST["fund"]);
    if(empty($input_fund)){
        $fund_err = "Please enter amount of funding.";
    } else{
        $fund = $input_fund;
    }
    
    $input_title = trim($_POST["project_title"]);
    if(empty($input_title)){
        $title_err = "Please enter project title.";
    } else{
        $title = $input_title;
    }
    
    $input_desc = trim($_POST["project_description"]);
    if(empty($input_desc)){
        $desc_err = "Please enter description.";
    } else{
        $desc = $input_desc;
    }
    
    //$input_ex_id = trim($_POST["elidek_ex_id"]);
    //if(empty($input_ex_id)){
        //$ex_id_err = "Please enter executive id.";
    //} else{
        //$ex_id = $input_ex_id;
    //}
    
    $ex_name = trim($_POST["elidek_ex"]);
    $full_name = explode(" ", $ex_name);
    $sql = "SELECT elidek_ex_id FROM elidek_ex 
                    WHERE first_name = '$full_name[0]' AND last_name = '$full_name[1]';";
                    $query2 = mysqli_query($link, $sql);
                    $result = $query2->fetch_array();
                    $ex_id = intval($result[0]);
    
    //$input_prog_id = trim($_POST["program_id"]);
    //if(empty($input_prog_id)){
        //$prog_id_err = "Please enter program id.";
    //} else{
        //$prog_id = $input_prog_id;
    //}
    
    $prog_name = trim($_POST["program_title"]);
    
    $sql = "SELECT program_id FROM program 
                    WHERE title = '$prog_name';";
                    $query = mysqli_query($link, $sql);
                    $result = $query->fetch_array();
                    $prog_id = intval($result[0]);
    
    //$input_org_id = trim($_POST["organisation_id"]);
    //if(empty($input_org_id)){
    //    $org_id_err = "Please enter organisation id.";
    //} else{
    //    $org_id = $input_org_id;
    //}
    
    $org_name = trim($_POST["organisation_name"]);
    
    $sql = "SELECT organisation_id FROM organisation 
                    WHERE organisation_name = '$org_name';";
                    $query11 = mysqli_query($link, $sql);
                    $result = $query11->fetch_array();
                    $org_id = intval($result[0]);
    
    
    $sup_name = trim($_POST["supervisor"]);
    $fullsup_name = explode(" ", $sup_name);
    $sql = "SELECT researcher_id FROM researcher 
                    WHERE first_name = '$fullsup_name[0]' AND last_name = '$fullsup_name[1]';";
                    $query3 = mysqli_query($link, $sql);
                    $result = $query3->fetch_array();
                    $super_id = intval($result[0]);
    
    //$input_super_id = trim($_POST["supervisor_id"]);
    //if(empty($input_super_id)){
        //$super_id_err = "Please enter supervisor id.";
    //} else{
        //$super_id = $input_super_id;
    //}
    
    $ev_name = trim($_POST["evaluator"]);
    $fullev_name = explode(" ", $ev_name);
    $sql = "SELECT researcher_id FROM researcher 
                    WHERE first_name = '$fullev_name[0]' AND last_name = '$fullev_name[1]';";
                    $query3 = mysqli_query($link, $sql);
                    $result = $query3->fetch_array();
                    $ev_id = intval($result[0]);
    
    
    //$input_ev_id = trim($_POST["evaluator_id"]);
    //if(empty($input_ev_id)){
        //$ev_id_err = "Please enter evaluator id.";
    //} else{
        //$ev_id = $input_ev_id;
    //}
    
    $input_grade = trim($_POST["eval_grade"]);
    if(empty($input_grade)){
        $grade_err = "Please enter evaluation grade.";
    } else{
        $grade = $input_grade;
    }
    
    $input_eval_date = trim($_POST["eval_date"]);
    if(empty($input_eval_date)){
        $eval_date_err = "Please enter evaluation date.";
    } else{
        $eval_date = $input_eval_date;
    }
    
    // Check input errors before inserting in database
    if(empty($s_date_err) && empty($e_date_err) && empty($fund_err) && empty($title_err) && empty($desc_err) && empty($ex_id_err) && empty($prog_id_err) && empty($org_id_err) && empty($super_id_err) && empty($ev_id_err) && empty($grade_err) && empty($eval_date_err)){
        // Prepare an insert statement
        $sql = "INSERT INTO project (start_date, end_date, fund, project_title, project_description, elidek_ex_id, program_id, organisation_id, supervisor_id, evaluator_id, eval_grade, eval_date) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?)";
         
        if($stmt = mysqli_prepare($link, $sql)){
            // Bind variables to the prepared statement as parameters
            mysqli_stmt_bind_param($stmt, "sssssssissss", $param_s_date, $param_e_date, $param_fund, $param_title, $param_desc, $param_ex_id, $param_prog_id, $param_org_id, $param_super_id, $param_ev_id, $param_grade, $param_eval_date);
            
            // Set parameters
            $param_s_date = $s_date;
            $param_e_date = $e_date;
            $param_fund = $fund;
            $param_title = $title;
            $param_desc = $desc;
            $param_ex_id = $ex_id;
            $param_prog_id = $prog_id;
            $param_org_id = $org_id;
            $param_super_id = $super_id;
            $param_ev_id = $ev_id;
            $param_grade = $grade;
            $param_eval_date = $eval_date;
            
            // Attempt to execute the prepared statement
            if(mysqli_stmt_execute($stmt)){
                // Records created successfully. Redirect to landing page
                header("location: index_project.php");
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
                            <label>Starting Date</label>
                            <input type="date" name="start_date" class="form-control <?php echo (!empty($s_date_err)) ? 'is-invalid' : ''; ?>" value="<?php echo $s_date; ?>">
                            <span class="invalid-feedback"><?php echo $s_date_err;?></span>
                        </div>
                        <div class="form-group">
                            <label>Ending Date</label>
                            <input type="date" name="end_date" class="form-control <?php echo (!empty($e_date_err)) ? 'is-invalid' : ''; ?>" value="<?php echo $e_date; ?>">
                            <span class="invalid-feedback"><?php echo $e_date_err;?></span>
                        </div>
                        <div class="form-group">
                            <label>Funding</label>
                            <input type="text" name="fund" class="form-control <?php echo (!empty($fund_err)) ? 'is-invalid' : ''; ?>" value="<?php echo $fund; ?>">
                            <span class="invalid-feedback"><?php echo $fund_err;?></span>
                        </div>
                        <div class="form-group">
                            <label>Title</label>
                            <input type="text" name="project_title" class="form-control <?php echo (!empty($title_err)) ? 'is-invalid' : ''; ?>" value="<?php echo $title; ?>">
                            <span class="invalid-feedback"><?php echo $title_err;?></span>
                        </div>
                        <div class="form-group">
                            <label>Description</label>
                            <input type="text" name="project_description" class="form-control <?php echo (!empty($desc_err)) ? 'is-invalid' : ''; ?>" value="<?php echo $desc; ?>">
                            <span class="invalid-feedback"><?php echo $desc_err;?></span>
                        </div>
                        
                        <div class="form-group"> 
                            <label>ELIDEK Executive</label>
                            <select name="elidek_ex">
                            <option disabled selected>-- ELIDEK Executive --</option>
                            <?php
                                $sql = "SELECT * FROM elidek_ex ORDER BY last_name;";
                                $records = mysqli_query($link, $sql);  
  
                                while($row = mysqli_fetch_array($records)) {
                                    echo "<option value='". $row['first_name']. " ". $row['last_name'] ."'>" .$row['first_name']." ".$row['last_name'] ."</option>"; 
                                }	  
                            ?>  
                            </select>
                            <br>
                        </div>
                        <div class="form-group"> 
                            <label>Program</label>
                            <select name="program_title">
                            <option disabled selected>-- Program --</option>
                            <?php
                                $sql = "SELECT * FROM program ORDER BY title;";
                                $records = mysqli_query($link, $sql);  
  
                                while($row = mysqli_fetch_array($records)) {
                                    echo "<option value='". $row['title'] ."'>" .$row['title'] ."</option>"; 
                                }	  
                            ?>  
                            </select>
                            <br>
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
                        <div class="form-group"> 
                            <label>Supervisor</label>
                            <select name="supervisor">
                            <option disabled selected>-- Supervisor --</option>
                            <?php
                                $sql = "SELECT * FROM researcher ORDER BY last_name;";
                                $records = mysqli_query($link, $sql);  
  
                                while($row = mysqli_fetch_array($records)) {
                                    echo "<option value='". $row['first_name']. " ". $row['last_name'] ."'>" .$row['first_name']." ".$row['last_name'] ."</option>"; 
                                }	  
                            ?>  
                            </select>
                            <br>
                        </div>
                        <div class="form-group"> 
                            <label>Evaluator</label>
                            <select name="evaluator">
                            <option disabled selected>-- Evaluator --</option>
                            <?php
                                $sql = "SELECT * FROM researcher ORDER BY last_name;";
                                $records = mysqli_query($link, $sql);  
  
                                while($row = mysqli_fetch_array($records)) {
                                    echo "<option value='". $row['first_name']. " ". $row['last_name'] ."'>" .$row['first_name']." ".$row['last_name'] ."</option>"; 
                                }	  
                            ?>  
                            </select>
                            <br>
                        </div>
                        <div class="form-group">
                            <label>Evaluation Grade</label>
                            <input type="text" name="eval_grade" class="form-control <?php echo (!empty($grade_err)) ? 'is-invalid' : ''; ?>" value="<?php echo $grade; ?>">
                            <span class="invalid-feedback"><?php echo $grade_err;?></span>
                        </div>
                        <div class="form-group">
                            <label>Evaluation Date</label>
                            <input type="date" name="eval_date" class="form-control <?php echo (!empty($eval_date_err)) ? 'is-invalid' : ''; ?>" value="<?php echo $eval_date; ?>">
                            <span class="invalid-feedback"><?php echo $eval_date_err;?></span>
                        </div>
                        <input type="submit" class="btn btn-primary" value="Submit">
                        <a href="index_project.php" class="btn btn-secondary ml-2">Cancel</a>
                    </form>
                </div>
            </div>        
        </div>
    </div>
</body>
</html>