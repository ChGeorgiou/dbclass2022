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
<h2>Top 3 Pairs of Scientific Fields</h2>
                    </div>
<?php
$sql = "SELECT x.field_name as first_field, i.field_name as second_field, COUNT(*) as quantity
        FROM is_about x 
        INNER JOIN is_about i 
        ON i.project_id = x.project_id 
        WHERE i.field_name > x.field_name  
        GROUP BY x.field_name, i.field_name 
        ORDER BY COUNT(*) DESC LIMIT 3;";
$result = $link->query($sql);
    
if($result->num_rows>0) {
    
    echo '<table class="table table-bordered table-striped">';
                                echo "<thead>";
                                    echo "<tr>";
                                        echo "<th>First Scientific Field</th>";
                                        echo "<th>Second Scientific Field</th>";
                                        echo "<th>Number of Pairs</th></tr>";
                                    echo "</tr>";
                                echo "</thead>";
                                echo "<tbody>";
                                while($row = mysqli_fetch_array($result)){
                                    echo "<tr>";
                                        echo "<td>" . $row['first_field'] . "</td>";
                                        echo "<td>" . $row['second_field'] . "</td>";
                                        echo "<td>" . $row['quantity'] . "</td>";
                                    echo "</tr>";
                                }
                                echo "</tbody>";                            
                            echo "</table>";
}
mysqli_close($link);
?>
                        </div>
            </div>        
        </div>
    </div>
</body>
</html>