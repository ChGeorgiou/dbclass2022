<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Dashboard</title>
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/font-awesome/4.7.0/css/font-awesome.min.css">
    <script src="https://code.jquery.com/jquery-3.5.1.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/popper.js@1.16.1/dist/umd/popper.min.js"></script>
    <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>
    <style>
        .wrapper{
            width: 1000px;
            margin: 0 auto;
        }
        table tr td:last-child{
            width: 120px;
        }
    </style>
    <script>
        $(document).ready(function(){
            $('[data-toggle="tooltip"]').tooltip();   
        });
    </script>
</head>
<body>
    <div class="wrapper">
        <div class="container-fluid">
            <div class="row">
                <div class="col-md-12">
                    <div class="mt-5 mb-3 clearfix">
                        <h2 class="pull-left">Project Details</h2>
                        <a href="create_project.php" class="btn btn-success pull-right"><i class="fa fa-plus"></i> Add New Record</a>
                    </div>
                    <?php
                    // Include config file
                    require_once "config.php";

                    // Attempt select query execution
                    $sql = "SELECT * FROM project";
                    if($result = mysqli_query($link, $sql)){
                        if(mysqli_num_rows($result) > 0){
                            echo '<table class="table table-bordered table-striped">';
                                echo "<thead>";
                                    echo "<tr>";
                                        echo "<th>#</th>";
                                        echo "<th>Starting Date</th>";
                                        echo "<th>Ending Date</th>";
                                        echo "<th>Duration</th>";
                                        echo "<th>Funding</th>";
                                        echo "<th>Title</th>";
                                        echo "<th>Description</th>";
                                        echo "<th>Executor ID</th>";
                                        echo "<th>Program ID</th>";
                                        echo "<th>Organisation ID</th>";
                                        echo "<th>Supervisor ID</th>";
                                        echo "<th>Evaluator ID</th>";
                                        echo "<th>Evaluation Grade</th>";
                                        echo "<th>Evaluation Date</th>";
                                    echo "</tr>";
                                echo "</thead>";
                                echo "<tbody>";
                                while($row = mysqli_fetch_array($result)){
                                    echo "<tr>";
                                        echo "<td>" . $row['project_id'] . "</td>";
                                        echo "<td>" . $row['start_date'] . "</td>";
                                        echo "<td>" . $row['end_date'] . "</td>";
                                        echo "<td>" . $row['duration'] . "</td>";
                                        echo "<td>" . $row['fund'] . "</td>";
                                        echo "<td>" . $row['project_title'] . "</td>";
                                        echo "<td>" . $row['project_description'] . "</td>";
                                        echo "<td>" . $row['elidek_ex_id'] . "</td>";
                                        echo "<td>" . $row['program_id'] . "</td>";
                                        echo "<td>" . $row['organisation_id'] . "</td>";
                                        echo "<td>" . $row['supervisor_id'] . "</td>";
                                        echo "<td>" . $row['evaluator_id'] . "</td>";
                                        echo "<td>" . $row['eval_grade'] . "</td>";
                                        echo "<td>" . $row['eval_date'] . "</td>";
                                        echo "<td>";
                                            echo '<a href="read_project.php?project_id='. $row['project_id'] .'" class="mr-3" title="View Record" data-toggle="tooltip"><span class="fa fa-eye"></span></a>';
                                            echo '<a href="update_project.php?project_id='. $row['project_id'] .'" class="mr-3" title="Update Record" data-toggle="tooltip"><span class="fa fa-pencil"></span></a>';
                                            echo '<a href="delete_project.php?project_id='. $row['project_id'] .'" title="Delete Record" data-toggle="tooltip"><span class="fa fa-trash"></span></a>';
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
 
                    // Close connection
                    mysqli_close($link);
                    ?>
                </div>
            </div>        
        </div>
    </div>
</body>
</html>