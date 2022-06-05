<?php
/* Database credentials. Assuming you are running MySQL
server with default setting (user 'root' with no password) */
define('DB_SERVER', 'localhost');
define('DB_USERNAME', 'root');
define('DB_PASSWORD', '');
define('DB_NAME', 'elidek');
 
/* Attempt to connect to MySQL database */
$link = mysqli_connect(DB_SERVER, DB_USERNAME, DB_PASSWORD, DB_NAME);
 
// Check connection
if($link === false){
    die("ERROR: Could not connect. " . mysqli_connect_error());
}
?>

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
        /* Add a black background color to the top navigation */
        .topnav {
            background-color: #333;
            overflow: hidden;
}

/* Style the links inside the navigation bar */
        .topnav a {
            float: left;
            color: #f2f2f2;
            text-align: center;
            padding: 14px 16px;
            text-decoration: none;
            font-size: 17px;
        }

/* Change the color of links on hover */
        .topnav a:hover {
            background-color: #ddd;
            color: black;
        }

/* Add a color to the active/current link */
        .topnav a.active {
            background-color: #04AA6D;
            color: white;
        }
    </style>
    <script>
        $(document).ready(function(){
            $('[data-toggle="tooltip"]').tooltip();   
        });
    </script>
</head>
<body>
        <div class="topnav">
            <a class="active" href="query.php">Queries</a>
            <a href="index_project.php">Projects</a>
            <a href="index_organisation.php">Organisations</a>
            <a href="index_researcher.php">Researchers</a>
            <a href="index_program.php">Programs</a>
            <a href="index_research_field.php">Research Fields</a>
            <a href="index_elidek_ex.php">ELIDEK executives</a>
            <a href="index_deliverable.php">Project Deliverables</a>
            <a href="index_phone.php">Organisation Phones</a>
            <a href="index_is_about.php">Project-Field</a>
            <a href="index_participates_in.php">Researcher-Project</a>
        </div>
</body>
</html>
