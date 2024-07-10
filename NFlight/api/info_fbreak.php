<?php
$sql="   
SELECT res.code as emNo,res.name as name,od.name as department,t.task_name as task,
CASE WHEN fi.sta IS NOT NULL AND fi.std IS NULL THEN concat('in_',fi.flight_num)
WHEN fi.sta IS NULL AND fi.std IS NOT NULL THEN concat('out_',fi.flight_num)
ELSE fi.flight_num
END as flightNum,
t.starttime, t.endtime ,null as fb from resource res
LEFT JOIN organization_department od ON od.id = res.department_id
LEFT JOIN task t ON t.assign_code = res.code 
LEFT JOIN flight fi ON t.flight_id = fi.id
" ;

$result = null;
if($result = $conn->query($sql)){
    $data = array();
    if ($result->num_rows > 0) {
        while($row = $result->fetch_assoc()) {
            $data[] = $row;
        }
        //array sorting
        function compareDates($a, $b) {
            $dateA = strtotime(substr($a, strpos($a, '#')+1),20);
            $dateB = strtotime(substr($b, strpos($b, '#')+1),20);
            if ($dateA == $dateB) {
                return 0;
            } else if ($dateA > $dateB) {
                return 1;
            } else {
                return -1;
            }
        }
        $resArr = [];
        foreach($data as &$value){
            $flag=true;
            foreach($resArr as&$resVal){
                if($resVal['emNo']==$value['emNo']){
                    $resVal['fb'] = $resVal['fb'].','.$value['flightNum'].'#'.$value['starttime'].'@'.$value['endtime'];
                    $flag=false;
                    break;
                }
            }
            if($flag&&$value['flightNum']!==null){
                $value['fb'] = $value['flightNum'].'#'.$value['starttime'].'@'.$value['endtime'];
                $resArr[$value['emNo']] = $value;
            }
        }
        foreach($resArr as&$resVal){
            $sortArr = explode(",",$resVal['fb'] ); 
            usort($sortArr, 'compareDates');
            $resVal['fb']= $sortArr;
        }
        foreach ($resArr as &$resVal){
            foreach($resVal['fb'] as &$fValue){
                $fValue = substr($fValue , 0,strpos($fValue, '#')).substr($fValue , strpos($fValue, '@'));
            }
        }
    }
    $msg_object['status']='success';
    $msg_object['message']=$resArr;
}else{
    $msg_object['message']='fail to query in infoFbreak..';
}
    
    
    ?>
