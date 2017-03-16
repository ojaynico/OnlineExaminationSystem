<?php

error_reporting(0);

session_start();
        if(!isset($_SESSION['tcname'])){
            $_GLOBALS['message']="Session Timeout.Click here to <a href=\"index.php\">Re-LogIn</a>";
        }
        else if(isset($_REQUEST['logout'])){
           unset($_SESSION['tcname']);
            $_GLOBALS['message']="You are Loggged Out Successfully.";
            header('Location: index.php');
        }
?>

<html>
    <head>
        <title>OES-DashBoard</title>
        <meta http-equiv="Content-Type" content="text/html; charset=UTF-8"/>
        <link rel="stylesheet" type="text/css" href="../oes.css"/>
    </head>
    <body>
        <?php

        if($_GLOBALS['message']) {
            echo "<div class=\"message\">".$_GLOBALS['message']."</div>";
        }
        ?>
        <div id="container">
            <div class="header">
             <h3 class="headtext"> &nbsp;Online Examination System </h3>
            </div>
            <div class="menubar">

                <form name="tcwelcome" action="tcwelcome.php" method="post">
                    <ul id="menu">
                        <?php if(isset($_SESSION['tcname'])){ ?>
                        <li><input type="submit" value="LogOut" name="logout" class="subbtn" title="Log Out"/></li>
                        <?php } ?>
                    </ul>
                </form>
            </div>
            <div class="admpage">
                <?php if(isset($_SESSION['tcname'])){ ?>

                        <a href="submng.php" alt="Manage Subjects" title="This takes you to Subjects Management Section">Manage Subjects</a>
                        <a href="testmng.php" alt="Manage Tests" title="This takes you to Tests Management Section">Manage Tests</a>
                        <a href="editprofile.php" alt="Edit Your Profile" title="This takes you to Edit Profile Section">Edit Your Profile</a>
                        <a href="rsltmng.php" alt="Manage Test Results" title="Click this to view Test Results.">Manage Test Results</a>
                        <a href="testmng.php?forpq=true" alt="Prepare Questions" title="Click this to prepare Questions for the Test">Prepare Questions</a>

                <?php }?>

            </div>

           <div id="footer">

      </div>
      </div>
  </body>
</html>
