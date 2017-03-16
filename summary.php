<?php

error_reporting(0);
session_start();
include_once 'oesdb.php';
if (!isset($_SESSION['stduname'])) {
    $_GLOBALS['message'] = "Session Timeout.Click here to <a href=\"index.php\">Re-LogIn</a>";
} else if (isset($_REQUEST['logout'])) {

    unset($_SESSION['stduname']);
    header('Location: index.php');

} else if (isset($_REQUEST['dashboard'])) {

    header('Location: stdwelcome.php');

} else if (isset($_REQUEST['change'])) {

    $_SESSION['qn'] = substr($_REQUEST['change'], 7);
    header('Location: testconducter.php');

} else if (isset($_REQUEST['finalsubmit'])) {

    header('Location: testack.php');

} else if (isset($_REQUEST['fs'])) {

    header('Location: testack.php');

}


?>

<html>
<head>
    <title>OES-Summary</title>
    <meta http-equiv="Content-Type" content="text/html; charset=UTF-8"/>
    <meta http-equiv="CACHE-CONTROL" content="NO-CACHE"/>
    <meta http-equiv="PRAGMA" content="NO-CACHE"/>
    <meta name="ROBOTS" content="NONE"/>
    <link href="css/materialize.min.css" rel="stylesheet" type="text/css"/>
  <link href="https://fonts.googleapis.com/icon?family=Material+Icons" rel="stylesheet">

    <script type="text/javascript" src="validate.js"></script>
    <script type="text/javascript" src="cdtimer.js"></script>
    <script type="text/javascript">
        <!--
        <?php
        $elapsed = time() - strtotime($_SESSION['starttime']);
        if (((int)$elapsed / 60) < (int)$_SESSION['duration']) {
            $result = executeQuery("select TIME_FORMAT(TIMEDIFF(endtime,CURRENT_TIMESTAMP),'%H') as hour,TIME_FORMAT(TIMEDIFF(endtime,CURRENT_TIMESTAMP),'%i') as min,TIME_FORMAT(TIMEDIFF(endtime,CURRENT_TIMESTAMP),'%s') as sec from studenttest where stdid=" . $_SESSION['stdid'] . " and testid=" . $_SESSION['testid'] . ";");
            if ($rslt = mysql_fetch_array($result)) {
                echo "var hour=" . $rslt['hour'] . ";";
                echo "var min=" . $rslt['min'] . ";";
                echo "var sec=" . $rslt['sec'] . ";";
            } else {
                $_GLOBALS['message'] = "Try Again";
            }
            closedb();
        } else {
            echo "var sec=01;var min=00;var hour=00;";
        }
        ?>

        -->
    </script>


</head>
<body style="background-image: url('images/slogo2.jpg'); background-size: contain">
<?php

if ($_GLOBALS['message']) {
    echo "<div class=\"red white-text\">" . $_GLOBALS['message'] . "</div>";
}
?>

<div class="container">
    <div class="row">
        <h2 class="center green-text text-darken-4">SITM Online Examination System</h2>
    </div>
    <form id="summary" action="summary.php" method="post">
        <div class="row center">
            <?php if (isset($_SESSION['stduname'])) {
            ?>
            <h3 class="card"><span id="timer" class="timerclass"></span></h3>
        </div>
        <div class="row">
            <?php

            $result = executeQuery("select * from studentquestion where testid=" . $_SESSION['testid'] . " and stdid=" . $_SESSION['stdid'] . " order by qnid ;");
            if (mysql_num_rows($result) == 0) {
                echo "<h3 style=\"color:#0000cc;text-align:center;\">Please Try Again.</h3>";
            } else {

                ?>
                <table class="table bordered striped highlight responsive-table">
                    <thead>
                    <tr class="blue">
                        <th>Question No</th>
                        <th>Status</th>
                        <th>Change Your Answer</th>
                    </tr>
                    </thead>
                    <?php
                    while ($r = mysql_fetch_array($result)) {
                        $i = $i + 1;
                        if ($i % 2 == 0) {
                            echo "<tr style='color: black'>";
                        } else {
                            echo "<tr style='color: black'>";
                        }
                        echo "<td>" . $r['qnid'] . "</td>";
                        if (strcmp(htmlspecialchars_decode($r['answered'], ENT_QUOTES), "unanswered") == 0 || strcmp(htmlspecialchars_decode($r['answered'], ENT_QUOTES), "review") == 0) {
                            echo "<td style=\"color:#ff0000\">" . htmlspecialchars_decode($r['answered'], ENT_QUOTES) . "</td>";
                        } else {
                            echo "<td>" . htmlspecialchars_decode($r['answered'], ENT_QUOTES) . "</td>";
                        }
                        echo "<td><input type=\"submit\" value=\"Change " . $r['qnid'] . "\" name=\"change\" class=\"btn white-text red\" /></td></tr>";
                    }

                    ?>
                    <tr>
                        <td colspan="3" style="text-align:center;"><input type="submit" name="finalsubmit"
                                                                          value="Final Submit" class="btn white-text green"/></td>
                    </tr>
                </table>
                <?php
            }
            closedb();

            }
            ?>
        </div>
    </form>
</div>
</body>
</html>

