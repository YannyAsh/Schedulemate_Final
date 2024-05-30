<?php
session_start();
include_once('db.php');

if (isset($_POST['approve'])) {
    $userID = $_POST['userID'];

    $select = "UPDATE tb_register SET userApproval = 'approved' WHERE userID = '$userID'";
    $result = mysqli_query($conn, $select);

    if ($result) {
        $_SESSION['message'] = "User Approved";
        header("Location: dean_dashboard.php");
    } else {

        $_SESSION['error'] = ("Error: ' . mysqli_error($conn) . '");
        header("Location: dean_dashboard.php");
    }
}

if (isset($_POST['deny'])) {
    $userID = $_POST['userID'];

    $select = "DELETE FROM tb_register WHERE userID = '$userID'";
    $result = mysqli_query($conn, $select);

    if ($result) {
        echo '<script type = "text/javascript">';
        echo 'alert("User Denied")';
        echo 'window.location.href = "dean_dashboard.php"';
        echo '</script>';
    } else {
        echo '<script type = "text/javascript">';
        echo 'alert("Error: ' . mysqli_error($conn) . '")';
        echo 'window.location.href = "dean_dashboard.php"';
        echo '</script>';
    }
}
?>