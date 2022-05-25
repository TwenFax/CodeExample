<?php
include ("head.php");
if (!(isset($_SESSION))) {
    session_start();
}
$_SESSION["user"] = 0;

$_SESSION['Err'] = "";
include ("dbconn.php");
$login = $_SESSION["Login"];
$passw = $_SESSION["Password"];


$stmt = $conn->prepare("SELECT * FROM myusers WHERE login = :login");
$stmt->execute(array(':login' => $login));
$row = $stmt->fetch();
if (!$row) {
    $stmt->closeCursor();
    $_SESSION['Err'] = "Пользователь не найден";
    header("Location: /");
    exit();
}
if (!password_verify($passw, $row["passw"])) {
    $stmt->closeCursor();
    $_SESSION['Err'] = "Неверный пароль";
    header("Location: /");
    exit();
}

$user = $row["ID"];
$uname = $row["name"];

$_SESSION["user"] = $user;
$_SESSION["uname"] = $row["name"];
$_SESSION["uemail"] = $row["email"];
$_SESSION["uphone"] = $row["phone"];
$_SESSION["ubalance"] = $row["balance"];
$_SESSION["utarif"] = $row["tarif"];
$_SESSION['f1p'] = $row['f1p'];
$_SESSION['f1g'] = $row['f1g'];
$_SESSION['f2p'] = $row['f2p'];
$_SESSION['f2g'] = $row['f2g'];
$stmt->closeCursor();


if ($user == 1) {
    include("loginadmin.php");
}
else {
    unset($_SESSION['groop']);
    $stmt = $conn->prepare("SELECT * FROM mydev WHERE IDuser = :iduser");
    $stmt->execute(array(':iduser' => $user));
    $devs = $stmt->rowCount();
    $_SESSION['devCol'] = $devs;
    if ($devs == 0) {
        $_SESSION['user'] = 0;
        $_SESSION['Err'] = "Не зарегистрированы устройства";
        $stmt->closeCursor();
        header("Location: /");
        exit();
    }
    if ($devs == 1) {
        $row=$stmt->fetch(PDO::FETCH_ASSOC);
        $_SESSION["dev"] = $row["ID"];
        $_SESSION["dname"] = $row["name"];
        $_SESSION["dphone"] = $row["phone"];
        $stmt->closeCursor();
        header('Refresh: 0; url="/gp/onedev.php"');
        exit();
    } else {
        include("seldev.php");
    }
}
