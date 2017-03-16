<?php

error_reporting(0);
session_start();
include_once '../oesdb.php';

if (isset($_REQUEST['admsubmit'])) {
    $result = executeQuery("select * from adminlogin where admname='" . htmlspecialchars($_REQUEST['name'], ENT_QUOTES) . "' and admpassword='" . md5(htmlspecialchars($_REQUEST['password'], ENT_QUOTES)) . "'");

    if (mysql_num_rows($result) > 0) {
        $r = mysql_fetch_array($result);
        if (strcmp($r['admpassword'], md5(htmlspecialchars($_REQUEST['password'], ENT_QUOTES))) == 0) {
            $_SESSION['admname'] = htmlspecialchars_decode($r['admname'], ENT_QUOTES);
            $_SESSION['role'] = htmlspecialchars_decode($r['role'], ENT_QUOTES);
            unset($_GLOBALS['message']);
            $q = executeQuery("SELECT * FROM adminlogin WHERE admname='" . htmlspecialchars($_REQUEST['name'], ENT_QUOTES) . "'");
            $r = mysql_fetch_array($q);
            $role = $r['role'];


            if ($role == "admin") {
                header('Location: admwelcome.php?role=' . $role);
            } else if ($role == "SE") {
                header('Location: admwelcome.php?role=' . $role);
            } else if ($role == "IMS") {
                header('Location: admwelcome.php?role=' . $role);
            } else if ($role == "VFX") {
                header('Location: admwelcome.php?role=' . $role);
            } else if ($role == "CERTIFICATE") {
                header('Location: admwelcome.php?role=' . $role);
            } else if ($role == "SCHOLARSHIP") {
                header('Location: admwelcome.php?role=' . $role);
            }

        } else {
            $_GLOBALS['message'] = "Check Your user name and Password.";
        }

    } else {
        $_GLOBALS['message'] = "Check Your user name and Password.";

    }
    closedb();
}
?>

<html>
<head>
    <title>Administrator Login</title>
    <meta http-equiv="Content-Type" content="text/html; charset=UTF-8"/>
    <link href="../css/materialize.min.css" rel="stylesheet" type="text/css"/>
  <link href="https://fonts.googleapis.com/icon?family=Material+Icons" rel="stylesheet">

</head>
<body>

<?php

if (isset($_GLOBALS['message'])) {
    echo "<div class=\"red white-text\">" . $_GLOBALS['message'] . "</div>";
}
?>

<div class="section">
    <div class="container">
        <center>
            <div class="row">
                <h2 class="center green-text text-darken-4">SITM Online Examination System</h2>
            </div>
        <div class="row">
            <div class="col l4"></div>
            <div class="col l4 card">
                            <img src="../images/logo.png" height="110" width="100" alt="The SITM Logo"/>
                        <h3 class="center">Admin Login</h3>
                        <form role="form" class="center">
                            <div class="input-field">
                                <label for="exampleInputUsername">Admin Username</label>
                                <input name="name" class="validate" id="exampleInputUsername" type="text">
                            </div>
                            <div class="input-field">
                                <label for="exampleInputPassword1">Admin Password</label>
                                <input name="password" class="validate" id="exampleInputPassword1" type="password">
                            </div>
                            <button name="admsubmit" type="submit" class="btn white-text waves-effect waves-light green">Login</button>
                        </form>
            </div>
            <div class="col l4"></div>
        </div>
        </center>
    </div>
</div>
<script type="text/javascript" src="../js/jquery-2.1.1.js"></script>
<script type="text/javascript" src="../js/materialize.min.js"></script>
<script type="text/javascript" src="../js/materialize.js"></script>
</body>
</html>
