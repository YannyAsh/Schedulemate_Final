<?php 
include 'conn/conn.php';
include 'include/header.php';
$db = new DatabaseHandler();
?>

            <!-- Start of the contents -->
            <div class="container-fluid px-4">
                <div class="row g-3 my-2">
                    <div class="container">
                        <div class="table-wrapper">
                            <div class="table-title">
                                <div class="row">
                                    <div class="col-sm-7">
                                        <h2>Manage PBRU Entries</h2>
                                    </div>
                                </div>
                            </div>
                            <div class="table-responsive">
                                <table id="myTable" class="table table-hover">
                                    <thead>
                                        <tr>
                                            <th>School Year</th>
                                            <th>Semester</th>
                                            <th>Room</th>
                                            <th>Actions</th>
                                        </tr>
                                    </thead>
                                    <tbody >
                                        <?php 
                                        $conditions = [];
                                        $sql = $db->getAllRowsFromTableWhereGroup('tb_scheduled',$conditions,' sy,semester,room');
                                        $i=0;
                                        
                                        foreach ($sql as $row) {
                                        ?>
                                        <tr>
                                            <td><?=$row['sy']?></td>
                                            <td><?=$row['semester']?></td>
                                            <td><?=$row['room']?></td>
                                            <td>
                                                <!-- <a href="#editSubj" class="edit" data-bs-toggle="modal"><i class="material-icons" data-bs-toggle="tooltip" title="Edit">&#xe254;</i></a> -->
                                                <!-- <a href="#statusSubj" class="status" data-bs-toggle="modal"><i class="material-icons" data-bs-toggle="tooltip" title="Status">&#xe909;</i></a> -->
                                                <a href="pdf-pbru.php?ay=<?=$row['sy']?>&semester=<?=$row['semester']?>&room=<?=$row['room']?>" target="_blank" class="status text-warning" ><i class="material-icons"  title="Status">&#xe415;</i></a>
                                            </td>
                                        </tr>
                                        <?php } ?>
                                    </tbody>
                                </table>
                            </div>
                        </div>
                    </div>
                    <!-- Add Modal HTML -->
                    <div id="addSubj" class="modal fade">
                        <div class="modal-dialog">
                            <div class="modal-content">
                                <form>
                                    <div class="modal-header">
                                        <h5 class="modal-title">Add New Subject</h5>
                                        <button type="button" class="close" data-bs-dismiss="modal" aria-hidden="true">&times;</button>
                                    </div>
                                    <div class="modal-body">
                                        <div class="form-group">
                                            <label style="font-weight: bold;">Subject Code</label>
                                            <input type="text" class="form-control" required>
                                        </div>
                                        <div class="form-group">
                                            <label style="font-weight: bold;">Subject Description</label>
                                            <input type="text" class="form-control" required>
                                        </div>
                                        <div class="form-group">
                                            <label style="font-weight: bold;">Subject Units</label>
                                            <input type="number" class="form-control" required>
                                        </div>
                                        <div class="form-group">
                                            <label style="font-weight: bold;">Subject Lab Hours</label>
                                            <input type="number" class="form-control" required>
                                        </div>
                                        <div class="form-group">
                                            <label style="font-weight: bold;">Subject Lec Hours</label>
                                            <input type="number" class="form-control" required>
                                        </div>
                                        <div class="form-group">
                                            <label style="font-weight: bold;">Status</label>
                                            <select class="form-control" required name="Status">
                                                <option value="" disabled selected>Select Status</option>
                                                <option value="active">Active</option>
                                                <option value="inactive">Inactive</option>
                                            </select>
                                        </div>
                                    </div>
                                    <div class="modal-footer">
                                        <input type="button" class="btn" data-bs-dismiss="modal" value="Cancel">
                                        <input type="submit" class="btn" value="Add">
                                    </div>
                                </form>
                            </div>
                        </div>
                    </div>
                    <!-- Edit Modal HTML -->
                    <div id="editSubj" class="modal fade">
                        <div class="modal-dialog">
                            <div class="modal-content">
                                <form>
                                    <div class="modal-header">
                                        <h5 class="modal-title">Edit Subject</h5>
                                        <button type="button" class="close" data-bs-dismiss="modal" aria-hidden="true">&times;</button>
                                    </div>
                                    <div class="modal-body">
                                        <div class="form-group">
                                            <label style="font-weight: bold;">Subject Code</label>
                                            <input type="text" class="form-control" required>
                                        </div>
                                        <div class="form-group">
                                            <label style="font-weight: bold;">Subject Description</label>
                                            <input type="text" class="form-control" required>
                                        </div>
                                        <div class="form-group">
                                            <label style="font-weight: bold;">Subject Units</label>
                                            <input type="number" class="form-control" required>
                                        </div>
                                        <div class="form-group">
                                            <label style="font-weight: bold;">Subject Lab Hours</label>
                                            <input type="number" class="form-control" required>
                                        </div>
                                        <div class="form-group">
                                            <label style="font-weight: bold;">Subject Lec Hours</label>
                                            <input type="number" class="form-control" required>
                                        </div>
                                        <div class="form-group">
                                            <label style="font-weight: bold;">Status</label>
                                            <select class="form-control" required name="Status">
                                                <option value="" disabled selected>Select Status</option>
                                                <option value="active">Active</option>
                                                <option value="inactive">Inactive</option>
                                            </select>
                                        </div>
                                    </div>
                                    <div class="modal-footer">
                                        <input type="button" class="btn" data-bs-dismiss="modal" value="Cancel">
                                        <input type="submit" class="btn" value="Add">
                                    </div>
                                </form>
                            </div>
                        </div>
                    </div>
                    <!-- Change Status Modal HTML -->
                    <div id="statusSubj" class="modal fade">
                        <div class="modal-dialog">
                            <div class="modal-content">
                                <form>
                                    <div class="modal-header">
                                        <h5 class="modal-title">Change Subject Status</h5>
                                        <button type="button" class="close" data-bs-dismiss="modal" aria-hidden="true">&times;</button>
                                    </div>
                                    <div class="modal-body">
                                        <h6>Are you sure you want to inactivate this Subject?</h6>
                                    </div>
                                    <div class="modal-footer">
                                        <input type="button" class="btn" data-bs-dismiss="modal" value="Cancel">
                                        <input type="submit" class="btn" value="Confirm Status">
                                    </div>
                                </form>
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

    <script src="https://ajax.googleapis.com/ajax/libs/jquery/1.12.4/jquery.min.js"></script>
    <script src="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/js/bootstrap.min.js"></script>
    <script>
        $(document).ready(function() {
            // Activate tooltip
            $('[data-toggle="tooltip"]').tooltip();
        });
    </script>
<link rel="stylesheet" href="https://cdn.datatables.net/2.0.5/css/dataTables.dataTables.min.css">
<script src="https://cdn.datatables.net/2.0.5/js/dataTables.min.js"></script>
<script>
    $('#myTable').DataTable();
</script>