
<?php
////"Server=eknjiznica-db.database.windows.net;Database=E-knjiznica;User Id=eknjiznica-sa;Password=yourStrong(!)Password;TrustServerCertificate=true", ta še dela za php:
// PHP Data Objects(PDO) Sample Code:
try {
    $conn = new PDO("sqlsrv:server = tcp:eknjiznica-db.database.windows.net,1433; Database = E-knjiznica", "eknjiznica-sa", "yourStrong(!)Password");
    $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
}
catch (PDOException $e) {
    print("Error connecting to SQL Server.");
    die(print_r($e));
}

// SQL Server Extension Sample Code:
$connectionInfo = array("UID" => "eknjiznica-sa", "pwd" => "yourStrong(!)Password", "Database" => "E-knjiznica", "LoginTimeout" => 30, "Encrypt" => 1, "TrustServerCertificate" => 0);
$serverName = "tcp:eknjiznica-db.database.windows.net,1433";
$conn = sqlsrv_connect($serverName, $connectionInfo);
?> 
