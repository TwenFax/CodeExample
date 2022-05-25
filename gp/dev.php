<?php
if (!(isset($_SESSION))) {
    session_start();
}

$user = $_SESSION["user"];
$_SESSION['Err'] = "";

require ("dbconn.php");

$dev = $_GET["idev"];

$stmt = $conn->prepare("SELECT * FROM mydev WHERE ID = :iddev");
$stmt->execute(array(':iddev' => $dev));

if ($stmt->rowCount()!=1) {
    $stmt->closeCursor();
    $_SESSION['Err'] = "Доступ запрещен";
    header("Location: /");
    exit();
}

$row = $stmt->fetch();

if ($user!=$row["IDuser"]) {
    $stmt->closeCursor();
    $_SESSION['Err'] = "Доступ запрещен по ID";
    header("Location: /");
    exit();
}

$_SESSION["dev"] = $row["ID"];
$_SESSION["dname"] = $row["name"];
$_SESSION["dphone"] = $row["phone"];
$stmt->closeCursor();
header('Refresh: 0; url="/gp/onedev.php"');
exit();
