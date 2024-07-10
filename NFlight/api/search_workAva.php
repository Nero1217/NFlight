<?php 
$sql="
SELECT res.code as resId, od.name as department,CONCAT(sf.starttime,'&',sf.endtime) as shift, CONCAT(t.starttime,'&',t.endtime) as task FROM resource res
LEFT JOIN organization_department od ON od.id = res.department_id
LEFT JOIN shift sf ON sf.code = res.code
LEFT JOIN task t ON t.assign_code = res.code
LEFT JOIN flight fi ON fi.id = t.flight_id
WHERE res.deleted != 1
ORDER BY res.code
" ;
$result = null;
$resArr = Array();
$atWork = Array(
	"sp"=>0,
	"ld"=>0,
	"eo"=>0,
	"rw"=>0
);
$available = Array(
	"sp"=>0,
	"ld"=>0,
	"eo"=>0,
	"rw"=>0
);
if($result = $conn->query($sql)){
	$data = array();
	if ($result->num_rows > 0) {
		while($row = $result->fetch_assoc()){
			$data[] = $row;
		}
		foreach($data as&$value){
			$flag = false;
			foreach($resArr as&$resVal){
				if($resVal['resId']==$value['resId']){
					$resVal['task'] = $resVal['task'].','.$value['task'];
					$flag=true;
					break;
				}
			}
			if($flag==false){
				$resArr[] = $value;
			}
		}
		$nowtime = new DateTime('2024-05-05 12:00:00');
		foreach($resArr as&$resVal){
			$shiftStart = new DateTime(substr($resVal['shift'],0,strpos($resVal['shift'],'&')));
			$shiftEnd = new DateTime(substr($resVal['shift'],strpos($resVal['shift'],'&')+1));
			if($nowtime >= $shiftStart && $nowtime <= $shiftEnd){
				$atWork[$resVal['department']] +=1;
				$taskArr = explode(',',$resVal['task']);
				$flag=true;
				foreach($taskArr as&$taskVal){
					$taskStart = new DateTime(substr($taskVal,0,strpos($taskVal,'&')));
					$taskEnd = new DateTime(substr($$taskVal,strpos($taskVal,'&')+1));
					if($nowtime >= $taskStart && $nowtime <= $taskEnd){
						$flag =false;
						break;
					}
				}
				if($flag){
					$available[$resVal['department']] +=1;
				}
			}
		}
	}
	$msg_object['status']='success';
	$msg_object['message']=[
		'atWork'=>$atWork,
		'available'=>$available
	];
}else{
	$msg_object['message']='fail to query in searchWork..';
}


?>