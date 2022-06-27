<?php
header("Content-Type: text/html; charset=utf-8");

//connection
require("param.inc.php");
$pdo = new PDO("mysql:host=" . MYHOST . ";dbname=" . MYDB, MYUSER, MYPASS);
$pdo->query("SET NAMES utf8");
$pdo->query("SET CHARACTER SET 'utf8'");


//http://127.0.0.1:8000/GetGrab.php?device=tablet?scor=123456

$requete = $pdo -> prepare("INSERT INTO `GRAB` (`ip`, `userAgent`, `scorGrab`, `device`, `dateGrab`) VALUES ('".$_SERVER['REMOTE_ADDR']."', '".$_SERVER['HTTP_USER_AGENT']."', '". htmlspecialchars($_GET['scor'])."', '". htmlspecialchars($_GET['device'])."', now());");
$requete -> execute();
/*
SQL

DROP TABLE IF EXISTS GRAB;

CREATE TABLE GRAB (
ip text NOT NULL,
userAgent text NOT NULL,
scorGrab integer NOT NULL,
device text NOT NULL,
dateGrab datetime NOT NULL,

CONSTRAINT UniqueStatistique UNIQUE (ip, userAgent)
PRIMARY KEY (ip, userAgent);

);

ALTER TABLE GRAB
ADD CONSTRAINT UC_Person UNIQUE (ip, userAgent);

*/
?>

