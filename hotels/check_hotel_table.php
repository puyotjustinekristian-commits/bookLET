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

$sql = "IF NOT EXISTS (SELECT * FROM sysobjects WHERE name='HOTEL_BOOKING' AND xtype='U')
    CREATE TABLE HOTEL_BOOKING (
        BOOKING_ID INT IDENTITY(1,1) PRIMARY KEY,
        USER_ID INT,
        HOTEL_NAME NVARCHAR(255),
        CHECK_IN DATE,
        CHECK_OUT DATE,
        ROOMS INT,
        GUESTS INT,
        TOTAL_PRICE DECIMAL(10, 2),
        PAYMENT_ID NVARCHAR(255),
        STATUS NVARCHAR(50) DEFAULT 'PENDING',
        CREATED_AT DATETIME DEFAULT GETDATE(),
        FIRST_NAME NVARCHAR(100),
        LAST_NAME NVARCHAR(100),
        EMAIL NVARCHAR(100),
        CONTACT_NUMBER NVARCHAR(50)
    )";

$stmt = sqlsrv_query($conn, $sql);
if ($stmt) {
    echo "Table HOTEL_BOOKING checked/created successfully.";
} else {
    echo "Error creating table: " . print_r(sqlsrv_errors(), true);
}
?>
