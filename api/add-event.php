<?php
require_once "db.php";

$title = isset($_POST['title']) ? $_POST['title'] : "";
$start = isset($_POST['start']) ? $_POST['start'] : "";
$end = isset($_POST['end']) ? $_POST['end'] : "";
$color = isset($_POST['color']) ? $_POST['color'] : "";

$sqlInsert = "INSERT INTO tbl_events (title, start, end, color) VALUES ('".$title."','".$start."','".$end."','".$color."')";

$result = mysqli_query($conn, $sqlInsert);

if (! $result) {
    $result = mysqli_error($conn);
}
?>