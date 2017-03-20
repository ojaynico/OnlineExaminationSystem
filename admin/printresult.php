<?php
error_reporting(0);
session_start();
include_once '../oesdb.php';
?>
<html>
<head>
    <link href="../css/bootstrap.css" rel="stylesheet" type="text/css"/>
  <link href="https://fonts.googleapis.com/icon?family=Material+Icons" rel="stylesheet">

    <script type="text/javascript" src="../validate.js"></script>
</head>
<body style="background-image: url('../images/slogo2.jpg'); background-size: contain" onload="window.print();">
<h1 class="text-center"> RESULTS</h1>

<?php
$role = $_SESSION['role'];
$sem = $_REQUEST['sem'];
$name = $_REQUEST['name'];
$id = $_REQUEST['sid'];
$score = $_REQUEST['score'];
?>
<div class="row">
    <div class="col-lg-4"></div>
    <div class="col-lg-4">
        <table class="table table-bordered table-responsive table-hover">
            <tr><td>Student No.</td><td><?php echo $id; ?></td></tr>
            <tr><td>Student Name</td><td><?php echo $name; ?></td></tr>
            <tr><td>Course</td><td><?php echo $role; ?></td></tr>
            <tr><td>Semester</td><td><?php echo $sem; ?></td></tr>
            <tr><td>Score</td><td><?php echo $score."%"; ?></td></tr>
        </table>
    </div>
    <div class="col-lg-4"></div>
</div>
</body>
</html>