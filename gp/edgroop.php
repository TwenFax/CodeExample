<?php
include('head.php');

if (!(isset($_SESSION))) {
    session_start();
}
$dev = $_SESSION["dev"];
$groop = $_GET["idgroop"];

require ("dbconn.php");

$stmt = $conn->prepare("SELECT * FROM mygroops WHERE ID = :idgroop");
$stmt->execute(array(':idgroop' => $groop));

if ($stmt->rowCount()!=1) {
    $stmt->closeCursor();
    $_SESSION['Err'] = "Доступ запрещен";
    header("Location: /");
    exit();
}

$row = $stmt->fetch();

if ($dev!=$row["IDev"]) {
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
            <h2>Редактирование группы</h2>
        </div>
        <div class="col-sm-1"></div>
    </div>
    <form class="myform" action="onedev.php" data-parsley-validate="" method="get">
        <div class="row">
            <div class="col-sm-4">
                <input type='text' name='idgroop' hidden value=<?php echo $groop; ?>>


            </div>
            <div class="col-sm-8" style="padding: 15px">
                <label>
                    <?php echo("Группа: " . $row['name'] . "<br>"); ?>
                </label>
            </div>
        </div>
        <div class="row">
            <div class="col-sm-4">
                <input type="submit" class="btn" style="width: 250px; color: black; background-color: #f4ee24" value="Принять новое имя">

            </div>
            <div class="col-sm-8">
                <input type="text" class="newphone" name="newgname"
                       autofocus
                       value='<?= $row['name'] ?>'>
            </div>

        </div>
        <div class="row">
            <div class="col-sm-4" style="margin-top: 25px">
                <button type="button" class="btn" style="width: 250px; color: black; background-color: #f4ee24"><a href="/gp/onedev.php">Отменить изменения</button>

            </div>
            <div class="col-sm-8">
            </div>

        </div>
        <div class="row">
            <div class="col-sm-4" style="margin-top: 25px">
                <?php
                if ( $row['enable'] ) {
                    echo ('<button type="button" class="btn" style="width: 250px; color: black; background-color: #f4ee24"><a href="/gp/onedev.php?idgroop='.$groop.'&newgname=reset">Восстановить группу</button>');
                }
                ?>

            </div>
            <div class="col-sm-8">
            </div>
        </div>
        <div class="row">
            <div class="col-sm-4" style="margin-top: 25px">
                <?php
                echo ('<button type="button" class="btn" style="width: 250px; color: black; background-color: #f4ee24"><a href="/gp/onedev.php?idgroop='.$groop.'&newgname=del">Удалить группу</button>');
                ?>

            </div>
            <div class="col-sm-8">
            </div>
        </div>
        <div class="row">
            <div class="col-sm-4" style="margin-top: 25px">
                <?php
                echo ('<button type="button" class="btn" style="width: 250px; color: black; background-color: #f4ee24"><a href="/gp/onedev.php?idgroop='.$groop.'&newgname=block">Блокировать группу</button>');
                ?>

            </div>
            <div class="col-sm-8">
            </div>
        </div>
    </form>
</div>
</body>

