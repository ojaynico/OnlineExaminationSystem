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

    }else if(isset($_REQUEST['savem']))
{

    if(empty($_REQUEST['cname'])||empty ($_REQUEST['password'])||empty ($_REQUEST['stduidno']))
    {
         $_GLOBALS['message']="Some of the required Fields are Empty.Therefore Nothing is Updated";
    }
    else
    {
     $query="update student set stdname='".htmlspecialchars($_REQUEST['cname'],ENT_QUOTES). "', stduname=" . htmlspecialchars($_REQUEST['stduname']) . "', stdpassword=ENCODE('".htmlspecialchars($_REQUEST['password'],ENT_QUOTES)."','oespass'),stduidno='".htmlspecialchars($_REQUEST['stduidno'],ENT_QUOTES)."',course='".htmlspecialchars($_REQUEST['course'],ENT_QUOTES). "' where stdid='".$_REQUEST['student']."';";
     if(!@executeQuery($query))
        $_GLOBALS['message']=mysql_error();
     else
        $_GLOBALS['message']="Your Profile is Successfully Updated.";
    }
    closedb();

}


?>

<html>
  <head>
    <title>OES-Edit Profile</title>
    <meta http-equiv="Content-Type" content="text/html; charset=UTF-8"/>
    <link rel="stylesheet" type="text/css" href="oes.css"/>
    <script type="text/javascript" src="validate.js" ></script>
    </head>
  <body >
       <?php

        if($_GLOBALS['message']) {
            echo "<div class=\"message\">".$_GLOBALS['message']."</div>";
        }
        ?>
      <div id="container">
      <div class="header">
                <h3 class="headtext"> &nbsp;Online Examination System </h3><hr/>
            </div>
           <form id="editprofile" action="editprofile.php" method="post">
          <div class="menubar">

                        <?php if(isset($_SESSION['stduname'])) {
    
                         ?>
                        <input type="submit" value="LogOut" name="logout" class="subbtn" title="Log Out"/>
                        <input type="submit" value="DashBoard" name="dashboard" class="subbtn" title="Dash Board"/>
                        <input type="submit" value="Save" name="savem" class="subbtn" onclick="validateform('editprofile')" title="Save the changes"/>
                     
      <div class="page">
          <?php
                       
                        $result=executeQuery("select stdid,stdname, stduname ,DECODE(stdpassword,'oespass') as stdpass ,stduidno,course from student where stduname='".$_SESSION['stduname']."';");
                        if(mysql_num_rows($result)==0) {
                           header('Location: stdwelcome.php');
                        }
                        else if($r=mysql_fetch_array($result))
                        {
            
                 ?>
           <table cellpadding="20" cellspacing="20" style="text-align:left;margin-left:15em" >
              <tr>
                  <td>Student Name</td>
                  <td><input type="text" name="cname" value="<?php echo htmlspecialchars_decode($r['stdname'],ENT_QUOTES); ?>" size="16" onkeyup="isalphanum(this)"/></td>

              </tr>
              <td>User Name</td>
                  <td><input type="text" name="stduname" value="<?php echo htmlspecialchars_decode($r['stduname'],ENT_QUOTES); ?>" size="16" onkeyup="isalphanum(this)"/></td>

              </tr>

                      <tr>
                  <td>Password</td>
                  <td><input type="password" name="password" value="<?php echo htmlspecialchars_decode($r['stdpass'],ENT_QUOTES); ?>" size="16" onkeyup="isalphanum(this)" /></td>
                 
              </tr>

              <tr>
                  <td>Student ID</td>
                  <td><input type="text" name="stduidno" value="<?php echo htmlspecialchars_decode($r['stduidno'],ENT_QUOTES); ?>" size="16" /></td>
              </tr>
                       <tr>
                  <td>Course Offered</td>
                  <td><input type="text" name="course" value="<?php echo htmlspecialchars_decode($r['course'],ENT_QUOTES); ?>" size="16" onkeyup="isnum(this)"/></td>
              </tr>

            </table>
<?php
                        closedb();
                        }
                        
                        }
  ?>
      </div>

           </form>
      </div>
  </body>
</html>
