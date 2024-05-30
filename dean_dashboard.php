<?php
include 'admin_approval.php';
include 'include/header.php';
?>

            <div class="container-fluid px-4">
                <div class="row g-3 my-2 box">
                    <div class="col-md-3">
                        <div class="p-3 bg-white shadow-sm d-flex justify-content-around align-items-center rounded">
                            <div>
                                <h3 class="fs-2">#</h3>
                                <p class="fs-5">Professors</p>
                            </div>
                            <i class="fas fa-chalkboard-user fs-1 primary-text p-3"></i>
                        </div>
                    </div>

                    <div class="col-md-3">
                        <div class="p-3 bg-white shadow-sm d-flex justify-content-around align-items-center rounded">
                            <div>
                                <h3 class="fs-2">#</h3>
                                <p class="fs-5">Subjects</p>
                            </div>
                            <i class="fas fa-book fs-1 primary-text p-3"></i>
                        </div>
                    </div>

                    <div class="col-md-3">
                        <div class="p-3 bg-white shadow-sm d-flex justify-content-around align-items-center rounded">
                            <div>
                                <h3 class="fs-2">#</h3>
                                <p class="fs-5">Rooms</p>
                            </div>
                            <i class="fas fa-school fs-1 primary-text p-3"></i>
                        </div>
                    </div>

                    <div class="col-md-3">
                        <div class="p-3 bg-white shadow-sm d-flex justify-content-around align-items-center rounded">
                            <div>
                                <h3 class="fs-2">#</h3>
                                <p class="fs-5">Schedules</p>
                            </div>
                            <i class="fas fa-calendar fs-1 primary-text p-3"></i>
                        </div>
                    </div>
                </div>

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
                                    <table class="table table-hover">
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
                                            $result = mysqli_query($conn, $query); // Corrected variable name from $result to $query
                                            while ($row = mysqli_fetch_array($result)) {
                                            ?>

                                                <tr>
                                                    <td><?php echo $row['userID']; ?></td>
                                                    <td><?php echo $row['userFname']; ?></td>
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