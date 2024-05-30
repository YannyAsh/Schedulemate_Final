<?php
session_start();

include_once('db.php');

$secProgram = "";
$secYearlvl = 0;
$secName = "";
$secSession = 0;
$secStatus = 0;
$secID = 0;
$sec_edit_state = false;

//saving records
if (isset($_POST['sec_add_new'])) {
    $secProgram = $_POST["secProgram"];
    $secYearlvl = $_POST["secYearlvl"];
    $secName = $_POST["secName"];
    $secSession = $_POST["secSession"];
    $secStatus = $_POST["secStatus"];
    $secCourse = $_SESSION["program"];

    // Check for duplicate entry 
    $stmt = $conn->prepare("SELECT COUNT(*) FROM tb_section WHERE secYearlvl=? AND secName=?");
    $stmt->bind_param("is", $secYearlvl, $secName);
    $stmt->execute();
    $stmt->bind_result($count);
    $stmt->fetch();
    $stmt->close();

    if ($count > 0) {
        $_SESSION['message'] = "Error: Duplicate entry";
        header("Location: section_index.php");
        exit();
    }

    //Add new data to the database
    $stmt = $conn->prepare("INSERT INTO tb_section (secProgram, secYearlvl, secName, secSession, secStatus,secCourse) VALUES (?, ?, ?, ?, ?,?)");
    $stmt->bind_param("sissss", $secProgram, $secYearlvl, $secName, $secSession, $secStatus,$secCourse);
    $stmt->execute();

    if ($stmt) {
        $_SESSION['message'] = "Information Saved Successfully";
        header("Location: section_index.php");
    } else {
        die("Something went wrong");
    }
    $stmt->close();
}


//For updating records
if (isset($_POST['sec_update'])) {
    $secProgram = $_POST["secProgram"];
    $secYearlvl = $_POST["secYearlvl"];
    $secName = $_POST["secName"];
    $secSession = $_POST["secSession"];
    $secStatus = $_POST["secStatus"];
    $secID = $_POST['secID'];

    // Fetch the current data from the database
    $currentDataQuery = "SELECT * FROM tb_section WHERE secID = ?";
    $currentDataStmt = $conn->prepare($currentDataQuery);
    $currentDataStmt->bind_param("i", $secID);
    $currentDataStmt->execute();
    $currentDataResult = $currentDataStmt->get_result();
    $currentDataRow = $currentDataResult->fetch_assoc();

    // Compare each field to check for changes
    $fieldsToCheck = ["secYearlvl", "secName", "secSession"];
    $changesDetected = false;

    //If there are changes made then it will proceed to update
    foreach ($fieldsToCheck as $field) {
        if ($currentDataRow[$field] != $_POST[$field]) {
            $changesDetected = true;
            break;
        }
    }

    //if changes are not made then it will send an alert
    if (!$changesDetected) {
        // No changes detected
        $_SESSION["error"] = "No changes detected in the information.";
        header('Location: section_index.php');
        exit;
    }

    // Check if the updated data is different from the current data
    if ($secProgram === $row['secProgram'] && $secYearlvl == $row['secYearlvl'] && $secName === $row['secName'] && $secSession === $row['secSession']) {
        $_SESSION['error'] = "No changes detected. Please make changes before updating.";
        header("Location: section_index.php");
        exit();
    }

    // Check if the updated name already exists in the table
    $checkNameQuery = $conn->prepare("SELECT secID FROM tb_section WHERE secYearlvl=? AND secName=? AND secID!=?");
    $checkNameQuery->bind_param("isi", $secYearlvl, $secName, $secID);
    $checkNameQuery->execute();
    $checkNameResult = $checkNameQuery->get_result();

    if ($checkNameResult->num_rows > 0) {
        $_SESSION['error'] = "Section already exists";
        header("Location: section_index.php");
        exit();
    }

    //Updated information of the Section will be added to the Database
    $stmt = $conn->prepare("UPDATE tb_section SET secProgram=?, secYearlvl=?, secName=?, secSession=?, secStatus=? WHERE secID=?");
    $stmt->bind_param("sisssi", $secProgram, $secYearlvl, $secName, $secSession, $secStatus, $secID);
    $stmt->execute();

    if ($stmt) {
        $_SESSION["message"] = "Section Details Updated Successfully";
        header('Location: section_index.php');
    } else {
        $_SESSION['error'] = "Error occurred while updating section details.";
        header("Location: section_index.php");
    }
    $stmt->close();
}

// Toggle Active and Inactive
if (isset($_POST['sec_toggle_status'])) {
    $secID = $_POST['secID'];

    $stmt = $conn->prepare("SELECT secStatus FROM tb_section WHERE secID=?");
    $stmt->bind_param("i", $secID);
    $stmt->execute();
    $stmt->bind_result($currentStatus);
    $stmt->fetch();
    $stmt->close();

    $newStatus = ($currentStatus == 1) ? 0 : 1;

    $stmt = $conn->prepare("UPDATE tb_section SET secStatus=? , status = 1 WHERE secID=?");
    $stmt->bind_param("ii", $newStatus, $secID);
    $stmt->execute();

    if ($stmt) {
        $_SESSION["message"] = "Status Updated Successfully";
        header('Location: section_index.php');
    } else {
        echo "Error: ";
    }
    $stmt->close();
}

if (isset($_POST['sec_toggle_statusActivate'])) {
    var_dump($_POST);
     $secID = $_POST['secID'];

    $stmt = $conn->prepare("SELECT secStatus FROM tb_section WHERE secID=?");
    $stmt->bind_param("i", $secID);
    $stmt->execute();
    $stmt->bind_result($currentStatus);
    $stmt->fetch();
    $stmt->close();

    $newStatus = ($currentStatus == 1) ? 0 : 1;

    $stmt = $conn->prepare("UPDATE tb_section SET secStatus=? , status = 0 WHERE secID=?");
    $stmt->bind_param("ii", $newStatus, $secID);
    $stmt->execute();

    if ($stmt) {
        $_SESSION["message"] = "Status Updated Successfully";
        header('Location: section_index.php');
    } else {
        echo "Error: ";
    }
    $stmt->close();
}

