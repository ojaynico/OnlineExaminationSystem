<?php


error_reporting(0);
session_start();
        if(!isset($_SESSION['stduname'])){
            $_GLOBALS['message']="Session Timeout.Click here to <a href=\"index.php\">Re-LogIn</a>";
        }
        else if(isset($_REQUEST['logout'])){
                unset($_SESSION['stduname']);
            $_GLOBALS['message']="You are Loggged Out Successfully.";
            header('Location: index.php');
        }
?>
<html>
    <head>
        <title>OES-DashBoard</title>
        <link href="./css/icons/icons.css" rel="stylesheet" type="text/css"/>
        <meta http-equiv="Content-Type" content="text/html; charset=UTF-8"/>
        <link href="css/materialize.min.css" rel="stylesheet" type="text/css"/>
  <link href="https://fonts.googleapis.com/icon?family=Material+Icons" rel="stylesheet">

    </head>
    <body style="background-image: url('images/slogo2.jpg'); background-size: contain">
        <?php
       
        if($_GLOBALS['message']) {
            echo "<div class=\"red white-text\">".$_GLOBALS['message']."</div>";
        }
        ?>
<div class="section">
        <div class="container">
            <div class="row">
                <h3 class="center green-text text-darken-4"><i class=" small material-icons ">school</i> Online Examination System</h3>
            </div>
            <hr/>

                <form name="stdwelcome" action="stdwelcome.php" method="post">
                    <div class="row">
                        <div class="col l4">
                        <?php if(isset($_SESSION['stduname'])){ ?>
                            <button  input type="submit" value="LogOut"  name="logout" class="btn tooltipped red darken-4 white-text waves-effect waves-light btn" data-position="top" data-delay="50" data-tooltip="Logout" ><i class="material-icons right">power_settings_new</i>Logout</button>
                        <?php } ?>
                        </div>
                        <div class="col l4"></div>
                        <div class="col l4"></div>
                    </div>
                </form>

                    <div class="row">
                        <h2 class="center black-text">Welcome</h2>
                        <h5 class="center red-text"><u><?php echo $_SESSION['studentname']; ?></u></h5>
                    </div>

            <div>
                <?php if(isset($_SESSION['stduname'])){ ?>
                <div class="row">
                    <div class="col l4"></div>
                    <div class="col l4">
                        <a href="stdtest.php"><button class="btn btn-block btn-large yellow darken-4 white-text" style="width: 100%"><i class="material-icons right waves-effect waves-light ">list</i>Multiple Choice Exam</button></a><br/>
                        <a href="theoryexam/student.php"><button class="btn btn-block btn-large indigo white-text" style="width: 100%"><i class="material-icons right waves-effect waves-light  ">library_books</i>Theory Exam</button></a></br>
                        <a href="resumetest.php"><button class="btn btn-block btn-large purple darken-4 white-text" style="width: 100%"><i class="material-icons right waves-effect waves-light ">restore</i>Resume Test</button></a>
                    </div>
                    <div class="col l4"></div>
                </div>
                <?php } ?>
            </div>

      </div>
</div>
        <script type="text/javascript" src="../js/materialize.min.js"></script>
        <script type="text/javascript" src="../js/materialize.js"></script>
  </body>
</html>
