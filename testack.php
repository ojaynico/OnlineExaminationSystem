<?php

error_reporting(0);
session_start();
include_once 'oesdb.php';
if(!isset($_SESSION['stduname'])) {
    $_GLOBALS['message']="Session Timeout.Click here to <a href=\"index.php\">Re-LogIn</a>";
}
else if(isset($_REQUEST['logout']))
{

    unset($_SESSION['stduname']);
    header('Location: index.php');

}
else if(isset($_REQUEST['dashboard'])){

     header('Location: stdwelcome.php');

}
if(isset($_SESSION['starttime']))
{
    unset($_SESSION['starttime']);
    unset($_SESSION['endtime']);
    unset($_SESSION['tqn']);
    unset($_SESSION['qn']);
    unset($_SESSION['duration']);
    executeQuery("update studenttest set status='over' where testid=".$_SESSION['testid']." and stdid=".$_SESSION['stdid'].";");
}
?>

<html>
  <head>
    <title>OES-Test Acknowledgement</title>
    <meta http-equiv="Content-Type" content="text/html; charset=UTF-8"/>
      <link href="css/materialize.min.css" rel="stylesheet" type="text/css"/>
  <link href="https://fonts.googleapis.com/icon?family=Material+Icons" rel="stylesheet">

      <script type="text/javascript" src="validate.js" ></script>
    </head>
  <body style="background-image: url('images/slogo2.jpg'); background-size: contain">
       <?php

        if($_GLOBALS['message']) {
            echo "<div class=\"red white-text\">".$_GLOBALS['message']."</div>";
        }
        ?>

      <div class="container">
          <div class="row">
              <h2 class="center green-text text-darken-4">SITM Online Examination System</h2>
          </div>
           <form id="editprofile" action="editprofile.php" method="post">
          <div class="row">
              <div class="col l6">
                        <?php if(isset($_SESSION['stduname'])) {
                         ?>
                        <input type="submit" value="LogOut" name="logout" class="btn red white-text" title="Log Out"/>
                        <input type="submit" value="Home" name="dashboard" class="btn green white-text" title="Dash Board"/>
              </div>
              <div class="col l6"></div>
          </div>
               <div class="row center">
                   <h5 class="red-text">Your answers are Successfully Submitted. Thanks for doing the exam.</h5>
                   <?php
                   }
                   ?>
               </div>
           </form>
      </div>
  </body>
</html>

