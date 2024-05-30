<?php 
include '../conn/conn.php';
$db = new DatabaseHandler();
// echo '<pre>';
// var_dump($_POST);
$sy = $_POST['sy'];
$semester = $_POST['semester'];
$section = $_POST['section'];

$conditions = ['sy= "'.$sy.'"',
'semester= "'.$semester.'"',
'section= "'.$section.'"',
];
$sql = $db->getAllRowsFromTableWhere('tb_scheduled',$conditions);
// var_dump($sql);
$row1='<table  class="table table-hover">
<thead>
    <tr>
        <th>Academic Year</th>
        <th>Semester</th>
        <th>Program & Section</th>
    </tr>
</thead>
<tbody >';
$row2='
<table class="table table-hover">
    <thead>
        <tr>
            <th>MIS Code</th>
            <th>Subject</th>
            <th>Professor</th>
            <th>Room</th>
            <th>Hours & Minutes</th>
        </tr>
    </thead>
    <tbody>
';
$count=0;


    foreach ($sql as $row ) {
$currentLowestTime='';
$currentHighestTime='';

    $profFName = $db->getIdByColumnValue('tb_professor','profID',$row['prof'],'profFName');
    $profMname = $db->getIdByColumnValue('tb_professor','profID',$row['prof'],'profMname');
    $profLname = $db->getIdByColumnValue('tb_professor','profID',$row['prof'],'profLname');
    // ---------------------------
    $timeDiffSeconds = 0; // Initialize with zero duration
    $timeDiffSeconds += getTimeDifferenceInSeconds($row['sMonday'],$row['eMonday']);
    $timeDiffSeconds += getTimeDifferenceInSeconds($row['sTuesday'],$row['eTuesday']);
    $timeDiffSeconds += getTimeDifferenceInSeconds($row['sWednesday'],$row['eWednesday']);
    $timeDiffSeconds += getTimeDifferenceInSeconds($row['sThursday'],$row['eThursday']);
    $timeDiffSeconds += getTimeDifferenceInSeconds($row['sFriday'],$row['eFriday']);
    $timeDiffSeconds += getTimeDifferenceInSeconds($row['sSaturday'],$row['eSaturday']);
    $timeDiffSeconds += getTimeDifferenceInSeconds($row['sSunday'],$row['eSunday']);
    // Convert total seconds to hours and minutes
    $hours = floor($timeDiffSeconds / 3600);
    $minutes = floor(($timeDiffSeconds % 3600) / 60);
    // ---------------------------

    // GET START & END

    
    $time1 = ($row['sMonday']);
    $time2 = ($row['sTuesday']);
    $time3 = ($row['sWednesday']);
    $time4 = ($row['sThursday']);
    $time5 = ($row['sFriday']);
    $time6 = ($row['sSaturday']);
    $time7 = ($row['sSunday']);

    $stime1 = ($row['eMonday']);
    $stime2 = ($row['eTuesday']);
    $stime3 = ($row['eWednesday']);
    $stime4 = ($row['eThursday']);
    $stime5 = ($row['eFriday']);
    $stime6 = ($row['eSaturday']);
    $stime7 = ($row['eSunday']);

    $currentLowestTime = WhichTime($currentLowestTime,$time1,'small');
    $currentLowestTime = WhichTime($currentLowestTime,$time2,'small');
    $currentLowestTime = WhichTime($currentLowestTime,$time3,'small');
    $currentLowestTime = WhichTime($currentLowestTime,$time4,'small');
    $currentLowestTime = WhichTime($currentLowestTime,$time5,'small');
    $currentLowestTime = WhichTime($currentLowestTime,$time6,'small');
    $currentLowestTime = WhichTime($currentLowestTime,$time7,'small');

    $currentHighestTime = WhichTime($currentHighestTime,$stime1,'high');
    $currentHighestTime = WhichTime($currentHighestTime,$stime2,'high');
    $currentHighestTime = WhichTime($currentHighestTime,$stime3,'high');
    $currentHighestTime = WhichTime($currentHighestTime,$stime4,'high');
    $currentHighestTime = WhichTime($currentHighestTime,$stime5,'high');
    $currentHighestTime = WhichTime($currentHighestTime,$stime6,'high');
    $currentHighestTime = WhichTime($currentHighestTime,$stime7,'high');

    // GET START & END

    $profName = $profFName.' '.$profMname.' '.$profLname;
    if($row['prof']=="TBA"){
        $profName ="TBA";
    }
    if($count==0){
        $row1 .="<tr>
                    <th>".$row['sy']."</th>
                    <th>".$row['semester']."</th>
                    <th>".strtoupper($row['course']).'/'.$row['section']."</th>
                </tr>";
    }
    $row2 .="
        <tr>
            <td>".$row['id']."</td>
            <td>".$row['subject']."</td>
            <td>".$profName."</td>
            <td>".$row['room']."</td>
            <td>". $currentLowestTime.'-'.$currentHighestTime."</td>
        </tr>
    ";
    
    $count++;
   
}
$row1.="  </tbody></table>";
$row2 .="</tbody></table>";

echo $row1.$row2;
?>


<?php 
function getTimeDifference($startTime, $endTime) {
    $startDateTime = DateTime::createFromFormat('H:i', $startTime);
    $endDateTime = DateTime::createFromFormat('H:i', $endTime);

    // Calculate the difference
    $interval = $startDateTime->diff($endDateTime);

    // Return the difference
    return $interval;
}
function getTimeDifferenceInSeconds($startTime, $endTime) {
    if($startTime!="" && $endTime!=""){
        $startDateTime = DateTime::createFromFormat('H:i', $startTime);
        $endDateTime = DateTime::createFromFormat('H:i', $endTime);
        $interval = $startDateTime->diff($endDateTime);
        return $interval->h * 3600 + $interval->i * 60 + $interval->s;
    }else{
        return 0;
    }
   
}

function WhichTime($time1, $time2,$type){
    $time = '';
    
    if($type=="small"){
        if($time1 == ""){
            return $time2;
        } else {
            if($time1 !="" && $time2 !=""){
                $time1 = strtotime($time1);
                $time2 = strtotime($time2);
                if ($time1 < $time2) {
                    $time = $time1;
                } else {
                    $time = $time2;
                }
            } else {
                $time = $time1;
            }
            
        }
    }else if($type=="high"){
        if($time1 == ""){
            return $time2;
        } else {
            if($time1 !="" && $time2 !=""){
                $time1 = strtotime($time1);
                $time2 = strtotime($time2);
                if ($time1 > $time2) {
                    $time = $time1;
                } else {
                    $time = $time2;
                }
            } else {
                $time = $time1;
            }
            
        }
    }
    
    return is_int($time) ? date("H:i", $time) : $time; // Format hours and minutes only if $time is an integer timestamp
}


?>