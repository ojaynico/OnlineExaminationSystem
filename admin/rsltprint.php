<?php

error_reporting(0);
session_start();
include_once '../oesdb.php';

if(!isset($_SESSION['admname'])) {
    $_GLOBALS['message']="Session Timeout.Click here to <a href=\"index.php\">Re-LogIn</a>";
}
else if(isset($_REQUEST['logout'])) {

    unset($_SESSION['admname']);
    header('Location: index.php');

}
else if(isset($_REQUEST['dashboard'])) {

    header('Location: admwelcome.php');

}
else if(isset($_REQUEST['back'])) {

    header('Location: rsltmng.php');
}

?>
<html>
<head>
    <title>OES-Manage Results</title>
    <meta http-equiv="Content-Type" content="text/html; charset=UTF-8"/>
    <link href="../css/main.css" rel="stylesheet" type="text/css"/>
  <link href="https://fonts.googleapis.com/icon?family=Material+Icons" rel="stylesheet">

</head>
<body style="background-image: url('../images/slogo2.jpg'); background-size: contain">
<?php

if($_GLOBALS['message']) {
    echo "<div class=\"message\">".$_GLOBALS['message']."</div>";
}
?>
<center>
    <div class="container">
        <div class="row">
            <h1 class="text-center text-success"> Online Examination System</h1>
        </div>
    </div>
</center>
<hr/>

<div id="container">
    <form name="rsltmng" action="rsltmng.php" method="post">
        <div class="row">
            <div class="span1"></div>
            <div class="span6">
                <?php if(isset($_SESSION['admname'])) {

                ?>
            </div>
            <div class="span8 right">

            </div>
        </div>
        <div class="container">
            <div class="page">
                <?php
                if(isset($_REQUEST['testid'])) {
                $student = executeQuery("select distinct(stdid) from studentquestion where testid=".$_REQUEST['testid']);
                ?>
                <table class="table table-bordered table-hover">
                    <thead>
                    <tr class="btn-info">
                        <th>Student Name</th>
                        <th>Student ID</th>
                        <th>Obtained Marks</th>
                        <th>Result(%)</th>
                    </tr>
                    </thead>
                    <?php
                    while ($s = mysql_fetch_array($student)){
                        $m = 0;
                        $query = executeQuery("select * from studentquestion where testid=".$_REQUEST['testid']." and stdid=".$s['stdid']);
                        while ($q = mysql_fetch_array($query)){
                            $query2 = executeQuery("select * from question where testid=".$_REQUEST['testid']." and qnid=".$q['qnid']);
                            while ($q2 = mysql_fetch_array($query2)){
                                if ($q['stdanswer'] == $q2['correctanswer']){
                                    $m += $q2['marks'];
                                }
                            }
                        }

                        $tdata = executeQuery("select * from student where stdid=".$s['stdid']);
                        $tmarks = executeQuery("select * from test where testid=".$_REQUEST['testid']);
                        while ($tm = mysql_fetch_array($tmarks)){
                            while ($td = mysql_fetch_array($tdata)){
                                $sem = $td['semester'];
                                $sname = $td['stdname'];
                                $sidno = $td['stduidno'];
                                $tmarks = (($m/$tm['totalquestions'])*100);
                                ?>
                                <tr style="color: black">
                                    <td><?php echo $sname; ?></td>
                                    <td><?php echo $sidno; ?></td>
                                    <td><?php echo $m; ?></td>
                                    <td><?php echo round($tmarks)."%"; ?></td>
                                </tr>
                            <?php }}?>
                        <?php
                    }
                    echo "</table>";
                    }
                    closedb();
                    }

                    ?>

            </div>
        </div>
    </form>
</div>
</body>
</html>

