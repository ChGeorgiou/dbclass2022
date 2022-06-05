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
            width: 300px;
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
<h3>Organisations that have received 10 or more projects in 2 consecutive years</h3>
                    </div>
<?php
$sql = "WITH proj_per_year AS (SELECT o.organisation_id AS ID, YEAR(p.start_date) AS xronos, COUNT(*) AS N 
FROM organisation o, project p
WHERE o.organisation_id = p.organisation_id 
GROUP by o.organisation_name, YEAR(p.start_date))
SELECT o.organisation_id AS o_id, o.organisation_name AS o_name
FROM organisation o, proj_per_year y1, proj_per_year y2
WHERE (o.organisation_id = y1.ID 
		AND o.organisation_id = y2.ID
        AND y1.xronos = y2.xronos-1
        AND y1.N = y2.N
        AND y1.N >= 10);";
$result = $link->query($sql);
    
if($result->num_rows>0) {
    
    echo '<table class="table table-bordered table-striped">';
                                echo "<thead>";
                                    echo "<tr>";
                                        echo "<th>Organisation Name</th>";
                                        echo "<th>Organisation ID</th>";
                                    echo "</tr>";
                                echo "</thead>";
                                echo "<tbody>";
                                while($row = mysqli_fetch_array($result)){
                                    echo "<tr>";
                                        echo "<td>" . $row['o_name'] . "</td>";
                                        echo "<td>" . $row['o_id'] . "</td>";
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