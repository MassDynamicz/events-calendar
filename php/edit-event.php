<?php
require_once "db.php";

$id = $_POST['id'];
$title = $_POST['title'];
$start = $_POST['start'];
$end = $_POST['end'];

$sqlUpdate = "UPDATE tbl_events SET title=?, start=?, end=? WHERE id=?";
$stmt = mysqli_prepare($conn, $sqlUpdate);
mysqli_stmt_bind_param($stmt, "sssi", $title, $start, $end, $id);
mysqli_stmt_execute($stmt);
mysqli_stmt_close($stmt);
mysqli_close($conn);
?>