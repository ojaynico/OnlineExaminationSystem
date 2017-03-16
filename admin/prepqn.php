<?php

error_reporting(0);
session_start();
include_once '../oesdb.php';

if (!isset($_SESSION['admname']) || !isset($_SESSION['testqn'])) {
    $_GLOBALS['message'] = "Session Timeout.Click here to <a href=\"index.php\">Re-LogIn</a>";
} else if (isset($_REQUEST['logout'])) {

    unset($_SESSION['admname']);
    header('Location: index.php');
} else if (isset($_REQUEST['managetests'])) {


    header('Location: testmng.php');
} else if (isset($_REQUEST['delete'])) {

    unset($_REQUEST['delete']);
    $hasvar = false;
    $count = 1;
    foreach ($_REQUEST as $variable) {
        if (is_numeric($variable)) {
            $hasvar = true;

            if (!@executeQuery("delete from question where testid=" . $_SESSION['testqn'] . " and qnid=" . htmlspecialchars($variable)))
                $_GLOBALS['message'] = mysql_error();
        }
    }

    $result = executeQuery("select qnid from question where testid=" . $_SESSION['testqn'] . " order by qnid;");
    while ($r = mysql_fetch_array($result))
        if (!@executeQuery("update question set qnid=" . ($count++) . " where testid=" . $_SESSION['testqn'] . " and qnid=" . $r['qnid'] . ";"))
            $_GLOBALS['message'] = mysql_error();

    if (!isset($_GLOBALS['message']) && $hasvar == true)
        $_GLOBALS['message'] = "Selected Questions are successfully Deleted";
    else if (!$hasvar) {
        $_GLOBALS['message'] = "First Select the Questions to be Deleted.";
    }
} else if (isset($_REQUEST['savem'])) {

    if (strcmp($_REQUEST['correctans'], "<Choose the Correct Answer>") == 0 || empty($_REQUEST['question']) || empty($_REQUEST['optiona']) || empty($_REQUEST['optionb']) || empty($_REQUEST['optionc']) || empty($_REQUEST['optiond']) || empty($_REQUEST['marks'])) {
        $_GLOBALS['message'] = "Some of the required Fields are Empty";
    } else if (strcasecmp($_REQUEST['optiona'], $_REQUEST['optionb']) == 0 || strcasecmp($_REQUEST['optiona'], $_REQUEST['optionc']) == 0 || strcasecmp($_REQUEST['optiona'], $_REQUEST['optiond']) == 0 || strcasecmp($_REQUEST['optionb'], $_REQUEST['optionc']) == 0 || strcasecmp($_REQUEST['optionb'], $_REQUEST['optiond']) == 0 || strcasecmp($_REQUEST['optionc'], $_REQUEST['optiond']) == 0) {
        $_GLOBALS['message'] = "Two or more options are representing same answers.Verify Once again";
    } else {
        $query = "update question set question='" . htmlspecialchars($_REQUEST['question'], ENT_QUOTES) . "',optiona='" . htmlspecialchars($_REQUEST['optiona'], ENT_QUOTES) . "',optionb='" . htmlspecialchars($_REQUEST['optionb'], ENT_QUOTES) . "',optionc='" . htmlspecialchars($_REQUEST['optionc'], ENT_QUOTES) . "',optiond='" . htmlspecialchars($_REQUEST['optiond'], ENT_QUOTES) . "',correctanswer='" . htmlspecialchars($_REQUEST['correctans'], ENT_QUOTES) . "',marks=" . htmlspecialchars($_REQUEST['marks'], ENT_QUOTES) . " where testid=" . $_SESSION['testqn'] . " and qnid=" . $_REQUEST['qnid'] . " ;";
        if (!@executeQuery($query))
            $_GLOBALS['message'] = mysql_error();
        else
            $_GLOBALS['message'] = "Question is updated Successfully.";
    }
    closedb();
} else if (isset($_REQUEST['savea'])) {
    $cancel = false;
    $result = executeQuery("select max(qnid) as qn from question where testid=" . $_SESSION['testqn'] . ";");
    $r = mysql_fetch_array($result);
    if (is_null($r['qn']))
        $newstd = 1;
    else
        $newstd = $r['qn'] + 1;

    $result = executeQuery("select count(*) as q from question where testid=" . $_SESSION['testqn'] . ";");
    $r2 = mysql_fetch_array($result);

    $result = executeQuery("select totalquestions from test where testid=" . $_SESSION['testqn'] . ";");
    $r1 = mysql_fetch_array($result);

    if (!is_null($r2['q']) && (int)htmlspecialchars_decode($r1['totalquestions'], ENT_QUOTES) == (int)$r2['q']) {
        $cancel = true;
        $_GLOBALS['message'] = "Already you have created all the Questions for this Test.<br /><b>Help:</b> If you still want to add some more questions then edit the test settings(option:Total Questions).";
    } else
        $cancel = false;

    $result = executeQuery("select * from question where testid=" . $_SESSION['testqn'] . " and question='" . htmlspecialchars($_REQUEST['question'], ENT_QUOTES) . "';");
    if (!$cancel && $r1 = mysql_fetch_array($result)) {
        $cancel = true;
        $_GLOBALS['message'] = "Sorry, You trying to enter same question for Same test";
    } else if (!$cancel)
        $cancel = false;

    if (strcmp($_REQUEST['correctans'], "<Choose the Correct Answer>") == 0 || empty($_REQUEST['question']) || empty($_REQUEST['optiona']) || empty($_REQUEST['optionb']) || empty($_REQUEST['optionc']) || empty($_REQUEST['optiond']) || empty($_REQUEST['marks'])) {
        $_GLOBALS['message'] = "Some of the required Fields are Empty";
    } else if (strcasecmp($_REQUEST['optiona'], $_REQUEST['optionb']) == 0 || strcasecmp($_REQUEST['optiona'], $_REQUEST['optionc']) == 0 || strcasecmp($_REQUEST['optiona'], $_REQUEST['optiond']) == 0 || strcasecmp($_REQUEST['optionb'], $_REQUEST['optionc']) == 0 || strcasecmp($_REQUEST['optionb'], $_REQUEST['optiond']) == 0 || strcasecmp($_REQUEST['optionc'], $_REQUEST['optiond']) == 0) {
        $_GLOBALS['message'] = "Two or more options are representing same answers.Verify Once again";
    } else if (!$cancel) {
        $query = "insert into question values(" . $_SESSION['testqn'] . ",$newstd,'" . htmlspecialchars($_REQUEST['question'], ENT_QUOTES) . "','" . htmlspecialchars($_REQUEST['optiona'], ENT_QUOTES) . "','" . htmlspecialchars($_REQUEST['optionb'], ENT_QUOTES) . "','" . htmlspecialchars($_REQUEST['optionc'], ENT_QUOTES) . "','" . htmlspecialchars($_REQUEST['optiond'], ENT_QUOTES) . "','" . htmlspecialchars($_REQUEST['correctans'], ENT_QUOTES) . "'," . htmlspecialchars($_REQUEST['marks'], ENT_QUOTES) . ")";
        if (!@executeQuery($query))
            $_GLOBALS['message'] = mysql_error();
        else
            $_GLOBALS['message'] = "Successfully New Question is Created.";
    }
    closedb();
} else if (isset($_REQUEST['submitExcel'])) {
    $cancel = false;
    $result = executeQuery("select max(qnid) as qn from question where testid=" . $_SESSION['testqn'] . ";");
    $r = mysql_fetch_array($result);
    if (is_null($r['qn']))
        $newstd = 1;
    else
        $newstd = $r['qn'] + 1;

    $result = executeQuery("select count(*) as q from question where testid=" . $_SESSION['testqn'] . ";");
    $r2 = mysql_fetch_array($result);

    $result = executeQuery("select totalquestions from test where testid=" . $_SESSION['testqn'] . ";");
    $r1 = mysql_fetch_array($result);

    if (!is_null($r2['q']) && (int)htmlspecialchars_decode($r1['totalquestions'], ENT_QUOTES) == (int)$r2['q']) {
        $cancel = true;
        $_GLOBALS['message'] = "Already you have created all the Questions for this Test.<br /><b>Help:</b> If you still want to add some more questions then edit the test settings(option:Total Questions).";
    } else
        $cancel = false;

    $result = executeQuery("select * from question where testid=" . $_SESSION['testqn'] . " and question='" . htmlspecialchars($_REQUEST['question'], ENT_QUOTES) . "';");
    if (!$cancel && $r1 = mysql_fetch_array($result)) {
        $cancel = true;
        $_GLOBALS['message'] = "Sorry, You trying to enter same question for Same test";
    } else if (!$cancel)
        $cancel = false;

    if (!$cancel) {
        include ("excel/PHPExcel/IOFactory.php");
        $file = $_FILES['file']['tmp_name'];
        $objPHPExcel = PHPExcel_IOFactory::load($file);
        foreach ($objPHPExcel->getWorksheetIterator() as $worksheet)
        {
            $highestRow = $worksheet->getHighestRow();
            for ($row=2; $row<=$highestRow; $row++)
            {

                $testid = $worksheet->getCellByColumnAndRow(0, $row)->getValue();
                $qnid = $worksheet->getCellByColumnAndRow(1, $row)->getValue();
                $question = $worksheet->getCellByColumnAndRow(2, $row)->getValue();
                $optiona = $worksheet->getCellByColumnAndRow(3, $row)->getValue();
                $optionb = $worksheet->getCellByColumnAndRow(4, $row)->getValue();
                $optionc = $worksheet->getCellByColumnAndRow(5, $row)->getValue();
                $optiond = $worksheet->getCellByColumnAndRow(6, $row)->getValue();
                $correctanswer = $worksheet->getCellByColumnAndRow(7, $row)->getValue();
                $marks = $worksheet->getCellByColumnAndRow(8, $row)->getValue();

                $query = executeQuery("insert into question values(" . $_SESSION['testqn'] . ",$qnid,'" . $question . "','" . $optiona . "','" . $optionb . "','" . $optionc . "','" . $optiond . "','" . $correctanswer . "'," . $marks . ")");

            }
            $_GLOBALS['message'] = "Successfully New Question is Created.";
        }

    }
    closedb();
}
?>
<html>
<head>
    <title>OES-Manage Questions</title>
    <meta http-equiv="Content-Type" content="text/html; charset=UTF-8"/>
    <link href="../css/materialize.min.css" rel="stylesheet" type="text/css"/>
    <link href="../css/icons/icons.css" rel="stylesheet" type="text/css"/>
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
        <h1 class="center green-text text-darken-4">SITM Online Examination System</h1>
    </div>
    <form name="prepqn" action="prepqn.php" method="post" enctype="multipart/form-data">
        <div class="row">
            <div class="col l12 center">
                <?php
                if (isset($_SESSION['admname']) && isset($_SESSION['testqn'])) {

                    ?>
                    <button  input type="submit" value="LogOut"  name="logout" class="btn tooltipped red darken-4 white-text waves-effect waves-light btn" data-position="top" data-delay="50" data-tooltip="Logout" ><i class="material-icons right">power_settings_new</i>Logout</button>
                    <button  input type="submit" value="managetests"  name="managetests" class="btn tooltipped orange darken-4 white-text waves-effect waves-light btn" data-position="top" data-delay="50" data-tooltip="Manage Tests" ><i class="material-icons right">list</i>Manage Tests</button>

                    <?php

                    if (isset($_REQUEST['add'])) {
                        ?>
                        <?php
                    } else if (isset($_REQUEST['edit'])) {
                        ?>
                        <?php
                    } else {
                        ?>
                        <button input type="submit" value="Delete" name="delete" class="btn tooltipped red white-text waves-effect waves-light btn" data-position="top" data-delay="50" data-tooltip="Delete forever"><i class="material-icons right">delete</i>Delete</button>
                        <button input type="submit" value="Add" name="add" class="btn tooltipped orange white-text waves-effect waves-light btn" data-position="top" data-delay="50" data-tooltip="Add new" ><i class="material-icons right">playlist_add</i>Add</button>
                        <input type="submit" value="Add From Excel" name="addExcel" class="btn indigo white-text" title="Add From Excel"/>
                    <?php }
                } ?>
            </div>
            <div class="col l2"></div>
        </div>

                <?php
                $result = executeQuery("select count(*) as q from question where testid=" . $_SESSION['testqn'] . ";");
                $r1 = mysql_fetch_array($result);

                $result = executeQuery("select totalquestions from test where testid=" . $_SESSION['testqn'] . ";");
                $r2 = mysql_fetch_array($result);
                if ((int)$r1['q'] == (int)htmlspecialchars_decode($r2['totalquestions'], ENT_QUOTES))
                    echo "<div class=\"black-text\"> Test Name: " . $_SESSION['testname'] . "<br/>Status: All the Questions are Created for this test.</div>";
                else
                    echo "<div class=\"red-text\"> Test Name: " . $_SESSION['testname'] . "<br/>Status: Still you need to create " . (htmlspecialchars_decode($r2['totalquestions'], ENT_QUOTES) - $r1['q']) . " Question/s. After that only, test will be available for candidates.</div>";
                ?>
                <?php
                if (isset($_SESSION['admname']) && isset($_SESSION['testqn'])) {

                    if (isset($_REQUEST['add'])) {
                        ?>
                        <div class="row">
                        <div class="col l4"></div>
                        <div class="col l4">
                            <div class="input-field">
                                <label for="question">Question</label>
                                <textarea name="question" id="question" class="materialize-textarea"></textarea>
                            </div>

                            <div class="input-field">
                                <label for="a">Option A</label>
                                <input type="text" name="optiona" id="a" class="validate"  value="">
                            </div>

                            <div class="input-field">
                                <label for="b">Option B</label>
                                <input type="text" name="optionb" id="b" class="validate" value="">
                            </div>

                            <div class="input-field">
                                <label for="c">Option C</label>
                                <input type="text" name="optionc" id="c" class="validate" value="">
                            </div>

                            <div class="input-field">
                                <label for="d">Option D</label>
                                <input type="text" name="optiond" id="d" class="validate" value="">
                            </div>

                                <div class="input-field">
                                    <select name="correctans">
                                        <option value="<Choose the Correct Answer>" selected>Choose Correct Answer</option>
                                        <option value="optiona">Option A</option>
                                        <option value="optionb">Option B</option>
                                        <option value="optionc">Option C</option>
                                        <option value="optiond">Option D</option>
                                    </select>
                                </div>

                            <div class="input-field">
                                <label for="m">Marks</label>
                                <input type="text" name="marks" class="validate"  value="1" onkeyup="isnum(this)"/>
                            </div>

                            <div class="input-field center">
                            <input type="submit" value="Save" name="savea" class="btn green white-text"
                                       onclick="validateqnform('prepqn')" title="Save the Changes"/>
                                <input type="submit" value="Cancel" name="cancel" class="btn red white-text" title="Cancel"/>
                            </div>
                            </div>
                        <div class="col l4"></div>
                        </div>
                        <?php
                    } else if (isset($_REQUEST['addExcel'])) {
                        ?>

                        <div class="row">
                        <div class="col l4"></div>
                        <div class="col l4">
                            <div class="file-field input-field">
                                <div class="btn">
                                    <span>File</span>
                                    <input type="file" name="file">
                                </div>
                                <div class="file-path-wrapper">
                                    <input class="file-path validate" type="text">
                                </div>
                            </div>
                            <div class="input-field center">
                            <input type="submit" name="submitExcel" class="btn green white-text" value="Submit"/>
                            </div>
                        </div>
                        <div class="col l4"></div>
                        </div>

                        <?php
                    } else if (isset($_REQUEST['edit'])) {

                        $result = executeQuery("select * from question where testid=" . $_SESSION['testqn'] . " and qnid=" . $_REQUEST['edit'] . ";");
                        if (mysql_num_rows($result) == 0) {
                            header('Location: prepqn.php');
                        } else if ($r = mysql_fetch_array($result)) {

                            ?>
                            <div class="row">
                            <div class="col l4"></div>
                            <div class="col l4">
                                <input type="hidden" name="qnid" value="<?php echo $r['qnid']; ?>">
                                <div class="input-field">
                                    <label for="question">Question</label>
                                    <textarea name="question" class="materialize-textarea"><?php echo htmlspecialchars_decode($r['question'], ENT_QUOTES); ?></textarea>
                                </div>

                                <div class="input-field">
                                    <label for="a">Option A</label>
                                    <input type="text" name="optiona" class="validate"  value="<?php echo htmlspecialchars_decode($r['optiona'], ENT_QUOTES); ?>"/>
                                </div>

                                <div class="input-field">
                                    <label for="b">Option B</label>
                                    <input type="text" name="optionb" class="validate" value="<?php echo htmlspecialchars_decode($r['optionb'], ENT_QUOTES); ?>"/>
                                </div>

                                <div class="input-field">
                                    <label for="c">Option C</label>
                                    <input type="text" name="optionc" class="validate" value="<?php echo htmlspecialchars_decode($r['optionc'], ENT_QUOTES); ?>"/>
                                </div>

                                <div class="input-field">
                                    <label for="d">Option D</label>
                                    <input type="text" name="optiond" class="validate" value="<?php echo htmlspecialchars_decode($r['optiond'], ENT_QUOTES); ?>"/>
                                </div>


                                <div class="input-field">
                                    <select name="correctans">
                                        <option
                                                value="optiona" <?php if (strcmp(htmlspecialchars_decode($r['correctanswer'], ENT_QUOTES), "optiona") == 0)
                                            echo "selected"; ?>>Option A
                                        </option>
                                        <option
                                                value="optionb" <?php if (strcmp(htmlspecialchars_decode($r['correctanswer'], ENT_QUOTES), "optionb") == 0)
                                            echo "selected"; ?>>Option B
                                        </option>
                                        <option
                                                value="optionc" <?php if (strcmp(htmlspecialchars_decode($r['correctanswer'], ENT_QUOTES), "optionc") == 0)
                                            echo "selected"; ?>>Option C
                                        </option>
                                        <option
                                                value="optiond" <?php if (strcmp(htmlspecialchars_decode($r['correctanswer'], ENT_QUOTES), "optiond") == 0)
                                            echo "selected"; ?>>Option D
                                        </option>
                                    </select>
                                </div>

                                <div class="input-field">
                                    <label for="m">Marks</label>
                                    <input type="text" name="marks" class="validate"  value="<?php echo htmlspecialchars_decode($r['marks'], ENT_QUOTES); ?>" onkeyup="isnum(this)"/>
                                </div>

                                <div class="input-field center">
                                <input type="submit" value="Save" name="savem" class="btn green white-text"
                                           onclick="validateqnform('prepqn')" title="Save the changes"/>
                                    <input type="submit" value="Cancel" name="cancel" class="btn red white-text" title="Cancel"/>
                                </div>
                            </div>
                            <div class="col l4"></div>
                            </div>
                            <?php
                            closedb();
                        }
                    } else {


                        $result = executeQuery("select * from question where testid=" . $_SESSION['testqn'] . " order by qnid;");
                        if (mysql_num_rows($result) == 0) {
                            echo "<h3 style=\"color:#0000cc;text-align:center;\">No Questions Yet..!</h3>";
                        } else {
                            $i = 0;
                            ?>
                            <table class="table bordered striped highlight responsive-table">
                                <thead class="blue white-text">
                                <tr>
                                    <th><a href="#" id="selectAll" onclick="multi()">All</a></th>
                                    <th>Qn.No</th>
                                    <th>Question</th>
                                    <th>Correct Answer</th>
                                    <th>Option</th>
                                    <th>Marks</th>
                                    <th>Edit</th>
                                </tr>
                                </thead>
                                <?php
                                while ($r = mysql_fetch_array($result)) {
                                    $i = $i + 1;
                                    if ($i % 2 == 0)
                                        echo "<tr style='color: black'>";
                                    else
                                        echo "<tr style='color: black'>";
                                    echo "<td style=\"text-align:center;\"><p><input type=\"checkbox\" name=\"d$i\" id='delete$i' value=\"" . $r['qnid'] . "\" /><label for='delete$i'></label></p></td><td> " . $i
                                        . "</td><td>" . htmlspecialchars_decode($r['question'], ENT_QUOTES) . "</td><td>" . htmlspecialchars_decode($r[htmlspecialchars_decode($r['correctanswer'], ENT_QUOTES)], ENT_QUOTES) . "</td><td>" . htmlspecialchars_decode($r['correctanswer'], ENT_QUOTES) . "</td><td>" . htmlspecialchars_decode($r['marks'], ENT_QUOTES) . "</td>"
                                        . "<td class=\"tddata\"><a title=\"Edit " . $r['qnid'] . "\"href=\"prepqn.php?edit=" . $r['qnid'] . "\"><i class=\"big green-text material-icons\">edit</i></a>"
                                        . "</td></tr>";
                                }
                                ?>
                            </table>
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
<script type="text/javascript">
function multi(){
    var cells = table.cells( ).nodes();
    $( cells ).find(':checkbox').prop('checked', $(this).is(':checked'));
}
    $(document).ready(function() {
        $('select').material_select();

        $('#selectAll').click(function(e){
    
       });
    });
</script>
</body>
</html>
