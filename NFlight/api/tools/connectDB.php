<?php 
/* connect to database */
$conn = new mysqli('localhost','root','','manpower');
if ($conn->connect_error) {
    $msg_object['message'] = "Cannot connect database";
}
?>