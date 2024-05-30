<?php
include 'subject_all_process.php';
include 'include/header.php';

?>


            <!-- Start of the contents -->
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
                                    <div class="col-sm-7">
                                        <h2>Manage Section Entries</h2>
                                    </div>
                                    <div class="col">
                                        <a href="#addSection" class="btn btn-success" data-bs-toggle="modal"><i class="material-icons">&#xE147;</i><span>Add New Section</span></a>
                                    </div>
                                </div>
                            </div>
                            <div class="table-responsive">
                                <table id="myTable" class="table table-hover">
                                    <thead>
                                        <tr>
                                            <th >No.</th>
                                            <th>Section Program</th>
                                            <th>Section Year Level</th>
                                            <th>Section Name</th>
                                            <th>Session</th>
                                            <th>Actions</th>
                                        </tr>
                                    </thead>
                                    <tbody >
                                        <?php
                                        $sql =  $db->getAllRowsFromTable('tb_section');
                                        $i=1;
                                        // Display Active Sections
                                        foreach ($sql as $row ) {
                                        ?>
                                            <tr>
                                                <td ><?php echo $i; ?></td>
                                                <td><?php echo $row["secProgram"] ?></td>
                                                <td><?php echo $row["secYearlvl"] ?></td>
                                                <td><?php echo $row["secName"] ?></td>
                                                <td><?php echo $row["secSession"] ?></td>
                                                <td>
                                                    <a href="" name="sec_edit" class="edit" data-bs-toggle="modal"
                                                    data-id="<?=$row["secID"]?>"
                                                    data-status="active"
                                                    ><i class="material-icons" data-bs-toggle="tooltip" title="Edit">&#xe254;</i></a>
                                                    <input type="hidden" name="secID" value="<?php echo $row['secID']; ?>">
                                                    <a href="#statusSection" class="status" data-bs-toggle="modal" data-secid="<?php echo $row['secID']; ?>"><i class="material-icons" data-bs-toggle="tooltip" title="Status">&#xe909;</i></a>
                                                </td>
                                            </tr>
                                        <?php
                                            $i++;
                                        }

                                        // Display Inactive Sections
                                        $conditions = ['status = 1'];
                                        $sql2 =  $db->getAllRowsFromTableWhere('tb_section',$conditions);
                                        foreach ($sql2 as $row ) {
                                        ?>
                                            <tr  >
                                                <td class="text-danger"><?php echo $i; ?></td>
                                                <td class="text-danger"><?php echo $row["secProgram"] ?></td>
                                                <td class="text-danger"><?php echo $row["secYearlvl"] ?></td>
                                                <td class="text-danger"><?php echo $row["secName"] ?></td>
                                                <td class="text-danger"><?php echo $row["secSession"] ?></td>
                                                <td class="text-warning">
                                                    <a href="#statusSectionActivate" 
                                                        class="status" 
                                                        data-bs-toggle="modal" 
                                                        data-secid="<?php echo $row['secID']; ?>">
                                                        <i class="material-icons" data-bs-toggle="tooltip" title="Status">
                                                            &#xe86c;
                                                        </i>
                                                    </a>
                                                </td>
                                            </tr>
                                        <?php
                                            $i++;
                                        }
                                        ?>
                                    </tbody>
                                </table>
                            </div>
                        </div>
                    </div>
                    <!-- Add Modal HTML -->
                    <div id="addSection" class="modal fade">
                        <div class="modal-dialog">
                            <div class="modal-content">

                                <form method="POST" action="section_all_process.php">
                                    <input type="hidden" name="secID" >
                                    <input type="hidden" name="secStatus" value="1"> <!-- Always set to "1" for active status -->

                                    <div class="modal-header">
                                        <h5 class="modal-title">Add New Section</h5>
                                        <button type="button" class="close" data-bs-dismiss="modal" aria-hidden="true">&times;</button>
                                    </div>

                                    <!-- Entries for the new Section -->
                                    <div class="modal-body">
                                        <div class="form-group">
                                            <label style="font-weight: bold;">Section Program</label>
                                            <input type="text" name="secProgram" class="form-control" required >
                                        </div>

                                        <div class="form-group">
                                            <label style="font-weight: bold;">Section Year Level</label>
                                            <select class="form-control" required name="secYearlvl" >
                                                <option value="" >Select Year Level</option>
                                                <option value="1">1</option>
                                                <option value="2">2</option>
                                                <option value="3">3</option>
                                                <option value="4">4</option>
                                            </select>
                                        </div>

                                        <div class="form-group">
                                            <label style="font-weight: bold;">Section Name</label>
                                            <input type="text" name="secName" class="form-control" required >
                                        </div>

                                        <div class="form-group">
                                            <label style="font-weight: bold;">Session</label>
                                            <select class="form-control" required name="secSession">
                                                <option value="" >Select Session</option>
                                                <option id="secDay" value="Day" >Day Class</option>
                                                <option id="secNight" value="Night" >Night Class</option>
                                            </select>
                                        </div>

                                    </div>

                                    <!-- Add Section Button -->
                                    <div class="modal-footer">
                                        <input type="button" class="btn" data-bs-dismiss="modal" value="Cancel">
                                        <input type="submit" name="sec_add_new" class="btn" value="Add">
                                    </div>
                                </form>
                            </div>
                        </div>
                    </div>


                    <!-- Edit Modal HTML -->
                    <div id="editSection" class="modal fade">
                        <div class="modal-dialog">
                            <div class="modal-content">

                                <form method="POST" action="section_all_process.php">
                                    <input type="hidden" name="secID" id="secID" value="<?php echo $secID; ?>">
                                    <input type="hidden" name="secStatus" value="1"> <!-- Always set to "1" for active status -->

                                    <div class="modal-header">
                                        <h5 class="modal-title">Edit Section</h5>
                                        <button type="button" class="close" data-bs-dismiss="modal" aria-hidden="true">&times;</button>
                                    </div>

                                    <!-- this is where user can edit the Section information -->
                                    <div class="modal-body">
                                        <div class="form-group">
                                            <label style="font-weight: bold;">Section Program</label>
                                            <input type="text" name="secProgram" id="secProgram" class="form-control" required>
                                        </div>

                                        <div class="form-group">
                                            <label style="font-weight: bold;">Section Year Level</label>
                                            <select class="form-control" id="secYearlvl" required name="secYearlvl">
                                                <option value="" disabled selected>Select Year Level</option>
                                                <option value="1">1</option>
                                                <option value="2">2</option>
                                                <option value="3">3</option>
                                                <option value="4">4</option>
                                            </select>
                                        </div>

                                        <div class="form-group">
                                            <label style="font-weight: bold;">Section Name</label>
                                            <input type="text" name="secName" id="secName" class="form-control" required value="<?php echo $secName ?>">
                                        </div>

                                        <div class="form-group">
                                            <label style="font-weight: bold;" for="secSession">Session</label>
                                            <select name="secSession" id="secSession" class="form-control" required>
                                                <option value="" disabled>Select Session</option>
                                                <option id="secDay" value="Day" >Day Class</option>
                                                <option id="secNight" value="Night">Night Class</option>
                                            </select>
                                        </div>
                                    </div>

                                    <!-- Update Section button -->
                                    <div class="modal-footer">
                                        <input type="button" class="btn" data-bs-dismiss="modal" value="Cancel">
                                        <input type="submit" name="sec_update" class="btn" value="Update">
                                    </div>
                                </form>
                            </div>
                        </div>
                    </div>


                    <!-- Change Status Modal HTML -->
                    <div id="statusSection" class="modal fade">
                        <div class="modal-dialog">
                            <div class="modal-content">
                                <form method="POST" action="section_all_process.php">
                                    <input type="hidden" name="secID" id="secID" value="<?php echo $secID; ?>">

                                    <div class="modal-header">
                                        <h5 class="modal-title">Change Section Status</h5>
                                        <button type="button" class="close" data-bs-dismiss="modal" aria-hidden="true">&times;</button>
                                    </div>

                                    <div class="modal-body">
                                        <h6>Are you sure you want to change the Status of this Section?</h6>
                                        <input type="hidden" name="secID" id="status_secID" value="">
                                    </div>

                                    <div class="modal-footer">
                                        <input type="button" class="btn" data-bs-dismiss="modal" value="Cancel">
                                        <input type="submit" class="btn" name="sec_toggle_status" value="Confirm Status">
                                    </div>
                                </form>
                            </div>
                        </div>
                    </div>

                    <!-- Change Status Modal HTML -->
                    <div id="statusSectionActivate" class="modal fade">
                        <div class="modal-dialog">
                            <div class="modal-content">
                                <form method="POST" action="section_all_process.php">
                                    <input type="hidden" name="secID" id="secID" value="<?php echo $secID; ?>">

                                    <div class="modal-header">
                                        <h5 class="modal-title">Change Section Status</h5>
                                        <button type="button" class="close" data-bs-dismiss="modal" aria-hidden="true">&times;</button>
                                    </div>

                                    <div class="modal-body">
                                        <h6>Are you sure you want to change the Status of this Section?</h6>
                                        <input type="hidden" name="secID" id="status_secIDz" value="">
                                    </div>

                                    <div class="modal-footer">
                                        <input type="button" class="btn" data-bs-dismiss="modal" value="Cancel">
                                        <input type="submit" class="btn" name="sec_toggle_statusActivate" value="Confirm Status">
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

    <script>
        // Filter table

        $(document).ready(function() {
            $("#tableSearch").on("keyup", function() {
                var value = $(this).val().toLowerCase();
                $("#myTable tr").filter(function() {
                    $(this).toggle($(this).text().toLowerCase().indexOf(value) > -1)
                });
            });
        });
    </script>

    <script>
        $(document).ready(function() {

            // display Edit modal

            $('.edit').on('click', function() {

                $('#editSection').modal('show');

                $tr = $(this).closest('tr');
                id = $(this).attr('data-id');

                var data = $tr.find("td").map(function() {
                    return $(this).text();
                }).get();

                console.log(data);
                $('#secID').val(id);
                $('#secProgram').val(data[1]);
                $('#secYearlvl').val(data[2]);
                $('#secName').val(data[3]);
                $('#secSession').val(data[4]);
                $('#secStatus').val('active');
            });
        });
    </script>

    <script>
        //this script is for the secStatus 

        // JavaScript to set secID when opening status modal
        $('.status').on('click', function() {
            var secID = $(this).data('secid');
            $('#status_secID').val(secID); // Set secID to the hidden input field
            $('#status_secIDz').val(secID); // Set secID to the hidden input field
            
            // $('#statusSection').modal('show');
        });
    </script>
    <style>
        .text-gray {
    color: gray !important;
}

    </style>
<link rel="stylesheet" href="https://cdn.datatables.net/2.0.5/css/dataTables.dataTables.min.css">
<script src="https://cdn.datatables.net/2.0.5/js/dataTables.min.js"></script>
<script>
$(document).ready(function() {
    var table = $('#myTable').DataTable();

    // Loop through each table row
    table.rows().every(function() {
        // Check if the row has the 'inactive' class
        var rowClass = $(this.node()).attr('class');
        console.log(rowClass);

        if (rowClass=="inactive") {
            // Add a CSS class to change background color to blue
            $(this.node()).css('background-color', 'blue!important');
            console.log(1123);
        }
    });
});

 

</script>