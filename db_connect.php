<?php
$serverName = "tcp:eknjiznica2.database.windows.net,1433";
$connectionOptions = array(
    "UID" => "CloudSA29084aa0",
    "PWD" => "{your_password_here}",  
    "Database" => "eknjiznica2",
    "LoginTimeout" => 30,
    "Encrypt" => true,
    "TrustServerCertificate" => false
);

$conn = sqlsrv_connect($serverName, $connectionOptions);

if ($conn === false) {
    die(print_r(sqlsrv_errors(), true));
}
// echo "Connected successfully to DB";
?>