<?php
include 'conn/conn.php';
$db = new DatabaseHandler();
include_once('db.php');

$profFname = "";
$profMname = "";
$profLname = "";
$profMobile = "";
$profAddress = "";
$profEduc = "";
$profExpert = "";
$profRank = "";
$profHrs = "";
$profEmployStatus = "";
$profEmployID="";
$profStatus = 0;
$profID = 0;
$prof_edit_state = false;

// Saving records
if (isset($_POST["prof_add_new"])) {
    $profEmployID = $_POST["profEmployID"];
    $profFname = $_POST["profFname"];
    $profMname = $_POST["profMname"];
    $profLname = $_POST["profLname"];
    $profMobile = $_POST["profMobile"];
    $profAddress = $_POST["profAddress"];
    $profEduc = $_POST["profEduc"];
    $profExpert = $_POST["profExpert"];
    $profRank = $_POST["profRank"];
    $profHrs = $_POST["profHrs"];
    $profEmployStatus = $_POST["profEmployStatus"];
    $profStatus = $_POST["profStatus"];

 
    // Validate required fields
    if (empty($profFname) || empty($profLname) || empty($profMobile)) {
        $_SESSION['message'] = "Error: Missing required fields";
        header("Location: prof_index.php");
        exit();
    }

    // Check for duplicate entry 
    $stmt = $conn->prepare("SELECT COUNT(*) FROM tb_professor WHERE profFname=? AND profLname=? AND profMobile=?");
    $stmt->bind_param("sss", $profFname, $profLname, $profMobile);
    $stmt->execute();
    $stmt->bind_result($count);
    $stmt->fetch();
    $stmt->close();

    if ($count > 0) {
        $_SESSION['message'] = "Error: Duplicate entry";
        header("Location: prof_index.php");
        die();
    }

     // Validate the mobile number format
    if (!preg_match('/^(?:\+639|09)\d{9}$/', $profMobile)) {
        $_SESSION['message'] = "Error: Invalid mobile number format. Use either '+639xxxxxxxxx' or '09xxxxxxxxx'.";
        header("Location: prof_index.php");
        die();
    }

    //Add the information to the Database
    $stmt = $conn->prepare("INSERT INTO tb_professor (profEmployID, profFname, profMname, profLname, profMobile, profAddress, profEduc, profExpert, profRank, profHrs, profEmployStatus, profStatus) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?)");
    $stmt->bind_param("isssssssssss", $profEmployID ,$profFname, $profMname, $profLname, $profMobile, $profAddress, $profEduc, $profExpert, $profRank, $profHrs, $profEmployStatus, $profStatus);
    $stmt->execute();

    if ($stmt) {
        $_SESSION['message'] = "Information Saved Successfully";
        header("Location: prof_index.php");
    } else {
        die("Something went wrong");
    }
    $stmt->close();
}


// For updating records
if (isset($_POST["prof_update"])) {
    $profEmployID = $_POST["profEmployID"];
    $profFname = $_POST["profFname"];
    $profMname = $_POST["profMname"];
    $profLname = $_POST["profLname"];
    $profMobile = $_POST["profMobile"];
    $profAddress = $_POST["profAddress"];
    $profEduc = $_POST["profEduc"];
    $profExpert = $_POST["profExpert"];
    $profRank = $_POST["profRank"];
    $profHrs = $_POST["profHrs"];
    $profEmployStatus = $_POST["profEmployStatus"];
    $profStatus = $_POST["profStatus"];
    $profID = $_POST["profID"];

    // Validate the mobile number format
    if (!preg_match('/^(?:\+639|09)\d{9}$/', $profMobile)) {
        $_SESSION['message'] = "Error: Invalid mobile number format. Use either '+639xxxxxxxxx' or '09xxxxxxxxx'.";
        header("Location: prof_index.php");
        die();
    }

    // Fetch the current data from the database
    $currentDataQuery = "SELECT * FROM tb_professor WHERE profID = ?";
    $currentDataStmt = $conn->prepare($currentDataQuery);
    $currentDataStmt->bind_param("i", $profID);
    $currentDataStmt->execute();
    $currentDataResult = $currentDataStmt->get_result();
    $currentDataRow = $currentDataResult->fetch_assoc();
    $currentDataStmt->close();

    // Compare each field to check for changes
    $fieldsToCheck = ["profEmployID", "profFname", "profMname" , "profLname", "profMobile", "profAddress", "profEduc", "profExpert", "profRank", "profHrs", "profEmployStatus"];
    $changesDetected = false;

    foreach ($fieldsToCheck as $field) {
        if ($currentDataRow[$field] != $_POST[$field]) {
            $changesDetected = true;
            break;
        }
    }

    if (!$changesDetected) {
        $_SESSION["message"] = "No changes detected in the information.";
        header('Location: prof_index.php');
        exit;
    }

    // Proceed with the update
    $stmt = $conn->prepare("UPDATE tb_professor SET profEmployID=?, profFname=?, profMname=?, profLname=?, profMobile=?, profAddress=?, profEduc=?, profExpert=?, profRank=?, profHrs=?, profEmployStatus=?, profStatus=? WHERE profID=?");
    $stmt->bind_param("isssssssssssi", $profEmployID, $profFname, $profMname, $profLname, $profMobile, $profAddress, $profEduc, $profExpert, $profRank, $profHrs, $profEmployStatus, $profStatus, $profID);
    $stmt->execute();

    if ($stmt) {
        $_SESSION["message"] = "Information Updated Successfully";
        header('Location: prof_index.php');
    } else {
        $_SESSION['error'] = "An error occurred while updating professor details.";
        header("Location: prof_index.php");
    }
    $stmt->close();
}

// Toggle Active and Inactive
if (isset($_POST['prof_toggle_status'])) {
    $profID = $_POST['profID'];

    $stmt = $conn->prepare("SELECT status FROM tb_professor WHERE profID=?");
    $stmt->bind_param("i", $profID);
    $stmt->execute();
    $stmt->bind_result($currentStatus);
    $stmt->fetch();
    $stmt->close();

    $newStatus = ($currentStatus == 1) ? 0 : 1;

    $stmt = $conn->prepare("UPDATE tb_professor SET status=? WHERE profID=?");
    $stmt->bind_param("ii", $newStatus, $profID);
    $stmt->execute();

    if ($stmt) {
        $_SESSION["message"] = "Status Updated Successfully";
        header('Location: prof_index.php');
    } else {
        die("Something went wrong");
    }
    $stmt->close();
}
?>