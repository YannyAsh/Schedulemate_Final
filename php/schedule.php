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
$sql = $db->getAllDataCourse('tb_scheduled',$conditions);
$row1='<table class="table table-striped table-hover">
<thead>
    <tr>
        <th>Academic Year</th>
        <th>Semester</th>
        <th>Section</th>
    </tr>
</thead>
<tbody >';
$row2='
<table class="table table-striped table-hover">
    <thead>
        <tr>
            <th>MIS Code</th>
            <th>Subject</th>
            <th>Professor</th>
            <th>Room</th>
        </tr>
    </thead>
    <tbody>
';
$count=0;

    foreach ($sql as $row ) {
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
        </tr>
    ";
    
    $count++;

}

$row1.="  </tbody></table>";
$row2 .="</tbody></table>";

echo $row1.$row2;
?>