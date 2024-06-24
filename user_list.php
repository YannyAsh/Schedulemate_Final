<?php
include 'admin_approval.php';
include 'include/header.php';

$college = $_SESSION["college"];
$position = $_SESSION["postion"];
?>

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
                            <h2>User List</h2>
                        </div>
                    </div>
                </div>
                <div class="table-responsive">
                    <table id="pendingTable" class="table table-hover">
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
                            $query = "SELECT * FROM tb_register WHERE userApproval = 'pending' ORDER BY userID ASC";
                            $result = mysqli_query($conn, $query);
                            while ($row = mysqli_fetch_array($result)) {
                            ?>
                                <tr>
                                    <td><?php echo $row['userEmployID']; ?></td>
                                    <td><?php echo $row['userFname'] . ' ' . $row['userMname'] . ' ' . $row['userLname']; ?></td>
                                    <td><?php echo $row['userProgram']; ?></td>
                                    <td><?php echo $row['userPosition']; ?></td>
                                    <td>
                                        <form method="POST" action="admin_approval.php">
                                            <input type="hidden" name="userID" value="<?php echo $row['userID']; ?>">
                                            <button type="submit" name="approve" class="approve" value="Approve" data-bs-toggle="tooltip" title="Approve">
                                                <i class="material-icons">&#xe86c;</i>
                                            </button>
                                            <button type="submit" name="deny" class="disapprove" value="Deny" data-bs-toggle="tooltip" title="Deny">
                                                <i class="material-icons">&#xe5c9;</i>
                                            </button>
                                        </form>
                                    </td>
                                </tr>
                            <?php
                            }
                            ?>
                        </tbody>
                    </table>
                </div>
            </div>

            <!-- Change Status Modal HTML DE-ACTIVE-->
            <div id="statusUser" class="modal fade">
                <div class="modal-dialog">
                    <div class="modal-content">
                        <form method="POST" action="admin_approval.php">
                            <input type="hidden" name="userID" id="userID" value="<?php echo $userID; ?>">

                            <div class="modal-header">
                                <h5 class="modal-title">Change User Status</h5>
                                <button type="button" class="close" data-bs-dismiss="modal" aria-hidden="true">&times;</button>
                            </div>

                            <div class="modal-body">
                                <h6>Are you sure you want to change the Status of this User?</h6>
                                <input type="hidden" name="roomID" id="status_userID" value="">
                            </div>

                            <div class="modal-footer">
                                <input type="button" class="btn" data-bs-dismiss="modal" value="Cancel">
                                <input type="submit" class="btn" name="user_toggle_status" value="Confirm Status">
                            </div>
                        </form>
                    </div>
                </div>
            </div>

            <!-- Change Status Modal HTML IN-ACTIVE-->
            <div id="statusUserActivate" class="modal fade">
                <div class="modal-dialog">
                    <div class="modal-content">
                        <form method="POST" action="admin_approval.php">
                            <input type="hidden" name="userID" id="userID" value="<?php echo $userID; ?>">

                            <div class="modal-header">
                                <h5 class="modal-title">Change User Status</h5>
                                <button type="button" class="close" data-bs-dismiss="modal" aria-hidden="true">&times;</button>
                            </div>

                            <div class="modal-body">
                                <h6>Are you sure you want to change the Status of this User?</h6>
                                <input type="hidden" name="userID" id="status_userIDz" value="">
                            </div>

                            <div class="modal-footer">
                                <input type="button" class="btn" data-bs-dismiss="modal" value="Cancel">
                                <input type="submit" class="btn" name="user_toggle_statusActivate" value="Confirm Status">
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.0.0-beta3/dist/js/bootstrap.bundle.min.js"></script>
<script src="https://ajax.googleapis.com/ajax/libs/jquery/1.12.4/jquery.min.js"></script>
<script src="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/js/bootstrap.min.js"></script>
<script>
    $(document).ready(function() {
        // Activate tooltip
        $('[data-toggle="tooltip"]').tooltip();

        // JavaScript to set userID when opening status modal
        $('.status').on('click', function() {
            var userID = $(this).data('userid'); 
            $('#status_userID').val(userID); // Set userID to the hidden input field for deactivation
            $('#status_userIDz').val(userID); // Set userID to the hidden input field for activation
        });

        // Initialize DataTables
        $('#pendingTable').DataTable();
    });
</script>
<link rel="stylesheet" href="https://cdn.datatables.net/2.0.5/css/dataTables.dataTables.min.css">
<script src="https://cdn.datatables.net/2.0.5/js/dataTables.min.js"></script>

</body>
</html>
