<?php

error_reporting(0);
session_start();
include_once '../oesdb.php';

if(!isset($_SESSION['admname'])) {
    $_GLOBALS['message']="Session Timeout.Click here to <a href=\"index.php\">Re-LogIn</a>";
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
    <link href="../css/materialize.min.css" rel="stylesheet" type="text/css"/>
    <link href="../css/style.css" rel="stylesheet" type="text/css"/>
  <link href="https://fonts.googleapis.com/icon?family=Material+Icons" rel="stylesheet">

    <link href="../css/datatables.min.css" rel="stylesheet" type="text/css"/>
</head>
<body style="background-image: url('../images/slogo2.jpg'); background-size: contain">
<div class="container">
    <div class="row">
        <h1 class="center green-text text-darken-4 large">SITM Online Examination System</h1>
    </div>
<?php
$result=executeQuery("select t.testname,DATE_FORMAT(t.testfrom,'%d %M %Y') as fromdate,DATE_FORMAT(t.testto,'%d %M %Y %H:%i:%S') as todate,sub.subname,IFNULL((select sum(marks) from question where testid=".$_REQUEST['testid']."),0) as maxmarks from test as t, subject as sub where sub.subid=t.subid and t.testid=".$_REQUEST['testid'].";") ;
if(mysql_num_rows($result)!=0) {
$r=mysql_fetch_array($result);

$result1=executeQuery("select s.stdname,s.stduidno,IFNULL((select sum(q.marks) from studentquestion as sq,question as q where q.qnid=sq.qnid and sq.testid=".$_REQUEST['testid']." and sq.stdid=st.stdid and sq.stdanswer=q.correctanswer),0) as om from studenttest as st, student as s where s.stdid=st.stdid and st.testid=".$_REQUEST['testid'].";" );

if(mysql_num_rows($result1)==0) {
echo"<h3 style=\"color:#0000cc;text-align:center;\">No Students Yet Attempted this Test!</h3>";
}
else {
?>

<table class="table bordered striped highlight responsive-table dataTables-example">
    <thead>
    <tr class="blue white-text">
        <th>Student Name</th>
        <th>Student ID</th>
        <th>Obtained Marks</th>
        <th>Result(%)</th>
    </tr>
    </thead>
    <?php
    while($r1=mysql_fetch_array($result1)) {
        $marks = (($r1['om']/$r['maxmarks'])*100);
        if ($marks <= 50) {
            ?>
            <tr style="color: black">
                <td><?php echo htmlspecialchars_decode($r1['stdname'], ENT_QUOTES); ?></td>
                <td><?php echo htmlspecialchars_decode($r1['stduidno'], ENT_QUOTES); ?></td>
                <td><?php echo $r1['om']; ?></td>
                <td><?php echo (($r1['om'] / $r['maxmarks']) * 100) . " %"; ?></td>
            </tr>
            <?php
        }
    }
    }
    }
    else {
        echo"<h3 style=\"color:#0000cc;text-align:center;\">Sorry. No Student has a retake.</h3>";
    }
    ?>
</table>
</div>
<script src="../js/jquery-2.1.1.js"></script>
<script src="../js/datatables.min.js"></script>
<script>
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
                        $(win.document.body).css('font-size', '10px');
                        $(win.document.body).find('table')
                            .addClass('compact')
                            .css('font-size', 'inherit');
                    },
                    title: header
                }
            ]
        });

    });
</script>
</body>
</html>