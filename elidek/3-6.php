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
<h2>Researches aged under 40 that participate in the most projects</h2>
                    </div>
<?php
$sql = "WITH max_proj(N, ID) AS (select count(*) AS N, i.researcher_id AS ID
FROM participates_in i, project p
WHERE i.project_id = p.project_id
AND p.end_date >= CURDATE()
GROUP BY i.researcher_id)
SELECT r.first_name as f_name, r.last_name as l_name, (SELECT max(N) from max_proj) AS number_of_projects
FROM researcher r
WHERE (DATEDIFF(CURDATE(), r.date_of_birth) < 40*365.25)
AND (SELECT m.N FROM max_proj m WHERE m.ID = r.researcher_id) = (SELECT max(N) FROM max_proj)";
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
                                        echo "<td>" . $row['number_of_projects'] . "</td>";
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
