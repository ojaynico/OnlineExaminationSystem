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
} else if (isset($_REQUEST['tcmng'])) {
    header('Location: tcmng.php');
} else if (isset($_REQUEST['printstudents'])) {
    $sem = htmlspecialchars($_REQUEST['printsemester'], ENT_QUOTES);
    header('Location: printusers.php?sem=' . $sem);
} else if (isset($_REQUEST['delete'])) {

    unset($_REQUEST['delete']);
    $hasvar = false;
    foreach ($_REQUEST as $variable) {
        if (is_numeric($variable)) {
            $hasvar = true;

            if (!@executeQuery("delete from student where stdid=$variable")) {
                if (mysql_errno() == 1451)
                    $_GLOBALS['message'] = "Too Prevent accidental deletions, system will not allow propagated deletions.<br/><b>Help:</b> If you still want to delete this user, then first manually delete all the records that are associated with this user.";
                else
                    $_GLOBALS['message'] = mysql_errno();
            }
        }
    }
    if (!isset($_GLOBALS['message']) && $hasvar == true)
        $_GLOBALS['message'] = "Selected User/s are successfully Deleted";
    else if (!$hasvar) {
        $_GLOBALS['message'] = "First Select the users to be Deleted.";
    }
} else if (isset($_REQUEST['savem'])) {

    if (empty($_REQUEST['cname']) || empty($_REQUEST['password']) || empty($_REQUEST['stduidno'])) {
        $_GLOBALS['message'] = "Some of the required Fields are Empty.Therefore Nothing is Updated";
    } else {
        $query = "update student set stdname='" . htmlspecialchars($_REQUEST['cname'], ENT_QUOTES) . "', stduname='" . htmlspecialchars($_REQUEST['stduidno'], ENT_QUOTES) . "', stdpassword=ENCODE('" . htmlspecialchars($_REQUEST['password']) . "','oespass'),stduidno='" . htmlspecialchars($_REQUEST['stduidno'], ENT_QUOTES) . "',course='" . htmlspecialchars($_REQUEST['course'], ENT_QUOTES) . "' where stdid=" . htmlspecialchars($_REQUEST['stdide'], ENT_QUOTES) . ";";
        if (!@executeQuery($query))
            $_GLOBALS['message'] = mysql_error();
        else
            $_GLOBALS['message'] = "User Information is Successfully Updated.";
    }
    closedb();
} else if (isset($_REQUEST['savea'])) {

    $result = executeQuery("select max(stdid) as std from student");
    $r = mysql_fetch_array($result);
    if (is_null($r['std']))
        $newstd = 1;
    else
        $newstd = $r['std'] + 1;

    $result = executeQuery("select stdname as std from student where stdname='" . htmlspecialchars($_REQUEST['cname'], ENT_QUOTES) . "';");


    if (empty($_REQUEST['cname']) || empty($_REQUEST['password']) || empty($_REQUEST['stduidno'])) {
        $_GLOBALS['message'] = "Some of the required Fields are Empty";
    } else if (mysql_num_rows($result) > 0) {
        $_GLOBALS['message'] = "Sorry User Already Exists.";
    } else {
        $query = "insert into student values($newstd,'" . htmlspecialchars($_REQUEST['cname'], ENT_QUOTES) . "','" . htmlspecialchars($_REQUEST['stduidno'], ENT_QUOTES) . "',ENCODE('" . htmlspecialchars($_REQUEST['password'], ENT_QUOTES) . "','oespass'),'" . htmlspecialchars($_REQUEST['stduidno'], ENT_QUOTES) . "','" . htmlspecialchars($_REQUEST['course'], ENT_QUOTES) . "','" . htmlspecialchars($_REQUEST['semester']) . "')";
        if (!@executeQuery($query)) {
            if (mysql_errno() == 1062)
                $_GLOBALS['message'] = "Given User Name voilates some constraints, please try with some other name.";
            else
                $_GLOBALS['message'] = mysql_error();
        } else
            $_GLOBALS['message'] = "Successfully New User is Created.";
    }
    closedb();
} else if (isset($_REQUEST['transfer'])) {

    $role = $_SESSION['role'];

        $query = "update student set semester='" . htmlspecialchars($_REQUEST['semesterto'], ENT_QUOTES) . "' where semester='" . htmlspecialchars($_REQUEST['semesterfrom'], ENT_QUOTES) . "' and course='".$role."';";
        if (!@executeQuery($query)) {
            $_GLOBALS['message'] = mysql_error();
        } else {
            $query2 = "delete from student where semester='completed'";
            if (!@executeQuery($query2))
                $_GLOBALS['message'] = mysql_error();
            else
                $_GLOBALS['message'] = "User Information is Successfully Updated.";
        }

    closedb();
}
?>
<html xmlns="http://www.w3.org/1999/html" xmlns="http://www.w3.org/1999/html" xmlns="http://www.w3.org/1999/html">
<head>
    <title>OES-Manage Users</title>
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
if (isset($_GLOBALS['message'])) {
    echo "<div class=\"red white-text\">" . $_GLOBALS['message'] . "</div>";
}
?>

<div class="section">
    <div class="container">
        <div class="row">
            <h1 class="center green-text text-darken-4"><i class=" small material-icons ">school</i>SITM Online Examination System </h1>
        </div>

        <form name="usermng" action="usermng.php" method="post">
            <div class="row">
                <div class="col l12 center">
                    <?php
                    if (isset($_SESSION['admname'])) {
                        ?>
                    <button  input type="submit" value="LogOut"  name="logout" class="btn tooltipped red darken-4 white-text waves-effect waves-light btn" data-position="top" data-delay="50" data-tooltip="Logout" ><i class="material-icons right">power_settings_new</i>Logout</button>

                    <button input type="submit" value="Home" name="dashboard"   class="btn tooltipped green white-text waves-effect waves-light btn" data-position="top" data-delay="50" data-tooltip="Dashboard" ><i class="material-icons right">home</i>Home</button>

                    <button input type="submit" value="Print" name="print" class="btn  tooltipped indigo white-textwaves-effect waves-light btn" data-position="top" data-delay="50" data-tooltip="Print" ><i class="material-icons right">print</i>Print</button>
                        <?php
                        if (isset($_REQUEST['add'])) {
                            ?>

                            <?php
                        } else if (isset($_REQUEST['edit'])) {
                            ?>

                            <?php
                        } else if (isset($_REQUEST['print'])) {
                            ?>

                            <?php
                        } else {
                            ?>
                    <button input type="submit" value="Delete" name="delete" class="btn tooltipped red white-text waves-effect waves-light btn" data-position="top" data-delay="50" data-tooltip="Delete forever"><i class="material-icons right">delete</i>Delete</button>
                    <button input type="submit" value="Add" name="add" class="btn tooltipped orange white-text waves-effect waves-light btn" data-position="top" data-delay="50" data-tooltip="Add a student" ><i class="material-icons right">person add</i>Add</button>
                            <button input type="submit" value="Add" name="semesterupdate" class="btn tooltipped indigo darken-4 white-text waves-effect waves-light btn" data-position="top" data-delay="50" data-tooltip="Semester Upgrade" ><i class="material-icons right">person edit</i>Bulk Update</button>

                        <?php }
                    }
                    ?>
                </div>
                <div class="col l4"></div>
            </div>

            <?php
            if (isset($_SESSION['admname'])) {
                if (isset($_REQUEST['add'])) {
                    ?>
                    <div class="row">
                        <div class="col l4"></div>
                        <div class="col l4">

                            <div class="input-field ">
                                <label for="cname" class="green-text text-darken-4">Student Name</label>
                                <input class="validate green-text darken-4" type="text" name="cname" id="cname" onkeyup="isalphanum(this)">
                            </div>
                            <div class="input-field">
                                <label for="stduidno"  class="green-text text-darken-4">Student ID</label>
                                <input class="validate green-text darken-4"  type="text" name="stduidno" id="stduidno">
                            </div>
                            <div class="input-field">
                                <label for="password" class="green-text text-darken-4">Password</label>
                                <input class="validate green-text darken-4" type="text" name="password" id="password"
                                       onkeyup="isalphanum(this)" value="<?php echo substr(md5(date('his')), 0, 10); ?>"
                                       readonly="readonly">
                            </div>
                            <div class="input-field">
                                <label for="course" class="green-text text-darken-4">Course Offered</label>
                                <input class="btn-block green-text darken-4 " type="text" name="course" id="course"
                                       value="<?php echo $_SESSION['role']; ?>" size="16" onkeyup="isnum(this)"
                                       readonly="readonly">
                            </div>
                            <div class="input-field">
                                <select class="green-text darken-4" name="semester">
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
                                <button input type="submit" value="Save" name="savea" class="btn green"
                                        onclick="validateform('usermng')" ><i class="material-icons right">thumb_up</i>Save </button>
                                <button input type="submit" value="Cancel" name="cancel" class="btn red" ><i class="material-icons right">cancel</i>cancel </button>
                            </div>

                        </div>
                        <div class="col l4"></div>
                    </div>
                    <?php
                } else if (isset($_REQUEST['edit'])) {

                    $result = executeQuery("select stdid,stdname, stduname, DECODE(stdpassword,'oespass') as stdpass, stduidno, course from student where stduname='" . htmlspecialchars($_REQUEST['edit'], ENT_QUOTES) . "';");
                    if (mysql_num_rows($result) == 0) {
                        header('Location: usermng.php');
                    } else if ($r = mysql_fetch_array($result)) {
                        ?>
                        <div class="row">
                            <div class="col l4"></div>
                            <div class="col l4">
                                <input type="hidden" name="stdide"
                                       value="<?php echo htmlspecialchars_decode($r['stdid'], ENT_QUOTES); ?>">
                                <div class="input-field">
                                    <label for="cname">Student Name</label>
                                    <input type="text" name="cname" id="cname" class="validate"
                                           value="<?php echo htmlspecialchars_decode($r['stdname'], ENT_QUOTES); ?>"
                                           onkeyup="isalphanum(this)">
                                </div>
                                <div class="input-field">
                                    <label for="stduidno">Student ID</label>
                                    <input type="text" name="stduidno" id="stduidno" class="validate"
                                           value="<?php echo htmlspecialchars_decode($r['stduidno'], ENT_QUOTES); ?>">
                                </div>

                                <div class="input-field">
                                    <label for="password">Password</label>
                                    <input type="text" name="password" class="validate"
                                           value="<?php echo htmlspecialchars_decode($r['stdpass'], ENT_QUOTES); ?>"
                                           onkeyup="isalphanum(this)" readonly="readonly"/>
                                </div>

                                <div class="input-field">
                                    <label for="course">Course Offered</label>
                                    <input type="text" name="course" class="validate"
                                           value="<?php echo $_SESSION['role']; ?>"
                                           onkeyup="isnum(this)" readonly="readonly">
                                </div>


                                <div class="input-field">
                                    <select name="semester">
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
                                    <button input type="submit" value="Save" name="savem" class="btn green"
                                            onclick="validateform('usermng')" ><i class="material-icons right">thumb_up</i>Save </button>
                                    <button input type="submit" value="Cancel" name="cancel" class="btn red" ><i class="material-icons right">cancel</i>cancel </button>
                                </div>
                            </div>
                            <div class="col l4"></div>
                        </div>
                        <?php
                        closedb();
                    }
                } else if (isset($_REQUEST['print'])) {
                    ?>
                    <div class="row">
                        <div class="col l4"></div>
                        <div class="col l4">
                            <h3 class="center">Print Student List</h3>
                            <h5 class="center">CHOOSE A SEMESTER</h5>
                            <div class="input-field">
                            <select name="printsemester">
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
                                <input type="submit" value="PRINT" name="printstudents" class="btn blue white-text">
                            </div>
                        </div>
                        <div class="col l4"></div>
                    </div>
                    <?php
                } else if (isset($_REQUEST['semesterupdate'])) {
                    ?>
                    <div class="row">
                        <div class="col l4"></div>
                        <div class="col l4">
                            <h3 class="center">Transfer Students to another semester</h3>
                            <div class="input-field">
                                <select name="semesterfrom">
                                        <option value="1">Semester 1</option>
                                        <option value="2">Semester 2</option>
                                        <option value="3">Semester 3</option>
                                        <option value="4">Semester 4</option>
                                </select>
                                <label class="green-text text-darken-4">Semester From</label>
                            </div>

                            <div class="input-field">
                                <select name="semesterto">
                                        <option value="2">Semester 2</option>
                                        <option value="3">Semester 3</option>
                                        <option value="4">Semester 4</option>
                                        <option value="completed">Completed</option>
                                </select>
                                <label class="green-text text-darken-4">Semester To</label>
                            </div>

                            <div class="input-field center">
                                <input type="submit" value="UPDATE" name="transfer" class="btn blue white-text">
                            </div>
                            </div>
                        </div>
                        <div class="col l4"></div>
                    </div>
                    <?php
                } else {
                    ?>
                    <?php
                    $role = $_SESSION['role'];
                    $result = executeQuery("select stdid, stdname, stduname, DECODE(stdpassword, 'oespass') as passw, stduidno, course, semester from student WHERE course='$role' order by course;");
                    if (mysql_num_rows($result) == 0) {
                        echo "<h3 style=\"color:#0000cc;text-align:center;\">No Users Yet..!</h3>";
                    } else {
                        $i = 0;
                        ?>
                        <div class="ibox-content">
                            <div class="table-responsive">
                                <table class="table bordered striped highlight responsive-table dataTables-example">
                                    <thead>
                                    <tr class="blue white-text">
                                        <th></th>
                                        <th>Student Name</th>
                                        <th>Student ID</th>
                                        <th>Password</th>
                                        <th>Course</th>
                                        <th>Semester</th>
                                        <th>Edit</th>
                                    </tr>
                                    </thead>
                                    <?php
                                    while ($r = mysql_fetch_array($result)) {
                                        $i = $i + 1;
                                        if ($i % 2 == 0)
                                            echo "<tr style=\"color: black\">";
                                        else
                                            echo "<tr style=\"color: black\">";
                                        echo "<td style=\"text-align:center;\"><p><input type=\"checkbox\" id='delete$i' name=\"d$i\" value=\"" . $r['stdid'] . "\" /><label for='delete$i'></label></p></td><td>" . htmlspecialchars_decode($r['stdname'], ENT_QUOTES) . "</td><td>" . htmlspecialchars_decode($r['stduidno'], ENT_QUOTES)
                                            . "</td><td>" . htmlspecialchars_decode($r['passw'], ENT_QUOTES) . "</td><td>" . htmlspecialchars_decode($r['course'], ENT_QUOTES) . "</td><td>" . htmlspecialchars_decode($r['semester'], ENT_QUOTES) . "</td>"
                                            . "<td class=\"tddata\"><a title=\"Edit " . htmlspecialchars_decode($r['stdname'], ENT_QUOTES) . "\"href=\"usermng.php?edit=" . htmlspecialchars_decode($r['stduname'], ENT_QUOTES) . "\"><i class=\"big green-text material-icons\">edit</i></a></td></tr>";
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
</div>
<script type="text/javascript" src="../js/jquery-2.1.1.js"></script>
<script type="text/javascript" src="../js/materialize.min.js"></script>
<script type="text/javascript" src="../js/materialize.js"></script>
<script src="../js/datatables.min.js"></script>
<script>
    $(document).ready(function () {
        $('select').material_select();
    });

    $(document).ready(function(){
        $('.tooltipped').tooltip({delay: 50});
    });

    $(document).ready(function () {
        $('.dataTables-example').DataTable({
            dom: '<"html5buttons"B>lTfgitp',
            buttons: [
                {extend: 'copy'},
                {extend: 'csv'},
                {extend: 'excel', title: 'ExampleFile'},
                {extend: 'pdf', title: 'ExampleFile'},

                {
                    extend: 'print',
                    customize: function (win) {
                        $(win.document.body).addClass('white-bg');
                        $(win.document.body).css('font-size', '10px');

                        $(win.document.body).find('table')
                            .addClass('compact')
                            .css('font-size', 'inherit');
                    }
                }
            ]

        });


    });
</script>
</body>
</html>

