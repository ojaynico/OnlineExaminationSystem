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
} else if (isset($_REQUEST['delete'])) {
    unset($_REQUEST['delete']);
    $hasvar = false;
    foreach ($_REQUEST as $variable) {
        if (is_numeric($variable)) {
            $hasvar = true;

            if (!@executeQuery("delete from test where testid=$variable")) {
                if (mysql_errno() == 1451)
                    $_GLOBALS['message'] = "Too Prevent accidental deletions, system will not allow propagated deletions.<br/><b>Help:</b> If you still want to delete this test, then first delete the questions that are associated with it.";
                else
                    $_GLOBALS['message'] = mysql_errno();
            }
        }
    }
    if (!isset($_GLOBALS['message']) && $hasvar == true)
        $_GLOBALS['message'] = "Selected Tests are successfully Deleted";
    else if (!$hasvar) {
        $_GLOBALS['message'] = "First Select the Tests to be Deleted.";
    }
} else if (isset($_REQUEST['savem'])) {

    $fromtime = $_REQUEST['testfrom'] . " " . date("H:i:s");
    $totime = $_REQUEST['testto'] . " 23:59:59";
    $_GLOBALS['message'] = strtotime($totime) . "  " . strtotime($fromtime) . "  " . time();
    if (strtotime($fromtime) > strtotime($totime) || strtotime($totime) < time())
        $_GLOBALS['message'] = "Start date of test is less than end date or last date of test is less than today's date.<br/>Therefore Nothing is Updated";
    else if (empty($_REQUEST['testname']) || empty($_REQUEST['testdesc']) || empty($_REQUEST['totalqn']) || empty($_REQUEST['duration']) || empty($_REQUEST['testfrom']) || empty($_REQUEST['testto']) || empty($_REQUEST['testcode'])) {
        $_GLOBALS['message'] = "Some of the required Fields are Empty.Therefore Nothing is Updated";
    } else {
        $query = "update test set testname='" . htmlspecialchars($_REQUEST['testname'], ENT_QUOTES) . "',testdesc='" . htmlspecialchars($_REQUEST['testdesc'], ENT_QUOTES) . "',totalquestions=" . htmlspecialchars($_REQUEST['totalqn'], ENT_QUOTES) . ",duration=" . htmlspecialchars($_REQUEST['duration'], ENT_QUOTES) . ",testfrom='" . $fromtime . "',testto='" . $totime . "',testcode=ENCODE('" . htmlspecialchars($_REQUEST['testcode'], ENT_QUOTES) . "','oespass') where testid=" . $_REQUEST['testid'] . ";";
        if (!@executeQuery($query))
            $_GLOBALS['message'] = mysql_error();
        else
            $_GLOBALS['message'] = "Test Information is Successfully Updated.";

    }
    closedb();
} else if (isset($_REQUEST['savea'])) {

    $noerror = true;
    $fromtime = $_REQUEST['testfrom'] . " " . date("H:i:s");
    $totime = $_REQUEST['testto'] . " 23:59:59";
    if (strtotime($fromtime) > strtotime($totime) || strtotime($fromtime) < (time() - 3600)) {
        $noerror = false;
        $_GLOBALS['message'] = "Start date of test is either less than today's date or greater than last date of test.";
    } else if ((strtotime($totime) - strtotime($fromtime)) <= 3600 * 24) {
        $noerror = true;
        $_GLOBALS['message'] = "Note:<br/>The test is valid upto " . date(DATE_RFC850, strtotime($totime));
    }

    $result = executeQuery("select max(testid) as tst from test");
    $r = mysql_fetch_array($result);
    if (is_null($r['tst']))
        $newstd = 1;
    else
        $newstd = $r['tst'] + 1;

    if (strcmp($_REQUEST['subject'], "<Choose the Subject>") == 0 || empty($_REQUEST['testname']) || empty($_REQUEST['testdesc']) || empty($_REQUEST['totalqn']) || empty($_REQUEST['duration']) || empty($_REQUEST['testfrom']) || empty($_REQUEST['testto']) || empty($_REQUEST['testcode'])) {
        $_GLOBALS['message'] = "Some of the required Fields are Empty";
    } else if ($noerror) {
        $query = "insert into test values($newstd,'" . htmlspecialchars($_REQUEST['testname'], ENT_QUOTES) . "','" . htmlspecialchars($_REQUEST['testdesc'], ENT_QUOTES) . "',(select curDate()),(select curTime())," . htmlspecialchars($_REQUEST['subject'], ENT_QUOTES) . ",'" . $fromtime . "','" . $totime . "'," . htmlspecialchars($_REQUEST['duration'], ENT_QUOTES) . "," . htmlspecialchars($_REQUEST['totalqn'], ENT_QUOTES) . ",0,ENCODE('" . htmlspecialchars($_REQUEST['testcode'], ENT_QUOTES) . "','oespass'),NULL)";
        if (!@executeQuery($query)) {
            if (mysql_errno() == 1062)
                $_GLOBALS['message'] = "Given Test Name voilates some constraints, please try with some other name.";
            else
                $_GLOBALS['message'] = mysql_error();
        } else
            $_GLOBALS['message'] = $_GLOBALS['message'] . "<br/>Successfully New Test is Created.";
    }
    closedb();
} else if (isset($_REQUEST['saveaOld'])) {

    $result = executeQuery("select max(testid) as tst from test");
    $r = mysql_fetch_array($result);
    if (is_null($r['tst']))
        $newstd = 1;
    else
        $newstd = $r['tst'] + 1;

    $query1 = executeQuery("select * from test where subid=" . htmlspecialchars($_REQUEST['subjectFrom'], ENT_QUOTES) . ";");
    while ($r1 = mysql_fetch_array($query1)) {
        $query2 = "insert into test values($newstd,'" . htmlspecialchars($_REQUEST['testname'], ENT_QUOTES) . "','" . $r1['testdesc'] . "','" . $r1['testdate'] . "','" . $r1['testtime'] . "'," . htmlspecialchars($_REQUEST['subjectNew'], ENT_QUOTES) . ",'" . $r1['testfrom'] . "','" . $r1['testto'] . "'," . $r1['duration'] . "," . $r1['totalquestions'] . ",0,'" . $r1['testcode'] . "',NULL)";
        if (!@executeQuery($query2)) {
            if (mysql_errno() == 1062)
                $_GLOBALS['message'] = "Given Test Name voilates some constraints, please try with some other name.";
            else
                $_GLOBALS['message'] = mysql_error();
        } else {
            $query3 = executeQuery("select testid from test where subid=" . htmlspecialchars($_REQUEST['subjectNew'], ENT_QUOTES) . ";");
            while ($r2 = mysql_fetch_array($query3)) {
                $query4 = executeQuery("select * from question where testid=" . htmlspecialchars($_REQUEST['subjectOld'], ENT_QUOTES) . ";");
                while ($r3 = mysql_fetch_array($query4)) {
                    $query5 = "insert into question values(" . $r2['testid'] . "," . $r3['qnid'] . ",'" . $r3['question'] . "','" . $r3['optiona'] . "','" . $r3['optionb'] . "','" . $r3['optionc'] . "','" . $r3['optiond'] . "','" . $r3['correctanswer'] . "'," . $r3['marks'] . ")";
                    if (!@executeQuery($query5)) {
                        if (mysql_errno() == 1062)
                            $_GLOBALS['message'] = "Sorry, error occured, try late";
                        else
                            $_GLOBALS['message'] = mysql_error();
                    } else {
                        $_GLOBALS['message'] = "Test Transfered Successfully";
                    }
                }
            }
        }
    }

} else if (isset($_REQUEST['manageqn'])) {

    $testname = $_REQUEST['manageqn'];
    $result = executeQuery("select testid from test where testname='" . htmlspecialchars($testname, ENT_QUOTES) . "';");

    if ($r = mysql_fetch_array($result)) {
        $_SESSION['testname'] = $testname;
        $_SESSION['testqn'] = $r['testid'];

        header('Location: prepqn.php');
    }
}
?>
<html xmlns="http://www.w3.org/1999/html">
<head>
    <title>OES-Manage Tests</title>
    <meta http-equiv="Content-Type" content="text/html; charset=UTF-8"/>
    <link rel="stylesheet" type="text/css" media="all" href="../calendar/jsDatePick.css"/>
    <link href="../css/datatables.min.css" rel="stylesheet" type="text/css"/>
    <link href="../css/materialize.min.css" rel="stylesheet" type="text/css"/>
    <link href="../css/icons/icons.css" rel="stylesheet" type="text/css"/>
    <link href="../css/style.css" rel="stylesheet" type="text/css"/>
  <link href="https://fonts.googleapis.com/icon?family=Material+Icons" rel="stylesheet">

    <script type="text/javascript" src="../validate.js"></script>
</head>
<body style="background-image: url('../images/slogo2.jpg'); background-size: contain">
<?php
if ($_GLOBALS['message']) {
    echo "<div class=\"red white-text\">" . $_GLOBALS['message'] . "</div>";
}
?>

<div class="container">
    <div class="row">
        <h1 class="center green-text text-darken-4"><i class=" small material-icons  ">school</i> Online
            Examination System</h1>
    </div>
    <form name="testmng" action="testmng.php" method="post">
        <div class="row">
            <div class="col l12 center">
                <?php
                if (isset($_SESSION['admname'])) {

                    ?>
                    <button input type="submit" value="LogOut" name="logout"
                            class="btn tooltipped red darken-4 white-text waves-effect waves-light btn"
                            data-position="top" data-delay="50" data-tooltip="Logout"><i class="material-icons right">power_settings_new</i>Logout
                    </button>

                    <button input type="submit" value="Home" name="dashboard"
                            class="btn tooltipped green white-text waves-effect waves-light btn" data-position="top"
                            data-delay="50" data-tooltip="Dashboard"><i class="material-icons right">home</i>Home
                    </button>


                    <?php

                    if (isset($_REQUEST['add'])) {
                        ?>
                        <?php
                    } else if (isset($_REQUEST['edit'])) {
                        ?>
                        <?php
                    } else {
                        ?>
                        <button input type="submit" value="Delete" name="delete"
                                class="btn tooltipped red white-text waves-effect waves-light btn" data-position="top"
                                data-delay="50" data-tooltip="Delete forever"><i class="material-icons right">delete</i>Delete
                        </button>
                        <button input type="submit" value="Add New" name="add"
                                class="btn tooltipped orange white-text waves-effect waves-light btn"
                                data-position="top" data-delay="50" data-tooltip="Add New"><i
                                    class="material-icons right">playlist_add</i>Add
                        </button>

                        <button input type="submit" value="Add from Existing" name="addOld"
                                class="btn indigo white-text waves-effect waves-light btn" data-position="top"
                                data-delay="50" data-tooltip="Upload a question paper"><i class="material-icons right">system_update_alt</i>Add
                            from Existing
                        </button>
                    <?php }
                } ?>
            </div>
        </div>

        <?php
        if (isset($_SESSION['admname'])) {

        if (isset($_REQUEST['forpq']))
            echo "<div class=\"pmsg\" style=\"text-align:center\"> Which test questions Do you want to Manage? <br/><b>Help:</b>Click on Questions button to manage the questions of respective tests</div>";
        if (isset($_REQUEST['add'])) {
            ?>

            <div class="row">
                <div class="col l4"></div>
                <div class="col l4">

                    <div class="input-field">
                        <select name="subject" class="browser-default">
                            <option selected value="<Choose the Subject>">&lt;Choose the Subject&gt;</option>
                            <?php
                            $role = $_SESSION['role'];
                            $result = executeQuery("select subid,subname from subject WHERE course='$role';");
                            while ($r = mysql_fetch_array($result)) {

                                echo "<option value=\"" . $r['subid'] . "\">" . htmlspecialchars_decode($r['subname'], ENT_QUOTES) . "</option>";
                            }
                            closedb();
                            ?>
                        </select>
                    </div>

                    <div class="input-field">
                        <label for="testcode" class="black-text text-darken-4">Test Code</label>

                        <input type="text" name="testname" class="validate black-text" value=""
                               onkeyup="isalphanum(this)">
                    </div>

                    <div class="input-field">
                        <label for="description">Test Description</label>
                        <textarea name="testdesc" id="description" class="materialize-textarea"></textarea>
                    </div>

                    <div class="input-field">
                        <label for="qn">Total Questions</label>
                        <input type="text" name="totalqn" class="validate" value="" onkeyup="isnum(this)">
                    </div>

                    <div class="input-field">
                        <label for="duration">Duration(Mins)</label>
                        <input type="text" name="duration" class="validate" value="" onkeyup="isnum(this)">
                    </div>

                    <div class="input-field">
                        <label for="testfrom">Test From</label>
                        <input id="testfrom" type="text" name="testfrom" class="validate" value=""
                               placeholder="Test From" readonly>
                    </div>

                    <div class="input-field">
                        <label for="testto">Test To</label>
                        <input id="testto" type="text" name="testto" class="validate" value="" placeholder="Test To"
                               readonly>
                    </div>

                    <div class="input-field">
                        <label for="code">Test Secret Code</label>
                        <input type="text" name="testcode" class="validate" value="" onkeyup="isalphanum(this)">
                    </div>

                    <div class="input-field center">
                        <input type="submit" value="Save" name="savea" class="btn green white-text"
                               onclick="validatetestform('testmng')" title="Save the Changes">
                        <input type="submit" value="Cancel" name="cancel" class="btn red white-text" title="Cancel">
                    </div>
                </div>
                <div class="col l4"></div>
            </div>
            <?php
        } else if (isset($_REQUEST['addOld'])){

            ?>
            <div class="row">
                <div class="col l4"></div>
                <div class="col l4">
                    <div class="input-field">
                        <select name="subjectNew" class="browser-default">
                            <option selected value="<Choose the Subject>">&lt;Choose the Subject&gt;</option>
                            <?php
                            $role = $_SESSION['role'];
                            $result = executeQuery("select subid,subname from subject WHERE course='$role';");
                            while ($r = mysql_fetch_array($result)) {
                                echo "<option value=\"" . $r['subid'] . "\">" . htmlspecialchars_decode($r['subname'], ENT_QUOTES) . "</option>";
                            }
                            closedb();
                            ?>
                        </select>
                    </div>

                    <div class="input-field">
                        <label for="testname">Test Code</label>
                        <input type="text" name="testname" class="validate" value="" onkeyup="isalphanum(this)"/>
                    </div>

                    <div class="input-field">
                        <select name="subjectFrom" class="browser-default">
                            <option selected value="<Choose the Subject>">&lt;Subject to Assign&gt;</option>
                            <?php
                            $role = $_SESSION['role'];
                            $result = executeQuery("select subid,subname from subject;");
                            while ($r = mysql_fetch_array($result)) {
                                echo "<option value=\"" . $r['subid'] . "\">" . htmlspecialchars_decode($r['subname'], ENT_QUOTES) . "</option>";
                            }
                            closedb();
                            ?>
                        </select>
                        
                    </div>

                    <div class="input-field">
                        <label for="testname">Test ID</label>
                        <input type="number" name="subjectOld" class="validate" />
                    </div>

                    <div class="input-field center">
                        <button input type="submit" value="Save" name="saveaOld"
                                class="btn green waves-effect waves-light btn"
                                onclick="validateform('testmng')"><i class="material-icons right">thumb_up</i>Save
                        </button>
                        <button input type="submit" value="Cancel" name="cancel"
                                class="btn red waves-effect waves-light btn"><i class="material-icons right">cancel</i>cancel
                        </button>

                    </div>
                </div>
                <div class="col l4"></div>
            </div>
            <?php

        } else if (isset($_REQUEST['edit'])) {

        $result = executeQuery("select t.totalquestions,t.duration,t.testid,t.testname,t.testdesc,t.subid,s.subname,DECODE(t.testcode,'oespass') as tcode,DATE_FORMAT(t.testfrom,'%Y-%m-%d') as testfrom,DATE_FORMAT(t.testto,'%Y-%m-%d') as testto from test as t,subject as s where t.subid=s.subid and t.testname='" . htmlspecialchars($_REQUEST['edit'], ENT_QUOTES) . "';");
        if (mysql_num_rows($result) == 0) {
            header('Location: testmng.php');
        } else if ($r = mysql_fetch_array($result)) {


        ?>
        <div class="row">
            <div class="col l4"></div>
            <div class="col l4">

                <div class="input-field">
                    <select name="subject" class="browser-default">
                        <?php
                        $result = executeQuery("select * from subject WHERE course='$role';");
                        while ($r1 = mysql_fetch_array($result)) {
                            if (strcmp($r['subname'], $r1['subname']) == 0)
                                echo "<option value=\"" . $r1['subid'] . "\" selected>" . htmlspecialchars_decode($r1['subname'], ENT_QUOTES) . "</option>";
                            else
                                echo "<option value=\"" . $r1['subid'] . "\">" . htmlspecialchars_decode($r1['subname'], ENT_QUOTES) . "</option>";
                        }
                        closedb();
                        ?>
                    </select>
                </div>
                <input type="hidden" name="testid" class="btn-block" value="<?php echo $r['testid']; ?>"/>

                <div class="input-field">
                    <label for="testcode">Test Code</label>
                    <input type="text" name="testname" class="validate"
                           value="<?php echo htmlspecialchars_decode($r['testname'], ENT_QUOTES); ?>"
                           onkeyup="isalphanum(this)">
                </div>

                <div class="input-field">
                    <label for="description">Test Description</label>
                    <textarea name="testdesc" id="description"
                              class="materialize-textarea"><?php echo htmlspecialchars_decode($r['testdesc'], ENT_QUOTES); ?></textarea>
                </div>

                <div class="input-field">
                    <label for="qn">Total Questions</label>
                    <input type="text" name="totalqn" class="validate"
                           value="<?php echo htmlspecialchars_decode($r['totalquestions'], ENT_QUOTES); ?>"
                           onkeyup="isnum(this)">
                </div>

                <div class="input-field">
                    <label for="duration">Duration(Mins)</label>
                    <input type="text" name="duration" class="validate"
                           value="<?php echo htmlspecialchars_decode($r['duration'], ENT_QUOTES); ?>"
                           onkeyup="isnum(this)">
                </div>

                <div class="input-field">
                    <label for="testfrom">Test From</label>
                    <input id="testfrom" type="text" name="testfrom" class="validate"
                           value="<?php echo $r['testfrom']; ?>" placeholder="Test From" readonly>
                </div>

                <div class="input-field">
                    <label for="testto">Test To</label>
                    <input id="testto" type="text" name="testto" class="validate" value="<?php echo $r['testto']; ?>"
                           placeholder="Test To" readonly>
                </div>

                <div class="input-field">
                    <label for="code">Test Secret Code</label>
                    <input type="text" name="testcode" class="validate"
                           value="<?php echo htmlspecialchars_decode($r['tcode'], ENT_QUOTES); ?>"
                           onkeyup="isalphanum(this)">
                </div>

                <div class="input-field center">
                    <button input type="submit" value="Save" name="savem" class="btn green waves-effect waves-light btn"
                            onclick="validateform('testmng')"><i class="material-icons right">thumb_up</i>Save
                    </button>
                    <button input type="submit" value="Cancel" name="cancel"
                            class="btn red waves-effect waves-light btn"><i class="material-icons right">cancel</i>cancel
                    </button>
                </div>
            </div>
                <div class="col l4"></div>
            </div>
            <?php
            closedb();
            }
            } else {
                $role = $_SESSION['role'];
                $result = executeQuery("select t.testid,t.testname,t.testdesc,s.subname,DECODE(t.testcode,'oespass') as tcode,DATE_FORMAT(t.testfrom,'%d-%M-%Y') as testfrom,DATE_FORMAT(t.testto,'%d-%M-%Y %H:%i:%s %p') as testto from test as t,subject as s where t.subid=s.subid AND s.course='$role' order by t.testdate desc,t.testtime desc;");
                if (mysql_num_rows($result) == 0) {
                    echo "<h3 style=\"color:#0000cc;text-align:center;\">No Tests Yet..!</h3>";
                } else {
                    $i = 0;
                    ?>
                    <div class="ibox-content">
                        <div class="table-responsive">
                            <table class="table bordered striped highlight responsive-table dataTables-example">
                                <thead class="blue white-text">
                                <tr>
                                    <th></th>
                                    <th>Id</th>
                                    <th>Description</th>
                                    <th>Subject</th>
                                    <th>Code</th>
                                    <th>Validity</th>
                                    <th></th>
                                    <th></th>
                                </tr>
                                </thead>
                                <tbody>
                                <?php
                                while ($r = mysql_fetch_array($result)) {
                                    $i = $i + 1;
                                    if ($i % 2 == 0)
                                        echo "";
                                    else
                                        echo "";
                                    echo "<tr style='font-size: 11pt ;'><td style=\"text-align:center;\"><p><input type=\"checkbox\" id='delete$i' name=\"d$i\" value=\"" . $r['testid'] . "\" /><label for='delete$i'></label></p></td><td>" . $r['testid'] . "</td><td> " . htmlspecialchars_decode($r['testname'], ENT_QUOTES) . " : " . htmlspecialchars_decode($r['testdesc'], ENT_QUOTES)
                                        . "</td><td>" . htmlspecialchars_decode($r['subname'], ENT_QUOTES) . "</td><td>" . htmlspecialchars_decode($r['tcode'], ENT_QUOTES) . "</td><td>" . $r['testfrom'] . " To " . $r['testto'] . "</td>"
                                        . "<td class=\"tddata\"><a title=\"Edit " . htmlspecialchars_decode($r['testname'], ENT_QUOTES) . "\"href=\"testmng.php?edit=" . htmlspecialchars_decode($r['testname'], ENT_QUOTES) . "\"><i class=\"big green-text material-icons\">edit</i></a></td>"
                                        . "<td class=\"tddata\"><a title=\"Manage Questions of " . htmlspecialchars_decode($r['testname'], ENT_QUOTES) . "\"href=\"testmng.php?manageqn=" . htmlspecialchars_decode($r['testname'], ENT_QUOTES) . "\"><i class=\"big green-text material-icons\">view_list</i></a></td></tr>";
                                }
                                ?>
                                </tbody>
                            </table>
                        </div>
                    </div>
                    <?php
                }
                closedb();
            }
            }
            ?>

    </form>
</div>
<script src="../js/jquery-2.1.1.js"></script>
<script type="text/javascript" src="../js/materialize.min.js"></script>
<script type="text/javascript" src="../js/materialize.js"></script>
<script src="../js/datatables.min.js"></script>
<script type="text/javascript" src="../calendar/jsDatePick.min.1.1.js"></script>
<script type="text/javascript">

    window.onload = function () {
        new JsDatePick({
            useMode: 2,
            target: "testfrom"
        });

        new JsDatePick({
            useMode: 2,
            target: "testto"
        });
    };
</script>
<script>
$('#selectAll').click(function(e){
    var table= $(e.target).closest('table');
    $('td input:checkbox',table).prop('checked',this.checked);
});

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
