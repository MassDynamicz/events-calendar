<?php
    require_once "db.php";

    $json = array();
    $sqlQuery = "SELECT * FROM tbl_events ORDER BY id";

    $result = mysqli_query($conn, $sqlQuery);
    $eventArray = array();
    while ($row = mysqli_fetch_assoc($result)) { 
        $eventArray[] = array( 
            'id' => $row['id'], 
            'title' => $row['title'], 
            'start' => $row['start'], 
            'end' => $row['end'], 
            'color' => $row['color'] 
        ); 
    } 
    mysqli_free_result($result);

    mysqli_close($conn);

    echo json_encode($eventArray);
?>