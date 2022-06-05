<?php
include_once 'config.php';
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Create Record</title>
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
    <style>
        .wrapper{
            width: 800px;
            margin: 0 auto;
        }
    </style>
</head>
<body>
    <div class="wrapper">
        <div class="container-fluid">
            <div class="row">
                <div class="col-md-12">
                    <h2 class="mt-5">Projects and Researchers per Research Field</h2>
                    <p>Please select the research field.</p>
                    <form method="post">
                    <br> Scientific Field: 
                    <select name="name">
                        <option disabled selected>-- Select Field --</option>
                        <?php
                        $sql = "SELECT * FROM research_field;";
                        $records = mysqli_query($link, $sql);  
  
                        while($row = mysqli_fetch_array($records))
                        {
                            echo "<option value='". $row['field_name'] ."'>" .$row['field_name'] ."</option>";  // displaying data in option menu
                        }	  
                        ?> 
                    </select>
                    <input type="submit" value="Submit">
                    </form>
                    <div class="form-group"> 
                            <?php
                                if (isset($_POST['name'])) {
                                $name = $_POST['name'];
    
                                if(!$name) {
                                    echo '<p "Please Select a field.</p>';
                                }
                                else {
                                    echo "<br>" . "Projects that are classified in this field:" . "<br>";
                                    
                                    $sql = "SELECT p.project_title AS title, p.project_id AS p_id
                                    FROM project p
                                    INNER JOIN is_about i
                                    ON p.project_id = i.project_id
                                    WHERE field_name ='$name' AND p.end_date > CURDATE() 
                                    ORDER BY title";
                                    if($result = mysqli_query($link, $sql)){
                                        if(mysqli_num_rows($result) > 0){
                                            echo '<table class="table table-bordered table-striped">';
                                            echo "<thead>";
                                            echo "<tr>";
                                            echo "<th>Project Title</th>";
                                            echo "<th>Project ID</th>";
                                            echo "</tr>";
                                            echo "</thead>";
                                            echo "<tbody>";
                                            while($row = mysqli_fetch_array($result)){
                                                echo "<tr>";
                                                echo "<td>" . $row['title'] . "</td>";
                                                echo "<td>" . $row['p_id'] . "</td>";
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
                                    
                                    echo "<br>" . "Researchers that participate in projects classified in this field:" . "<br>";
                                    $sql = "SELECT r.researcher_id AS r_id, r.first_name AS f_name, r.last_name AS l_name 
                                    FROM project p, researcher r 
                                    WHERE 
                                    EXISTS(SELECT p_id from participates_in i 
                                    WHERE (i.researcher_id = r.researcher_id AND i.project_id = p.project_id)) 
                                    AND exists(select project_id FROM is_about f 
                                    WHERE p.project_id = f.project_id AND f.field_name = '$name') AND p.end_date > CURDATE() 
                                    ORDER BY l_name;";
                                    if($result = mysqli_query($link, $sql)){
                                        if(mysqli_num_rows($result) > 0){
                                            echo '<table class="table table-bordered table-striped">';
                                            echo "<thead>";
                                            echo "<tr>";
                                            echo "<th>First Name</th>";
                                            echo "<th>Last Name</th>";
                                            echo "<th>Researcher ID</th>";
                                            echo "</tr>";
                                            echo "</thead>";
                                            echo "<tbody>";
                                            while($row = mysqli_fetch_array($result)){
                                                echo "<tr>";
                                                echo "<td>" . $row['f_name'] . "</td>";
                                                echo "<td>" . $row['l_name'] . "</td>";
                                                echo "<td>" . $row['r_id'] . "</td>";
                                                echo "</tr>";
                                            }
                                            echo "</tbody>";                            
                                            echo "</table>";
                                        
                                        } else{
                                            echo '<div class="alert alert-danger"><em>No records were found.</em></div>';
                                        }
                                    } else{
                                        echo "Oops! Something went wrong. Please try again later.";
                                    }
                                    
                                    // Close connection
                                    mysqli_close($link);
                                }
                                }
                            ?>
                    </div>
                </div>
            </div>        
        </div>
    </div>
</body>
</html>