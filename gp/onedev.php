<?php
include("head.php");
if (!(isset($_SESSION))) {
    session_start();
}
$user = $_SESSION["user"];
$uname = $_SESSION["uname"];
$dname = $_SESSION["dname"];
$dev = $_SESSION["dev"];
$dphone = $_SESSION['dphone'];
$f1p = $_SESSION['f1p'];
$f1g = $_SESSION['f1g'];
$f2p = $_SESSION['f2p'];
$f2g = $_SESSION['f2g'];
require("dbconn.php");


// Функция нормализации номера телефона к виду 7ХХХХХХХХХХ
function myTnorm($ph) {
    $out = implode('', array_filter(str_split($ph), function($digit) { return (is_numeric($digit)); }));
    if (strlen($out)==10) {
        $out = "7" . $out;
    }
    if ( $out[0]== '8') { $out[0] = '7'; }
    return $out;
}

if (isset($_GET['idgroop'])) {
    $groop = $_GET['idgroop'];
    $gname = $_GET['newgname'];
    switch ($gname) {
        case "reset":
            $stmt = $conn->prepare("UPDATE `glopultr_mygate`.`mygroops` SET `enable` = 0 WHERE (`ID` = :idgroop);");
            $stmt->bindParam(':idgroop',$groop);
            $stmt->execute();
            break;
        case "block":
            $stmt = $conn->prepare("UPDATE `glopultr_mygate`.`mygroops` SET `enable` = -1 WHERE (`ID` = :idgroop);");
            $stmt->bindParam(':idgroop',$groop);
            $stmt->execute();
            break;
        case "del":
            $stmt = $conn->prepare("UPDATE `glopultr_mygate`.`mygroops` SET `enable` = 1 WHERE (`ID` = :idgroop);");
            $stmt->bindParam(':idgroop',$groop);
            $stmt->execute();
            break;
        default:
            $stmt = $conn->prepare("UPDATE `glopultr_mygate`.`mygroops` SET `name` = :name WHERE (`ID` = :idgroop);");
            $stmt->bindParam(':name',$gname);
            $stmt->bindParam(':idgroop',$groop);
            $stmt->execute();
    }
    $stmt->closeCursor();
    header('Refresh: 0; url="/gp/onedev.php"');
    exit();
}

if (isset($_GET['newpname'])) {
    $stmt = $conn->prepare("UPDATE `glopultr_mygate`.`mypult` SET `name` = :newpname WHERE (`ID` = :idpult);");
    $stmt->bindParam(':newpname',$_GET['newpname']);
    $stmt->bindParam(':idpult',$_GET['idpult']);
    $stmt->execute();
    $stmt->closeCursor();
    header('Refresh: 0; url="/gp/onedev.php"');
    exit();
}

if (isset($_GET['newph'])) {
    $new = $_GET['newph'];
    $pult = $_GET['idpult'];
    $stmt = $conn->prepare("SELECT * FROM `glopultr_mygate`.`mypult` WHERE (`ID` = :idpult);");
    $stmt->bindParam(':idpult',$_GET['idpult']);
    $stmt->execute();
    $row = $stmt->fetch();
    if ($row['phone']!="Удалён") {
        $phone = myTnorm($row['phone']);    // Текущий номер
    }
    $old = myTnorm($row['oldphone']);   // Старый номер
    switch ($new) {
        case "reset":
            $phone = $old;
            $old = "";
            $en = 0;
            break;
        case "block":
            if ($old=="") { $old = $phone;}
            $en = -1;
            break;
        case "del":
            if ($old=="") { $old = $phone;}
            $phone="Удалён";
            $en = -2;
            break;
        default:
            if ($new==$phone) {
                $en = $row['enable'];
            }
            elseif ($new==$old) {
                $phone = $old;
                $old = "";
                $en = $row['enable'];
                if ($en==1){ $en=0;}
            }
            else {
                if ($old=="") {
                    $old=$phone;
                    $phone=$new;
                    $en=1;
                }
                else {
                    $phone=$new;
                    $en=1;
                }
            }
    }
    $stmt = $conn->prepare("UPDATE `glopultr_mygate`.`mypult` SET `phone` = :phone, `oldphone` = :old, `enable` = :en WHERE (`ID` = :idpult);");
    $stmt->bindParam(':en',$en);
    $stmt->bindParam(':phone',$phone);
    $stmt->bindParam(':old',$old);
    $stmt->bindParam(':idpult',$pult);
    $stmt->execute();
    $stmt->closeCursor();
    header('Refresh: 0; url="/gp/onedev.php"');
    exit();


}

if (isset($_POST['newgroop'])) {
    $newgroop = $_POST['newgroopname'];
    unset($_POST);
    $conn->query("INSERT INTO `glopultr_mygate`.`mygroops` (`IDev`, `name`) VALUES ('" . $dev . "', '" . $newgroop . "');");
    header('Refresh: 0; url="/gp/onedev.php"');
    exit();
}
if (isset($_POST['newpult'])) {
    $newpult = $_POST['newpultname'];
    $newphone = $_POST['newpultphone'];
    unset($_POST);
    $conn->query("INSERT INTO `glopultr_mygate`.`mypult` (`IDgroop`, `name`, `phone`) VALUES ('" . $_SESSION['groop'] . "', '" . $newpult . "', '" . $newphone . "');");
    header('Refresh: 0; url="/gp/onedev.php"');
    exit();
}

?>
<body id="myLogin" data-spy="scroll" data-target=".navbar" data-offset="60">
<nav class="navbar navbar-default navbar-fixed-top">
    <div class="container">
        <div class="navbar-header">
            <button type="button" class="navbar-toggle" data-toggle="collapse" data-target="#myNavbar">
                <span class="icon-bar"></span>
                <span class="icon-bar"></span>
                <span class="icon-bar"></span>
            </button>
            <a class="navbar-brand" href="/index.php"><img src="/img/logo.png"></a>
        </div>
        <div class="collapse navbar-collapse" id="myNavbar">
            <ul class="nav navbar-nav navbar-right">
                <li><a><span style="color: yellow">+7(900)641-89-62</span></a></li>
                <li><a><span style="color: yellow">info@glopult.ru</span></a></li>
                <li><a href="/index.php">Выход</a></li>
                <?php
                if ($_SESSION['devCol'] > 1) {
                    echo '<li><a href="/gp/login.php">Ворота</a></li>';
                 }
                 ?>
            </ul>
        </div>
    </div>
</nav>
<div class="container-fluid">
    <div class="row">
        <div class="col-sm-2">
            <h4><?= $uname ?><br><br></h4>

            <button type="button" class="btn" style="width: 150px; color: black; background-color: #f4ee24" data-toggle="modal"
                    data-target="#addGroop">
                Добавить группу
            </button>
            <br><br>
            <button type="button" class="btn" style="width: 150px; color: black; background-color: #f4ee24" data-toggle="modal"
                    data-target="#addPult">
                Добавить пульт
            </button>
            <?php
                if ( $_SESSION["utarif"]>0 ) {    //  Выводим информацию о Балансе и Тарифе только если Тариф>0
                    echo ('<br><br>Баланс: <?= $_SESSION["ubalance"] ?> руб.');
                    echo ('<br>Тариф: <?= $_SESSION["utarif"] ?> руб./мес.');
                }
            ?>

        </div>
        <div class="col-sm-4">
            <h4><?= $dname ?></h4>
            <table class="table table-condensed table-striped">
                <thead>
                <tr>
                    <th>№</th>
                    <th>Группа</th>
                    <?php
                    if ($f1g) {
                        echo "<th>" . $f1g . "</th>";
                    }
                    ?>
                </tr>
                </thead>
                <tbody>
                <?php
                $stmt = $conn->prepare("SELECT * FROM mygroops WHERE IDev = :dev"); // ORDER BY name
                $stmt->execute(array(':dev' => $dev));
                $count = 1;
                while ($rgroop = $stmt->fetch()) {
                    if ($count == 1) {
                        if (!isset($_SESSION['groop'])) {
                            $_SESSION['groop'] = $rgroop['ID'];
                            $_SESSION['groop_name'] = $rgroop['name'];
                        }
                    }
                    ?>
                    <tr class="<?php if ($rgroop["enable"]<0) {
                        echo "info";
                    }
                    elseif ($rgroop["enable"]>0){
                        echo "danger";
                    }
                    else {
                        echo "success";
                    } ?>">
                        <td scope=”row”>
                            <?php
//                                echo $count . ' <span class="glyphicon glyphicon-edit"></span>';
                            ?>
                            <a href="edgroop.php?idgroop= <?php echo $rgroop["ID"] ?>"><span class="glyphicon glyphicon-edit"></span></a>
                        </td>
                        <td>
                            <a href="groop.php?idgroop= <?php echo $rgroop["ID"] ?>"> <?php echo $rgroop["name"]; ?></a>
                        </td>
                        <?php
                        if ($f1g) {
                            echo "<td>" . $rgroop['f1'] . "</td>";
                        }
                        ?>
                    </tr>
                    <?php
                    $count++;
                }
                $stmt->closeCursor();
                ?>
                </tbody>
            </table>
        </div>
        <div class="col-sm-6">
            <h4><?php echo "Группа: " . $_SESSION['groop_name']; ?></h4>
            <table class="table table-condensed table-striped">
                <thead>
                <tr>
                    <th>№</th>
                    <th>Владелец пульта</th>
                    <th>Телефон</th>
                    <?php
                    if ($f1p) {
                        echo "<th>" . $f1p . "</th>";
                    }
                    ?>
                </tr>
                </thead>
                <tbody>
                <?php
                $stmt = $conn->prepare("SELECT * FROM mypult WHERE IDgroop = :groop");
                $stmt->execute(array(':groop' => $_SESSION['groop']));
                $count = 1;
                while ($rpult = $stmt->fetch()) { ?>
                    <tr class="<?php if ($rpult["enable"]<0) {
                        if ($rpult["enable"]==-1){
                            echo "info";
                        }
                        else {
                            echo "danger";
                        }
                    }
                    elseif ($rpult["enable"]>0){
                        echo "warning";
                    }
                    else {
                        echo "success";
                    } ?>">
                        <td scope=”row”>
                            <?php echo $count . ' '; ?>
                        </td>
                        <td>
                            <a href="edpult.php?idpult= <?php echo $rpult["ID"] ?>"> <?php echo $rpult["name"]; ?></a>
                        </td>
                        <td>
                            <a href="edpultphone.php?idpult= <?php echo $rpult["ID"] ?>"> <?php
                                $pult = $rpult['phone'];
                                if ( $pult == "" ) { echo "Восстановить"; }
                                echo $rpult["phone"];
                                ?></a>
                        </td>
                        <?php
                        if ($f1p) {
                            echo "<td>" . $rpult['f1'] . "</td>";
                        }
                        ?>
<!--                        <td>-->
<!--                            --><?php //echo $rpult["num"]; ?>
<!--                        </td>-->
                    </tr>
                    <?php
                    $count++;
                } ?>
                </tbody>
            </table>
        </div>
        <div class="col-sm-2">
            <!-- Модальное окно для новой группы пультов -->
            <div class="modal fade" id="addGroop" tabindex="-1" role="dialog"
                 aria-labelledby="exampleModalCenterTitle" aria-hidden="true">
                <div class="modal-dialog modal-dialog-centered" role="document">
                    <div class="modal-content">
                        <!--                        Заголовок окна-->
                        <div class="modal-header">
                            <h4 class="modal-title" id="exampleModalLongTitle"><br>Создать группу пультов для
                                устройства: "<?= $dname ?>"</h4>
                            <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                <span aria-hidden="true">&times;</span>
                            </button>
                        </div>
                        <!--                          Содержимое окна-->
                        <div class="modal-body">
                            <!--                         Форма ввода-->
                            <form class="needs-validation" novalidate method="post">
                                <div class="form-row">
                                    <div class="col-md-4 mb-3">
                                        <label for="validationCustom01">Имя группы</label>
                                    </div>
                                    <div class="form-row">
                                        <input name="newgroopname" type="text" class="form-control"
                                               id="validationCustom01"
                                               placeholder="Имя" required>
                                    </div>
                                </div>

                                <script>
                                    (function () {
                                        'use strict';
                                        window.addEventListener('load', function () {
                                            // Fetch all the forms we want to apply custom Bootstrap validation styles to
                                            let forms = document.getElementsByClassName('needs-validation');
                                            // Loop over them and prevent submission
                                            let validation = Array.prototype.filter.call(forms, function (form) {
                                                form.addEventListener('submit', function (event) {
                                                    if (form.checkValidity() === false) {
                                                        event.preventDefault();
                                                        event.stopPropagation();
                                                    }
                                                    form.classList.add('was-validated');
                                                }, false);
                                            });
                                        }, false);
                                    })();
                                </script>
                                <!--                            -->

                                <div class="modal-footer">
                                    <button name="newgroop" class="btn" type="submit" style="background-color: #f4ee24">
                                        Сохранить группу
                                    </button>
                                    <button type="button" class="btn btn-secondary" data-dismiss="modal">Отмена</button>
                                </div>
                            </form>
                        </div>
                    </div>
                </div>
            </div>


            <!-- Модальное окно для нового пульта -->
            <div class="modal fade" id="addPult" tabindex="-1" role="dialog"
                 aria-labelledby="exampleModalCenterTitle" aria-hidden="true">
                <div class="modal-dialog modal-dialog-centered" role="document">
                    <div class="modal-content">
                        <!--                        Заголовок окна-->
                        <div class="modal-header">
                            <h4 class="modal-title" id="exampleModalLongTitle"><br>Создать новый пульт в группе:
                                "<?= $_SESSION['groop_name'] ?>"</h4>
                            <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                <span aria-hidden="true">&times;</span>
                            </button>
                        </div>
                        <!--                          Содержимое окна-->
                        <div class="modal-body">
                            <!--                         Форма ввода-->
                            <form class="needs-validation" novalidate method="post">
                                <div class="form-row">
                                    <div class="col-md-4 mb-3">
                                        <label for="validationCustom01">Имя владельца пульта</label>
                                    </div>
                                    <div class="form-row">
                                        <input name="newpultname" type="text" class="form-control"
                                               id="validationCustom01"
                                               placeholder="Владелец пульта" required>
                                    </div>
                                </div>
<!--                                <div class="form-row">-->
<!--                                    <div class="col-md-4 mb-3">-->
<!--                                        <label for="validationCustom03">Номер в системе</label>-->
<!--                                    </div>-->
<!--                                    <div class="form-row">-->
<!--                                        <input name="newpultnum" type="tel" id="validationCustom03"-->
<!--                                               placeholder="XXX" pattern="\(\d{3}\) \d{3}\-\d{4}" class="form-control">-->
<!--                                    </div>-->
<!--                                </div>-->
                                <div class="form-row">
                                    <div class="col-md-4 mb-3">
                                        <label for="validationCustom02">Телефон владельца пульта</label>
                                    </div>
                                    <div class="form-row">
                                        <input name="newpultphone" type="tel" id="validationCustom02"
                                               placeholder="XXX-XXX-XXXX" pattern="\(\d{3}\) \d{3}\-\d{4}"
                                               class="form-control" required>
                                    </div>
                                </div>

                                <div class="modal-footer">
                                    <button name="newpult" class="btn" type="submit" style="background-color: #f4ee24">
                                        Сохранить пульт
                                    </button>
                                    <button type="button" class="btn btn-secondary" data-dismiss="modal">Отмена</button>
                                </div>
                            </form>
                        </div>
                    </div>
                </div>
            </div>


        </div>
    </div>
</body>
</html>