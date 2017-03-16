<html>
<title>Sai Pali Institute of Information & Technology</title>
<meta charset="utf-8">
<meta http-equiv="X-UA-Compatible" content="IE=edge">
<meta name="viewport" content="width=device-width, initial-scale=1">
<meta name="description" content="">
<link href="globe.png" rel="shortcut icon">
<?php
date_default_timezone_set("Asia/Calcutta");
//echo date_default_timezone_get();
?>


<?php
$conn = new PDO('mysql:host=localhost; dbname=saipali', 'root', '') or die(mysql_error());
if (isset($_POST['submit']) != "") {
    $name = $_FILES['photo']['name'];
    $size = $_FILES['photo']['size'];
    $type = $_FILES['photo']['type'];
    $temp = $_FILES['photo']['tmp_name'];
    $date = date('Y-m-d H:i:s');
    $caption1 = $_POST['caption'];
    $link = $_POST['link'];

    move_uploaded_file($temp, "files/" . $name);

    $query = $conn->query("INSERT INTO upload (name,date) VALUES ('$name','$date')");
    if ($query) {
        header("location:index.php");
    } else {
        die(mysql_error());
    }
}
?>


<html>
<head>
    <link rel="stylesheet" type="text/css" href="../css/materialize.min.css">
    <link rel="stylesheet" type="text/css" href="../css/datatables.min.css">
    <link rel="stylesheet" type="text/css" href="../css/icons/icons.css">
  <link href="https://fonts.googleapis.com/icon?family=Material+Icons" rel="stylesheet">

    <link rel="stylesheet" href="../css/style.css">
</head>

<body>

<?php include('dbcon.php'); ?>
<div class="section">
    <div class="container">
        <div class="row">
            <h1 class="center green-text text-darken-4"><i class=" small material-icons ">school</i> SITM Online Examination System</h1>
            <div class="col l4"></div>
            <div class="col l4"></div>
            <div class="col l4 right">
                <a href="../stdwelcome.php">
                    <button class="btn green white-text waves-effect waves-light btn" data-position="bottom" data-delay="50" data-tooltip="Back" ><i class="material-icons right">replay</i>Back</button>
                </a>
            </div>
        </div>
    </div>
</div>

<div class="container">
    <div class="row">
                <div class="table-responsive">
                    <form method="post" action="delete.php">
                        <table class="table bordered striped highlight responsive-table dataTables-example" id="example">
                            <thead class="blue white-text center">
                            <tr>
                                <th>ID</th>
                                <th>FILE NAME</th>
                                <th>COURSE</th>
                                <th>SEMESTER</th>
                                <th>DATE</th>
                                <th class="center">VIEW</th>
                            </tr>
                            </thead>
                            <tbody>
                            <?php
                            $query = mysql_query("select * from upload ORDER BY id DESC") or die(mysql_error());
                            while ($row = mysql_fetch_array($query)) {
                                $id = $row['id'];
                                $name = $row['name'];
                                $date = $row['date'];
                                ?>
                                <tr>
                                    <td><?php echo $row['id'] ?></td>
                                    <td><?php echo $row['name'] ?></td>
                                    <td><?php echo $row['course'] ?></td>
                                    <td><?php echo $row['semester'] ?></td>
                                    <td><?php echo $row['date'] ?></td>
                                    <td class="center">
                                        <a href="<?php echo "pdfreader/web/viewer.html?file=%2Foes/theoryexam/files/" . $row['name']; ?>"
                                           title="click to view"><i class="big green-text material-icons">visibility</i></a>
                                    </td>
                                </tr>

                            <?php } ?>
                            </tbody>
                        </table>
                </div>
    </div>
</div>
<script src="../js/jquery-2.1.1.js" type="text/javascript"></script>
<script src="../js/materialize.min.js" type="text/javascript"></script>
<script type="text/javascript" charset="utf-8" language="javascript" src="../js/datatables.min.js"></script>
<script>
    $(document).ready(function () {
        $('select').material_select();
    });

    $(document).ready(function () {
        $('.dataTables-example').DataTable({
            dom: '<"html5buttons"B>lTfgitp',
            buttons: [
                {extend: 'copy'},
                {extend: 'csv'},
                {extend: 'excel', title: 'ExampleFile'},
                {extend: 'pdf', title: 'ExampleFile'},

                {
                    extend: 'print',
                    customize: function (win) {
                        $(win.document.body).addClass('white-bg');
                        $(win.document.body).css('font-size', '10px');

                        $(win.document.body).find('table')
                            .addClass('compact')
                            .css('font-size', 'inherit');
                    }
                }
            ]

        });


    });
</script>
</body>
</html>


