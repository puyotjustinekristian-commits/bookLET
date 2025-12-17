<?php
$serverName = "JustinePuyot\SQLEXPRESS";
$connectionOptions = [
    "Database" => "BOOKLET",
    "Uid" => "",
    "PWD" => ""
];

$conn = sqlsrv_connect($serverName, $connectionOptions);
if (!$conn) {
    die("Connection failed: " . print_r(sqlsrv_errors(), true));
}

$sql = "SELECT name, is_identity FROM sys.columns WHERE object_id = OBJECT_ID('HOTEL_BOOKING')";
$stmt = sqlsrv_query($conn, $sql);

if ($stmt === false) {
    die(print_r(sqlsrv_errors(), true));
}

echo "<h2>Table Structure: HOTEL_BOOKING</h2>";
echo "<table border='1'><tr><th>Column Name</th><th>Is Identity?</th></tr>";
while ($row = sqlsrv_fetch_array($stmt, SQLSRV_FETCH_ASSOC)) {
    echo "<tr>";
    echo "<td>" . $row['name'] . "</td>";
    echo "<td>" . ($row['is_identity'] ? '<b>YES</b>' : 'No') . "</td>";
    echo "</tr>";
}
echo "</table>";
?>
