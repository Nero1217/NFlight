<?php
global $conn,$msg_object,$username,$password,$startime,$endtime,$uid;
require('./tools/allowOrigin.php');
require('./tools/msgObj.php');
$data['reqType'] = "";
$username = "";
$password = "";
$starttime ="";
$endtime = "";  
$nowtime = ""; 
$uid = "";
if(isset($_POST["reqType"])){
    $data['reqType'] = $_POST["reqType"];
}
if(isset($_POST["starttime"])){
    $starttime = $_POST["starttime"];
}
if(isset($_POST["endtime"])){
    $endtime = $_POST["endtime"];
}
if(isset($_POST["nowtime"])){
    $nowtime = new Datetime($_POST["nowtime"]);
}
if(isset($_POST["yesterdayExactStartTime"])){
    $yesterdayExactStartTime = new Datetime($_POST["yesterdayExactStartTime"]);
}
if(isset($_POST["todayExactStartTime"])){
    $todayExactStartTime = new Datetime($_POST["todayExactStartTime"]);
}
if(isset($_POST["todayExactEndTime"])){
    $todayExactEndTime = new Datetime($_POST["todayExactEndTime"]);
}
if($data['reqType'] !==null&&$data['reqType'] !==""&&$data['reqType'] !==0){
    /* connect to database */
    require('./tools/connectDB.php');
    //different request types
    if($data['reqType'] =='search_flight'){
        if($starttime !==null&&$starttime !==""&&$starttime !==0&&$endtime !==null&&$endtime !==""&&$endtime !==0){
            require('./searchFlight.php');
        }else{
            $msg_object['message'] = "Invalid time.";
        }
    }else if($data['reqType'] =='search_workAva'){
        if($starttime !==null&&$starttime !==""&&$starttime !==0&&$endtime !==null&&$endtime !==""&&$endtime !==0){
            require('./search_workAva.php');
        }else{
            $msg_object['message'] = "Invalid time.";
        }
    }else if($data['reqType'] =='info_member'){
        if($starttime !==null&&$starttime !==""&&$starttime !==0&&$endtime !==null&&$endtime !==""&&$endtime !==0){
            require('./info_member.php');
        }else{
            $msg_object['message'] = "Invalid time.";
        }
    }else if($data['reqType'] =='info_fbreak'){
         if($starttime !==null&&$starttime !==""&&$starttime !==0&&$endtime !==null&&$endtime !==""&&$endtime !==0){
             require('./info_fbreak.php');
         }else{
             $msg_object['message'] = "Invalid time.";
         }
    }else if($data['reqType'] =='info_card'){
        require('./info_card.php');
    }
    else{
        $msg_object['message'] = "Invalid reqType.";
    }
    echo json_encode($msg_object);

}else{
    echo("Nothing sent to server yet..");
}


?>