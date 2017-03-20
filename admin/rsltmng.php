<?php

error_reporting(0);
session_start();
include_once '../oesdb.php';

if (!isset($_SESSION['admname'])) {
    $_GLOBALS['message'] = "Session Timeout.Click here to <a href=\"index.php\">Re-LogIn</a>";
} else if (isset($_REQUEST['logout'])) {
    unset($_SESSION['admname']);
    header('Location: index.php');
} else if (isset($_REQUEST['dashboard'])) {
    header('Location: admwelcome.php');
} else if (isset($_REQUEST['back'])) {
    header('Location: rsltmng.php');
} else if (isset($_REQUEST['bulkdelete'])) {
    $query1 = "delete from studenttest";
    $query2 = "delete from studentquestion";

    if (executeQuery($query1))
        executeQuery($query2);
    else
        $_GLOBALS['message'] = "Test Results Deletion Failed";
}

?>
<html>
<head>
    <title>OES-Manage Results</title>
    <meta http-equiv="Content-Type" content="text/html; charset=UTF-8"/>
    <link href="../css/materialize.min.css" rel="stylesheet" type="text/css"/>
    <link href="../css/style.css" rel="stylesheet" type="text/css"/>
    <link href="../css/icons/icons.css" rel="stylesheet" type="text/css"/>
  <link href="https://fonts.googleapis.com/icon?family=Material+Icons" rel="stylesheet">

    <link href="../css/datatables.min.css" rel="stylesheet" type="text/css"/>
</head>
<body style="background-image: url('../images/slogo2.jpg'); background-size: contain">
<?php

if ($_GLOBALS['message']) {
    echo "<div class=\"red white-text\">" . $_GLOBALS['message'] . "</div>";
}
?>

<div class="container">
    <div class="row">
        <h1 class="center green-text text-darken-4 large"><i class=" small material-icons ">school</i> Online Examination System </h1>
    </div>
    <form name="rsltmng" action="rsltmng.php" method="post">
        <div class="row">
            <div class="col l12 center">
                <?php if (isset($_SESSION['admname'])) {

                ?>
                <button type="submit" value="LogOut"  name="logout" class="btn tooltipped red darken-4 white-text waves-effect waves-light btn" data-position="top" data-delay="50" data-tooltip="Logout" ><i class="material-icons right">power_settings_new</i>Logout</button>
                <?php if (isset($_REQUEST['testid'])) { ?>
                <button type="submit" id="noprint" value="Back" name="back" class="btn tooltipped orange white-text waves-effect waves-light btn" data-position="top" data-delay="50" data-tooltip="Back" ><i class="material-icons right">replay</i>Back</button>
                    <a  href="retake.php?testid=<?php echo $_REQUEST['testid']; ?>"<input type="button" value="Retakes"
                                                                                          class="btn tooltipped green white-text  waves-effect waves-light btn"
                                                                                           data-position="top" data-delay="50" data-tooltip="Retakes" ><i class="material-icons right">swap_calls</i>Retakes</a>
                <?php } else { ?>
                    <button type="submit" value="Home" name="dashboard" class="btn tooltipped green white-text waves-effect waves-light btn" data-position="top" data-delay="50" data-tooltip="Dashboard" ><i class="material-icons right">home</i>Home</button>
                <?php if ($_SESSION['role'] == "admin") { ?>
                    <button data-target="modal1" value="Delete all Results" class="btn tooltipped red white-text waves-effect waves-light modal-trigger" data-position="top" data-delay="50" data-tooltip="Delete All Student Results" ><i class="material-icons right">delete</i>Delete All Results</button>
                    <div id="modal1" class="modal">
                        <div class="modal-content">
                            <h4>Are you sure you want to delete all results</h4>
                            <p class="center red-text darken-4">Note: Please make sure you have a backup</p>
                        </div>
                        <div class="modal-footer">
                            <button name="bulkdelete" type="submit" class="modal-action modal-close waves-effect waves-green btn-flat green white-text">YES</button>
                        </div>
                    </div>
                <?php } } ?>
            </div>
            <div class="col l6"></div>
        </div>
        <?php
        if (isset($_REQUEST['testid'])) {
        $student = executeQuery("select distinct(stdid) from studentquestion where testid=" . $_REQUEST['testid']);
        ?>
        <input type="hidden" id="testid" value="<?php echo $_REQUEST['testid']; ?>">
        <div class="ibox-content">
                <table class="table bordered striped highlight responsive-table dataTables-example">
                    <thead>
                    <tr class="blue white-text">
                        <th>SNO</th>
                        <th>Student Name</th>
                        <th>Student ID</th>
                        <th>Obtained Marks</th>
                        <th>Result(%)</th>
                    </tr>
                    </thead>
                    <?php
                    $count = 1;
                    while ($s = mysql_fetch_array($student)) {
                        $m = 0;
                        $query = executeQuery("select * from studentquestion where testid=" . $_REQUEST['testid'] . " and stdid=" . $s['stdid']);
                        while ($q = mysql_fetch_array($query)) {
                            $query2 = executeQuery("select * from question where testid=" . $_REQUEST['testid'] . " and qnid=" . $q['qnid']);
                            while ($q2 = mysql_fetch_array($query2)) {
                                if ($q['stdanswer'] == $q2['correctanswer']) {
                                    $m += $q2['marks'];
                                }
                            }
                        }

                        $tdata = executeQuery("select * from student where stdid=" . $s['stdid']);
                        $tmarks = executeQuery("select sum(marks) as s from question where testid=" . $_REQUEST['testid']);
                        while ($tm = mysql_fetch_assoc($tmarks)) {
                            while ($td = mysql_fetch_array($tdata)) {
                                $sem = $td['semester'];
                                $sname = $td['stdname'];
                                $sidno = $td['stduidno'];
                                $tmarks = (($m / $tm['s']) * 100);
                                ?>
                                <tr style="color: black">
                                    <td><?php echo $count++; ?></td>
                                    <td><?php echo $sname; ?></td>
                                    <td><?php echo $sidno; ?></td>
                                    <td><?php echo $m; ?></td>
                                    <td><?php echo round($tmarks) . "%"; ?></td>
                                </tr>
                            <?php }
                        } ?>
                        <?php
                    }
                    echo "</table>";
                    echo "</div>";
                    }
                    else {

                        $result = executeQuery("select t.testid,t.testname,DATE_FORMAT(t.testfrom,'%d %M %Y') as fromdate,DATE_FORMAT(t.testto,'%d %M %Y %H:%i:%S') as todate,sub.subname,(select count(stdid) from studenttest where testid=t.testid) as attemptedstudents from test as t, subject as sub where sub.subid=t.subid order by t.testid desc;");
                        if (mysql_num_rows($result) == 0) {
                            echo "<h3 style=\"color:#0000cc;text-align:center;\">No Tests Yet...!</h3>";
                        } else {
                            $i = 0;

                            ?>
                            <div class="ibox-content">
                                    <table class="table bordered striped highlight responsive-table dataTables-example">
                                        <thead class="blue white-text">
                                        <tr>
                                            <th>Test Name</th>
                                            <th>Validity</th>
                                            <th>Subject</th>
                                            <th>Students</th>
                                            <th>Details</th>
                                        </tr>
                                        </thead>
                                        <?php
                                        while ($r = mysql_fetch_array($result)) {
                                            $i = $i + 1;
                                            if ($i % 2 == 0) {
                                                //echo "<tr style=\"color: black\">";
                                            } else {
                                                //echo "<tr style='color: black'>";
                                            }
                                            $sid = $r['subname'];

                                            $check = executeQuery("SELECT * FROM subject WHERE subname='$sid'");
                                            $q = mysql_fetch_array($check);

                                            if ($q['course'] == $_SESSION['role']) {
                                                echo "<tr><td>" . htmlspecialchars_decode($r['testname'], ENT_QUOTES) . "</td><td>" . $r['fromdate'] . " To " . $r['todate'] . " PM </td>"
                                                    . "<td>" . htmlspecialchars_decode($r['subname'], ENT_QUOTES) . "</td><td>" . $r['attemptedstudents'] . "</td>"
                                                    . "<td class=\"tddata\"><a title=\"Details\" href=\"rsltmng.php?testid=" . $r['testid'] . "&subname=" . htmlspecialchars_decode($r['subname'], ENT_QUOTES) . "&semester=" . $q['semester'] . "\"><i class=\"big green-text material-icons\">visibility</i></a></td></tr>";
                                            } else {

                                            }
                                        }
                                        ?>
                                    </table>
                            </div>
                            <?php
                        }
                    }
                    closedb();
                    }

                    ?>
    </form>
</div>
<script src="../js/jquery-2.1.1.js"></script>
<script src="../js/datatables.min.js"></script>
<script type="text/javascript" src="../js/materialize.min.js"></script>
<script type="text/javascript" src="../js/materialize.js"></script>
<script>
    $('.modal-trigger').leanModal();

    var header = "<center>SAIPALI INSTITUTE OF TECHNOLOGY AND MANAGEMENT" +
        "<br/><h3>Course Unit : <?php echo $_REQUEST['subname']; ?>" +
        "<br/>Semester <?php echo $_REQUEST['semester']; ?></h3></center>";

    $(document).ready(function () {
        $('.dataTables-example').DataTable({
            dom: '<"html5buttons"B>lTfgitp',
            buttons: [
                {extend: 'copy'},
                {extend: 'csv'},
                {extend: 'excel', title: 'Results'},
                {extend: 'pdf', title: 'Results'},

                {
                    extend: 'print',
                    customize: function (win) {
                        $(win.document.body).addClass('white-bg');
                        $(win.document.body).css('font-size', '14px');
                        $(win.document.body).find('table')
                            .addClass('compact')
                            .css('font-size', 'inherit');
                    },
                     color: 'black',
                    title: header
                }
            ]
        });

    });
</script>
</body>
</html>

