<?php 
session_start();

if (isset($_POST["submit"])) {
    $employID = $_POST["userEmployID"];
    $fname = $_POST["userFname"];
    $mname = $_POST["userMname"];
    $lname = $_POST["userLname"];
    $email = $_POST["userEmail"];
    $position = $_POST["userPosition"];
    $college = isset($_POST["userCollege"]) ? $_POST["userCollege"] : ""; // Default value if not set
    $prog = isset($_POST["userProgram"]) ? $_POST["userProgram"] : ""; // Default value if not set
    $password = $_POST["userPass"];
    $passwordRepeat = $_POST["userPasscon"];
    $userApproval = isset($_POST["userApproval"]) ? $_POST["userApproval"] : 'pending';
    $passwordHash = password_hash($password, PASSWORD_DEFAULT);
    $errors = array();

    // Detect errors
    if (empty($employID) || empty($fname) || empty($mname) || empty($lname) || empty($email) || empty($position) || empty($password) || empty($passwordRepeat)) {
        array_push($errors, "All fields are required");
    }
    if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        array_push($errors, "Email is not valid");
    }
    if (strlen($password) < 8) {
        array_push($errors, "Password must be at least 8 characters long");
    }
    if ($password !== $passwordRepeat) {
        array_push($errors, "Password does not match");
    }

    // Additional checks based on user position
    if ($position === "chairperson" && (empty($college) || empty($prog))) {
        array_push($errors, "College and Program are required for Chairperson");
    } elseif ($position === "Dean" && empty($prog)) {
        array_push($errors, "Program is required for Dean");
    }

    require_once dirname(__FILE__) . "/db.php";

    // Check if the email already exists
    $sql = "SELECT * FROM tb_register WHERE userEmail = ?";
    $stmt = mysqli_stmt_init($conn);
    if (mysqli_stmt_prepare($stmt, $sql)) {
        mysqli_stmt_bind_param($stmt, "s", $email);
        mysqli_stmt_execute($stmt);
        mysqli_stmt_store_result($stmt);
        $rowCount = mysqli_stmt_num_rows($stmt);
        if ($rowCount > 0) {
            array_push($errors, "Email already exists!");
        }
    }

    if (count($errors) > 0) {
        $_SESSION['errors'] = $errors;
        header("Location: index.php");
    } else {
        // Insert user into the database with status 'pending'
        $sql = "INSERT INTO tb_register (userEmployID, userFname, userMname, userLname, userEmail, userPosition, userCollege, userProgram, userPass, userApproval) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?)";
        $stmt = mysqli_stmt_init($conn);
        if (mysqli_stmt_prepare($stmt, $sql)) {
            mysqli_stmt_bind_param($stmt, "isssssssss", $employID, $fname, $mname, $lname, $email, $position, $college, $prog, $passwordHash, $userApproval);
            mysqli_stmt_execute($stmt);
            $_SESSION["success"] = 1;
            header("Location: index.php"); // Assuming 'register.php' is your registration page
            exit();
        } else {
            die("Something went wrong");
        }
    }
}
?>
