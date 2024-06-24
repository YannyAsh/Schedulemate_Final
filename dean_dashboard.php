<?php
include 'admin_approval.php';
include 'include/header.php';


$college = $_SESSION["college"];
$position = $_SESSION["postion"];

echo "<script>";
echo "console.log('College:', '" . $college . "');";
echo "console.log('Position:', '" . $position . "');";
echo "</script>";
?>

<div class="container-fluid px-4">
    <div class="container-fluid px-4">
        <div class="row g-3 my-2">
            <div class="container">

                <!-- this is for the alerts -->
                <?php if (isset($_SESSION['message'])) : ?>
                    <script>
                        Swal.fire({
                            icon: 'success',
                            title: '<?php echo $_SESSION['message']; ?>',
                            showConfirmButton: false,
                            timer: 1500
                        });
                    </script>
                <?php unset($_SESSION['message']);
                endif; ?>

                <?php if (isset($_SESSION['error'])) : ?>
                    <script>
                        Swal.fire({
                            icon: 'error',
                            title: '<?php echo $_SESSION['error']; ?>',
                            showConfirmButton: false,
                            timer: 1500
                        });
                    </script>
                <?php unset($_SESSION['error']);
                endif; ?>

                <div class="table-wrapper">
                    <div class="table-title">
                        <div class="row">
                            <div class="col-sm-6">
                                <h2>Pending Approvals</h2>
                            </div>
                        </div>
                    </div>
                    <div class="table-responsive">
                        <table class="table table-striped">
                            <thead>
                                <tr>
                                    <th>Employee ID</th>
                                    <th>Name</th>
                                    <th>Program</th>
                                    <th>Position</th>
                                    <th>Actions</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php
                                if ($position == 'admin') {
                                    // For admin, show all pending approvals
                                    $query = "SELECT * FROM tb_register WHERE userApproval = 'pending' ORDER BY userID ASC";
                                } elseif ($position == 'dean') {
                                    // For dean, show pending approvals for their college
                                    $query = "SELECT * FROM tb_register WHERE userApproval = 'pending' AND userCollege = '$college' ORDER BY userID ASC";
                                } else {
                                    // Handle other positions or unauthorized access (optional)
                                    echo "Unauthorized access";
                                    exit; // Exit or handle as per your application's logic
                                }

                                // Execute the query
                                $result = mysqli_query($conn, $query);

                                // Process the results as before
                                while ($row = mysqli_fetch_array($result)) {
                                    // Output rows as table rows
                                ?>
                                    <tr>
                                        <td><?php echo $row['userEmployID']; ?></td>
                                        <td><?php echo $row['userFname'] . ' ' . $row['userMname'] . ' ' . $row['userLname']; ?></td>
                                        <td><?php echo $row['userProgram']; ?></td>
                                        <td><?php echo $row['userPosition']; ?></td>
                                        <td>
                                            <form method="POST" action="admin_approval.php">
                                                <input type="hidden" name="userID" value="<?php echo $row['userID']; ?>">
                                                <button type="submit" name="approve" class="approve" value="Approve" data-bs-toggle="modal">
                                                    <i class="material-icons" data-bs-toggle="tooltip" title="Edit">&#xe86c;</i>
                                                </button>
                                                <button type="submit" name="deny" class="disapprove" value="Deny" data-bs-toggle="modal">
                                                    <i class="material-icons" data-bs-toggle="tooltip" title="Status">&#xe5c9;</i>
                                                </button>
                                            </form>
                                        </td>
                                    </tr>
                                <?php
                                }
                                ?>
                            </tbody>
                        </table>
                        <div class="clearfix">
                            <div class="hint-text">Showing <b>5</b> out of <b>25</b> entries</div>
                            <ul class="pagination">
                                <li class="page-item"><a href="#" class="page-link">Previous</a></li>
                                <li class="page-item"><a href="#" class="page-link">1</a></li>
                                <li class="page-item"><a href="#" class="page-link">2</a></li>
                                <li class="page-item active"><a href="#" class="page-link">3</a></li>
                                <li class="page-item"><a href="#" class="page-link">4</a></li>
                                <li class="page-item"><a href="#" class="page-link">5</a></li>
                                <li class="page-item"><a href="#" class="page-link">Next</a></li>
                            </ul>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

</div>
</div>
</div>
<!-- /#page-content-wrapper -->
</div>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.0.0-beta3/dist/js/bootstrap.bundle.min.js"></script>
<script>
    var el = document.getElementById("wrapper");
    var toggleButton = document.getElementById("menu-toggle");

    toggleButton.onclick = function() {
        el.classList.toggle("toggled");
    };

    var dropdown = document.getElementsByClassName("list-group-submenu");
    var i;

    for (i = 0; i < dropdown.length; i++) {
        dropdown[i].addEventListener("click", function() {
            this.classList.toggle("active");
            var dropdownContent = this.nextElementSibling;
            if (dropdownContent.style.display === "block") {
                dropdownContent.style.display = "none";
            } else {
                dropdownContent.style.display = "block";
            }
        });
    }
</script>


</body>

</html>