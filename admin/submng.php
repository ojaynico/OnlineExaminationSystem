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

            if (!@executeQuery("delete from subject where subid=$variable")) {
                if (mysql_errno() == 1451)
                    $_GLOBALS['message'] = "Too Prevent accidental deletions, system will not allow propagated deletions.<br/><b>Help:</b> If you still want to delete this subject, then first delete the tests that are conducted/dependent on this subject.";
                else
                    $_GLOBALS['message'] = mysql_errno();
            }
        }
    }
    if (!isset($_GLOBALS['message']) && $hasvar == true)
        $_GLOBALS['message'] = "Selected Subject/s are successfully Deleted";
    else if (!$hasvar) {
        $_GLOBALS['message'] = "First Select the subject/s to be Deleted.";
    }
} else if (isset($_REQUEST['savem'])) {

    if (empty($_REQUEST['subname'])) {
        $_GLOBALS['message'] = "Some of the required Fields are Empty.Therefore Nothing is Updated";
    } else {
        $query = "update subject set subname='" . htmlspecialchars($_REQUEST['subname'], ENT_QUOTES) . "', course='" . htmlspecialchars($_REQUEST['couname'], ENT_QUOTES) . "', semester='" . htmlspecialchars($_REQUEST['semname'], ENT_QUOTES) . "' where subid=" . $_REQUEST['subide'] . ";";
        if (!@executeQuery($query))
            $_GLOBALS['message'] = mysql_error();
        else
            $_GLOBALS['message'] = "Subject Information is Successfully Updated.";
    }
    closedb();
} else if (isset($_REQUEST['savea'])) {

    $result = executeQuery("select max(subid) as sub from subject");
    $r = mysql_fetch_array($result);
    if (is_null($r['sub']))
        $newstd = 1;
    else
        $newstd = $r['sub'] + 1;

    $result = executeQuery("select subname as sub from subject where subname='" . htmlspecialchars($_REQUEST['subname'], ENT_QUOTES) . "';");

    if (empty($_REQUEST['subname'])) {
        $_GLOBALS['message'] = "Some of the required Fields are Empty";
    } else if (mysql_num_rows($result) > 0) {
        $_GLOBALS['message'] = "Sorry Subject Already Exists.";
    } else {
        $query = "insert into subject values($newstd,'" . htmlspecialchars($_REQUEST['subname'], ENT_QUOTES) . "','" . htmlspecialchars($_REQUEST['couname'], ENT_QUOTES) . "','" . htmlspecialchars($_REQUEST['semname'], ENT_QUOTES) . "',NULL)";
        if (!@executeQuery($query)) {
            if (mysql_errno() == 1062)
                $_GLOBALS['message'] = "Given Subject Name voilates some constraints, please try with some other name.";
            else
                $_GLOBALS['message'] = mysql_error();
        } else
            $_GLOBALS['message'] = "Successfully New Subject is Created.";
    }
    closedb();
}
?>
<html>
<head>
    <title>OES-Manage Subjects</title>
    <meta http-equiv="Content-Type" content="text/html; charset=UTF-8"/>
    <link href="../css/materialize.min.css" rel="stylesheet" type="text/css"/>
    <link href="../css/style.css" rel="stylesheet" type="text/css"/>
    <link href="../css/icons/icons.css" rel="stylesheet" type="text/css"/>
  <link href="https://fonts.googleapis.com/icon?family=Material+Icons" rel="stylesheet">

    <link href="../css/datatables.min.css" rel="stylesheet" type="text/css"/>
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
        <h1 class="center green-text text-darken-4"><i class=" small material-icons ">school</i> Online Examination System</h1>
    </div>
    <form name="submng" action="submng.php" method="post">
        <div class="row">
            <div class="col l12 center ">
                <?php
                if (isset($_SESSION['admname'])) {

                    ?>
                    <button  input type="submit" value="LogOut"  name="logout" class="btn tooltipped red darken-4 white-text waves-effect waves-light btn" data-position="top" data-delay="50" data-tooltip="Logout" ><i class="material-icons right">power_settings_new</i>Logout</button>

                    <button input type="submit" value="Home" name="dashboard"   class="btn tooltipped green white-text waves-effect waves-light btn" data-position="top" data-delay="50" data-tooltip="Dashboard" ><i class="material-icons right">home</i>Home</button>

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
                        <button input type="submit" value="Add" name="add" class="btn tooltipped orange white-text waves-effect waves-light btn" data-position="top" data-delay="50" data-tooltip="Add a subject" ><i class="material-icons right">library_add</i>Add</button>
                    <?php }
                } ?>
            </div>
            <div class="col l6"></div>
        </div>
            <?php
            if (isset($_SESSION['admname'])) {

                if (isset($_REQUEST['add'])) {
                    ?>
                    <div class="row">
                    <div class="col l4"></div>
                    <div class="col l4">
                        <h3 class="center">Add New Subject</h3>
                        <div class="input-field">
                            <label for="subname">Subject Name</label>
                            <input class="validate" type="text" name="subname" value="" id="subname" onkeyup="isalphanum(this)"
                                       onblur="if(this.value==''){alert('Subject Name is Empty');this.focus();this.value='';}">
                        </div>
                        <div class="input-field">
                            <h5>Course Name</h5>
                            <input class="validate" type="text" name="couname" value="<?php echo $_SESSION['role']; ?>" readonly="readonly"/>
                        </div>
                        <div class="input-field">
                                <select name="semname">
                                    <?php if (($_SESSION['role'] == "SE") || ($_SESSION['role'] == "IMS") || ($_SESSION['role'] == "VFX") || ($_SESSION['role'] == "admin")) { ?>
                                        <option value="1">Semester 1</option>
                                        <option value="2">Semester 2</option>
                                        <option value="3">Semester 3</option>
                                        <option value="4">Semester 4</option>
                                    <?php }
                                    if ($_SESSION['role'] == "CERTIFICATE") { ?>
                                        <option value="5">Certificate in MS Office</option>
                                        <option value="6">Certificate in Programming</option>
                                        <option value="7">Certificate in Hardware</option>
                                        <option value="8">Certificate in Graphics</option>
                                    <?php }
                                    if ($_SESSION['role'] == "SCHOLAR") { ?>
                                        <option value="9">Scholarship</option>
                                    <?php } ?>
                                </select>
                        </div>

                        <div class="input-field center">
                            <button input type="submit" value="Save" name="savea" class="btn green waves-effect waves-light btn"
                                    onclick="validateform('usermng')" ><i class="material-icons right ">thumb_up</i>Save </button>
                            <button input type="submit" value="Cancel" name="cancel" class="btn red waves-effect waves-light btn" ><i class="material-icons right">cancel</i>cancel </button>
                        </div>
                    </div>
                    <div class="col l4"></div>
                    </div>
                    <?php
                } else if (isset($_REQUEST['edit'])) {


                    $result = executeQuery("select subid,subname,course,semester from subject where subname='" . htmlspecialchars($_REQUEST['edit'], ENT_QUOTES) . "';");
                    if (mysql_num_rows($result) == 0) {
                        header('submng.php');
                    } else if ($r = mysql_fetch_array($result)) {
                        ?>
                        <div class="row">
                        <div class="col l4"></div>
                        <div class="col l4">
                            <h3 class="center">Edit Subject</h3>
                            <input type="hidden" name="subide" value="<?php echo htmlspecialchars_decode($r['subid'], ENT_QUOTES); ?>">
                            <div class="input-field">
                                <label for="subname">Subject Name</label>
                                <input type="text" name="subname" class="btn-block"
                                       value="<?php echo htmlspecialchars_decode($r['subname'], ENT_QUOTES); ?>"
                                       onkeyup="isalphanum(this)"">
                            </div>

                            <div class="input-field">
                                <h5>Course Name</h5>
                                <input type="text" name="couname" class="btn-block"
                                       value="<?php echo htmlspecialchars_decode($r['course'], ENT_QUOTES); ?>" readonly="readonly"/>
                            </div>

                            <div class="input-field">
                                <select name="semname">
                                    <?php if (($_SESSION['role'] == "SE") || ($_SESSION['role'] == "IMS") || ($_SESSION['role'] == "VFX") || ($_SESSION['role'] == "admin")) { ?>
                                        <option value="1">Semester 1</option>
                                        <option value="2">Semester 2</option>
                                        <option value="3">Semester 3</option>
                                        <option value="4">Semester 4</option>
                                    <?php }
                                    if ($_SESSION['role'] == "CERTIFICATE") { ?>
                                        <option value="5">Certificate in MS Office</option>
                                        <option value="6">Certificate in Programming</option>
                                        <option value="7">Certificate in Hardware</option>
                                        <option value="8">Certificate in Graphics</option>
                                    <?php }
                                    if ($_SESSION['role'] == "SCHOLAR") { ?>
                                        <option value="9">Scholarship</option>
                                    <?php } ?>
                                </select>
                            </div>

                            <div class="input-field center">
                                <input type="submit" value="Save" name="savem" class="btn green"
                                       onclick="validatesubform('submng')" title="Save the changes">
                            <input type="submit" value="Cancel" name="cancel" class="btn red" title="Cancel">
                            </div>

                        </div>
                        <div class="col l4"></div>
                        </div>
                        <?php
                        closedb();
                    }
                } else {
                    echo "<div class=\"green-text\" style=\"text-align:center;\"><h2 class='red-text'>Subject List</h2></div>";
                    $role = $_SESSION['role'];
                    $result = executeQuery("select * from subject WHERE course='$role' order by subid;");
                    if (mysql_num_rows($result) == 0) {
                        echo "<h3 style=\"color:#0000cc;text-align:center;\">No Subjets Yet..!</h3>";
                    } else {
                        $i = 0;
                        ?>
            <div class="ibox-content">
                <div class="table-responsive">
                        <table class="table bordered striped highlight responsive-table dataTables-example">
                            <thead>
                            <tr class="blue white-text">
                                <th>&nbsp;</th>
                                <th>Subject Name</th>
                                <th>Course</th>
                                <th>Semester</th>
                                <th>Edit</th>
                            </tr>
                            </thead>
                            <?php
                            while ($r = mysql_fetch_array($result)) {
                                $i = $i + 1;
                                if ($i % 2 == 0) {
                                    echo "<tr style=\"color: black;\">";
                                } else {
                                    echo "<tr style='color: black'>";
                                }
                                echo "<td style=\"text-align:center;\"><p><input type=\"checkbox\" id='delete$i' name=\"d$i\" value=\"" . $r['subid'] . "\" /><label for='delete$i'></label></p></td><td>" . htmlspecialchars_decode($r['subname'], ENT_QUOTES)
                                    . "</td><td>" . htmlspecialchars_decode($r['course'], ENT_QUOTES) . "</td><td>" . htmlspecialchars_decode($r['semester'], ENT_QUOTES) . "</td>"
                                    . "<td class=\"tddata\"><a title=\"Edit " . htmlspecialchars_decode($r['stdname'], ENT_QUOTES) . "\"href=\"submng.php?edit=" . htmlspecialchars_decode($r['subname'], ENT_QUOTES) . "\"><i class=\"big green-text material-icons\">edit</i></a></td></tr>";
                            }
                            ?>
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
<script type="text/javascript" src="../js/jquery-2.1.1.js"></script>
<script type="text/javascript" src="../js/materialize.min.js"></script>
<script type="text/javascript" src="../js/materialize.js"></script>
<script src="../js/datatables.min.js"></script>
<script>
    var header = "<center>SAIPALI INSTITUTE OF TECHNOLOGY AND MANAGEMENT" +
        "<br/><h3>Course Unit : <?php echo $_REQUEST['subname']; ?>" +
        "<br/>Semester <?php echo $_REQUEST['semester']; ?></h3></center>";

    $(document).ready(function () {
        $('select').material_select();
    });

    $(document).ready(function(){
        $('.dataTables-example').DataTable({
            dom: '<"html5buttons"B>lTfgitp',
            buttons: [
                { extend: 'copy'},
                {extend: 'csv'},
                {extend: 'excel', title: 'Results'},
                {extend: 'pdf', title: 'Results'},

                {extend: 'print',
                    customize: function (win){
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

