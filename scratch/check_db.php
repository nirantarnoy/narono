<?php
$conn = mysqli_connect("localhost", "root", "", "narono");
if (!$conn) die("Connection failed");
$table = isset($argv[1]) ? $argv[1] : "work_queue";
$result = mysqli_query($conn, "DESCRIBE $table");
if (!$result) die("Table $table not found");
while($row = mysqli_fetch_assoc($result)) {
    echo $row['Field'] . "\n";
}
mysqli_close($conn);
?>
