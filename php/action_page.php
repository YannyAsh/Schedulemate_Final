<?php 
include '../conn/conn.php';
$db = new DatabaseHandler();
// echo '<pre>';
// var_dump($_POST);
if(isset($_POST['plotProf'])){

    // Insert data for schedule in weeks 
    foreach ($_POST['plotProf'] as $key => $value) {
        foreach ($_POST['day'] as $dayKey => $day) { // Add the schedule for days
            $data = array(
                'school_yr' => $_POST["plotYear"],
                'semester' => $_POST["plotSem"],
                'section_id' => $_POST["plotSection"],
                'subject_id' => $_POST["plotSubj"][$key],
                'prof_id' => $_POST["plotProf"][$key],
                'room_id' => $_POST["plotRoom"][$key],
                'start_time' => $_POST["start_time"][$dayKey], // Start Time
                'end_time' => $_POST["end_time"][$dayKey], // End Time
                'course' => $_SESSION['program'],
                'status' => 1, // Status means active sched - 1 Actvie 0 Not Active Okay? 
                'day' => $day // Days of the week
            );

            if (!empty($_POST["start_time"][$dayKey]) && !empty($_POST["end_time"][$dayKey])) { // check if the day is not empty! okay?
                if($db->insertData('tb_scheduled_2',$data)){ // Successful Insert
                    echo "<script>
                    alert('Schedule Added')
                    window.location.href='../schedule_index.php'
                    </script>
                    ";
                }else{
                    echo "<script>
                    alert('Schedule Schedule Has Been Used')
                    window.location.href='../schedule_index.php'
                    </script>
                    ";
                }    
            }
        }
    }


}else if(isset($_POST['prof_add_new'])){
    $data = [
        'profEmployID' => $_POST['profEmployID'],
        'profFname' => $_POST['profFname'],
        'profMname' => $_POST['profMname'],
        'profLname' => $_POST['profLname'],
        'profMobile' => $_POST['profMobile'],
        'profAddress' => $_POST['profAddress'],
        'profEduc' => $_POST['profEduc'],
        'profExpert' => $_POST['profExpert'],
        'profRank' => $_POST['profRank'],
        'profHrs' => $_POST['profHrs'],
        'profMax' => $_POST['profHrs'],
        'profEmployStatus' => $_POST['profEmployStatus'],
    ];

    if($db->insertData('tb_professor',$data)){
        echo "<script>
        alert('Professor Added')
        window.location.href='../prof_index.php'
        </script>
        ";
    }
}else if(isset($_POST['prof_update'])){
    $data = array(
        'profEmployID' => $_POST['profEmployID'],
        'profFname' => $_POST['profFname'],
        'profMname' => $_POST['profMname'],
        'profLname' => $_POST['profLname'],
        'profMobile' => $_POST['profMobile'],
        'profAddress' => $_POST['profAddress'],
        'profEduc' => $_POST['profEduc'],
        'profExpert' => $_POST['profExpert'],
        'profRank' => $_POST['profRank'],
        'profHrs' => $_POST['profHrs'],
        'profMax' => $_POST['profHrs'],
        'profEmployStatus' => $_POST['profEmployStatus'],
        );
    
        $whereClause = array(
            'profID' => $_POST['profID'],
            'status' => 0
        );
    if($db->updateData('tb_professor',$data,$whereClause)){
    }   

        echo "<script>
        alert('Professor Updated')
        window.location.href='../prof_index.php'
        </script>
        ";
}else if(isset($_POST['del_profID'])){
    $data = array(
        'status' => 1,
        
        );
    
        $whereClause = array(
            'profID' => $_POST['del_profID'],
            'status' => 0
        );
    if($db->updateData('tb_professor',$data,$whereClause)){
    }   

        echo "<script>
        alert('Proffessor Deleted')
        window.location.href='../prof_index.php'
        </script>
        ";
}else if(isset($_POST['room_add_new'])){
    $data = [
        'roomBuild' => $_POST['roomBuild'],
        'roomFloornum' => $_POST['roomFloornum'],
        'roomNum' => $_POST['roomNum'],
    ];

    if($db->insertData('tb_room',$data)){
        echo "<script>
        alert('Room Added')
        window.location.href='../room_index.php'
        </script>
        ";
    }
}else if(isset($_POST['deactivate_schedule'])){
    $data = array(
        'status' => 1,
        );
    
        $whereClause = array(
            'sy' => $_POST['deac_sy'],
            'semester' => $_POST['deac_semester'],
            'section' => $_POST['deac_section'],
        );
    if($db->updateData('tb_scheduled',$data,$whereClause)){
    }   

        echo "<script>
        alert('Schedule Deleted')
        window.location.href='../schedule_index.php'
        </script>
        ";
}else if(isset($_POST['schedule_edit_id'])){
    // EDITING THE SCHEDULE
    
$schedule = $_POST['schedule_edit_id'];
// echo '<pre>';
// var_dump($_POST);

foreach ($schedule as $key => $value) {

    $sec_edit = $_POST['sec_edit'][$key];
    $prof_edit = $_POST['prof_edit'][$key];
    $schedule_edit_id = $_POST['schedule_edit_id'][$key];

    $sMonday = $_POST["sMonday"][$key];
    $eMonday = $_POST["eMonday"][$key];
    $sTuesday = $_POST["sTuesday"][$key];
    $eTuesday = $_POST["eTuesday"][$key];
    $sWednesday = $_POST["sWednesday"][$key];
    $eWednesday = $_POST["eWednesday"][$key];
    $sThursday = $_POST["sThursday"][$key];
    $eThursday = $_POST["eThursday"][$key];
    $sFriday = $_POST["sFriday"][$key];
    $eFriday = $_POST["eFriday"][$key];
    $sSaturday = $_POST["sSaturday"][$key];
    $eSaturday = $_POST["eSaturday"][$key];
    $sSunday = $_POST["sSunday"][$key];
    $eSunday = $_POST["eSunday"][$key];


    $data = array(
        'section' => $sec_edit,
        'prof' => $prof_edit,
        'sMonday' => $sMonday,
        'eMonday' => $eMonday,
        'sTuesday' => $sTuesday,
        'eTuesday' => $eTuesday,
        'sWednesday' => $sWednesday,
        'eWednesday' => $eWednesday,
        'sThursday' => $sThursday,
        'eThursday' => $eThursday,
        'sFriday' => $sFriday,
        'eFriday' => $eFriday,
        'sSaturday' => $sSaturday,
        'eSaturday' => $eSaturday,
        'sSunday' => $sSunday,
        'eSunday' => $eSunday,
        );
    
        $whereClause = array(
            'id' => $schedule_edit_id,
        );
    if($db->updateData('tb_scheduled',$data,$whereClause)){
    }   
}
echo "<script>
alert('Schedule Edited Successfully')
window.location.href='../schedule_index.php'
</script>
";
}else{
    echo "<script>
    alert('it seems there is an empty in your forms, please verify your transaction again')
    window.location.href='../schedule_index.php'
    </script>
    ";
}