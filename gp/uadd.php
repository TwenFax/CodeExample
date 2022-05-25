<?php
if(!($_SESSION)) {
    session_start();
}
$user = $_SESSION["user"];
if ($user!=1) {
    $_SESSION['Err'] = "Только для администратора";
    header("Location: /");
    exit();
}
require ("dbconn.php");

$name = $_POST["Name"];
$login = $_POST["Login"];
if ($login==""){
    $_SESSION['Err'] = "Пустой логин";
    header("Location: /");
    exit();
}
$passw = $_POST["Password"];
$email = $_POST["EMail"];
$phone = $_POST["Telephone"];
$balance = 0;
$tarif = 0;
$hash = password_hash($passw,PASSWORD_DEFAULT);

$stmt = $conn->query("SELECT * FROM myusers WHERE login='" . $login . "'");
if ( !$stmt ){
    $_SESSION['Err'] = "Нет тоступа к БД пользователей";
    header("Location: /");
    exit();
}
if ($stmt->rowCount()) {
    $stmt->closeCursor();
    $_SESSION['Err'] = "Не уникальный логин";
    header("Location: /");
    exit();
}
$stmt->closeCursor();
$sq= "INSERT INTO myusers (name,login,phone,email,passw,balance,tarif) VALUES ('".$name."','".$login."','".$phone."','".$email."','".$hash."','".$balance."','".$tarif."')";
//echo $sq;
$stmt = $conn->query($sq);
$stmt->closeCursor();
$_SESSION['Err'] = "Пользователь добавлен";
header("Location:/");
?>