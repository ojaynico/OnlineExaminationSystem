<?php

error_reporting(0);
session_start();
include_once 'oesdb.php';

if ($_REQUEST['stdsubmit']) {

    $result = executeQuery("select *,DECODE(stdpassword,'oespass') as std from student where stduname='" . htmlspecialchars($_REQUEST['name'], ENT_QUOTES) . "' and stdpassword=ENCODE('" . htmlspecialchars($_REQUEST['password'], ENT_QUOTES) . "','oespass')");
    if (mysql_num_rows($result) > 0) {

        $r = mysql_fetch_array($result);
        if (strcmp(htmlspecialchars_decode($r['std'], ENT_QUOTES), (htmlspecialchars($_REQUEST['password'], ENT_QUOTES))) == 0) {
            $_SESSION['stduname'] = htmlspecialchars_decode($r['stduname'], ENT_QUOTES);
            $_SESSION['stdid'] = $r['stdid'];
            $_SESSION['course'] = $r['course'];
            $_SESSION['semester'] = $r['semester'];
            $_SESSION['studentname'] = $r['stdname'];
            unset($_GLOBALS['message']);
            header('Location: stdwelcome.php');
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
    <title>Online Examination System</title>
    <meta http-equiv="Content-Type" content="text/html; charset=UTF-8"/>
    <link href="css/materialize.min.css" rel="stylesheet" type="text/css"/>
  <link href="https://fonts.googleapis.com/icon?family=Material+Icons" rel="stylesheet">

</head>
<body>
<?php

if ($_GLOBALS['message']) {
    echo "<div class=\"red white-text\">" . $_GLOBALS['message'] . "</div>";
}
?>
<?php if (isset($_SESSION['stduname'])) {
    header('Location: stdwelcome.php');
} else {

    ?>

<?php } ?>

<div class="section">
    <div class="container">
<center>
    <div class="row">
        <h2 class="center green-text text-darken-4">SITM Online Examination System</h2>
    </div>
    <div class="row">
        <div class="col l4"></div>
        <div class="col l4 card">
            <img src="images/logo.png" height="110" width="100" alt="The SITM Logo">
            <h3 class="center">Student Login</h3>
            <form class="center" action="" method="post">
                <div class="input-field">
                    <label for="username">Student ID Number</label>
                    <input class="validate" id="username" name="name" type="text">
                </div>
                <div class="input-field">
                    <label for="Password">Password</label>
                    <input type="password" class="validate" name="password" id="Password">
                </div>
                <button type="submit" value="Login" name="stdsubmit" class="btn white-text waves-effect waves-light green">Login</button>
            </form>
        </div>
        <div class="col l4"></div>
    </div>
</center>
    </div>
</div>
<script type="text/javascript" src="js/jquery-2.1.1.js"></script>
<script type="text/javascript" src="js/materialize.min.js"></script>
<script type="text/javascript" src="js/materialize.js"></script>
</body>
</html>
