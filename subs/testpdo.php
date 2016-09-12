<?php

echo print_r(PDO::getAvailableDrivers()).'<br>';

try {
    $conn = new PDO("mssql:host=192.168.100.45,1433;dbname=jest","jestadmin","into99black");
    $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
}
catch( PDOException $e ) {
    echo '<pre>';
    die( "Error connecting to SQL Server: ". $e);
    echo '</pre>';
}

echo "Connected to SQL Server\n";
$query = 'select securityid from security where officeid=89';
$stmt = $conn->query($query);
while ($row = $stmt->fetch(PDO::FETCH_ASSOC)){
print_r($row);
}
?>