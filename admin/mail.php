<?php

session_start();

$db = new PDO("mysql:host=localhost;dbname=exam;charset=utf8", "root", "");

include "fonksiyon.php";

$nowstudent = new student;

$exams_student = new onlineexam;

$teacher = new teacher;

$examresult = new result();

$getexamname = $db->prepare("select * from mail where mail_idstu=".$_GET["id"]);
$getexamname->execute();

while ($lastgetexamname = $getexamname->fetch(PDO::FETCH_ASSOC)):

?>

            <h1><?php echo $lastgetexamname["mail_stuname"]; ?> Öğrencisine Ait Tets Sonuçları</h1>
			<p>Tetsin Adı : <?php echo $lastgetexamname["mail_examname"]; ?></p>
			<p>Tetsin Doğru Cevap Sayısı : <?php echo $lastgetexamname["mail_correct"]; ?></p>
			<p>Tetsin Yalnış Cevap Sayısı : <?php echo $lastgetexamname["mail_wrong"]; ?></p>
			<p>Tetsin Net Cevap Sayısı : <?php echo $lastgetexamname["mail_net"]; ?></p>

<?php

    endwhile;

?>