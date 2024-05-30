<?php
include_once('db.php');

$plotYear = "";
$plotSem = "";
$plotSubj = "";
$plotSection = "";
$plotRoom = "";
$plotProf = "";
$plotDay = "";
$plotTimeStart = 0;
$plotTimeEnd = 0;
$plotID = 0;
$sched_edit_state = false;

//saving records
if (isset($_POST['sched_add_new'])) {
    $plotYear = $_POST["plotYear"];
    $plotSem = $_POST["plotSem"];
    $plotSubj = $_POST["plotSubj"];
    $plotSection = $_POST["plotSection"];
    $plotRoom = $_POST["plotRoom"];
    $plotProf = $_POST["plotProf"];
    // echo"<pre>";
    // var_dump($plotYear);
    // echo"</pre>";
    // die;

    for ($i = 1; $i < count($subCode); $i++) {
        $stmt = $conn->prepare("INSERT INTO tb_plotting (plotYear, plotSem, plotSubj, plotSection, plotRoom, plotProf) VALUES (?, ?, ?, ?, ?, ?)");
        $stmt->bind_param("ssssss", $plotYear, $plotSem, $plotSubj[$i], $plotSection, $plotRoom[$i], $plotProf[$i]);
        $stmt->execute();
        
    }

    $result = $conn->query("SELECT * FROM tb_plotting ORDER BY plotID DESC limit 1");
    $row = $result->fetch_row();
    // echo"<pre>";
    // var_dump($row[0]);
    // echo"</pre>";
    // die;

    if ($stmt) {
        $_SESSION['message'] = "Schedule Details Saved Successfully";
        header("Location: schedule_index.php");
    } else {
        echo "Error: ";
    }
    $stmt->close();
}



//For updating records
if (isset($_POST['sched_update'])) {
    $plotSubj = $_POST["plotSubj"];
    $plotSection = $_POST["plotSection"];
    $plotRoom = $_POST["plotRoom"];
    $plotProf = $_POST["plotProf"];
    $plotWeek  = $_POST["plotWeek"];
    $plotTimeStart  = $_POST["plotTimeStart"];
    $plotTimeEnd = $_POST["plotTimeEnd"];
    $plotID = $_POST['plotID'];

    $stmt = $conn->prepare("UPDATE tb_plotting SET plotSubj=?, plotSection=?, plotRoom=?, plotProf=?, plotWeek=? , plotTimeStart=?, plotTimeEnd=? WHERE plotID=?");
    $stmt->bind_param("sssssssi", $plotSubj[$i], $plotSection, $plotRoom[$i], $plotProf[$i], $plotID);
    $stmt->execute();

    if ($stmt) {
        $_SESSION["message"] = "Schedule Details Updated Successfully";
        header('Location:   schedule_index.php');
    } else {
        echo "Error: ";
    }
    $stmt->close();
}
