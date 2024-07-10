<?php
$sql=" 
SELECT res.code as resNo,res.name as resName,
st.starttime, st.endtime
from resource res
LEFT JOIN shift st ON st.code = res.code
";

$result = null;
if($result = $conn->query($sql)){
    $data = array();
    if ($result->num_rows > 0) {
        while($row = $result->fetch_assoc()) {
            $data[] = $row;
        }
        
    }
    $msg_object['status']='success';
    $msg_object['message']=$data;
}else{
    $msg_object['message']='fail to query in infoMember..';
}


?>