<?php

error_reporting(0);

session_start();
if (!isset($_SESSION['admname'])) {
    $_GLOBALS['message'] = "Session Timeout.Click here to <a href=\"index.php\">Re-LogIn</a>";
} else if (isset($_REQUEST['logout'])) {
    unset($_SESSION['admname']);
    $_GLOBALS['message'] = "You are Loggged Out Successfully.";
    header('Location: index.php');
}
?>

<html>
<head>
    <title>Admin-DashBoard</title>
    <link rel="icon"
          type="image/ico"
          href="../images/admin.ico"/>
    <meta http-equiv="Content-Type" content="text/html; charset=UTF-8"/>
    <link href="../css/materialize.min.css" rel="stylesheet" type="text/css"/>
    <link href="../css/icons/icons.css" rel="stylesheet" type="text/css"/>
  <link href="https://fonts.googleapis.com/icon?family=Material+Icons" rel="stylesheet">
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
            <h3 class="center green-text text-darken-4"> <i class=" medium material-icons  ">school</i> SITM Online Examination System</h3>
        </div>

    <div class="center">
<!--        <img src="../images/admin2.ico" width="80" height="80"/>-->
        <h4>Admin Dashboard-
        <?php echo $_SESSION['role']; ?> </h4>
    </div>
        <div class="row">
            <div class="col l4"></div>
            <div class="col l4">
                <?php if (isset($_SESSION['admname'])) { ?>
                    <a href="usermng.php">
                        <button class="btn btn-block btn-large orange white-text" style="width: 100%"><i class="material-icons right waves-effect waves-light ">group</i>Manage Users </button>
                    </a><br/>
                    <a href="submng.php">
                        <button class="btn btn-block btn-large purple white-text" style="width: 100%"><i class="material-icons right waves-effect waves-light ">subject</i>Manage Subjects</button>
                    </a><br/>
                    <a href="rsltmng.php">
                        <button class="btn btn-block btn-large pink white-text" style="width: 100%"><i class="material-icons right waves-effect waves-light  ">school</i>Manage Test Results</button>
                    </a><br/>
                    <a href="testmng.php?forpq=true">
                        <button class="btn btn-block btn-large yellow darken-4 white-text" style="width: 100%"><i class="material-icons right waves-effect waves-light ">list</i>Multiple Choice Exam</button>
                    </a><br/>
                    <a href="../theoryexam/index.php">
                        <button class="btn btn-block btn-large indigo white-text" style="width: 100%"><i class="material-icons right waves-effect waves-light  ">library_books</i>Theory Exam</button>
                    </a><br/>
                  
                    <?php if ($_SESSION['role'] == "admin") { ?>
                        <a href="admins.php">
                            <button class="btn btn-block btn-large pink darken-4 white-text" style="width: 100%">Modify Admins</button>
                        </a><br/>
                    <?php }
                } ?>
                <center>
                    <form name="admwelcome" action="admwelcome.php" method="post" class="">
                        <?php if (isset($_SESSION['admname'])) { ?>
                            <button type="submit" name="logout" class="btn btn-block btn-large red darken-4 white-text" title="Log Out">
                                <i class="material-icons right ">settings_power</i> LogOut
                            </button>
                        <?php } ?>
                    </form>
                </center>
            </div>
            <div class="col l4"></div>
        </div>
    </div>
</div>
<script type="text/javascript" src="../js/materialize.min.js"></script>
<script type="text/javascript" src="../js/materialize.js"></script>
</body>
</html>
