<?php 
$sql="
SELECT fi.flight_num, fi.sta, fi.ata, fi.std, fi.atd, fi.ORIGIN as origin, fi.DEST as dest, fi.AC_TYPE as ac_type, fi.STAND as stand, t.employeeNo, t.department, t.task_name, t.starttime,t.endtime,
null as sp, null as ld, null as eo, null as rw
FROM flight fi
LEFT JOIN (SELECT t.task_name, t.flight_id, t.starttime, t.endtime,t.assign_code,res.code as employeeNo, od.name as department,t.deleted from task t
LEFT JOIN (SELECT res.code, res.name, res.department_id, res.deleted from resource res)res ON res.code = t.assign_code AND res.deleted != 1
LEFT JOIN (SELECT od.id, od.name, od.deleted from organization_department od)od ON od.id = res.department_id AND od.deleted != 1
) t ON fi.id = t.flight_id AND t.deleted != 1
WHERE t.starttime >='2024-05-05 00:00' AND t.starttime <='2024-05-05 23:59:59'
AND fi.deleted != 1 
" ;

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
                if($flightVal['flight_num']==$value['flight_num']){
                    $flightVal[$value['department']]+=1;
                    $flag =false;
                    break;
                }
            }
            if($flag){
                $value['sp'] = 0;$value['ld'] = 0;$value['eo'] = 0;$value['rw'] = 0;
                $value[$value['department']]+=1;
                $flightArr[] = $value;
            }
        }
        
    }
    $msg_object['status']='success';
    $msg_object['message']=$flightArr;
}else{
    $msg_object['message']='fail to query in searchFlight..';
}

?>