
<?php
$dbConn = new mysqli('localhost', 'unn_w16005124', 'Laptop3299', 'unn_w16005124');

if ($dbConn->connect_error) {
    echo "<p>Connection failed: ".$dbConn->connect_error."</p>\n";
    exit;
}
?>
