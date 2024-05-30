<?php 
include '../conn/conn.php';
$db = new DatabaseHandler();
// echo '<pre>';
// var_dump($_POST);
if(isset($_POST['plotProf'])){

    foreach ($_POST['plotProf'] as $key => $value) {
        $data = array(
            'sy' => $_POST["plotYear"],
            'semester' => $_POST["plotSem"],
            'section' => $_POST["plotSection"],
            'subject' => $_POST["plotSubj"][$key],
            'prof' => $_POST["plotProf"][$key],
            'room' => $_POST["plotRoom"][$key],
            'sMonday' => $_POST["tsMon"][$key+1],
            'eMonday' => $_POST["teMon"][$key+1],
            'sTuesday' => $_POST["tsTue"][$key+1],
            'eTuesday' => $_POST["teTue"][$key+1],
            'sWednesday' => $_POST["tsWed"][$key+1],
            'eWednesday' => $_POST["teWed"][$key+1],
            'sThursday' => $_POST["tsThu"][$key+1],
            'eThursday' => $_POST["teThu"][$key+1],
            'sFriday' => $_POST["tsFri"][$key+1],
            'eFriday' => $_POST["teFri"][$key+1],
            'sSaturday' => $_POST["tsSat"][$key+1],
            'eSaturday' => $_POST["teSat"][$key+1],
            'sSunday' => $_POST["tsSun"][$key+1],
            'eSunday' => $_POST["teSun"][$key+1],
            'course' => $_SESSION['program']
        );
        if($db->insertData('tb_scheduled',$data)){
            echo "<script>
            alert('Schedule Added')
            window.location.href='../schedule_index.php'
            </script>
            ";
        }else{
            echo "<script>
            alert('Schedule Error')
            window.location.href='../schedule_index.php'
            </script>
            ";
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
        alert('Proffessor Added')
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
        'profEmployStatus' => $_POST['profEmployStatus'],
        );
    
        $whereClause = array(
            'profID' => $_POST['profID'],
            'status' => 0
        );
    if($db->updateData('tb_professor',$data,$whereClause)){
    }   

        echo "<script>
        alert('Proffessor Updated')
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
