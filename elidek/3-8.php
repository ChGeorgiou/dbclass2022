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
<h3>Researchers that currently work on projects with no deliverables</h3>
                    </div>
<?php
$sql = "SELECT r.first_name as f_name, r.last_name as l_name, COUNT(*) as amount
FROM researcher r
INNER JOIN participates_in w
ON r.researcher_id = w.researcher_id
INNER JOIN project p 
ON p.project_id = w.project_id
WHERE NOT EXISTS (SELECT * FROM deliverable d WHERE p.project_id = d.project_id) AND DATEDIFF(CURRENT_DATE(), p.end_date) < 0
GROUP BY r.researcher_id
HAVING COUNT(*) > 4
ORDER BY COUNT(*) DESC";
$result = $link->query($sql);
    
if($result->num_rows>0) {
    
    echo '<table class="table table-bordered table-striped">';
                                echo "<thead>";
                                    echo "<tr>";
                                        echo "<th>First Name</th>";
                                        echo "<th>Last Name</th>";
                                        echo "<th>Amount of Projects</th></tr>";
                                    echo "</tr>";
                                echo "</thead>";
                                echo "<tbody>";
                                while($row = mysqli_fetch_array($result)){
                                    echo "<tr>";
                                        echo "<td>" . $row['f_name'] . "</td>";
                                        echo "<td>" . $row['l_name'] . "</td>";
                                        echo "<td>" . $row['amount'] . "</td>";
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