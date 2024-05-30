<?php

include_once('db.php');

$roomBuild = "";
$roomFloornum = 0;
$roomNum = 0;
$roomStatus = 0;
$roomID = 0;
$room_edit_state = false;

//saving records
if (isset($_POST["room_add_new"])) {
    $roomBuild = $_POST["roomBuild"];
    $roomFloornum = $_POST["roomFloornum"];
    $roomNum = $_POST["roomNum"];
    $roomStatus = $_POST["roomStatus"];

    // Check for valid floor number (1 digit or less) and not negative
    if (!preg_match('/^\d{1}$/', $roomFloornum) || $roomFloornum < 0) {
        $_SESSION['error'] = "Invalid floor number";
        header("Location: room_index.php");
        exit;
    }

    // Check for valid room number (3 digits only) and not negative
    if (!preg_match('/^\d{3}$/', $roomNum) || $roomNum < 0) {
        $_SESSION['error'] = "Invalid room number";
        header("Location: room_index.php");
        exit;
    }

    // Check for duplicate room, excluding the current one
    $stmt = $conn->prepare("SELECT COUNT(*) FROM tb_room WHERE roomBuild=? AND roomFloornum=? AND roomNum=? AND roomID != ?");
    if (!$stmt) {
        die("Error preparing statement: " . $conn->error);
    }

    $stmt->bind_param("siii", $roomBuild, $roomFloornum, $roomNum, $roomID);
    $stmt->execute();

    if (!$stmt) {
        die("Error executing statement: " . $stmt->error);
    }
    $stmt->bind_result($count);
    $stmt->fetch();
    $stmt->close();

    if ($count > 0) {
        $_SESSION['error'] = "Another room with the same details exists";
        header("Location: room_index.php");
        exit;
    }

    //Add New Room for DATABASE
    $stmt = $conn->prepare("INSERT INTO tb_room (roomBuild, roomFloornum, roomNum, roomStatus) VALUES (?, ?, ?, ?)");
    $stmt->bind_param("siii", $roomBuild, $roomFloornum, $roomNum, $roomStatus);
    $stmt->execute();

    if ($stmt) {
        $_SESSION['message'] = "Room Details Saved Successfully";
        header("Location: room_index.php");
    } else {
        echo "Error: ";
    }
    $stmt->close();
}



// For updating records
if (isset($_POST["room_update"])) {
    $roomBuild = $_POST["roomBuild"];
    $roomFloornum = $_POST["roomFloornum"];
    $roomNum = $_POST["roomNum"];
    $roomStatus = $_POST["roomStatus"];
    $roomID = $_POST["roomID"];

    // Fetch the current data from the database
    $currentDataQuery = "SELECT * FROM tb_room WHERE roomID = ?";
    $currentDataStmt = $conn->prepare($currentDataQuery);
    $currentDataStmt->bind_param("i", $roomID);
    $currentDataStmt->execute();
    $currentDataResult = $currentDataStmt->get_result();
    $currentDataRow = $currentDataResult->fetch_assoc();

    // Compare each field to check for changes
    $fieldsToCheck = ["roomBuild", "roomFloornum", "roomNum"];
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
        $_SESSION["error"] = "No changes detected.";
        header('Location: room_index.php');
        exit;
    }

    // Check if the updated name already exists in the table
    $checkNameQuery = $conn->prepare("SELECT roomID FROM tb_room WHERE roomNum=? AND roomID!=?");
    $checkNameQuery->bind_param("ii", $roomNum, $roomID);
    $checkNameQuery->execute();
    $checkNameResult = $checkNameQuery->get_result();

    if ($checkNameResult->num_rows > 0) {
        $_SESSION['error'] = "Room already exists. Please input another a different room.";
        header("Location: room_index.php");
        exit();
    }

    // Update the record if changes were made
    $stmt = $conn->prepare("UPDATE tb_room SET roomBuild=?, roomFloornum=?, roomNum=?, roomStatus=? WHERE roomID=?");
    $stmt->bind_param("sisii", $roomBuild, $roomFloornum, $roomNum, $roomStatus, $roomID);
    $stmt->execute();

    if ($stmt) {
        $_SESSION["message"] = "Information Updated Successfully";
        header('Location: room_index.php');
    } else {
        die("Something went wrong");
    }
    $stmt->close();
}


// Toggle Active and Inactive
if (isset($_POST['room_toggle_status'])) {
    $roomID = $_POST['roomID'];

    $stmt = $conn->prepare("SELECT roomStatus FROM tb_room WHERE roomID=?");
    $stmt->bind_param("i", $roomID);
    $stmt->execute();
    $stmt->bind_result($currentStatus);
    $stmt->fetch();
    $stmt->close();

    $newStatus = ($currentStatus == 1) ? 0 : 1;

    $stmt = $conn->prepare("UPDATE tb_room SET roomStatus=?,status=1 WHERE roomID=?");
    $stmt->bind_param("ii", $newStatus, $roomID);
    $stmt->execute();

    if ($stmt) {
        $_SESSION["message"] = "Status Updated Successfully";
        header('Location: room_index.php');
    } else {
        echo "Error: ";
    }
    $stmt->close();
}
