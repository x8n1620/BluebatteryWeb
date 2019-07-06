<?php
$db_host = "localhost"; // Host-Adresse
$db_name = "bluebattery"; // Datenbankname
$db_user = "<username>"; // Benutzername
$db_pwd = "<Password>"; // Passwort

error_reporting(E_ALL);
//error_reporting(0);
$db = new mysqli("$db_host", "$db_user", "$db_pwd", "$db_name");


print_r ($db->connect_error);

if ($db->connect_errno) {
    die('Es ist ein Problem aufgetreten');
}
?>
