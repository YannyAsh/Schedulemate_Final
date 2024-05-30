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
            <th>DAY</th>
            <th>TIME</th>
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
  
    $timeDetails = '';
    $dayDetails = '';

    if($row['sMonday']!=""){
        if($timeDetails!=""){
            $dayDetails.=' / M';
            $timeDetails.= ' / '.$row['sMonday'].'-'.$row['eMonday'];
        }else{
            $dayDetails.='M';
            $timeDetails.= $row['sMonday'].'-'.$row['eMonday'];
        }
    }
    if($row['sTuesday']!=""){
        if($timeDetails!=""){
            $dayDetails.=' / T';
            $timeDetails.= ' / '.$row['sTuesday'].'-'.$row['eTuesday'];
        }else{
            $dayDetails.='T';
            $timeDetails.= $row['sTuesday'].'-'.$row['eTuesday'];
        }
        
    }
    if($row['sWednesday']!=""){
        if($timeDetails!=""){
            $dayDetails.=' / W';
            $timeDetails.= ' / '.$row['sWednesday'].'-'.$row['eWednesday'];
        }else{
            $dayDetails.='W';
            $timeDetails.= $row['sWednesday'].'-'.$row['eWednesday'];
        }
    }
    if($row['sThursday']!=""){
        if($timeDetails!=""){
            $dayDetails.=' / Th';
            $timeDetails.= ' / '.$row['sThursday'].'-'.$row['eThursday'];
        }else{
            $dayDetails.='Th';
            $timeDetails.= $row['sThursday'].'-'.$row['eThursday'];
        }
    }
    if($row['sFriday']!=""){
        if($timeDetails!=""){
            $dayDetails.=' / Fri';
            $timeDetails.= ' / '.$row['sFriday'].'-'.$row['eFriday'];
        }else{
            $dayDetails.='Fri';
            $timeDetails.= $row['sFriday'].'-'.$row['eFriday'];
        }
    }
    if($row['sSaturday']!=""){
        if($timeDetails!=""){
            $dayDetails.=' / Sat';
            $timeDetails.= ' / '.$row['sSaturday'].'-'.$row['eSaturday'];
        }else{
            $dayDetails.='Sat';
            $timeDetails.= $row['sSaturday'].'-'.$row['eSaturday'];
        }
    }
    if($row['sSunday']!=""){
        if($timeDetails!=""){
            $dayDetails.=' / Sun';
            $timeDetails.= ' / '.$row['sSunday'].'-'.$row['eSunday'];
        }else{
            $dayDetails.='Sun';
            $timeDetails.= $row['sSunday'].'-'.$row['eSunday'];
        }
    }









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
            <td>".$dayDetails."</td>
            <td>". $timeDetails."</td>
        </tr>
    ";
    
    $count++;
   
}
$row1.="  </tbody></table>";
$row2 .="</tbody></table>";

echo $row1.$row2;
?>
