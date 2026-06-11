<?php
$mysqli = new mysqli("localhost", "root", "", "narono");
if ($mysqli->connect_errno) {
    echo "Failed to connect to MySQL: " . $mysqli->connect_error;
    exit();
}
$mysqli->query("ALTER TABLE work_queue_dropoff ADD warehouse_plus FLOAT DEFAULT 0;");
echo "Column added successfully";
$mysqli->close();
