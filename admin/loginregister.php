<?php

session_start();

$db = new PDO("mysql:host=localhost;dbname=exam;charset=utf8","root","");

include "fonksiyon.php";

$alluser = new allusers;

$newstudent = new student;

$newteacher = new teacher;

?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Sınav Sistemi Panel</title>

    <!-- Google Font: Source Sans Pro -->
    <link rel="stylesheet"
          href="https://fonts.googleapis.com/css?family=Source+Sans+Pro:300,400,400i,700&display=fallback">
    <!-- Font Awesome -->
    <link rel="stylesheet" href="../plugins/fontawesome-free/css/all.min.css">
    <!-- icheck bootstrap -->
    <link rel="stylesheet" href="../plugins/icheck-bootstrap/icheck-bootstrap.min.css">
    <!-- Theme style -->
    <link rel="stylesheet" href="../dist/css/adminlte.min.css">
</head>
<body class="hold-transition login-page">
<div class="login-box">
    <!-- /.login-logo -->
    <div class="card card-outline card-primary">
        <div class="card-header text-center">
            <a href="#" class="h1"><b>Online</b>Sınav</a>
        </div>
        <div class="card-body">


            <?php

            @$islem = $_GET["user"];

            switch ($islem):

                case "addteach":

                    $newteacher->addteacher($db);

                    break;

                case "userout";

                    session_destroy();

                    echo '<div class="col-xl-12 col-lg-12 col-md-12 mx-auto"><div class="alert alert-danger">Çıkış Yapılıyor</div></div>';

                    header("refresh:2,url=loginregister.php");

                    break;

                case "newstudent":

                    $newstudent->addstudent($db);

                    break;


                case "stulogin";

                    $newstudent->loginstudentform($db);

                    break;


               /* case "stulogout":

                    $newstudent->logoutstudent($db);

                    break;*/


                default:

                    $alluser->loginform($db);


            endswitch;


            ?>


        </div>
        <!-- /.card-body -->
    </div>
    <!-- /.card -->
</div>
<!-- /.login-box -->

<!-- jQuery -->
<script src="../plugins/jquery/jquery.min.js"></script>
<!-- Bootstrap 4 -->
<script src="../plugins/bootstrap/js/bootstrap.bundle.min.js"></script>
<!-- AdminLTE App -->
<script src="../dist/js/adminlte.min.js"></script>
</body>
</html>
