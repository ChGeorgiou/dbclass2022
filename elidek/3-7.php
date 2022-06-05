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
<h3>Top 5 ELIDEK executives that have funded a Firm the most</h3>
                    </div>
<?php
$sql = "SELECT org.organisation_name as o_name, SUM(p.fund) as funding, e.first_name as f_name, e.last_name as l_name, COUNT(p.fund) as amount
FROM project p
INNER JOIN elidek_ex e
ON p.elidek_ex_id = e.elidek_ex_id
INNER JOIN organisation org
ON p.organisation_id = org.organisation_id
WHERE org.organisation_type = 'Firm'
GROUP BY e.elidek_ex_id, org.organisation_id
ORDER BY SUM(p.fund) DESC 
LIMIT 5";
$result = $link->query($sql);
    
if($result->num_rows>0) {
    
    echo '<table class="table table-bordered table-striped">';
                                echo "<thead>";
                                    echo "<tr>";
                                        echo "<th>Organisation Name</th>";
                                        echo "<th>Funding Amount</th>";
                                        echo "<th>First Name</th>";
                                        echo "<th>Last Name</th>";
                                        echo "<th>Number of Projects</th>";
                                    echo "</tr>";
                                echo "</thead>";
                                echo "<tbody>";
                                while($row = mysqli_fetch_array($result)){
                                    echo "<tr>";
                                        echo "<td>" . $row['o_name'] . "</td>";
                                        echo "<td>" . $row['funding'] . "</td>";
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