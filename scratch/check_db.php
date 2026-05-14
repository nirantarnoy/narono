<?php
$conn = mysqli_connect("localhost", "root", "", "narono");
if (!$conn) die("Connection failed");
$result = mysqli_query($conn, "DESCRIBE work_queue");
while($row = mysqli_fetch_assoc($result)) {
    echo $row['Field'] . "\n";
}
mysqli_close($conn);
?>
