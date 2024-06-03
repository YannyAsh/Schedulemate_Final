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
                    alert('Schedule Has Been Used')
                    window.location.href='../schedule_index.php'
                    </script>
                    ";
                }    
            }
        }
    }


}else if(isset($_POST['deactivate_schedule'])){
    $data = array(
        'status' => 0,
        );
    
        $whereClause = array(
            'sy' => $_POST['deac_sy'],
            'semester' => $_POST['deac_semester'],
            'section' => $_POST['deac_section'],
        );
    if($db->updateData('tb_scheduled_2',$data,$whereClause)){
    }   

        echo "<script>
        alert('Schedule Deleted')
        window.location.href='../schedule_index.php'
        </script>
        ";
} else if(isset($_POST['sched_edit_new'])){
        // EDITING THE SCHEDULE
        
    $day = $_POST['day'];
    //echo '<pre>';
    //var_dump($_POST);
    $schedule_edit_ids = $_POST['schedID'];
    foreach ($day as $key => $value) {
        $subj_edit = $_POST['plotSubj2'][$key];
        $room_edit = $_POST['plotRoom2'][$key];
        $day = $_POST['day'][$key];
        $data = array(
            'subj_id' => $subj_edit,
            'room_id' => $room_edit,
        );

        $whereClause = array(
            'id' => $schedule_edit_ids,
        );
        if($db->updateData('tb_scheduled_2',$data,$whereClause)){
            echo "<script>
            alert('Schedule Edited Successfully')
            window.location.href='../schedule_index.php'
            </script>
            ";
        }   
    }
    
 } else {
    echo "<script>
    alert('it seems there is an empty in your forms, please verify your transaction again')
    window.location.href='../schedule_index.php'
    </script>
    ";
}
