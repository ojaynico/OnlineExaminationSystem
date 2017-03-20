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
        $query = "update adminlogin set admname='" . htmlspecialchars($_REQUEST['ausername'], ENT_QUOTES) . "', admpassword='" . md5(htmlspecialchars($_REQUEST['apassword'])) . "' ,role='" . htmlspecialchars($_REQUEST['arole'], ENT_QUOTES) . "' where id=" . htmlspecialchars($_REQUEST['admin'], ENT_QUOTES) . ";";
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
    <link href="../css/materialize.min.css" rel="stylesheet" type="text/css"/>
   
    <link href="../css/icons/icons.css" rel="stylesheet" type="text/css"/>
    <link href="../css/datatables.min.css" rel="stylesheet" type="text/css"/>

     <link href="../css/materialize.min.css" rel="stylesheet" type="text/css"/>
    <link href="../css/icons/icons.css" rel="stylesheet" type="text/css"/>
  <link href="https://fonts.googleapis.com/icon?family=Material+Icons" rel="stylesheet">


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
        <h3 class="center green-text text-darken-4"><i class=" small material-icons ">school</i> Online Examination System </h3>
    </div>

    <form name="usermng" action="admins.php" method="post">
        <div class="row">
            <div class="col l12 center">
                <?php
                if (isset($_SESSION['admname'])) {
                    ?>
                    <button type="submit" name="logout" class="btn tooltipped red darken-4 white-text waves-effect waves-light btn" data-position="top" data-delay="50" data-tooltip="Logout"  title="Log Out"><i class="material-icons right">power_settings_new</i>Logout</button>
                    <button type="submit" value="Home" name="dashboard" class="btn tooltipped green white-text waves-effect waves-light btn" data-position="top" data-delay="50" data-tooltip="Dashboard" title="Dash Board"><i class="material-icons right">home</i>Home</button>
                    <?php
                    if (isset($_REQUEST['add'])) {
                        ?>
                        <?php
                    } else if (isset($_REQUEST['edit'])) {
                        ?>
                        <?php
                    } else {
                        ?>
                        <button type="submit" value="Delete" name="delete" class="btn tooltipped red white-text waves-effect waves-light btn" data-position="top" data-delay="50" data-tooltip="Delete forever"><i class="material-icons right">delete</i>Delete</button>
                        <button type="submit" value="Add" name="add" class="btn tooltipped orange white-text waves-effect waves-light btn" data-position="top" data-delay="50" data-tooltip="Add a Admin" ><i class="material-icons right">person add</i>Add</button>
                    <?php }
                }
                ?>
            </div>
        </div>

        <div class="row">
            <?php
            if (isset($_SESSION['admname'])) {
                echo "<div class=\"black-text\" style=\"text-align:center;\"><h2>Admin List</h2></div>";
                if (isset($_REQUEST['add'])) {
                    ?>
                    <div class="col l4"></div>
                    <div class="col l4">

                        <div class="input-field">
                            <label>Admin Username</label>
                            <input type="text" name="ausername" class="validate" value="" onkeyup="isalphanum(this)"/>
                        </div>
                        <div class="input-field">
                            <label>Admin Password</label>
                            <input type="password" name="apassword" class="validate" value="" onkeyup="isalphanum(this)"/>
                        </div>
                        <div class="input-field">
                            <label>Retype Password</label>
                            <input type="password" name="apassword2" class="validate" value="" onkeyup="isnum(this)"/>
                        </div>
                        <div class="input-field">
                            <select name="arole">
                                    <option value="SE">SE</option>
                                    <option value="IMS">IMS</option>
                                    <option value="VFX">VFX</option>
                                    <option value="CERTIFICATE">CERTIFICATE</option>
                                    <option value="SCHOLARSHIP">SCHOLARSHIP</option>
                            </select>
                            <label>Admin Role</label>
                        </div>
                        <div class="input-field center">
                            <button type="submit" name="savea" class="btn green" onclick="validateform('usermng')" title="Save the Changes"><i class="material-icons right">thumb_up</i>Save</button>
                            <button type="submit" value="Cancel" name="cancel" class="btn red" title="Cancel"><i class="material-icons right">cancel</i>Cancel</button>
                        </div>

                        </div>
                    <div class="col l4"></div>

                    <?php
                } else if (isset($_REQUEST['edit'])) {

                    $result = executeQuery("select id, admname, role from adminlogin where id=" . htmlspecialchars($_REQUEST['edit'], ENT_QUOTES) . ";");
                    if (mysql_num_rows($result) == 0) {
                        header('Location: admins.php');
                    } else if ($r = mysql_fetch_array($result)) {
                        ?>
                        <div class="col l4"></div>
                        <div class="col l4">
                            <div class="input-field">
                                <input type="hidden" name="admin" value="<?php echo $_GET['edit']; ?>"/>
                                <label>Admin Username</label>
                                <input type="text" name="ausername" class="validate" value="<?php echo $r['admname']; ?>" onkeyup="isalphanum(this)"/>
                            </div>
                            <div class="input-field">
                                <label>Admin Password</label>
                                <input type="password" name="apassword" class="validate" value="" onkeyup="isalphanum(this)"/>
                            </div>
                            <div class="input-field">
                                <label>Retype Password</label>
                                <input type="password" name="apassword2" class="validate" value="" onkeyup="isnum(this)"/>
                            </div>
                            <div class="input-field">
                                <select name="arole">
                                    <option value="admin" <?php if ($r['role'] == "admin") echo 'selected'?>>Admin</option>
                                    <option value="SE" <?php if ($r['role'] == "SE") echo 'selected'?>>SE</option>
                                    <option value="IMS" <?php if ($r['role'] == "IMS") echo 'selected'?>>IMS</option>
                                    <option value="VFX" <?php if ($r['role'] == "VFX") echo 'selected'?>>VFX</option>
                                    <option value="CERTIFICATE" <?php if ($r['role'] == "CERTIFICATE") echo 'selected'?>>CERTIFICATE</option>
                                    <option value="SCHOLARSHIP"<?php if ($r['role'] == "SCHOLARSHIP") echo 'selected'?>>SCHOLARSHIP</option>
                                </select>
                                <label>Admin Role</label>
                            </div>
                            <div class="input-field center">
                                <button type="submit" value="Save" name="savem" class="btn green" onclick="validateform('usermng')" title="Save the changes"><i class="material-icons right">thumb_up</i>Save</button>
                                <button type="submit" value="Cancel" name="cancel" class="btn red" title="Cancel"><i class="material-icons right">cancel</i>Cancel</button>
                            </div>

                            </div>
                        <div class="col l4"></div>
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
                        <table class="table bordered striped highlight responsive-table">
                            <thead>
                            <tr class="blue white-text">
                                <th>&nbsp;</th>
                                <th>Admin Username</th>
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
                                echo "<td style=\"text-align:center;\"><input type=\"checkbox\" id='delete$i' name=\"d$i\" value=\"" . $r['id'] . "\"/><label for='delete$i'></label></td><td>" . htmlspecialchars_decode($r['admname'], ENT_QUOTES)
                                    . "</td><td>" . htmlspecialchars_decode($r['role'], ENT_QUOTES) . "</td>"
                                    . "<td class=\"tddata\"><a title=\"Edit " . htmlspecialchars_decode($r['stdname'], ENT_QUOTES) . "\" href=\"admins.php?edit=" . htmlspecialchars_decode($r['id'], ENT_QUOTES) . "\">EDIT</a></td></tr>";
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

    </form>
</div>
</div>
<script type="text/javascript" src="../js/jquery-2.1.1.js"></script>
<script type="text/javascript" src="../js/materialize.min.js"></script>
<script type="text/javascript" src="../js/materialize.js"></script>
<script>
    $(document).ready(function () {
        $('select').material_select();
    });

    $(document).ready(function(){
        $('.tooltipped').tooltip({delay: 50});
    });
</script>
</body>
</html>

