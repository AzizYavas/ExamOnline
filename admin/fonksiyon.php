<?php

$db = new PDO("mysql:host=localhost;dbname=exam;charset=utf8", "root", "");
use PHPMailer\PHPMailer\PHPMailer;

//TODO :: ÖĞRETMEN VE ÖĞRENCİ KAYDETMEDE KULLANICI ADINI UNİQUE OLARAK KAYDETTİR


class allusers
{

    function loginform($logindb)
    {

        @$buton = $_POST["enter"];
        @$username = $_POST["username"];
        @$password = $_POST["password"];
        @$stade = $_POST["stade"];

        if ($buton) :

            $securitypass = md5(sha1(md5($password)));

            if (empty($username) || empty($password) || empty($stade)):

                /* $gk = $logindb->prepare("select * from examuser where username='$username' && password='$securitypass' && stade='$stade'");
                 $gk->execute();
                 $son = $gk->get_result();


                 $stadeuser = $logindb->prepare("select * from examuser where stade='$stade'");
                 $stadeuser->execute();
                 $laststadeuser=$stadeuser->fetch_assoc();*/

                echo "<BR><BR><h3>!!! BOŞ YER KALAMAZ !!!</h3>";
                header("refresh:2,url=loginregister.php");

            else:


                $gk = $logindb->prepare("SELECT * FROM examuser WHERE username=:username && password=:password && stade=:stade");
                $gk->bindParam(":username", $username);
                $gk->bindParam(":password", $securitypass);
                $gk->bindParam(":stade", $stade);
                $gk->execute();
                $gkson = $gk->fetch(PDO::FETCH_ASSOC);


                $stadeuser = $logindb->prepare("SELECT * FROM examuser where stade=:stade");
                $stadeuser->bindParam(":stade", $stade);
                $stadeuser->execute();
                $laststadeuser = $stadeuser->fetch(PDO::FETCH_ASSOC);

                if ($gkson["stade"] !== $stade && $gkson["username"] !== $username && $gkson["password"] !== $securitypass):

                    echo "<BR><BR><h3>BİLGİLER UYUŞMADI TEKRAR KONTROL EDİN</h3>";
                    header("refresh:2,url=loginregister.php");

                else:

                    if ($laststadeuser["stade"] == 3):

                        /* setcookie("username", $username, time() + 60 * 60 * 24);
                         setcookie("password", $securitypass, time() + 60 * 60 * 24);
                         setcookie("stade", $stade, time() + 60 * 60 * 24);*/

                        $_SESSION["username"] = $username;
                        $_SESSION["password"] = $securitypass;
                        $_SESSION["stade"] = $stade;

                        echo "<BR><BR><h3>ÖĞRETMEN GİRİŞ BAŞARILI</h3>";
                        header("refresh:2,url=panel.php");

                    elseif ($laststadeuser["stade"] == 1):

                        /* setcookie("username", $username, time() + 60 * 60 * 24);
                         setcookie("password", $securitypass, time() + 60 * 60 * 24);
                         setcookie("stade", $stade, time() + 60 * 60 * 24);*/

                        $_SESSION["username"] = $username;
                        $_SESSION["password"] = $securitypass;
                        $_SESSION["stade"] = $stade;

                        echo "<BR><BR><h3>ADMİN GİRİŞ BAŞARILI</h3>";
                        header("refresh:2,url=panel.php");

                    else:

                        echo "<BR><BR><h3>Başarısız</h3>";
                        header("refresh:2,url=loginregister.php");


                    endif;


                    /*                    if ($laststadeuser["stade"] === 1):

                                            echo "1";

                                            setcookie("username", $username, time() + 60 * 60 * 24);
                                            setcookie("password", $securitypass, time() + 60 * 60 * 24);
                                            setcookie("stade", $stade, time() + 60 * 60 * 24);

                                            echo "<BR><BR><h3>ADMİN</h3>";
                                            header("refresh:2,url=index.php");

                                        elseif ($laststadeuser["stade"] === 2):

                                            echo "2";

                                            echo "<BR><BR><h3>ÖĞRENCİ</h3>";
                                            header("refresh:2,url=index.php");

                                        elseif ($laststadeuser["stade"] === 3):

                                            echo "3";

                                            echo "<BR><BR><h3>ÖĞRETMEN</h3>";
                                            header("refresh:2,url=index.php");

                                        endif;*/
                endif;


            endif;

        else:

            ?>

            <form action="#" method="post">

                <h3>Öğretmen Giriş Formu</h3>
                <br>
                <div class="input-group mb-3">
                    <input type="text" name="username" class="form-control" placeholder="Kullanıcı Adı">
                    <div class="input-group-append">
                        <div class="input-group-text">
                            <span class="fas fa-user"></span>
                        </div>
                    </div>
                </div>
                <div class="input-group mb-3">
                    <input type="password" name="password" class="form-control" placeholder="Şifreniz">
                    <div class="input-group-append">
                        <div class="input-group-text">
                            <span class="fas fa-lock"></span>
                        </div>
                    </div>
                </div>

                <div class="form-group">
                    <select name="stade" class="form-control">
                        <option value="1">Admin</option>
                        <option value="3">Öğretmen</option>
                    </select>
                </div>

                <div class="row">
                    <div class="col-md-12">
                        <input type="submit" name="enter" class="btn btn-primary btn-block" value="Giriş Yap">
                    </div>
                    <br>
                    <div class="col-md-12 text-center">
                        <a href="studentlogin.php">Öğrenci Giriş</a>
                        <br>
                        <a href="loginregister.php?user=newstudent" class="text-center">Öğrenci Hesabı Aç</a>
                        <br>
                        <a href="loginregister.php?user=addteach" class="text-center">Öğretmen Hesabı Aç</a>

                    </div>
                </div>
            </form>

        <?php

        endif;

    }

    function adduser($adduserdb)
    {

        @$buton = $_POST["kulekle"];
        @$kulad = $_POST["username"];
        @$mail = $_POST["mail"];
        @$pass = $_POST["pass"];
        @$pass_again = $_POST["pass_again"];
        @$departmen = $_POST["depmt"];
        @$stade = 3;

        if ($buton):

            if ($kulad != "" & $mail != "" && $pass != "" & $pass_again != "" & $stade != "") :

                $pass = md5(sha1(md5($pass)));
                $pass_again = md5(sha1(md5($pass_again)));

                $ekle = "insert into examuser (username,password,password_again,mail,stade) VALUES ('$kulad','$pass','$pass_again','$mail','$stade')";

                $ekleson = $adduserdb->prepare($ekle);
                $ekleson->execute();

                echo '<div class="col-md-12 text-center" style="margin-top:20px;">
					<div class="alert alert-info">		
						KULLANICI EKLENDİ
					</div>
					</div>';

                header("refresh:2,url=loginregister.php");

            else:

                echo '<div class="col-md-12 text-center" style="margin-top:20px;">
					<div class="alert alert-danger">		
						TÜM BİLGİLER DOLU OLMALI
					</div>
					</div>';

                header("refresh:2,url=loginregister.php?user=adduser");

            endif;


        else:

            ?>

            <form action="" method="post">

                <div class="input-group mb-3">
                    <input type="text" name="username" class="form-control" placeholder="Kullanıcı Adınız...">
                    <div class="input-group-append">
                        <div class="input-group-text">
                            <span class="fas fa-envelope"></span>
                        </div>
                    </div>
                </div>

                <div class="input-group mb-3">
                    <input type="text" name="mail" class="form-control" placeholder="Mail Adresiniz...">
                    <div class="input-group-append">
                        <div class="input-group-text">
                            <span class="fas fa-envelope"></span>
                        </div>
                    </div>
                </div>

                <div class="input-group mb-3">
                    <input type="text" name="pass" class="form-control" placeholder="Şifreniz...">
                    <div class="input-group-append">
                        <div class="input-group-text">
                            <span class="fas fa-lock"></span>
                        </div>
                    </div>
                </div>

                <div class="input-group mb-3">
                    <input type="text" name="pass_again" class="form-control" placeholder="Şifreniz Tekrar...">
                    <div class="input-group-append">
                        <div class="input-group-text">
                            <span class="fas fa-lock"></span>
                        </div>
                    </div>
                </div>

                <div class="form-group">
                    <select name="stade" class="form-control">
                        <option value="1">Admin</option>
                        <option value="2">Öğrenci</option>
                        <option value="3">Öğretmen</option>
                    </select>
                </div>

                <div class="row">
                    <div class="col-md-12">
                        <input class="btn btn-primary btn-block" type="submit" name="kulekle" value="Kaydol">
                    </div>
                </div>

            </form>


        <?php

        endif;


    }


}

class student
{

    function loginstudentform($dbstuform)
    {

        @$buton = $_POST["enter"];
        @$stuname = $_POST["name"];
        @$stupassword = $_POST["password"];
        @$stuclassroom = $_POST["classroom"];
        @$studepartment = $_POST["department"];
        @$stumail = $_POST["mail"];

        @$stade = 2;

        if ($buton) :

            $securitypass = md5(sha1(md5($stupassword)));

            if (empty($stuname) || empty($stupassword) || empty($stade)):

                /* $gk = $logindb->prepare("select * from examuser where username='$username' && password='$securitypass' && stade='$stade'");
                 $gk->execute();
                 $son = $gk->get_result();


                 $stadeuser = $logindb->prepare("select * from examuser where stade='$stade'");
                 $stadeuser->execute();
                 $laststadeuser=$stadeuser->fetch_assoc();*/

                echo "<BR><BR><h3>!!! BOŞ YER KALAMAZ !!!</h3>";
                header("refresh:2,url=loginregister.php");

            else:

                $st = $dbstuform->prepare("select * from allstudent where stu_name=:stu_name && password=:password && stade=:stade");
                $st->bindParam(":stu_name", $stuname);
                $st->bindParam(":password", $securitypass);
                $st->bindParam(":stade", $stade);
                $st->execute();
                $stson = $st->fetch(PDO::FETCH_ASSOC);


                if ($stson["stu_name"] !== $stuname && $stson["password"] !== $securitypass && $stson["stade"] !== $stade):

                    echo "<BR><BR><h3>ÖĞRENCİ BİLGİLER UYUŞMADI TEKRAR KONTROL EDİN</h3>";
                    header("refresh:2,url=studentlogin.php");

                else:

                    $_SESSION["ownstuid"] = $stson["id"];
                    $_SESSION["stu_name"] = $stuname;
                    $_SESSION["stu_class"] = $stuclassroom;
                    $_SESSION["stu_depart"] = $studepartment;
                    $_SESSION["stu_mail"] = $stumail;
                    $_SESSION["password"] = $securitypass;
                    $_SESSION["stade"] = $stade;

                    /* setcookie("username", $stuname, time() + 60 * 60 * 24);
                     setcookie("stu_class", $stuclassroom, time() + 60 * 60 * 24);
                     setcookie("stu_depart", $studepartment, time() + 60 * 60 * 24);
                     setcookie("stu_mail", $stumail, time() + 60 * 60 * 24);
                     setcookie("password", $securitypass, time() + 60 * 60 * 24);
                     setcookie("stade", $stade, time() + 60 * 60 * 24);*/

                    echo "<BR><BR><h3>ÖĞRENCİ GİRİŞ BAŞARILI</h3>";
                    header("refresh:2,url=index.php");


                endif;

            endif;

        else:

            ?>

            <form action="#" method="post">

                <h3>Öğrenci Giriş Formu</h3>
                <br>
                <div class="input-group mb-3">
                    <input type="text" name="name" class="form-control" placeholder="Öğrencinin Adı"
                           required="required">
                    <div class="input-group-append">
                        <div class="input-group-text">
                            <span class="fas fa-user"></span>
                        </div>
                    </div>
                </div>
                <div class="input-group mb-3">
                    <input type="password" name="password" class="form-control" placeholder="Öğrenci Şifresi"
                           required="required">
                    <div class="input-group-append">
                        <div class="input-group-text">
                            <span class="fas fa-lock"></span>
                        </div>
                    </div>
                </div>

                <div class="row">
                    <div class="col-md-12">
                        <input type="submit" name="enter" class="btn btn-outline-danger btn-block" value="Giriş Yap">
                    </div>
                </div>
            </form>

        <?php


        endif;


    }

    function liststudent($dbstudent)
    {

        $liststudent = $dbstudent->prepare("SELECT * FROM allstudent WHERE stade=2");
        $liststudent->execute();


        ?>

        <div class="col-md-12">
            <div class="card">
                <div class="card-header">
                    <h3 class="card-title">Öğrenciler</h3>
                </div>
                <div class="card-body">
                    <table class="table table-bordered">
                        <thead>
                        <tr>
                            <th style="width: 10px">#</th>
                            <th>Öğrenci Adı</th>
                            <th>Öğrenci Sınıfı</th>
                            <th>Öğrenci Bölümü</th>
                            <th>Mail</th>
                            <th>Detay</th>

                        </tr>
                        </thead>
                        <tbody>
                        <?php

                        while ($liststudentend = $liststudent->fetch(PDO::FETCH_ASSOC)):

                            ?>
                            <tr>
                                <td><?php echo $liststudentend["id"]; ?></td>
                                <td><?php echo $liststudentend["stu_name"]; ?></td>
                                <td><?php echo $liststudentend["classroom"]; ?></td>
                                <td><?php echo $liststudentend["department"]; ?></td>
                                <td><?php echo $liststudentend["mail"]; ?></td>
                                <td style="text-align: right">
                                    <a href="panel.php?panel=mailadd&id=<?php echo $liststudentend["id"]; ?>"
                                       class="btn btn-outline-primary btn-md">
                                        <i class="fa fa-plus"></i></a>
                                </td>
                            </tr>

                        <?php

                        endwhile;

                        ?>
                        </tbody>
                    </table>
                </div>
                <div class="card-footer clearfix">
                    <ul class="pagination pagination-sm m-0 float-right">
                        <li class="page-item"><a class="page-link" href="#">«</a></li>
                        <li class="page-item"><a class="page-link" href="#">1</a></li>
                        <li class="page-item"><a class="page-link" href="#">2</a></li>
                        <li class="page-item"><a class="page-link" href="#">3</a></li>
                        <li class="page-item"><a class="page-link" href="#">»</a></li>
                    </ul>
                </div>
            </div>

        </div>

        <?php


    }

    function addstudent($adddb)
    {

        @$buton = $_POST["addstd"];
        @$name = $_POST["stu_name"];
        @$surname = $_POST["stu_surname"];
        @$mail = $_POST["stu_mail"];
        @$pass = $_POST["stu_pass"];
        @$pass_again = $_POST["pass_again"];
        @$class = $_POST["classroom"];
        @$stu_dep = $_POST["department"];
        @$stade = 2;

        if ($buton):

            if ($name != "" & $mail != "" && $pass != "" & $pass_again != "" & $stade != "" & $class != "" & $surname != "" & $stu_dep != "") :

                $pass = md5(sha1(md5($pass)));
                $pass_again = md5(sha1(md5($pass_again)));

                $ekleson = $adddb->prepare("insert into allstudent (stu_name,stu_surname,mail,password,pass_again,classroom,department,stade) VALUES ('$name','$surname','$mail','$pass','$pass_again','$class','$stu_dep','$stade')");

                $ekleson->execute();

                echo '<div class="col-md-12 text-center" style="margin-top:20px;">
					<div class="alert alert-info">		
						ÖĞRENCİ KULLANICI EKLENDİ
					</div>
					</div>';

                header("refresh:2,url=loginregister.php");

            else:

                echo '<div class="col-md-12 text-center" style="margin-top:20px;">
					<div class="alert alert-danger">		
						TÜM BİLGİLER DOLU OLMALI
					</div>
					</div>';

                header("refresh:2,url=loginregister.php?user=newstudent");


            endif;


        else:

            ?>

            <form action="" method="post">

                <h3>Öğrenci Kaydolma Formu</h3>
                <br>
                <div class="input-group mb-3">
                    <input type="text" name="stu_name" class="form-control" placeholder="Öğrenci Adı...">
                    <div class="input-group-append">
                        <div class="input-group-text">
                            <span class="fas fa-user"></span>
                        </div>
                    </div>
                </div>

                <div class="input-group mb-3">
                    <input type="text" name="stu_surname" class="form-control" placeholder="Öğrenci Soyadı...">
                    <div class="input-group-append">
                        <div class="input-group-text">
                            <span class="fas fa-user"></span>
                        </div>
                    </div>
                </div>

                <div class="input-group mb-3">
                    <input type="text" name="stu_mail" class="form-control" placeholder="Mail Adresiniz...">
                    <div class="input-group-append">
                        <div class="input-group-text">
                            <span class="fas fa-box-open"></span>
                        </div>
                    </div>
                </div>

                <div class="input-group mb-3">
                    <input type="text" name="stu_pass" class="form-control" placeholder="Şifreniz...">
                    <div class="input-group-append">
                        <div class="input-group-text">
                            <span class="fas fa-lock"></span>
                        </div>
                    </div>
                </div>

                <div class="input-group mb-3">
                    <input type="text" name="pass_again" class="form-control" placeholder="Şifreniz Tekrar...">
                    <div class="input-group-append">
                        <div class="input-group-text">
                            <span class="fas fa-lock"></span>
                        </div>
                    </div>
                </div>

                <div class="input-group mb-3">
                    <input type="text" name="classroom" class="form-control" placeholder="Sınıfınız...">
                    <div class="input-group-append">
                        <div class="input-group-text">
                            <span class="fas fa-user-check"></span>
                        </div>
                    </div>
                </div>

                <div class="form-group">
                    <span>Bölümünüz</span>
                    <select name="department" class="form-control">
                        <option value="Sayısal">Sayısal</option>
                        <option value="Eşit Ağırlık">Eşit Ağırlık</option>
                        <option value="Sözel">Sözel</option>
                        <option value="Dil">Dil</option>
                    </select>
                </div>

                <div class="row">
                    <div class="col-md-12">
                        <input class="btn btn-primary btn-block" type="submit" name="addstd" value="Kaydol">
                    </div>
                </div>

            </form>


        <?php

        endif;

    }

    function familymail($dbmail){

        if(isset($_GET["id"])):

        if (@$_POST["upda"]):

                $mail = $_POST["fam_mail"];

                $exam_update=$dbmail->prepare("UPDATE allstudent SET family_mail=:family_mail WHERE id=".$_GET["id"]);
                $exam_update->bindParam(':family_mail',$mail);

                if ($exam_update->execute()):

                echo "oldu";

                else:

                echo "başarısız";

                endif;

                else:

                ?>

                <div class="col-md-12">
                <div class="card card-success">
                    <div class="card-header">
                        <h3 class="card-title">Mail Ekle</h3>
                    </div>

                    <form method="post">
                        <div class="card-body">

                            <div class="form-group">
                                <label for="exampleInputEmail1">Velinin Maili</label>
                                <input type="text" class="form-control" name="fam_mail">
                            </div>

                        </div>
                        <div class="card-footer">
                            <input type="submit" name="upda" value="Mail Ekle">
                        </div>
                    </form>
                </div>
            </div>



                <?php

        endif;
        endif;


    }

}

class teacher
{

    function listteacher($dbteacher)
    {

        $listteach = $dbteacher->prepare("SELECT * FROM examuser WHERE stade=3");
        $listteach->execute();

        ?>

        <div class="col-md-12">
            <div class="card">
                <div class="card-header">
                    <h3 class="card-title">Öğretmenler</h3>
                </div>
                <div class="card-body">
                    <table class="table table-bordered">
                        <thead>
                        <tr>
                            <th style="width: 10px">#</th>
                            <th>Öğretmen Adı</th>
                            <th>Mail</th>
                            <th>Detay</th>
                            <th>Branş</th>

                        </tr>
                        </thead>
                        <tbody>
                        <?php

                        while ($listallteach = $listteach->fetch(PDO::FETCH_ASSOC)):

                            ?>
                            <tr>
                                <td><?php echo $listallteach["id"]; ?></td>
                                <td><?php echo $listallteach["username"]; ?></td>
                                <td><?php echo $listallteach["mail"]; ?></td>
                                <td>Branş Gelecek</td>
                                <td style="text-align: right">
                                    <a href="#"
                                       class="btn btn-outline-primary btn-md">
                                        <i class="fa fa-book"></i></a>
                                </td>

                                <!--İlerleme Barı  <td>
                                     <div class="progress progress-xs">
                                         <div class="progress-bar progress-bar-danger" style="width: 55%"></div>
                                     </div>
                                 </td>-->
                            </tr>

                        <?php

                        endwhile;

                        ?>
                        </tbody>
                    </table>
                </div>
                <div class="card-footer clearfix">
                    <ul class="pagination pagination-sm m-0 float-right">
                        <li class="page-item"><a class="page-link" href="#">«</a></li>
                        <li class="page-item"><a class="page-link" href="#">1</a></li>
                        <li class="page-item"><a class="page-link" href="#">2</a></li>
                        <li class="page-item"><a class="page-link" href="#">3</a></li>
                        <li class="page-item"><a class="page-link" href="#">»</a></li>
                    </ul>
                </div>
            </div>

        </div>


        <?php


    }

    function addteacher($dbaddteach)
    {

        @$buton = $_POST["kulekle"];
        @$kulad = $_POST["username"];
        @$mail = $_POST["mail"];
        @$pass = $_POST["pass"];
        @$pass_again = $_POST["pass_again"];
        @$departmen = $_POST["depmt"];
        @$stade = 3;

        if ($buton):

            if ($kulad != "" & $mail != "" && $pass != "" & $pass_again != "" & $stade != "" & $departmen != "") :

                $pass = md5(sha1(md5($pass)));
                $pass_again = md5(sha1(md5($pass_again)));

                $ekle = "insert into examuser (username,password,password_again,mail,stade,department) VALUES ('$kulad','$pass','$pass_again','$mail','$stade','$departmen')";

                $ekleson = $dbaddteach->prepare($ekle);
                $ekleson->execute();

                echo '<div class="col-md-12 text-center" style="margin-top:20px;">
					<div class="alert alert-info">		
						ÖĞRETMEN KULLANICI EKLENDİ
					</div>
					</div>';

                header("refresh:2,url=loginregister.php");

            else:

                echo '<div class="col-md-12 text-center" style="margin-top:20px;">
					<div class="alert alert-danger">		
						TÜM BİLGİLER DOLU OLMALI
					</div>
					</div>';

                header("refresh:2,url=loginregister.php?user=addteach");

            endif;


        else:

            ?>


            <form action="" method="post">

                <div class="input-group mb-3">
                    <input type="text" name="username" class="form-control" placeholder="Kullanıcı Adınız...">
                    <div class="input-group-append">
                        <div class="input-group-text">
                            <span class="fas fa-envelope"></span>
                        </div>
                    </div>
                </div>

                <div class="input-group mb-3">
                    <input type="text" name="depmt" class="form-control" placeholder="Bölümünüz...">
                    <div class="input-group-append">
                        <div class="input-group-text">
                            <span class="fas fa-envelope"></span>
                        </div>
                    </div>
                </div>

                <div class="input-group mb-3">
                    <input type="text" name="mail" class="form-control" placeholder="Mail Adresiniz...">
                    <div class="input-group-append">
                        <div class="input-group-text">
                            <span class="fas fa-envelope"></span>
                        </div>
                    </div>
                </div>

                <div class="input-group mb-3">
                    <input type="text" name="pass" class="form-control" placeholder="Şifreniz...">
                    <div class="input-group-append">
                        <div class="input-group-text">
                            <span class="fas fa-lock"></span>
                        </div>
                    </div>
                </div>

                <div class="input-group mb-3">
                    <input type="text" name="pass_again" class="form-control" placeholder="Şifreniz Tekrar...">
                    <div class="input-group-append">
                        <div class="input-group-text">
                            <span class="fas fa-lock"></span>
                        </div>
                    </div>
                </div>

                <div class="row">
                    <div class="col-md-12">
                        <input class="btn btn-primary btn-block" type="submit" name="kulekle" value="Kaydol">
                    </div>
                </div>

            </form>


        <?php

        endif;


    }


}

class onlineexam
{

    function allexams($dballexams)
    {

        $all_exams = $dballexams->prepare("SELECT * FROM allexams");
        $all_exams->execute();

        ?>

        <div class="col-md-12">
            <div class="card">
                <div class="card-header">
                    <h3 class="card-title">Sistemde Kayıtlı Sorular</h3>
                </div>
                <div class="card-body">
                    <div class="card-header text-right">
                        <a href="panel.php?panel=addexam" class="btn btn-md btn-outline-secondary">+ Sınav Ekle</a>
                    </div>
                    <table class="table table-bordered">
                        <thead>
                        <tr>
                            <th style="width: 10px">#</th>
                            <th>Sınav Kodu</th>
                            <th>Kategorisi</th>
                            <th>Hazırlayan Öğretmen</th>
                            <th>Soru Ekle</th>
                        </tr>
                        </thead>
                        <tbody>
                        <?php

                        while ($liststudentend = $all_exams->fetch(PDO::FETCH_ASSOC)):

                            ?>
                            <tr>
                                <td><?php echo $liststudentend["id"]; ?></td>
                                <td><?php echo $liststudentend["exam_id"]; ?></td>
                                <td><?php echo $liststudentend["category_exname"]; ?></td>
                                <td><?php echo $liststudentend["teacher_name"]; ?></td>
                                <td style="text-align: right">
                                    <a href="panel.php?panel=addquestion&id=<?php echo $liststudentend["id"]; ?>"
                                       class="btn btn-outline-primary btn-md">
                                        <i class="fa fa-plus"></i></a>
                                    <a href="panel.php?panel=listquestion&id=<?php echo $liststudentend["id"]; ?>"
                                       class="btn btn-md btn-outline-secondary">Sorular</a>

                                </td>

                                <!--İlerleme Barı  <td>
                                     <div class="progress progress-xs">
                                         <div class="progress-bar progress-bar-danger" style="width: 55%"></div>
                                     </div>
                                 </td>-->
                            </tr>

                        <?php

                        endwhile;

                        ?>
                        </tbody>
                    </table>
                </div>
                <div class="card-footer clearfix">
                    <ul class="pagination pagination-sm m-0 float-right">
                        <li class="page-item"><a class="page-link" href="#">«</a></li>
                        <li class="page-item"><a class="page-link" href="#">1</a></li>
                        <li class="page-item"><a class="page-link" href="#">2</a></li>
                        <li class="page-item"><a class="page-link" href="#">3</a></li>
                        <li class="page-item"><a class="page-link" href="#">»</a></li>
                    </ul>
                </div>
            </div>

        </div>


        <?php


    }

    function addexam($adddb)
    {

        @$code = $_POST["code"];
        @$category = $_POST["category"];
        @$teacher = $_SESSION["username"];

        if (@$_POST["add"]):

            $getstudent = $adddb->prepare("select * from allstudent");
        $getstudent->execute();

            while($lastgetstudent = $getstudent->fetch(PDO::FETCH_ASSOC)):

                $stu_id = $lastgetstudent["id"];

                $addownstudent = $adddb->prepare("insert into ownsrudentexam (name_exam,id_student,stade) VALUES ('$code','$stu_id',0)");
                $addownstudent->execute();

            endwhile;


            $ekleson = $adddb->prepare("insert into allexams (exam_id,category_exname,teacher_name) VALUES ('$code','$category','$teacher')");

            if ($ekleson->execute()):

                echo '<div class="col-xl-12 col-lg-12 col-md-12 mx-auto"><div class="alert alert-success">SINAV OLUŞTURULDU</div></div>';
            /*                header("refresh:2,url=panel.php?panel=readyexams");*/

            else:

                echo '<div class="col-xl-12 col-lg-12 col-md-12 mx-auto"><div class="alert alert-danger">EKLENMEDİ</div></div>';
                /*                    header("refresh:2,url=panel.php?panel=readyexams");*/


            endif;

        else:

            $examcategory = $adddb->prepare("select * from examcategory");
            $examcategory->execute();

            ?>

            <div class="col-md-12">
                <div class="card card-primary">
                    <div class="card-header">
                        <h3 class="card-title">Sınav Ekle</h3>
                    </div>

                    <form method="post">
                        <div class="card-body">

                            <div class="form-group">
                                <select name="category" class="form-control">
                                    <?php while ($allexamcategory = $examcategory->fetch(PDO::FETCH_ASSOC)): ?>
                                        <option value="<?php echo $allexamcategory["examcat_name"]; ?>"><?php echo $allexamcategory["examcat_name"]; ?></option>
                                    <?php endwhile; ?>
                                </select>
                            </div>

                            <div class="form-group">
                                <label for="exampleInputEmail1">Sınav Kodu</label>
                                <input type="text" class="form-control" name="code"
                                       placeholder="Her Sınav İçin Farklı Kod Giriniz...">
                            </div>

                        </div>
                        <div class="card-footer">
                            <input type="submit" name="add" value="Sınav Ekle">
                        </div>
                    </form>
                </div>
            </div>


        <?php


        endif;


    }

    function addquestion($dbquestion)
    {

        if (isset($_GET["id"])):

        @$id = $_GET["id"];
        @$newquestion = $_POST["newquestion"];
        @$formdc = $_POST["formdc"];
        @$answ1 = $_POST["answ1"];
        @$answ2 = $_POST["answ2"];
        @$answ3 = $_POST["answ3"];
        @$answ4 = $_POST["answ4"];
        @$answ5 = $_POST["answ5"];

        if (@$_POST["q_add"]):

            $add_qu = $dbquestion->prepare("insert into question (exam_way,question,ans1,ans2,ans3,ans4,ans5,dc) VALUES ('$id','$newquestion','$answ1','$answ2','$answ3','$answ4','$answ5','$formdc')");

            if ($add_qu->execute()):

                echo '<div class="col-xl-12 col-lg-12 col-md-12 mx-auto"><div class="alert alert-success">SORU EKLENDİ</div></div>';
            /*                header("refresh:2,url=panel.php?panel=readyexams");*/

            else:

                echo '<div class="col-xl-12 col-lg-12 col-md-12 mx-auto"><div class="alert alert-danger">EKLENMEDİ</div></div>';
                /*                    header("refresh:2,url=panel.php?panel=readyexams");*/


            endif;

        else:

            ?>

            <div class="col-md-12">
                <div class="card card-primary">
                    <div class="card-header">
                        <h3 class="card-title">Sınav Ekle</h3>
                    </div>

                    <form method="post">
                        <div class="card-body">

                            <div class="form-group">
                                <label for="exampleInputEmail1">Soru</label>
                                <input type="text" class="form-control" name="newquestion" placeholder="Sorunuz...">
                            </div>


                            <div class="form-group">
                                <label for="exampleInputEmail1">1. Şık</label>
                                <input type="text" class="form-control" name="answ1">
                            </div>

                            <div class="form-group">
                                <label for="exampleInputEmail1">2. Şık</label>
                                <input type="text" class="form-control" name="answ2">
                            </div>

                            <div class="form-group">
                                <label for="exampleInputEmail1">3. Şık</label>
                                <input type="text" class="form-control" name="answ3">
                            </div>

                            <div class="form-group">
                                <label for="exampleInputEmail1">4. Şık</label>
                                <input type="text" class="form-control" name="answ4">
                            </div>

                            <div class="form-group">
                                <label for="exampleInputEmail1">5. Şık</label>
                                <input type="text" class="form-control" name="answ5">
                            </div>

                             <div class="form-group">
                                <label for="exampleInputEmail1">Doğru Cevap</label>
                                <input type="text" class="form-control" name="formdc" placeholder="Sorunuz...">
                            </div>


                        </div>
                        <div class="card-footer">
                            <input type="submit" name="q_add" value="Sınav Ekle">
                        </div>
                    </form>
                </div>
            </div>


        <?php


        endif;
        endif;


    }

    function allquestions($dballquestions)
    {

        if (isset($_GET["id"])):

            $allques = $dballquestions->prepare("select * from question where exam_way=" . $_GET["id"]);
            $allques->execute();

            $examname = $dballquestions->prepare("SELECT * FROM allexams where id=" . $_GET["id"]);
            $examname->execute();
            $lastexamname = $examname->fetch(PDO::FETCH_ASSOC);


            ?>

            <div class="col-12">
                <div class="card">
                    <div class="card-header">
                        <h3 class="card-title"><b><?php echo $lastexamname["exam_id"]; ?></b> Testine Ait Sorular</h3>
                    </div>
                    <div class="card-body">
                        <div id="example1_wrapper" class="dataTables_wrapper dt-bootstrap4">
                            <!-- <div class="row">
                                 <div class="col-sm-12 col-md-6">
                                     <div class="dt-buttons btn-group flex-wrap">
                                         <button class="btn btn-secondary buttons-excel buttons-html5" tabindex="0"
                                                 aria-controls="example1" type="button"><span>Excel</span></button>
                                         <button class="btn btn-secondary buttons-pdf buttons-html5" tabindex="0"
                                                 aria-controls="example1" type="button"><span>PDF</span></button>
                                         <button class="btn btn-secondary buttons-print" tabindex="0"
                                                 aria-controls="example1" type="button"><span>Yazdır</span></button>
                                     </div>
                                 </div>
                                 <div class="col-sm-12 col-md-6">
                                     <div id="example1_filter" class="dataTables_filter"><label>Search:<input
                                                     type="search" class="form-control form-control-sm" placeholder=""
                                                     aria-controls="example1"></label></div>
                                 </div>
                             </div>-->
                            <div class="row">
                                <div class="col-sm-12">
                                    <table id="example1"
                                           class="table table-bordered table-striped dataTable dtr-inline collapsed"
                                           role="grid" aria-describedby="example1_info">
                                        <thead>
                                        <tr role="row">
                                            <th class="sorting sorting_asc" tabindex="0" aria-controls="example1"
                                                rowspan="1" colspan="1" aria-sort="ascending"
                                                aria-label="Rendering engine: activate to sort column descending">
                                                SORU
                                            </th>
                                            <th class="sorting" tabindex="0" aria-controls="example1" rowspan="1"
                                                colspan="1" aria-label="Browser: activate to sort column ascending">
                                                CEVAP (A)
                                            </th>
                                            <th class="sorting" tabindex="0" aria-controls="example1" rowspan="1"
                                                colspan="1" aria-label="Platform(s): activate to sort column ascending">
                                                CEVAP (B)
                                            </th>
                                            <th class="sorting" tabindex="0" aria-controls="example1" rowspan="1"
                                                colspan="1"
                                                aria-label="Engine version: activate to sort column ascending">
                                                CEVAP (C)
                                            </th>
                                            <th class="sorting" tabindex="0" aria-controls="example1" rowspan="1"
                                                colspan="1"
                                                aria-label="Engine version: activate to sort column ascending">
                                                CEVAP (D)
                                            </th>
                                            <th class="sorting" tabindex="0" aria-controls="example1" rowspan="1"
                                                colspan="1"
                                                aria-label="Engine version: activate to sort column ascending">
                                                CEVAP (E)
                                            </th>
                                        </tr>
                                        </thead>
                                        <tbody>

                                        <?php while ($lastallques = $allques->fetch(PDO::FETCH_ASSOC)): ?>

                                            <tr class="odd">
                                                <td class="dtr-control sorting_1"
                                                    tabindex="0"><?php echo $lastallques["question"] ?></td>
                                                <td><?php echo $lastallques["ans1"] ?></td>
                                                <td><?php echo $lastallques["ans2"] ?></td>
                                                <td><?php echo $lastallques["ans3"] ?></td>
                                                <td><?php echo $lastallques["ans4"] ?></td>
                                                <td><?php echo $lastallques["ans5"] ?></td>
                                            </tr>

                                        <?php endwhile; ?>

                                        </tbody>
                                        <tfoot>
                                        <tr>
                                            <th rowspan="1" colspan="1">SORU</th>
                                            <th rowspan="1" colspan="1">CEVAP (A)</th>
                                            <th rowspan="1" colspan="1">CEVAP (B)</th>
                                            <th rowspan="1" colspan="1">CEVAP (C)</th>
                                            <th rowspan="1" colspan="1">CEVAP (D)</th>
                                            <th rowspan="1" colspan="1">CEVAP (E)</th>
                                        </tr>
                                        </tfoot>
                                    </table>
                                </div>
                            </div>
                            <div class="row">
                                <div class="col-sm-12 col-md-7">
                                    <div class="dataTables_paginate paging_simple_numbers" id="example1_paginate">
                                        <ul class="pagination">
                                            <li class="paginate_button page-item previous disabled"
                                                id="example1_previous"><a href="#" aria-controls="example1"
                                                                          data-dt-idx="0" tabindex="0"
                                                                          class="page-link">Previous</a></li>
                                            <li class="paginate_button page-item active"><a href="#"
                                                                                            aria-controls="example1"
                                                                                            data-dt-idx="1" tabindex="0"
                                                                                            class="page-link">1</a></li>
                                            <li class="paginate_button page-item "><a href="#" aria-controls="example1"
                                                                                      data-dt-idx="2" tabindex="0"
                                                                                      class="page-link">2</a></li>
                                            <li class="paginate_button page-item "><a href="#" aria-controls="example1"
                                                                                      data-dt-idx="3" tabindex="0"
                                                                                      class="page-link">3</a></li>
                                            <li class="paginate_button page-item "><a href="#" aria-controls="example1"
                                                                                      data-dt-idx="4" tabindex="0"
                                                                                      class="page-link">4</a></li>
                                            <li class="paginate_button page-item "><a href="#" aria-controls="example1"
                                                                                      data-dt-idx="5" tabindex="0"
                                                                                      class="page-link">5</a></li>
                                            <li class="paginate_button page-item "><a href="#" aria-controls="example1"
                                                                                      data-dt-idx="6" tabindex="0"
                                                                                      class="page-link">6</a></li>
                                            <li class="paginate_button page-item next" id="example1_next"><a href="#"
                                                                                                             aria-controls="example1"
                                                                                                             data-dt-idx="7"
                                                                                                             tabindex="0"
                                                                                                             class="page-link">Next</a>
                                            </li>
                                        </ul>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <!-- /.card-body -->
                </div>
            </div>


        <?php


        endif;

    }


}

class home
{

    function examsstudent($dbexstudent)
    {

               $stu = $_SESSION["stu_name"];
               $stu_id = $_SESSION["ownstuid"];

        $gelenler = $dbexstudent->prepare("select * from ownsrudentexam where stade=0 and id_student='$stu_id'");
        $gelenler->execute();


        ?>

        <div class="col-md-12">
            <div class="card">
                <div class="card-header">
                    <h3 class="card-title">Sınavlar</h3>
                </div>
                <div class="card-body">
                    <div class="card-header text-right">
                        <a href="studentlogin.php?stu=stulogout" class="btn btn-md btn-outline-danger">Çıkış</a>
                    </div>


                    <table class="table table-bordered">
                        <thead>
                        <tr>
                            <th style="width: 10px">#</th>
                            <th>Sınav Kodu</th>
                            <th>Kategorisi</th>
                            <th>Hazırlayan Öğretmen</th>
                            <th>Sorular</th>
                        </tr>
                        </thead>
                        <tbody>
                        <?php


                     while($lastgelenler = $gelenler->fetch(PDO::FETCH_ASSOC)):

                            $nameex = $lastgelenler["name_exam"];

                              $all_exams = $dbexstudent->prepare("SELECT * FROM allexams where exam_id='$nameex'");
                                $all_exams->execute();

                                while ($lastexams = $all_exams->fetch(PDO::FETCH_ASSOC)):

                            ?>

                            <tr>
                                <td><?php echo $lastexams["id"]; ?></td>
                                <td><?php echo $lastexams["exam_id"]; ?></td>
                                <td><?php echo $lastexams["category_exname"]; ?></td>
                                <td><?php echo $lastexams["teacher_name"]; ?></td>
                                <td style="text-align: right">
                                    <a href="index.php?home=allexamsindex&id=<?php echo $lastexams["id"]; ?>"
                                       class="btn btn-md btn-outline-secondary">Sınava Başla</a>
                                </td>
                            </tr>

                        <?php

                       endwhile;
                        endwhile;

                        if ($gelenler->rowCount()===0):

                            echo '<div class="alert alert-default-success text-center">!!! TÜM TESTLERİ TAMAMLADINIZ !!!</div>';

                        endif;

                        ?>
                        </tbody>
                    </table>


                </div>
                <div class="card-footer clearfix">
                    <ul class="pagination pagination-sm m-0 float-right">
                        <li class="page-item"><a class="page-link" href="#">«</a></li>
                        <li class="page-item"><a class="page-link" href="#">1</a></li>
                        <li class="page-item"><a class="page-link" href="#">2</a></li>
                        <li class="page-item"><a class="page-link" href="#">3</a></li>
                        <li class="page-item"><a class="page-link" href="#">»</a></li>
                    </ul>
                </div>
            </div>

        </div>


        <?php

    }

    function studentques($quesdb)
    {

        if (isset($_GET["id"])):

            if (isset($_SESSION["stu_name"])):

                $examname = $quesdb->prepare("SELECT * FROM allexams where id=" . $_GET["id"]);
                $examname->execute();
                $lastexamname = $examname->fetch(PDO::FETCH_ASSOC);

                $namestu = $_SESSION["stu_name"];
                $idstu = $_SESSION["ownstuid"];
                $nameteach = $lastexamname["teacher_name"];
                $nameexam = $lastexamname["exam_id"];
                $idexam = $lastexamname["id"];


                if (@$_POST["stuq_add"]):

                    ?>

                    <div class="container">
                        <div class="row">
                            <div class="col-md-12">
                                <div class="card card-success">
                                    <div class="card-header">
                                        <h2 class="card-title">SONUÇLAR</h2>
                                    </div>
                                    <div class="card-body">
                    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/css/bootstrap.min.css"
                          rel="stylesheet"
                          integrity="sha384-1BmE4kWBq78iYhFldvKuhfTAU6auU8tT94WrHftjDbrCEXSU1oBoqyl2QvZ6jIW3"
                          crossorigin="anonymous">

                    <?php

                    $wrong = 0;
                    $myexamtrue = 0;

                    $myques = $quesdb->prepare("select * from question where exam_way=" . $_GET["id"]);
                    $myques->execute();


                    while ($last = $myques->fetch(PDO::FETCH_ASSOC)):


                        @$teachques = $_POST["hidques" . $last["id"]];
                        @$oneans = $_POST["answ" . $last["id"]];
                        @$dc = $_POST["dc" . $last["id"]];
                        @$myid = $_POST["id" . $last["id"]];


                        $stmt = $quesdb->prepare("INSERT INTO completed_exam (stu_id,stu_name,teach_name,exam_number,exam_name,question,answer,correct) VALUES ('$idstu','$namestu','$nameteach','$idexam','$nameexam','$teachques','$oneans','$dc')");
                        $stmt->execute();


                        $needid = $quesdb->prepare("select * from question where id='$myid'");
                        $needid->execute();
                        $lastmyid = $needid->fetch(PDO::FETCH_ASSOC);


                        if (@$lastmyid["dc"] === @$oneans):

                            ++$myexamtrue;


                            echo '
                           	
							 <div class="container">
							 
							 <div class="row">
                    <div class="col-md-12 alert alert-warning text-center">SORU NO : ' . @$lastmyid["id"] . '</div>
                  </div>
                
                 <div class="row">
                    <div class="col-6 alert alert-info text-center">Verdiğin Cevap : ' . @$oneans . '</div>
                    <div class="col-6 alert alert-success text-center">Doğru Cevap : ' . @$lastmyid["dc"] . '</div>
                  </div>
                
           
           
                </div>
                <hr>
							';

                        elseif (@$lastmyid["dc"] != @$oneans):

                            ++$wrong;

                            echo '
                         
							
							 <div class="container">
							 
							 <div class="row">
                    <div class="col-md-12 alert alert-warning text-center">SORU NO : ' . @$lastmyid["id"] . '</div>
                  </div>
                
                 <div class="row">
                    <div class="col-6 alert alert-info text-center">Verdiğin Cevap : ' . @$oneans . '</div>
                    <div class="col-6 alert alert-success text-center">Doğru Cevap : ' . @$lastmyid["dc"] . '</div>
                  </div>           
           
                </div>
						<hr>	
							';


                        endif;


                    endwhile;

                    if ($wrong == 0):

                        /*                        echo "burası 2";*/

                        echo '
        
                <div class="container">
                
                 <div class="row">
                    <div class="col-6 alert alert-danger">Toplam <b>Yalnış</b> Sayısı :' . $wrong . '</div>
                    <div class="col-6 alert alert-success">Toplam <b>Doğru</b> Sayısı :' . $myexamtrue . '</div>
                  </div>
                
                  <div class="row">
                    <div class="col-md-12 alert alert-warning">Net Sayısı :' . ($myexamtrue - $wrong * (1 / 4)) . '</div>
                  </div>
                  
                </div>
                
                <a href="index.php"><input class="btn btn-block btn-outline-danger btn-flat" type="submit"name="stuq_add" value="Testlere Dön"></a>

                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
            
            ';

                    else:

                        /*                        echo "burası 1";*/

                        echo '
               <div class="container">
                
                 <div class="row">
                    <div class="col-6 alert alert-danger">Toplam <b>Yalnış</b> Sayısı :' . $wrong . '</div>
                    <div class="col-6 alert alert-success">Toplam <b>Doğru</b> Sayısı :' . $myexamtrue . '</div>
                  </div>
                
                  <div class="row">
                    <div class="col-md-12 alert alert-warning">Net Sayısı :' . ($myexamtrue - $wrong * (1 / 4)) . '</div>
                  </div>
                  
                </div>
            
            <a href="index.php"><input class="btn btn-block btn-outline-danger btn-flat" type="submit"name="stuq_add" value="Testlere Dön"></a>
            
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>            
            ';

                    endif;

                    /*BURASI tamamladığında stuownexam güncellme*/

                    $gelenid = 1;

                    $updateidstu = $_SESSION["ownstuid"];

                    $updatenameexam = $lastexamname["exam_id"];

                    $exam_update=$quesdb->prepare("UPDATE ownsrudentexam SET stade=1 WHERE name_exam=:name_exam AND id_student=:id_student");
                    $exam_update->BindValue(':id_student',$updateidstu, PDO::PARAM_STR);
                    $exam_update->BindValue(':name_exam',$updatenameexam, PDO::PARAM_STR);
                    $exam_update->execute();

                    /*BURASI Biten testleri ekrandan silmek*/

                     $studentexam = $quesdb->prepare("INSERT INTO studentexam (id_exam,id_student,student_exam,name_student,stade) VALUES ('$idexam','$idstu','$nameexam','$namestu',0)");
                     $studentexam->execute();


                    /*BURASI Velilere mail atmak için tabloya ekleme*/


                    $net = ($myexamtrue - $wrong * (1 / 4));

                    $addmail = $quesdb->prepare("INSERT INTO mail (mail_idstu,mail_stuname,mail_examname,mail_correct,mail_wrong,mail_net) VALUES ('$idstu','$namestu','$nameexam','$myexamtrue','$wrong','$net')");
                    $addmail->execute();

                    /*BURASI TAMAMLANAN SINAVLARI TABLOYA KAYDETME YERİ*/

                    $finishedit = $quesdb->prepare("INSERT INTO finishexam (fin_examcode,fin_examname,fin_student,fin_teach) VALUES ('$idexam','$nameexam','$namestu','$nameteach')");
                    $finishedit->execute();


                else:

                echo $idstu;
                echo $nameexam;

                    $needinform = $quesdb->prepare("SELECT * FROM question LEFT JOIN allexams ON question.exam_way = allexams.id WHERE exam_way=" . $_GET["id"]);
                    $needinform->execute();
                    $lastneedinform = $needinform->fetch(PDO::FETCH_ASSOC);

                    $allques = $quesdb->prepare("select * from question where exam_way=" . $_GET["id"]);
                    $allques->execute();

                    ?>

                    <div class="container">
                        <div class="row">
                            <div class="col-md-12">
                                <div class="callout callout-danger">
                                    <h5><?php echo $lastneedinform["category_exname"]; ?></h5>
                                </div>
                                <div class="card card-warning">
                                    <div class="card-header">
                                        <h2 class="card-title"><?php echo $lastneedinform["exam_id"]; ?> TESTİNE AİT
                                            SORULAR</h2>
                                    </div>
                                    <div class="card-body">
                                        <form action="" method="post">
                                            <?php while ($lastallques = $allques->fetch(PDO::FETCH_ASSOC)): ?>
                                                <br>
                                                <div class="alert alert-light" role="alert">
                                                    <label for="">SORU
                                                        : <?php echo mb_strtoupper($lastallques["question"], "UTF-8"); ?></label>
                                                </div>
                                                <input type="hidden" name="hidques<?php echo $lastallques["id"]; ?>"
                                                       value="<?php echo $lastallques["question"]; ?>">
                                                <input type="hidden" name="dc<?php echo $lastallques["id"]; ?>"
                                                       value="<?php echo $lastallques["dc"]; ?>">
                                                <hr>
                                                <div class="form-check form-check-inline">
                                                    <input class="form-check-input" type="radio"
                                                           name="answ<?php echo $lastallques["id"]; ?>"
                                                           id="inlineRadio1"
                                                           value="<?php echo $lastallques["ans1"]; ?>">
                                                    <label class="form-check-label"
                                                           for="inlineRadio1"><?php echo $lastallques["ans1"]; ?></label>
                                                </div>
                                                <div class="form-check form-check-inline">
                                                    <input class="form-check-input" type="radio"
                                                           name="answ<?php echo $lastallques["id"]; ?>"
                                                           id="inlineRadio2"
                                                           value="<?php echo $lastallques["ans2"]; ?>">
                                                    <label class="form-check-label"
                                                           for="inlineRadio2"><?php echo $lastallques["ans2"]; ?></label>
                                                </div>

                                                <div class="form-check form-check-inline">
                                                    <input class="form-check-input" type="radio"
                                                           name="answ<?php echo $lastallques["id"]; ?>"
                                                           id="inlineRadio2"
                                                           value="<?php echo $lastallques["ans3"]; ?>">
                                                    <label class="form-check-label"
                                                           for="inlineRadio2"><?php echo $lastallques["ans3"]; ?></label>
                                                </div>

                                                <div class="form-check form-check-inline">
                                                    <input class="form-check-input" type="radio"
                                                           name="answ<?php echo $lastallques["id"]; ?>"
                                                           id="inlineRadio2"
                                                           value="<?php echo $lastallques["ans4"]; ?>">
                                                    <label class="form-check-label"
                                                           for="inlineRadio2"><?php echo $lastallques["ans4"]; ?></label>
                                                </div>

                                                <div class="form-check form-check-inline">
                                                    <input class="form-check-input" type="radio"
                                                           name="answ<?php echo $lastallques["id"]; ?>"
                                                           id="inlineRadio2"
                                                           value="<?php echo $lastallques["ans5"]; ?>">
                                                    <label class="form-check-label"
                                                           for="inlineRadio2"><?php echo $lastallques["ans5"]; ?></label>
                                                </div>
                                                <input type="hidden" name="id<?php echo $lastallques["id"]; ?>"
                                                       value="<?php echo $lastallques["id"]; ?>">
                                                <br>
                                                <br>
                                                <br>
                                            <?php endwhile; ?>

                                            <input class="btn btn-block btn-outline-success btn-flat" type="submit"
                                                   name="stuq_add" value="Cevapları Yolla">

                                        </form>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>


                <?php


                endif;
            endif;
        endif;


    }

}

class result
{

    function studentresultlist($dbstudent)
    {

         $liststudent = $dbstudent->prepare("SELECT * FROM allstudent WHERE stade=2");
         $liststudent->execute();


           ?>

        <div class="col-md-12">
            <div class="card">
                <div class="card-header">
                    <h3 class="card-title">Sonuçlar</h3>
                </div>
                <div class="card-body">
                    <table class="table table-bordered">
                        <thead>
                        <tr>
                            <th>Öğrenci Adı</th>
                            <th>Tamamladığı Testler</th>
                            <th>Sonuçlar</th>
                        </tr>
                        </thead>
                        <tbody>
                        <?php

                        while ($liststudentend = $liststudent->fetch(PDO::FETCH_ASSOC)):

                            $gelenler = $liststudentend["stu_name"];

                            $finishexamid = $dbstudent->prepare("select * from finishexam where fin_student='$gelenler'");
                            $finishexamid->execute();

                            $finishexam = $dbstudent->prepare("select * from finishexam where fin_student='$gelenler'");
                            $finishexam->execute();

                            ?>
                            <tr>
                                <td><?php echo $liststudentend["stu_name"]; ?></td>
                                <td>
                              <?php while ($lastfinishexam = $finishexam->fetch(PDO::FETCH_ASSOC)): ?>
                                <?php echo $lastfinishexam["fin_examname"];?>
                                <a href="panel.php?panel=showresult&id=<?php echo $liststudentend["id"]; ?>&examid=<?php echo $lastfinishexam["fin_examcode"]; ?>"
                                       class="btn btn-outline-primary btn-md">
                                        <i class="fa fa-book"></i></a>
                                <?php endwhile;?>
                                </td>
                                <td style="text-align: right">
                                    <!--<a href="panel.php?panel=showresult<?php /*while ($lastfinishexamid = $finishexamid->fetch(PDO::FETCH_ASSOC)):*/?>&exams<?php /*echo $lastfinishexamid["fin_examcode"]; */?>=<?php /*echo $lastfinishexamid["fin_examcode"]; endwhile; */?>&id=<?php /*echo $liststudentend["id"]; */?>"
                                       class="btn btn-outline-primary btn-md">
                                        <i class="fa fa-book"></i></a>-->
                                         <a href="panel.php?panel=mailatt&id=<?php echo $liststudentend["id"]; ?>"
                                       class="btn btn-outline-danger btn-md">
                                        <i class="far fa-envelope"></i> Sonuçları Veliye Maille</a>
                                </td>
                            </tr>

                        <?php

                        endwhile;


                        ?>
                        </tbody>
                    </table>
                </div>
                <div class="card-footer clearfix">
                    <ul class="pagination pagination-sm m-0 float-right">
                        <li class="page-item"><a class="page-link" href="#">«</a></li>
                        <li class="page-item"><a class="page-link" href="#">1</a></li>
                        <li class="page-item"><a class="page-link" href="#">2</a></li>
                        <li class="page-item"><a class="page-link" href="#">3</a></li>
                        <li class="page-item"><a class="page-link" href="#">»</a></li>
                    </ul>
                </div>
            </div>

        </div>

        <?php


    }

    function examresult($dbresult){

        /*$_SESSION["ownstuid"]*/

        if (isset($_GET["examid"]) && isset($_GET["id"])):

            $getidstu =  $_GET["id"];
            $getidex =  $_GET["examid"];

                $getexam = $dbresult->prepare("select * from completed_exam where stu_id='$getidstu' and exam_number='$getidex'");
                $getexam->execute();

                $getques = $dbresult->prepare("select * from completed_exam where stu_id='$getidstu' and exam_number='$getidex'");
                $getques->execute();

                /*$denemem = $dbresult->prepare("SELECT * FROM completed_exam LEFT JOIN allexams ON completed_exam.exam_number = allexams.id where stu_id=".$_GET["id"]);
                $denemem ->execute();
                $lastexamname = $denemem->fetch(PDO::FETCH_ASSOC);*/

                $getexamname = $dbresult->prepare("select * from allexams where id='$getidex'");
                $getexamname->execute();
                $lastgetexamname = $getexamname->fetch(PDO::FETCH_ASSOC);


                ?>

          <div class="col-md-12">
            <div class="card">
                <div class="card-header">
                    <h3 class="card-title">Sonuçlar</h3>
                </div>
                <div class="card-body">

                        <div class="container">
							 <div class="row">
                                <div class="col-md-12 alert alert-default-warning text-center">TESTİN ADI : <b><?php echo $lastgetexamname["exam_id"]; ?></b><br></div>
                             </div>

                            <?php

                             $correct = 0;
                             $wrong = 0;

                            while($lastgetexam = $getexam->fetch(PDO::FETCH_ASSOC)):

                            if ($lastgetexam["answer"] === $lastgetexam["correct"]):

                            ++$correct;

                            elseif ($lastgetexam["answer"] !== $lastgetexam["correct"]):

                            ++$wrong;

                            endif;

                            endwhile;

                            ?>

                            <?php while ($lastgetques = $getques->fetch(PDO::FETCH_ASSOC)): ?>
                             <div class="row">
                                <div class="col-4 alert alert-default-warning text-center">Soru : <?php echo $lastgetques["question"]; ?></div>
                                <div class="col-4 alert alert-default-<?php if ($lastgetques["answer"] === $lastgetques["correct"]): echo  "success"; else: echo "danger";  endif; ?> text-center">Verilen Cevap : <?php echo $lastgetques["answer"]; ?></div>
                                <div class="col-4 alert alert-default-success text-center">Doğru Cevap : <?php echo $lastgetques["correct"]; ?></div>
                             </div>
                            <?php endwhile; ?>

                             <div class="row">
                                <div class="col-6 alert alert-default-info text-center">Doğru Sayısı : <?php echo $correct; ?></div>
                                <div class="col-6 alert alert-default-danger text-center">Yalnış Sayısı : <?php echo $wrong; ?></div>
                             </div>

                             <div class="row">
                                 <div class="col-md-12 alert alert-default-success text-center">Net Sayısı : <?php echo ($correct - $wrong * (1 / 4)); ?></div>
                             </div>

                        </div>

                </div>
            </div>
        </div>

                <?php

        endif;

    }

    function resultmail($dbmail){

        if (isset($_GET["id"])):

        $getstudent = $dbmail->prepare("select * from allstudent where id=".$_GET["id"]);
        $getstudent->execute();
        $lastgetstudent = $getstudent->fetch(PDO::FETCH_ASSOC);


        require '../src/Exception.php';
        require '../src/PHPMailer.php';
        require '../src/SMTP.php';

        $mail = new PHPMailer();
        $mail->isSMTP();
        $mail->SMTPDebug=0;
        $mail->Host = 'smtp.mailtrap.io';
        $mail->SMTPAuth = true;
        $mail->Port = 2525;
        $mail->Username = '2b7fdc93df25e4';
        $mail->Password = '4dfa248da487de';
        $mail->CharSet = 'UTF-8';
        $mail->setFrom($_SESSION["username"]."@gmail.com"); // gönderen kim
        $mail->addAddress($lastgetstudent["family_mail"]);
        $mail->addReplyTo($lastgetstudent["family_mail"]);


        $mail->isHTML(true);
        $mail->Subject = $lastgetstudent["stu_name"]." Öğrenciye Ait Test Sonuçları";


        $infomail = $dbmail->prepare("select * from mail where mail_idstu=".$_GET["id"]);
        $infomail->execute();

        $mail->Body = '<html>
                <head>
                </head><body>';

        $mail->Body .= '<h1>'.$lastgetstudent["stu_name"].' Öğrencisine Ait Tets Sonuçları</h1><hr>';

        while ($lastinfomail = $infomail->fetch(PDO::FETCH_ASSOC)):

                $mail->Body .= '<p>Tetsin Adı : '.$lastinfomail["mail_examname"].'</p>';

                $mail->Body .= '<p>Tetsin Doğru Cevap Sayısı : '.$lastinfomail["mail_correct"].'</p>';

                $mail->Body .= '<p>Tetsin Yalnış Cevap Sayısı : '.$lastinfomail["mail_wrong"].'</p>';

                $mail->Body .= '<p>Tetsin Net Cevap Sayısı : '.$lastinfomail["mail_net"].'</p><hr>';

        endwhile;

        $mail->Body .= '</body></html>';


        if ($mail->send()):

        echo '<div class="col-md-12 alert alert-default-success text-center">!!! MAİL GÖNDERİLDİ !!!</div>';

        else:

        echo '<div class="col-md-12 alert alert-default-danger text-center">"Malesef hata var . Hata kodu : ",'.$mail->ErrorInfo.'</div>';

        endif;


        endif;


    }

}

?>