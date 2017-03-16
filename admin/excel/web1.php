<html>
<head><title>Demo</title>
<style type="text/css">
body
{
margin: 0;
padding: 0;
font-family:calibri;
font-size:30px;
background-color:#FFFFFF;
text-align:center;
}
.top-bar
{
width: 100%;
height: auto;
text-align: center;
background-color:#FFF;
border-bottom: 1px solid #000;
margin-bottom: 20px;
}
.inside-top-bar
{
margin-top: 15px;
margin-bottom: -10px;
}
.link
{
font-size: 18px;
text-decoration: none;
background-color: #000;
color: #FFF;
padding: 5px;
}
.link:hover
{
background-color: #FCF3F3;
}
</style>
</head>
<body>

<div class="top-bar">
<div class="inside-top-bar">Import Question Paper from Excelsheet<br><br>
</div>
</div>
<div style="text-align:left; border:2px solid #333333; width:300px; margin:0 auto; padding:10px;">


<form name="import" method="post" enctype="multipart/form-data">
<input type="file" name="file" /><br /><br/>
<input type="submit" name="submit" value="Submit" />
</form>

</div>

<?php
if(isset($_POST["submit"]))
{
$connect = mysqli_connect("localhost", "root", "admin", "world");  
 include ("PHPExcel/IOFactory.php");  
 $html="<center><table border='1' cellspacing=0 cellpadding=4 width=60%>";  
$file = $_FILES['file']['tmp_name'];
 $objPHPExcel = PHPExcel_IOFactory::load($file); 
  foreach ($objPHPExcel->getWorksheetIterator() as $worksheet)   
 {  
      $highestRow = $worksheet->getHighestRow();  
      for ($row=2; $row<=$highestRow; $row++)  
      {  
           $html.="<tr>";
           $testid = mysqli_real_escape_string($connect, $worksheet->getCellByColumnAndRow(0, $row)->getValue());  
           $qnid = mysqli_real_escape_string($connect, $worksheet->getCellByColumnAndRow(1, $row)->getValue());  
           $question = mysqli_real_escape_string($connect, $worksheet->getCellByColumnAndRow(2, $row)->getValue());  
           $optiona = mysqli_real_escape_string($connect, $worksheet->getCellByColumnAndRow(3, $row)->getValue()); 
           $optionb = mysqli_real_escape_string($connect, $worksheet->getCellByColumnAndRow(4, $row)->getValue());  
           $optionc = mysqli_real_escape_string($connect, $worksheet->getCellByColumnAndRow(5, $row)->getValue()); 
           $optiond = mysqli_real_escape_string($connect, $worksheet->getCellByColumnAndRow(6, $row)->getValue());  
           $correctanswer = mysqli_real_escape_string($connect, $worksheet->getCellByColumnAndRow(7, $row)->getValue()); 
           $marks = mysqli_real_escape_string($connect, $worksheet->getCellByColumnAndRow(8, $row)->getValue());  

           $sql = "INSERT INTO question(testid,qnid,question,optiona,optionb,optionc,optiond,correctanswer,marks) VALUES ('".$testid."', '".$qnid."', '".$question."', '".$optiona."', '".$optionb."', '".$optionc."', '".$optiond."', '".$correctanswer."', '".$marks."')";  
           mysqli_query($connect, $sql);  
           $html.= '<td>'.$testid.'</td>';  
           $html .= '<td>'.$qnid.'</td>';  
           $html .= '<td>'.$question.'</td>';  
           $html .= '<td>'.$optiona.'</td>';  
           $html .= '<td>'.$optionb.'</td>';  
           $html .= '<td>'.$optionc.'</td>';  
           $html .= '<td>'.$optiond.'</td>';  
           $html .= '<td>'.$correctanswer.'</td>';  
           $html .= '<td>'.$marks.'</td>';  
           $html .= "</tr>";  
      }  
 }  
 $html .= '</table></center>';  
 echo $html;  
 echo '<br /><h2>Data Inserted into Database</h2>';  
}
?>

</body>
</html>
