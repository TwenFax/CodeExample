<?php
$dbhost = '*********';
//$dbhost = '*********';
$db   = '***************';
$dbuser = '**************';
$dbpass = '**********';
$dbcharset = 'utf8';

$dsn = "mysql:host=$dbhost;dbname=$db;charset=$dbcharset";
$dboptions = [
PDO::ATTR_ERRMODE            => PDO::ERRMODE_EXCEPTION,
PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
PDO::ATTR_EMULATE_PREPARES   => false,
];
try {
//$conn = new PDO('mysql:host=www.glopult.ru:3306;dbname=glopultr_mygate','root','glopultr_mygate',$dboptions);
$conn = new PDO($dsn, $dbuser, $dbpass, $dboptions);
} catch (\PDOException $e) {
throw new \PDOException($e->getMessage(), (int)$e->getCode());
}
?>