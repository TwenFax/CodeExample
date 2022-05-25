<?php
include('head.php');
if (!(isset($_SESSION))) {
    session_start();
}
$dev = $_SESSION["dev"];
$groop = $_SESSION['groop'];
$idPult = $_GET["idpult"];

require("dbconn.php");

$stmt = $conn->prepare("SELECT * FROM mypult WHERE ID = :idpult");
$stmt->execute(array(':idpult' => $idPult));

if ($stmt->rowCount() != 1) {
    $stmt->closeCursor();
    $_SESSION['Err'] = "Доступ запрещен";
    header("Location: /");
    exit();
}

$row = $stmt->fetch();

if ($groop != $row["IDgroop"]) {
    $stmt->closeCursor();
    $_SESSION['Err'] = "Доступ запрещен по ID";
    header("Location: /");
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
                <li><a><span style="color: yellow">info@glopult.ru</span></a></li>
                <li><a href="/index.php">Выход</a></li>
                <li><a href="/gp/onedev.php">Отмена</a></li>
            </ul>
        </div>
    </div>
</nav>
<div class="container-fluid" style="padding: 1.5cm">
    <div class="row">
        <div class="col-sm-3 "></div>
        <div class="col-sm-8">
            <h2>Редактирование телефонного номера пульта</h2>
        </div>
        <div class="col-sm-1"></div>
    </div>
    <form class="myform" action="onedev.php" data-parsley-validate="" method="get">
        <div class="row">
            <div class="col-sm-4">
                <input type='text' name='idpult' hidden value=<?php echo $idPult; ?>>


            </div>
            <div class="col-sm-8" style="padding: 15px">
                <label>
                    <?php echo("Владелец пульта: " . $row['name'] . "<br>
                        Текущий телефон пульта: " . $row['phone'] . "<br>"); ?>
                </label>
            </div>
        </div>
        <div class="row">
            <div class="col-sm-4">
                <?php
                if ($row['enable']>=0) {
                    echo ('<input type="submit" class="btn" style="width: 250px; color: black; background-color: #f4ee24" value="Принять новый номер">');
                }
                ?>


            </div>
            <div class="col-sm-8">
                <input type="tel" class="newphone" name="newph"
                       autofocus
                       value=<?= $row['phone'] ?>>
            </div>

        </div>
        <div class="row">
            <div class="col-sm-4" style="margin-top: 25px">
                <button type="button" class="btn" style="width: 250px; color: black; background-color: #f4ee24"><a href="/gp/onedev.php">Отменить изменения и вернуться</button>

            </div>
            <div class="col-sm-8">
            </div>

        </div>
        <div class="row">
            <div class="col-sm-4" style="margin-top: 25px">
                <?php
                if ($row['enable']!=0) {
                    echo ('<button type="button" class="btn" style="width: 250px; color: black; background-color: #f4ee24"><a href="/gp/onedev.php?idpult='.$idPult.'&newph=reset">Восстановить номер</button>');
                }
                ?>

            </div>
            <div class="col-sm-8">
            </div>
        </div>
        <div class="row">
            <div class="col-sm-4" style="margin-top: 25px">
                <?php
                if ($row['enable']>=0) {
                    echo('<button type="button" class="btn" style="width: 250px; color: black; background-color: #f4ee24"><a href="/gp/onedev.php?idpult=' . $idPult . '&newph=del">Удалить номер</button>');
                }
                ?>

            </div>
            <div class="col-sm-8">
            </div>
        </div>
        <div class="row">
            <div class="col-sm-4" style="margin-top: 25px">
                <?php
                if ($row['enable']>=0) {
                    echo ('<button type="button" class="btn" style="width: 250px; color: black; background-color: #f4ee24"><a href="/gp/onedev.php?idpult='.$idPult.'&newph=block">Блокировать номер</button>');
                }
                ?>

            </div>
            <div class="col-sm-8">
            </div>
        </div>
    </form>
</div>
</body>
