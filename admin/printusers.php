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
<h1 class="text-center">SAIPALI INSTITUTE OF TECHNOLOGY AND MANAGEMENT</h1>

<?php
$role = $_SESSION['role'];
$sem = $_REQUEST['sem'];
?>
<div class="row">
    <div class="col-lg-4"></div>
    <div class="col-lg-4">
        <h4 class="text-center">Course : <?php echo $role; ?></h4>
        <h4 class="text-center">Semester : <?php echo $sem; ?></h4>
</div>
<div class="col-lg-4"></div>
</div>
<?php
$result = executeQuery("select stdid, stdname, stduname, DECODE(stdpassword, 'oespass') as passw, stduidno, course, semester from student WHERE course='$role' AND semester='$sem' order by stdname;");
if (mysql_num_rows($result) == 0) {
    echo "<h3 style=\"color:#0000cc;text-align:center;\">No Users Yet..!</h3>";
} else {
$i = 0;
?>
<table class="table table-bordered table-responsive table-hover">
    <thead>
    <tr class="btn-info">
        <th>Student Name</th>
        <th>Student ID</th>
        <th>Password</th>
    </tr>
    </thead>
    <?php
    while ($r = mysql_fetch_array($result)) {
        $i = $i + 1;
        if ($i % 2 == 0)
            echo "<tr style=\"color: black\">";
        else
            echo "<tr style=\"color: black\">";
        echo "<td>" . htmlspecialchars_decode($r['stdname'], ENT_QUOTES) . "</td><td>" . htmlspecialchars_decode($r['stduidno'], ENT_QUOTES)
            . "</td><td>" . htmlspecialchars_decode($r['passw'], ENT_QUOTES) . "</td>";
    }
    ?>
</table>
<?php
}
closedb();
?>
</body>
</html>