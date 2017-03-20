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
} else if (isset($_REQUEST['delete'])) {

    unset($_REQUEST['delete']);
    $hasvar = false;
    foreach ($_REQUEST as $variable) {
        if (is_numeric($variable)) {
            $hasvar = true;

            if (!@executeQuery("delete from adminlogin where id=$variable")) {
                if (mysql_errno() == 1451)
                    $_GLOBALS['message'] = "Too Prevent accidental deletions, system will not allow propagated deletions.<br/><b>Help:</b> If you still want to delete this user, then first manually delete all the records that are associated with this user.";
                else
                    $_GLOBALS['message'] = mysql_errno();
            }
        }
    }
    if (!isset($_GLOBALS['message']) && $hasvar == true)
        $_GLOBALS['message'] = "Selected Admin/s are successfully Deleted";
    else if (!$hasvar) {
        $_GLOBALS['message'] = "First Select the Admin to be Deleted.";
    }
} else if (isset($_REQUEST['savem'])) {

    if (empty($_REQUEST['ausername']) || empty($_REQUEST['apassword'])) {
        $_GLOBALS['message'] = "Some of the required Fields are Empty.Therefore Nothing is Updated";
    } else {
        $query = "update adminlogin set admname='" . htmlspecialchars($_REQUEST['ausername'], ENT_QUOTES) . "', admpassword=ENCODE('" . htmlspecialchars($_REQUEST['apassword']) . "','oespass'),role='" . htmlspecialchars($_REQUEST['arole'], ENT_QUOTES) . "' where id='" . htmlspecialchars($_REQUEST['admin'], ENT_QUOTES) . "';";
        if (!@executeQuery($query))
            $_GLOBALS['message'] = mysql_error();
        else
            $_GLOBALS['message'] = "User Information is Successfully Updated.";
    }
    closedb();
}
else if (isset($_REQUEST['savea'])) {

    $result = executeQuery("select max(id) as aid from adminlogin");
    $r = mysql_fetch_array($result);
    if (is_null($r['aid']))
        $newstd = 1;
    else
        $newstd = $r['aid'] + 1;

    $result = executeQuery("select admname as aid from adminlogin where admname='" . htmlspecialchars($_REQUEST['ausername'], ENT_QUOTES) . "';");


    if (empty($_REQUEST['ausername']) || empty($_REQUEST['apassword'])) {
        $_GLOBALS['message'] = "Some of the required Fields are Empty";
    } else if (mysql_num_rows($result) > 0) {
        $_GLOBALS['message'] = "Sorry Admin Already Exists.";
    } else {
        //$query = "insert into adminlogin values($newstd,'" . htmlspecialchars($_REQUEST['ausername'], ENT_QUOTES) . "','" . "'ENCODE('" . htmlspecialchars($_REQUEST['apassword'], ENT_QUOTES) . "','oespass'),'" . htmlspecialchars($_REQUEST['arole'], ENT_QUOTES) ."')";
        $query = "INSERT INTO adminlogin VALUES ($newstd, '".htmlspecialchars($_REQUEST['ausername'], ENT_QUOTES)."','".md5(htmlspecialchars($_REQUEST['apassword'], ENT_QUOTES))."','".htmlspecialchars($_REQUEST['arole'], ENT_QUOTES)."')";
        if (!@executeQuery($query)) {
            if (mysql_errno() == 1062)
                $_GLOBALS['message'] = "Given Admin Name voilates some constraints, please try with some other name.";
            else
                $_GLOBALS['message'] = mysql_error();
        } else
            $_GLOBALS['message'] = "Successfully New Admin is Created.";
    }
    closedb();
}
?>
<html>
<head>
    <title>OES-Manage Users</title>
    <meta http-equiv="Content-Type" content="text/html; charset=UTF-8"/>
    <link href="../css/main.css" rel="stylesheet" type="text/css"/>
  <link href="https://fonts.googleapis.com/icon?family=Material+Icons" rel="stylesheet">

    <script type="text/javascript" src="../validate.js" ></script>
</head>
<body style="background-image: url('../images/slogo2.jpg'); background-size: contain">
<?php
if (isset($_GLOBALS['message'])) {
    echo "<div class=\"message\">" . $_GLOBALS['message'] . "</div>";
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
    <form name="usermng" action="admins.php" method="post">
        <div class="row">
            <div class="span1"></div>
            <div class="span6">
                <?php
                if (isset($_SESSION['admname'])) {
                    ?>
                    <input type="submit" value="LogOut" name="logout" class="btn-danger" title="Log Out"/>
                    <input type="submit" value="Home" name="dashboard" class="btn-info" title="Dash Board"/>

                    <?php
                    if (isset($_REQUEST['add'])) {
                        ?>
                        <?php
                    } else if (isset($_REQUEST['edit'])) {
                        ?>
                        <?php
                    } else {
                        ?>
                        <input type="submit" value="Delete" name="delete" class="btn-danger" title="Delete"/>
                        <input type="submit" value="Add" name="add" class="btn-info" title="Add"/>
                    <?php }
                }
                ?>
            </div>
            <div class="span1"></div>
        </div>
        <div class="container">
        <div class="page">
            <?php
            if (isset($_SESSION['admname'])) {
                echo "<div class=\"pmsg\" style=\"text-align:center;\">Admin List </div>";
                if (isset($_REQUEST['add'])) {
                    ?>
                    <div class="span4"></div>
                    <div class="span4">
                    <ul class="unstyled">
                        <li>
                            <li><h5>Admin Username</h5></li>
                            <li><input type="text" name="ausername" class="btn-block" value="" size="16" onkeyup="isalphanum(this)"/></li>
                        </li>
                        <li>
                            <li><h5>Admin Password</h5></li>
                            <li><input type="text" name="apassword" class="btn-block" value="" size="16" onkeyup="isalphanum(this)"/></li>
                        </li>
                        <li>
                            <li><h5>Retype Password</h5></li>
                            <li><input type="text" name="apassword2" class="btn-block" value="" size="16" onkeyup="isnum(this)"/></li>
                        </li>
                        <li>
                            <li><h5>Admin Role</h5></li>
                            <li><select name="arole" class="btn-block">
                                    <option value="SE">SE</option>
                                    <option value="IMS">IMS</option>
                                    <option value="VFX">VFX</option>
                                    <option value="CERTIFICATE">CERTIFICATE</option>
                                    <option value="SCHOLARSHIP">SCHOLARSHIP</option>
                                </select></li>
                        </li>
                        <li>
                        <li><input type="submit" value="Save" name="savea" class="btn-info btn-block" onclick="validateform('usermng')" title="Save the Changes"/></li><br/>
                        <li><input type="submit" value="Cancel" name="cancel" class="btn-danger btn-block" title="Cancel"/></li>
                        </li>
                    </ul>
                        </div>
                    <div class="span4"></div>

                    <?php
                } else if (isset($_REQUEST['edit'])) {

                    $result = executeQuery("select id,admname, DECODE(admpassword) from adminlogin where id='" . htmlspecialchars($_REQUEST['edit'], ENT_QUOTES) . "';");
                    if (mysql_num_rows($result) == 0) {
                        header('Location: admins.php');
                    } else if ($r = mysql_fetch_array($result)) {
                        ?>
                        <div class="span4"></div>
                        <div class="span4">
                        <ul class="unstyled">
                            <li>
                                <li><h5>Admin Username</h5></li>
                                <li><input type="text" name="ausername" class="btn-block" value="" size="16" onkeyup="isalphanum(this)"/></li>

                            </li>
                            <li>
                                <li><h5>Admin Password</h5></li>
                                <li><input type="text" name="apassword" class="btn-block" value="" size="16" onkeyup="isalphanum(this)"/></li>

                            </li>
                            <li>
                                <li><h5>Retype Password</h5></li>
                                <li><input type="text" name="apassword2" class="btn-block" value="" size="16" onkeyup="isnum(this)"/></li>
                            </li>
                            <li>
                                <li><h5>Admin Role</h5></li>
                                <li><select name="arole" class="btn-block">
                                        <option value="SE">SE</option>
                                        <option value="IMS">IMS</option>
                                        <option value="VFX">VFX</option>
                                        <option value="CERTIFICATE">CERTIFICATE</option>
                                        <option value="SCHOLARSHIP">SCHOLARSHIP</option>
                                    </select></li>
                            </li>
                            <li>
                            <li><input type="submit" value="Save" name="savem" class="btn-info" onclick="validateform('usermng')" title="Save the changes"/></li>
                            <li><input type="submit" value="Cancel" name="cancel" class="btn-danger" title="Cancel"/></li>
                            </li>
                        </ul>
                            </div>
                        <div class="span4"></div>
                        <?php
                        closedb();
                    }
                } else {

                    $result = executeQuery("select * from adminlogin order by id;");
                    if (mysql_num_rows($result) == 0) {
                        echo "<h3 style=\"color:#0000cc;text-align:center;\">No Admin Yet..!</h3>";
                    } else {
                        $i = 0;
                        ?>
                        <table class="table table-bordered table-hover">
                            <thead>
                            <tr class="btn-info">
                                <th>&nbsp;</th>
                                <th>Admin Userame</th>
                                <th>Admin Role</th>
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
                                echo "<td style=\"text-align:center;\"><input type=\"checkbox\" name=\"d$i\" value=\"" . $r['id'] . "\" /></td><td>" . htmlspecialchars_decode($r['admname'], ENT_QUOTES)
                                    . "</td><td>" . htmlspecialchars_decode($r['role'], ENT_QUOTES) . "</td>"
                                    . "<td class=\"tddata\"><a title=\"Edit " . htmlspecialchars_decode($r['stdname'], ENT_QUOTES) . "\"href=\"admins.php?edit=" . htmlspecialchars_decode($r['id'], ENT_QUOTES) . "\">EDIT</a></td></tr>";
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
</body>
</html>

