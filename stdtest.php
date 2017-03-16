<?php

error_reporting(0);
session_start();
include_once 'oesdb.php';
if (!isset($_SESSION['stduname'])) {
    $_GLOBALS['message'] = "Session Timeout.Click here to <a href=\"index.php\">Re-LogIn</a>";
} else if (isset($_SESSION['starttime'])) {
    header('Location: testconducter.php');
} else if (isset($_REQUEST['logout'])) {

    unset($_SESSION['stduname']);
    header('Location: index.php');
} else if (isset($_REQUEST['dashboard'])) {

    header('Location: stdwelcome.php');
} else if (isset($_REQUEST['starttest'])) {

    if (!empty($_REQUEST['tc'])) {
        $result = executeQuery("select DECODE(testcode,'oespass') as tcode from test where testid=" . $_SESSION['testid'] . ";");

        if ($r = mysql_fetch_array($result)) {
            if (strcmp(htmlspecialchars_decode($r['tcode'], ENT_QUOTES), htmlspecialchars($_REQUEST['tc'], ENT_QUOTES)) != 0) {
                $display = true;
                $_GLOBALS['message'] = "You have entered an Invalid Test Code.Try again.";
            } else {

                $result = executeQuery("select * from question where testid=" . $_SESSION['testid'] . " order by qnid;");
                if (mysql_num_rows($result) == 0) {
                    $_GLOBALS['message'] = "Tests questions cannot be selected.Please Try after some time!";
                } else {

                    $error = false;
                    if (!executeQuery("insert into studenttest values(" . $_SESSION['stdid'] . "," . $_SESSION['testid'] . ",(select CURRENT_TIMESTAMP),date_add((select CURRENT_TIMESTAMP),INTERVAL (select duration from test where testid=" . $_SESSION['testid'] . ") MINUTE),0,'inprogress')"))
                        $_GLOBALS['message'] = "error" . mysql_error();
                    else {
                        while ($r = mysql_fetch_array($result)) {
                            if (!executeQuery("insert into studentquestion values(" . $_SESSION['stdid'] . "," . $_SESSION['testid'] . "," . $r['qnid'] . ",'unanswered',NULL)")) {
                                $_GLOBALS['message'] = "Failure while preparing questions for you.Try again";
                                $error = true;
                            }
                        }
                        if ($error == true) {

                        } else {
                            $result = executeQuery("select totalquestions,duration from test where testid=" . $_SESSION['testid'] . ";");
                            $r = mysql_fetch_array($result);
                            $_SESSION['tqn'] = htmlspecialchars_decode($r['totalquestions'], ENT_QUOTES);
                            $_SESSION['duration'] = htmlspecialchars_decode($r['duration'], ENT_QUOTES);
                            $result = executeQuery("select DATE_FORMAT(starttime,'%Y-%m-%d %H:%i:%s') as startt,DATE_FORMAT(endtime,'%Y-%m-%d %H:%i:%s') as endt from studenttest where testid=" . $_SESSION['testid'] . " and stdid=" . $_SESSION['stdid'] . ";");
                            $r = mysql_fetch_array($result);
                            $_SESSION['starttime'] = $r['startt'];
                            $_SESSION['endtime'] = $r['endt'];
                            $_SESSION['qn'] = 1;
                            header('Location: testconducter.php');
                        }
                    }
                }
            }
        } else {
            $display = true;
            $_GLOBALS['message'] = "You have entered an Invalid Test Code.Try again.";
        }
    } else {
        $display = true;
        $_GLOBALS['message'] = "Enter the Test Code First!";
    }
} else if (isset($_REQUEST['testcode'])) {

    if ($r = mysql_fetch_array($result = executeQuery("select testid from test where testname='" . htmlspecialchars($_REQUEST['testcode'], ENT_QUOTES) . "';"))) {
        $_SESSION['testname'] = $_REQUEST['testcode'];
        $_SESSION['testid'] = $r['testid'];
    }
} else if (isset($_REQUEST['savem'])) {

    if (empty($_REQUEST['cname']) || empty($_REQUEST['password']) || empty($_REQUEST['stduidno'])) {
        $_GLOBALS['message'] = "Some of the required Fields are Empty.Therefore Nothing is Updated";
    } else {
        $query = "update student set stdname='" . htmlspecialchars($_REQUEST['cname'], ENT_QUOTES) . "', stduname='" . htmlspecialchars($_REQUEST['stduname'], ENT_QUOTES) . "', stdpassword=ENCODE('" . htmlspecialchars($_REQUEST['password'], ENT_QUOTES) . "','oespass'),stduidno='" . htmlspecialchars($_REQUEST['stduidno'], ENT_QUOTES) . "',course='" . htmlspecialchars($_REQUEST['course'], ENT_QUOTES) . "' where stdid='" . $_REQUEST['student'] . "';";
        if (!@executeQuery($query))
            $_GLOBALS['message'] = mysql_error();
        else
            $_GLOBALS['message'] = "Your Profile is Successfully Updated.";
    }
    closedb();
}
?>

<html>
    <head>
        <title>OES-Offered Tests</title>
        <meta http-equiv="Content-Type" content="text/html; charset=UTF-8"/>
        <meta http-equiv="CACHE-CONTROL" content="NO-CACHE"/>
        <link href="./css/icons/icons.css" rel="stylesheet" type="text/css"/>
  <link href="https://fonts.googleapis.com/icon?family=Material+Icons" rel="stylesheet">

        <meta http-equiv="PRAGMA" content="NO-CACHE"/>
        <meta name="ROBOTS" content="NONE"/>
        <link href="css/materialize.min.css" rel="stylesheet" type="text/css"/>
        <script type="text/javascript" src="validate.js" ></script>
    </head>
    <body style="background-image: url('images/slogo2.jpg'); background-size: contain">
        <?php
        if ($_GLOBALS['message']) {
            echo "<div class=\"red white-text\">" . $_GLOBALS['message'] . "</div>";
        }
        ?>

        <div class="section">
        <div class="container">
            <div class="row">
                <h3 class="center green-text text-darken-4"><i class=" medium material-icons  ">school</i> SITM Online Examination System</h3>
            </div>
            <hr/>
            <form id="stdtest" action="stdtest.php" method="post">
                <div class="row">
                    <div class="col l12 center">
                        <?php
                        if (isset($_SESSION['stduname'])) {

                        ?>
                        <button  input type="submit" value="LogOut"  name="logout" class="btn tooltipped red darken-4 white-text waves-effect waves-light btn" data-position="top" data-delay="50" data-tooltip="Logout" ><i class="material-icons right">power_settings_new</i>Logout</button>

                        <button input type="submit" value="Home" name="dashboard"   class="btn tooltipped green white-text waves-effect waves-light btn" data-position="top" data-delay="50" data-tooltip="Dashboard" ><i class="material-icons right">home</i>Home</button>

                    </div>
                    <div class="col l4"></div>
                    <div class="col l4"></div>
                    </div>

                <div class="container">
                    <div class="page">
                    <?php
                            if (isset($_REQUEST['testcode'])) {
                                echo "<div class=\"pmsg\" style=\"text-align:center;\">What is the Code of " . $_SESSION['testname'] . " ? </div>";
                            } else {
                                echo "<div style=\"text-align:center;\"><h5>Offered Tests</h5></div>";
                            }
                    ?>
                    <?php
                            if (isset($_REQUEST['testcode']) || $display == true) {
                    ?>
                                <div class="row center text-warning">
                                    <div class="col l4"></div>
                                    <div class="col l4">
                                <ul class="unstyled">
                                    <li>
                                        <li><h5 class="red-text">Enter Test Code</h5></li>
                                        <li><input type="text" name="tc" value="" class="validate center"/></li>
                                        <li><div class="red-text text-darken-4"><b>Note:</b><br/>Once you press start test<br/>button, timer will be started</div></li>
                                    </li>
                                    <br/>
                                    <li>
                                        <button type="submit" name="starttest" class="btn white-text waves-effect waves-light blue">Start Test</button>
                                    </li>
                                </ul>
                                    </div>
                                    <div class="col l4"></div>
                                </div>


                    <?php
                            } else {
                                $course = $_SESSION['course'];
                                $semester = $_SESSION['semester'];
                                $result = executeQuery("select t.*,s.subname from test as t, subject as s where s.subid=t.subid and CURRENT_TIMESTAMP<t.testto and t.totalquestions=(select count(*) from question where testid=t.testid) and NOT EXISTS(select stdid,testid from studenttest where testid=t.testid and stdid=" . $_SESSION['stdid'] . ");");
                                if (mysql_num_rows($result) == 0) {
                                    echo"<h5 style=\"text-align:center;\" class='red-text'>Sorry...! For this moment, You have not Offered to take any tests.</h5>";
                                } else {
                         
                    ?>
                                    <table class="table table-bordered table-hover">
                                        <thead>
                                        <tr class="blue white-text">
                                            <th>Test Name</th>
                                            <th>Test Description</th>
                                            <th>Subject Name</th>
                                            <th>Duration</th>
                                            <th>Total Questions</th>
                                            <th>Take Test</th>
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
                                        $sid = $r['subid'];

                                        $check = executeQuery("SELECT * FROM subject WHERE subid='$sid'");
                                        $q = mysql_fetch_array($check);

                                        if (($q['course'] == $course) && ($q['semester'] == $semester)){
                                            echo "<td>" . htmlspecialchars_decode($r['testname'], ENT_QUOTES) . "</td><td>" . htmlspecialchars_decode($r['testdesc'], ENT_QUOTES) . "</td><td>" . htmlspecialchars_decode($r['subname'], ENT_QUOTES)
                                                . "</td><td>" . htmlspecialchars_decode($r['duration'], ENT_QUOTES) . "</td><td>" . htmlspecialchars_decode($r['totalquestions'], ENT_QUOTES) . "</td>"
                                                . "<td class=\"tddata\"><a title=\"Start Test\" class=\"btn green waves-light btn\" href=\"stdtest.php?testcode=" . htmlspecialchars_decode($r['testname'], ENT_QUOTES) . "\">START</a></td></tr>";
                                        } else if ($q['course'] == $course){
                                            echo "<td>" . htmlspecialchars_decode($r['testname'], ENT_QUOTES) . "</td><td>" . htmlspecialchars_decode($r['testdesc'], ENT_QUOTES) . "</td><td>" . htmlspecialchars_decode($r['subname'], ENT_QUOTES)
                                                . "</td><td>" . htmlspecialchars_decode($r['duration'], ENT_QUOTES) . "</td><td>" . htmlspecialchars_decode($r['totalquestions'], ENT_QUOTES) . "</td>"
                                                . "<td class=\"tddata\"><a class=\"btn green white-text\" title=\"Start Test\" href=\"stdtest.php?testcode=" . htmlspecialchars_decode($r['testname'], ENT_QUOTES) . "\">
     
            START </a></td></tr>";
                                        }

                                 }
                        ?>
                                </table>
                    <?php
                                }
                                closedb();
                            }
                        }
                    ?>

                </div>
</div>
            </form>
        </div>
        </div>
        <script type="text/javascript" src="../js/materialize.min.js"></script>
        <script type="text/javascript" src="../js/materialize.js"></script>
    </body>
</html>

