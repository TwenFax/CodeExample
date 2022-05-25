<?php

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

$_SESSION['groop'] = $groop;
$_SESSION['groop_name'] = $row["name"];

$stmt->closeCursor();
header('Refresh: 0; url="/gp/onedev.php"');
exit();
