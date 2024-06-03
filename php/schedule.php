<?php 
include '../conn/conn.php';
$db = new DatabaseHandler();
// echo '<pre>';
// var_dump($_POST);
$sy = $_POST['sy'];
$semester = $_POST['semester'];
$section = $_POST['section'];

$conditions = [
    'school_yr = "'.$sy.'"',
    'semester = "'.$semester.'"',
    'section_id = "'.$section.'"'
];
$sql = $db->getAllDataCourse('tb_scheduled_2',$conditions);
$row1='<table  class="table table-hover">
<thead>
    <tr>
        <th>Academic Year</th>
        <th>Semester</th>
        <th>Section</th>
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

    $start_time = date('h:i A', strtotime($row['start_time']));
    $end_time = date('h:i A', strtotime($row['end_time']));
    $timeDetails = $start_time . ' - ' . $end_time;
    $dayDetails = '';

    $day = array(1 => 'Monday', 
        2 => 'Tuesday',
        3 => 'Wednesday',
        4 => 'Thursday',
        5 => 'Friday',
        6 => 'Saturday',
        7 => 'Sunday'
    );

    // Get the specific day
    if(array_key_exists($row['day'], $day)){
        $dayDetails = $day[$row['day']];
    }

    // GET START & END
    if($row['prof_id'] == "TBA"){
        $profName = "TBA";
    }
    if($count==0){
        $row1 .="<tr>
                    <th>".$row['school_yr']."</th>
                    <th>".$row['semester']."</th>
                    <th>".strtoupper($row['secProgram'] .' '. $row['secYearlvl'] . '-' . $row['secName'] )."</th>
                </tr>";
    }
    $row2 .="
        <tr>
            <td>".$row['id']."</td>
            <td>".$row['subCode']. "-". $row['subDesc'] ."</td>
            <td>".$row['profLname']. ',' . $row['profFname'] . ' ' . $row['profMname'] . "</td>
            <td>".$row['roomBuild']. " ". $row['roomNum'] ."</td>
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