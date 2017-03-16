<?php

error_reporting(0);
session_start();
include_once 'oesdb.php';
$final=false;
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

    }else if(isset($_REQUEST['next']) || isset($_REQUEST['summary']) || isset($_REQUEST['viewsummary']))
    {

        $answer='unanswered';
        if(time()<strtotime($_SESSION['endtime']))
        {
            if(isset($_REQUEST['markreview']))
            {
                $answer='review';
            }
            else if(isset($_REQUEST['answer']))
            {
                $answer='answered';
            }
            else
            {
                $answer='unanswered';
            }
            if(strcmp($answer,"unanswered")!=0)
            {
                if(strcmp($answer,"answered")==0)
                {
                    $query="update studentquestion set answered='answered',stdanswer='".htmlspecialchars($_REQUEST['answer'],ENT_QUOTES)."' where stdid=".$_SESSION['stdid']." and testid=".$_SESSION['testid']." and qnid=".$_SESSION['qn'].";";
                }
                else
                {
                    $query="update studentquestion set answered='review',stdanswer='".htmlspecialchars($_REQUEST['answer'],ENT_QUOTES)."' where stdid=".$_SESSION['stdid']." and testid=".$_SESSION['testid']." and qnid=".$_SESSION['qn'].";";
                }
                if(!executeQuery($query))
                {

                $_GLOBALS['message']="Your previous answer is not updated.Please answer once again";
                }
                closedb();
            }
            if(isset($_REQUEST['viewsummary']))
            {
                 header('Location: summary.php');
            }
            if(isset($_REQUEST['summary']))
             {

                     header('Location: summary.php');
             }
        }
        if((int)$_SESSION['qn']<(int)$_SESSION['tqn'])
        {
        $_SESSION['qn']=$_SESSION['qn']+1;
       
        }
        if((int)$_SESSION['qn']==(int)$_SESSION['tqn'])
        {
           $final=true;
        }

    }
    else if(isset($_REQUEST['previous']))
    {

        $answer='unanswered';
        if(time()<strtotime($_SESSION['endtime']))
        {
            if(isset($_REQUEST['markreview']))
            {
                $answer='review';
            }
            else if(isset($_REQUEST['answer']))
            {
                $answer='answered';
            }
            else
            {
                $answer='unanswered';
            }
            if(strcmp($answer,"unanswered")!=0)
            {
                if(strcmp($answer,"answered")==0)
                {
                    $query="update studentquestion set answered='answered',stdanswer='".htmlspecialchars($_REQUEST['answer'],ENT_QUOTES)."' where stdid=".$_SESSION['stdid']." and testid=".$_SESSION['testid']." and qnid=".$_SESSION['qn'].";";
                }
                else
                {
                    $query="update studentquestion set answered='review',stdanswer='".htmlspecialchars($_REQUEST['answer'],ENT_QUOTES)."' where stdid=".$_SESSION['stdid']." and testid=".$_SESSION['testid']." and qnid=".$_SESSION['qn'].";";
                }
                if(!executeQuery($query))
                {

                $_GLOBALS['message']="Your previous answer is not updated.Please answer once again";
                }
                closedb();
            }
        }

        if((int)$_SESSION['qn']>1)
        {
            $_SESSION['qn']=$_SESSION['qn']-1;
        }

    }
    else if(isset($_REQUEST['fs']))
    {

        header('Location: testack.php');
    }
?>
<?php
header("Cache-Control: no-cache, must-revalidate");
?>

<html>
  <head>
    <title>OES-Test Conducter</title>
    <meta http-equiv="Content-Type" content="text/html; charset=UTF-8"/>
    <meta http-equiv="CACHE-CONTROL" content="NO-CACHE"/>
    <meta http-equiv="PRAGMA" content="NO-CACHE"/>
    <meta name="ROBOTS" content="NONE"/>
      <link href="css/materialize.min.css" rel="stylesheet" type="text/css"/>
  <link href="https://fonts.googleapis.com/icon?family=Material+Icons" rel="stylesheet">

    <script type="text/javascript" src="validate.js" ></script>
    <script type="text/javascript" src="cdtimer.js" ></script>
    <script type="text/javascript" >
    <!--
        <?php
                $elapsed=time()-strtotime($_SESSION['starttime']);
                if(((int)$elapsed/60)<(int)$_SESSION['duration'])
                {
                    $result=executeQuery("select TIME_FORMAT(TIMEDIFF(endtime,CURRENT_TIMESTAMP),'%H') as hour,TIME_FORMAT(TIMEDIFF(endtime,CURRENT_TIMESTAMP),'%i') as min,TIME_FORMAT(TIMEDIFF(endtime,CURRENT_TIMESTAMP),'%s') as sec from studenttest where stdid=".$_SESSION['stdid']." and testid=".$_SESSION['testid'].";");
                    if($rslt=mysql_fetch_array($result))
                    {
                     echo "var hour=".$rslt['hour'].";";
                     echo "var min=".$rslt['min'].";";
                     echo "var sec=".$rslt['sec'].";";
                    }
                    else
                    {
                        $_GLOBALS['message']="Try Again";
                    }
                    closedb();
                }
                else
                {
                    echo "var sec=01;var min=00;var hour=00;";
                }
        ?>

    -->
    </script>
    </head>
  <body >
      <noscript><h2>For the proper Functionality, You must use Javascript enabled Browser</h2></noscript>
       <?php

        if($_GLOBALS['message']) {
            echo "<div class=\"red white-text\">".$_GLOBALS['message']."</div>";
        }
        ?>
      <div class="section">
      <div id="container">
           <form id="testconducter" action="testconducter.php" method="post">
          <div class="row" style="text-align:center;">
              <h4 class="red-text">Test in Progress</h4>
              <h3 class="card"><span id="timer" class="timerclass"></span></h3>
          </div>

          <?php
         
          if(isset($_SESSION['stduname']))
          {
                $result=executeQuery("select stdanswer,answered from studentquestion where stdid=".$_SESSION['stdid']." and testid=".$_SESSION['testid']." and qnid=".$_SESSION['qn'].";");
                $r1=mysql_fetch_array($result);
                $result=executeQuery("select * from question where testid=".$_SESSION['testid']." and qnid=".$_SESSION['qn'].";");
                $r=mysql_fetch_array($result);
          ?>
          <div class="row">
              <div class="col l2"></div>
              <div class="col l8">
              <table width="100%" class="table table-bordered table-hover">
                  <thead>
                  <tr class="blue">
                      <th><h6 class="white-text">Question No: <?php echo $_SESSION['qn']; ?> </h6></th>
                      <th class="blue darken-4"><h6 class="right">
                              <input class="white-text" id="markreview" type="checkbox" name="markreview" value="mark">
                              <label class="white-text" for="markreview">Mark to change Answer</label>
                          </h6></th>
                  </tr>
                  </thead>
              </table>
             <textarea cols="100" rows="8" name="question" readonly style="width:100%;text-align:left;font-size:120%;font-weight:bold;margin-bottom:0;color:#0000ff;padding:2px 2px 2px 2px;"><?php echo htmlspecialchars_decode($r['question'],ENT_QUOTES); ?></textarea>
              <table border="0" width="100%" class="table">
                  <tr style="color: black"><td >A. <input class="with-gap" type="radio" name="answer" id="a" value="optiona" <?php if((strcmp(htmlspecialchars_decode($r1['answered'],ENT_QUOTES),"review")==0 ||strcmp(htmlspecialchars_decode($r1['answered'],ENT_QUOTES),"answered")==0)&& strcmp(htmlspecialchars_decode($r1['stdanswer'],ENT_QUOTES),"optiona")==0 ){echo "checked";} ?>><label class="black-text" for="a"><?php echo htmlspecialchars_decode($r['optiona'],ENT_QUOTES); ?></label> </td></tr>
                  <tr style="color: black"><td >B. <input class="with-gap" type="radio" name="answer" id="b" value="optionb" <?php if((strcmp(htmlspecialchars_decode($r1['answered'],ENT_QUOTES),"review")==0 ||strcmp(htmlspecialchars_decode($r1['answered'],ENT_QUOTES),"answered")==0)&& strcmp(htmlspecialchars_decode($r1['stdanswer'],ENT_QUOTES),"optionb")==0 ){echo "checked";} ?>><label class="black-text" for="b"><?php echo htmlspecialchars_decode($r['optionb'],ENT_QUOTES); ?></label></td></tr>
                  <tr style="color: black"><td >C. <input class="with-gap" type="radio" name="answer" id="c" value="optionc" <?php if((strcmp(htmlspecialchars_decode($r1['answered'],ENT_QUOTES),"review")==0 ||strcmp(htmlspecialchars_decode($r1['answered'],ENT_QUOTES),"answered")==0)&& strcmp(htmlspecialchars_decode($r1['stdanswer'],ENT_QUOTES),"optionc")==0 ){echo "checked";} ?>><label class="black-text" for="c"><?php echo htmlspecialchars_decode($r['optionc'],ENT_QUOTES); ?></label></td></tr>
                  <tr style="color: black"><td >D. <input class="with-gap" type="radio" name="answer" id="d" value="optiond" <?php if((strcmp(htmlspecialchars_decode($r1['answered'],ENT_QUOTES),"review")==0 ||strcmp(htmlspecialchars_decode($r1['answered'],ENT_QUOTES),"answered")==0)&& strcmp(htmlspecialchars_decode($r1['stdanswer'],ENT_QUOTES),"optiond")==0 ){echo "checked";} ?>><label class="black-text" for="d"><?php echo htmlspecialchars_decode($r['optiond'],ENT_QUOTES); ?></label></td></tr>

                  <tr>
                      <th style="width:80%;"><h4><button type="submit" name="<?php if($final==true){ echo "viewsummary" ;}else{ echo "next";} ?>" value="<?php if($final==true){ echo "View Summary" ;}else{ echo "Next";} ?>" class="btn green white-text waves-effect waves-light">Next</button></h4></th>
                      <th style="width:12%;text-align:right;"><h4><button type="submit" name="previous" value="Previous" class="btn orange white-text waves-effect waves-light">Previous</button></h4></th>
                      <th style="width:8%;text-align:right;"><h4><button type="submit" name="summary" value="Summary" class="btn red white-text waves-effect waves-light">Summary</button></h4></th>
                  </tr>
                  
              </table>
              </div>
              <div class="col l2"></div>
          </div>
          <?php
          closedb();
          }
          ?>

           </form>

      </div>
      </div>
  </body>
</html>

