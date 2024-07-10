<?php
$sql=" 
SELECT res.code as resNo,res.name as resName,od.name as department,
t.task_name as task,fi.flight_num as flightNum,
fi.ORIGIN,fi.DEST,fi.AC_TYPE,fi.REG,fi.STAND,fi.BAG,
fi.sta,fi.std,fi.ata,fi.atd,fi.date,
null as sp, null as ld, null as eo, null as eo, null as rw
from resource res
LEFT JOIN organization_department od ON od.id = res.department_id
LEFT JOIN task t ON t.assign_code = res.code 
LEFT JOIN flight fi ON t.flight_id = fi.id
";

$result = null;
if($result = $conn->query($sql)){
    $data = array();
    if ($result->num_rows > 0) {
        while($row = $result->fetch_assoc()) {
            $data[] = $row;
        }
        $flightArr = [];
        foreach ($data as &$value ){
            $flag= true;
            foreach($flightArr as&$flightVal){
                if($flightVal['flightNum']==$value['flightNum']){
                    if($flightVal[$value['department']]==''){
                        $flightVal[$value['department']] = $value['resNo'];
                    }else{
                        $flightVal[$value['department']] = $flightVal[$value['department']].','.$value['resNo'];
                    }
                    
                    $flag =false;
                    break;
                }
            }
            if($flag&&$value['flightNum']!==null){
                $value['sp'] = "";$value['ld'] = "";$value['eo'] = "";$value['rw'] = "";
                $value[$value['department']] = $value['resNo'];
                $flightArr[$value['flightNum']] = $value;
            }
        }
    }
    $msg_object['status']='success';
    $msg_object['message']=$flightArr;
}else{
    $msg_object['message']='fail to query in infoMember..';
}


?>