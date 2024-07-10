<?php 
$msg_object = new stdClass;
$msg_object= Array(
    "status"=>"fail",
    "message"=>""
);
$request = json_decode(file_get_contents("php://input"));
?>