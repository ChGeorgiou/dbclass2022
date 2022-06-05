<html>
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
<?php include 'config.php';?>
                    </div> <h3>Projects per Organisation</h3>
                    <?php
$sql = "SELECT organisation_id, organisation_name, project_id, project_title FROM projects_per_organisation";
$result = $link->query($sql);
    
if($result->num_rows>0) {
    
    echo '<table class="table table-bordered table-striped">';
                                echo "<thead>";
                                    echo "<tr>";
                                        echo "<th>Organisation ID</th>";
                                        echo "<th>Organisation Name</th>";
                                        echo "<th>Project Title</th>";
                                        echo "<th>Project ID</th>";
                                    echo "</tr>";
                                echo "</thead>";
                                echo "<tbody>";
                                while($row = mysqli_fetch_array($result)){
                                    echo "<tr>";
                                        echo "<td>" . $row['organisation_id'] . "</td>";
                                        echo "<td>" . $row['organisation_name'] . "</td>";
                                        echo "<td>" . $row['project_title'] . "</td>";
                                        echo "<td>" . $row['project_id'] . "</td>";
                                    echo "</tr>";
                                }
                                echo "</tbody>";                            
                            echo "</table>";
}
mysqli_close($link);
?>
                    <div>
                    </div>
                        </div>
            </div>        
        </div>
    </div>
</body>
</html>