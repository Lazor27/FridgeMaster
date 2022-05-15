<?php
$user = 'root';
$pass = 'root';
$dbh = new PDO('mysql:host=db;dbname=laravel', $user, $pass);
$row = $dbh->query('SELECT * FROM migrations;');
var_dump($row->fetchAll());

$dbh = null;
