<?php

session_start();

$db = new PDO("mysql:host=localhost;dbname=exam;charset=utf8", "root", "");

include "fonksiyon.php";

$loginalluser = new allusers;

$loginnewstudent = new student;

$loginnewteacher = new teacher;

?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Öğrenci Giriş</title>

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
            <a href="#" class="h1"><b>Öğrenci</b>Giriş</a>
        </div>
        <div class="card-body">


            <?php

            @$islem = $_GET["stu"];

            switch ($islem):

                case "stulogout";

                    session_destroy();

                    echo '<div class="col-xl-12 col-lg-12 col-md-12 mx-auto"><div class="alert alert-danger">Çıkış Yapılıyor</div></div>';

                    header("refresh:2,url=studentlogin.php");

                    break;

                default:

                    $loginnewstudent->loginstudentform($db);


            endswitch;



            ?>


        </div>
    </div>
</div>

<script src="../plugins/jquery/jquery.min.js"></script>
<script src="../plugins/bootstrap/js/bootstrap.bundle.min.js"></script>
<script src="../dist/js/adminlte.min.js"></script>
</body>
</html>
