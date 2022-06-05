<?php
include_once 'config.php';
$s_date = $e_date = "";
$s_date_err = $e_date_err = "";
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
                    <h2 class="mt-5">Programs and Projects based on given criteria</h2>
                    <p>To check all available programs click <a href="index_program.php" class="alert-link">here</a>.</p>
                    <p>Please select your criteria. Projects that much them will appear after submission.</p>
                    <form action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>" method="post">
                    <div class="form-group">
                        <label>Starting Date after (yyyy-mm-dd):</label>
                        <input type="date" name="s_date" class="form-control <?php echo (!empty($s_date_err)) ? 'is-invalid' : ''; ?>" value="<?php echo $s_date; ?>">
                        <span class="invalid-feedback"><?php echo $s_date_err;?></span>
                    </div>
                    <div class="form-group">
                        <label>Ending Date before (yyyy-mm-dd):</label>
                        <input type="date" name="e_date" class="form-control <?php echo (!empty($e_date_err)) ? 'is-invalid' : ''; ?>" value="<?php echo $e_date; ?>">
                        <span class="invalid-feedback"><?php echo $e_date_err;?></span>
                    </div>
                    <div class="form-group">
                            Duration (in years): 
                            <select name="duration">
                                <option value=0 selected>None</option>
                                <option value=1>1</option>
                                <option value=2>2</option>
                                <option value=3>3</option>
                                <option value=4>4</option> 
                            </select>
                    </div>
                    <div class="form-group">
                            <br>ELIDEK Executive: 
                            <select name="executive">
                                <option selected>None</option>
                                <?php
                                $sql = "SELECT * FROM elidek_ex ORDER BY last_name;";
                                $records = mysqli_query($link, $sql);  
    
                                while($row = mysqli_fetch_array($records))
                                {
                                     echo "<option value='". $row['first_name']. " ". $row['last_name'] ."'>" .$row['first_name']." ".$row['last_name'] ."</option>";
                                }	  
                                ?> 
                            </select>
                    </div>
                    <br><input type="submit" value="Submit">
                    </form>
                    <div> 
                        <?php
                        if($_SERVER["REQUEST_METHOD"] == "POST"){
                        $sql = "SELECT p.project_id AS id, p.project_title AS title FROM project p INNER JOIN elidek_ex e ON p.elidek_ex_id = e.elidek_ex_id WHERE 1=1";  
                        
                        if (isset($_POST['s_date'])) {
                            $s_date = $_POST['s_date'];
                            if (!empty($s_date)){
                                $sql .= " AND start_date > '$s_date'";
                            }
                        }
                            
                        if (isset($_POST['e_date'])) {
                            $e_date = $_POST['e_date'];
                            if (!empty($e_date)){
                                $sql .= " AND end_date < '$e_date'";
                            }
                        }
                       
                        if (isset($_POST['duration'])) {
                            $duration = $_POST['duration'];
                            if ($duration != 0){
                                $sql .= " AND duration = '$duration'";
                            }
                        }
                       
                        if (isset($_POST['executive'])) {
                            if ($_POST['executive'] != "None"){
                                $executive = $_POST['executive'];
                                $exec = explode(" ", $executive);
                                $sql .= " AND e.first_name = '$exec[0]' AND e.last_name = '$exec[1]'";
                            }
                        }
            
                        if (isset($_POST['s_date']) and isset($_POST['e_date']) and isset($_POST['duration']) and isset($_POST['executive'])) {
                            if($result = mysqli_query($link, $sql)){
                            if(mysqli_num_rows($result) > 0){
                                echo '<table class="table table-bordered table-striped">';
                                echo "<thead>";
                                echo "<tr>";
                                echo "<th>Project ID</th>";
                                echo "<th>Project Title</th>";
                                echo "<th>View Researchers</th>";
                                echo "</tr>";
                                echo "</thead>";
                                echo "<tbody>";
                                while($row = mysqli_fetch_array($result)){
                                    echo "<tr>";
                                    echo "<td>" . $row['id'] . "</td>";
                                    echo "<td>" . $row['title'] . "</td>";
                                    echo "<td>";
                                        echo '<a href="view_researcher.php?project_id='. $row['id'] .'" class="mr-3"       title="View Researchers" data-toggle="tooltip"><span class="fa fa-eye"></span></a>';
                                    echo "</td>";
                                    echo "</tr>";
                                }
                                echo "</tbody>";                            
                                echo "</table>";
                                // Free result set
                                mysqli_free_result($result);
                            } else{
                                echo '<div class="alert alert-danger"><em>No records were found.</em></div>';
                            }
                            } else{
                                echo "Oops! Something went wrong. Please try again later.";
                            }
                        }
                               
                        // Close connection
                        mysqli_close($link);
                        }
                        ?>
                    </div>
                </div>
            </div>        
        </div>
    </div>
</body>
</html>