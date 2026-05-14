<?php
$conn = mysqli_connect("localhost", "root", "", "narono");
if (!$conn) die("Connection failed");
$result = mysqli_query($conn, "SELECT * FROM route_plan_price LIMIT 5");
while($row = mysqli_fetch_assoc($result)) {
    print_r($row);
}
mysqli_close($conn);
?>
