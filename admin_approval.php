<?php
include 'conn/conn.php';
$db = new DatabaseHandler();
include_once('db.php');

$userID = 0;

if (isset($_POST['approve'])) {
    $userID = $_POST['userID'];

    $select = "UPDATE tb_register SET userApproval = 'approved' WHERE userID = ?";
    $stmt = $conn->prepare($select);
    $stmt->bind_param("i", $userID);
    $result = $stmt->execute();

    if ($result) {
        $_SESSION['message'] = "User Approved";
        header("Location: dean_dashboard.php");
    } else {
        $_SESSION['error'] = "Error: " . mysqli_error($conn);
        header("Location: dean_dashboard.php");
    }
    $stmt->close();
}

if (isset($_POST['deny'])) {
    $userID = $_POST['userID'];

    $select = "DELETE FROM tb_register WHERE userID = ?";
    $stmt = $conn->prepare($select);
    $stmt->bind_param("i", $userID);
    $result = $stmt->execute();

    if ($result) {
        echo '<script type="text/javascript">';
        echo 'alert("User Denied");';
        echo 'window.location.href = "dean_dashboard.php";';
        echo '</script>';
    } else {
        echo '<script type="text/javascript">';
        echo 'alert("Error: ' . mysqli_error($conn) . '");';
        echo 'window.location.href = "dean_dashboard.php";';
        echo '</script>';
    }
    $stmt->close();
}

// Toggle Active and Inactive
if (isset($_POST['user_toggle_status'])) {
    $userID = $_POST['userID'];

    $stmt = $conn->prepare("SELECT userStatus FROM tb_register WHERE userID=?");
    $stmt->bind_param("i", $userID);
    $stmt->execute();
    $stmt->bind_result($currentStatus);
    $stmt->fetch();
    $stmt->close();

    $newStatus = ($currentStatus == 1) ? 0 : 1;

    $stmt = $conn->prepare("UPDATE tb_register SET userStatus=?,status=1 WHERE userID=?");
    $stmt->bind_param("ii", $newStatus, $userID);
    $stmt->execute();

    if ($stmt) {
        $_SESSION["message"] = "Status Updated Successfully";
        header('Location: user_list.php');
    } else {
        echo "Error: ";
    }
    $stmt->close();
}

if (isset($_POST['user_toggle_statusActivate'])) {
    // var_dump($_POST);
    $userID = $_POST['userID'];

    $stmt = $conn->prepare("SELECT userStatus FROM tb_register WHERE userID=?");
    $stmt->bind_param("i", $userID);
    $stmt->execute();
    $stmt->bind_result($currentStatus);
    $stmt->fetch();
    $stmt->close();

    $newStatus = ($currentStatus == 1) ? 0 : 1;

    $stmt = $conn->prepare("UPDATE tb_register SET userStatus=? , status = 0 WHERE userID=?");
    $stmt->bind_param("ii", $newStatus, $userID);
    $stmt->execute();

    if ($stmt) {
        $_SESSION["message"] = "Status Updated Successfully";
        header('Location: user_list.php');
    } else {
        echo "Error: ";
    }
    $stmt->close(); // <-- Ensure there is a semicolon here
}

?>
