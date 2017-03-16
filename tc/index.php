<?php

 
      error_reporting(0);
      session_start();
      include_once '../oesdb.php';

      if(isset($_REQUEST['tcsubmit']))
      {

          $result=executeQuery("select *,DECODE(tcpassword,'oespass') as tc from testconductor where tcname='".htmlspecialchars($_REQUEST['name'],ENT_QUOTES)."' and tcpassword=ENCODE('".htmlspecialchars($_REQUEST['password'],ENT_QUOTES)."','oespass')");
          if(mysql_num_rows($result)>0)
          {

              $r=mysql_fetch_array($result);
              if(strcmp(htmlspecialchars_decode($r['tc'],ENT_QUOTES),(htmlspecialchars($_REQUEST['password'],ENT_QUOTES)))==0)
              {
                  $_SESSION['tcname']=htmlspecialchars_decode($r['tcname'],ENT_QUOTES);
                  $_SESSION['tcid']=$r['tcid'];
                  unset($_GLOBALS['message']);
                  header('Location: tcwelcome.php');
              }else
          {
              $_GLOBALS['message']="Check Your user name and Password.";
          }

          }
          else
          {
              $_GLOBALS['message']="Check Your user name and Password.";
          }
          closedb();
      }
 ?>

<html>
  <head>
    <title>Online Examination System</title>
    <meta http-equiv="Content-Type" content="text/html; charset=UTF-8"/>
    <link rel="stylesheet" type="text/css" href="../oes.css"/>
  </head>
  <body>
      <?php

        if(isset($_GLOBALS['message']))
        {
         echo "<div class=\"message\">".$_GLOBALS['message']."</div>";
        }
      ?>
      
      <div id="container">
            
               <div class="header">
<h3 class="headtext"> &nbsp;Online Examination System </h3>
            </div>
     <form id="tcloginform" action="index.php" method="post">
      <div class="menubar">
       
       <ul id="menu">
                    <?php if(isset($_SESSION['tcname'])){
                          header('Location: tcwelcome.php');}
                          
                        ?>

           <li></li>
                       
                    </ul>

      </div>
      <div class="page">
              
              <table cellpadding="30" cellspacing="10">
              <tr>
                  <td>TC Name</td>
                  <td><input type="text" tabindex="1" name="name" value="" size="16" /></td>

              </tr>
              <tr>
                  <td>Password</td>
                  <td><input type="password" tabindex="2" name="password" value="" size="16" /></td>
              </tr>

              <tr>
                  <td colspan="2">
                      <input type="submit" tabindex="3" value="Log In" name="tcsubmit" class="subbtn" />
                  </td><td></td>
              </tr>
            </table>


      </div>
       </form>

     <div id="footer">

      </div>
      </div>
  </body>
</html>
