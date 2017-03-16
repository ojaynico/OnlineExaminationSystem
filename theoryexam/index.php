<html>
<title>Sai Pali Institute of Information & Technology</title>
<meta charset="utf-8">
<meta http-equiv="X-UA-Compatible" content="IE=edge">
<meta name="viewport" content="width=device-width, initial-scale=1">
<meta name="description" content="">
<link href="globe.png" rel="shortcut icon">
<head>
    <?php
    date_default_timezone_set("Asia/Calcutta");
    //echo date_default_timezone_get();
    ?>


    <?php
    $conn = new PDO('mysql:host=localhost; dbname=saipali', 'root', '') or die(mysql_error());
    if (isset($_POST['submit']) != "") {

        $semester = $_POST['semester'];
        $course = $_POST['course'];

        $name = $_FILES['photo']['name'];
        $size = $_FILES['photo']['size'];
        $type = $_FILES['photo']['type'];
        $temp = $_FILES['photo']['tmp_name'];
        $date = date('Y-m-d H:i:s');


        move_uploaded_file($temp, "files/" . $name);

        $query = $conn->query("INSERT INTO upload (name,course,semester,date) VALUES ('$name','$course','$semester','$date')");
        if ($query) {
            header("location:index.php");
        } else {
            die(mysql_error());
        }
    }
    ?>

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
                <a href="../admin/admwelcome.php">
                    <button class="btn green white-text waves-effect waves-light btn" data-position="bottom" data-delay="50" data-tooltip="Back" ><i class="material-icons right">replay</i>Back</button>
                </a>
            </div>
        </div>
    </div>
</div>

<div class="row">
    <div class="col l4"></div>
    <div class="col l4">
        <form enctype="multipart/form-data" action="" id="wb_Form1" class="card" name="form" method="post">
            <div class="file-field input-field">
                <div class="btn ">
                    <span>File</span>
                    <input type="file" name="photo" id="photo" required="required">
                </div>
                <div class="file-path-wrapper">
                    <input class="file-path validate" type="text">
                </div>
            </div>
            <div class="input-field">
                <select name="course">
                    <option value="" disabled selected>Choose your option</option>
                    <option value="VFX">VFX</option>
                    <option value="SE">SE</option>
                    <option value="IMS">IMS</option>
                    <option value="CERTIFICATE">Certificate</option>
                </select>
                <label>Course</label>
            </div>
            <div class="input-field">
                <select name="semester">
                    <option value="" disabled selected>Choose your option</option>
                    <option value="I">Semester I</option>
                    <option value="II">Semester II</option>
                    <option value="III">Semester III</option>
                    <option value="IV">Semester IV</option>
                    <option value="certificate">Certificate</option>
                </select>
                <label>Semester</label>
            </div>
            <div class="input-field center">
                <button input type="submit" value="SUBMIT" name="submit" class="btn red white-text btn tooltipped  white-text waves-effect waves-light btn" data-position="bottom" data-delay="50" data-tooltip="submit" ><i class="material-icons right">send</i>Submit</button>
            </div>
        </form>
    </div>
    <div class="col l4"></div>
</div>


    <div class="container">
        <div class="row">
                    <div class="table-responsive">
                        <form method="post" action="delete.php">
                            <table class="table bordered striped highlight responsive-table dataTables-example">
                                <thead class="blue white-text center">
                                <tr>
                                    <th>ID</th>
                                    <th>FILE NAME</th>
                                    <th>COURSE</th>
                                    <th>SEMESTER</th>
                                    <th>DATE</th>
                                    <th class="center">VIEW EXAM</th>
                                    <th class="center">REMOVE</th>
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
                                        <td class="center">
                                            <a href="delete.php?del=<?php echo $row['id'] ?>"><i class="big red-text material-icons">delete</i></a>
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


