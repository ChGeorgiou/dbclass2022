<?php
// Include config file
require_once "config.php";
 
// Define variables and initialize with empty values
$s_date = $e_date = $fund = $title = $desc = $ex_id = $prog_id = $org_id = $super_id = $ev_id = $grade = $eval_date = "";
$s_date_err = $e_date_err = $fund_err = $title_err = $desc_err = $ex_id_err = $prog_id_err = $org_id_err = $super_id_err = $ev_id_err = $grade_err = $eval_date_err = "";
 
// Processing form data when form is submitted
if(isset($_POST["project_id"]) && !empty($_POST["project_id"])){
    // Get hidden input value
    $id = $_POST["project_id"];
    
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
    
    $input_ex_id = trim($_POST["elidek_ex_id"]);
    if(empty($input_ex_id)){
        $ex_id_err = "Please enter executor id.";
    } else{
        $ex_id = $input_ex_id;
    }
    
    $input_prog_id = trim($_POST["program_id"]);
    if(empty($input_prog_id)){
        $prog_id_err = "Please enter program id.";
    } else{
        $prog_id = $input_prog_id;
    }
    
    $input_org_id = trim($_POST["organisation_id"]);
    if(empty($input_org_id)){
        $org_id_err = "Please enter organisation id.";
    } else{
        $org_id = $input_org_id;
    }
    
    $input_super_id = trim($_POST["supervisor_id"]);
    if(empty($input_super_id)){
        $super_id_err = "Please enter supervisor id.";
    } else{
        $super_id = $input_super_id;
    }
    
    $input_ev_id = trim($_POST["evaluator_id"]);
    if(empty($input_ev_id)){
        $ev_id_err = "Please enter evaluator id.";
    } else{
        $ev_id = $input_ev_id;
    }
    
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
        // Prepare an update statement
        $sql = "UPDATE project SET start_date=?, end_date=?, fund=?, project_title=?, project_description=?, elidek_ex_id=?, program_id=?, organisation_id=?, supervisor_id=?, evaluator_id=?, eval_grade=?, eval_date=? WHERE project_id=?";
         
        if($stmt = mysqli_prepare($link, $sql)){
            // Bind variables to the prepared statement as parameters
            mysqli_stmt_bind_param($stmt, "ssssssssssssi", $param_s_date, $param_e_date, $param_fund, $param_title, $param_desc, $param_ex_id, $param_prog_id, $param_org_id, $param_super_id, $param_ev_id, $param_grade, $param_eval_date, $param_id);
            
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
            $param_id = $id;
            
            // Attempt to execute the prepared statement
            if(mysqli_stmt_execute($stmt)){
                // Records updated successfully. Redirect to landing page
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
} else{
    // Check existence of id parameter before processing further
    if(isset($_GET["project_id"]) && !empty(trim($_GET["project_id"]))){
        // Get URL parameter
        $id =  trim($_GET["project_id"]);
        
        // Prepare a select statement
        $sql = "SELECT * FROM project WHERE project_id = ?";
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
                $s_date = $row['start_date'];
                $e_date = $row['end_date'];
                $fund = $row['fund'];
                $title = $row['project_title'];
                $desc = $row['project_description'];
                $ex_id = $row['elidek_ex_id'];
                $prog_id = $row['program_id'];
                $org_id = $row['organisation_id'];
                $super_id = $row['supervisor_id'];
                $ev_id = $row['evaluator_id'];
                $grade = $row['eval_grade'];
                $eval_date = $row['eval_date'];
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
                            <label>Starting Date</label>
                            <input type="text" name="start_date" class="form-control <?php echo (!empty($s_date_err)) ? 'is-invalid' : ''; ?>" value="<?php echo $s_date; ?>">
                            <span class="invalid-feedback"><?php echo $s_date_err;?></span>
                        </div>
                        <div class="form-group">
                            <label>Ending Date</label>
                            <input type="text" name="end_date" class="form-control <?php echo (!empty($e_date_err)) ? 'is-invalid' : ''; ?>" value="<?php echo $e_date; ?>">
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
                            <label>Executive ID</label>
                            <input type="text" name="elidek_ex_id" class="form-control <?php echo (!empty($ex_id_err)) ? 'is-invalid' : ''; ?>" value="<?php echo $ex_id; ?>">
                            <span class="invalid-feedback"><?php echo $ex_id_err;?></span>
                        </div>
                        <div class="form-group">
                            <label>Program ID</label>
                            <input type="text" name="program_id" class="form-control <?php echo (!empty($prog_id_err)) ? 'is-invalid' : ''; ?>" value="<?php echo $prog_id; ?>">
                            <span class="invalid-feedback"><?php echo $prog_id_err;?></span>
                        </div>
                        <div class="form-group">
                            <label>Organisation ID</label>
                            <input type="text" name="organisation_id" class="form-control <?php echo (!empty($org_id_err)) ? 'is-invalid' : ''; ?>" value="<?php echo $org_id; ?>">
                            <span class="invalid-feedback"><?php echo $org_id_err;?></span>
                        </div>
                        <div class="form-group">
                            <label>Supervisor ID</label>
                            <input type="text" name="supervisor_id" class="form-control <?php echo (!empty($super_id_err)) ? 'is-invalid' : ''; ?>" value="<?php echo $super_id; ?>">
                            <span class="invalid-feedback"><?php echo $super_id_err;?></span>
                        </div>
                        <div class="form-group">
                            <label>Evaluator ID</label>
                            <input type="text" name="evaluator_id" class="form-control <?php echo (!empty($ev_id_err)) ? 'is-invalid' : ''; ?>" value="<?php echo $ev_id; ?>">
                            <span class="invalid-feedback"><?php echo $ev_id_err;?></span>
                        </div>
                        <div class="form-group">
                            <label>Evaluation Grade</label>
                            <input type="text" name="eval_grade" class="form-control <?php echo (!empty($grade_err)) ? 'is-invalid' : ''; ?>" value="<?php echo $grade; ?>">
                            <span class="invalid-feedback"><?php echo $grade_err;?></span>
                        </div>
                        <div class="form-group">
                            <label>Evaluation Date</label>
                            <input type="text" name="eval_date" class="form-control <?php echo (!empty($eval_date_err)) ? 'is-invalid' : ''; ?>" value="<?php echo $eval_date; ?>">
                            <span class="invalid-feedback"><?php echo $eval_date_err;?></span>
                        </div>
                        <input type="hidden" name="project_id" value="<?php echo $id; ?>"/>
                        <input type="submit" class="btn btn-primary" value="Submit">
                        <a href="index_project.php" class="btn btn-secondary ml-2">Cancel</a>
                    </form>
                </div>
            </div>        
        </div>
    </div>
</body>
</html>