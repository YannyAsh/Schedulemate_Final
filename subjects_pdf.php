<?php
include 'subject_all_process.php';
include 'include/header.php';
// var_dump($_SESSION);
if (isset($_GET['sub_edit'])) {
    $subID = $_GET['sub_edit'];
    $sub_edit_state = true;
    $record = mysqli_query($conn, "SELECT * FROM tb_subjects WHERE subID=$subID");
    $data = mysqli_fetch_array($record);
    $subCode = $data['subCode'];
    $subDesc = $data['subDesc'];
    $subUnits = $data['subUnits'];
    $subLabhours = $data['subLabhours'];
    $subLechours = $data['subLechours'];
    $subStatus = $data['subStatus'];
}

// Function to generate academic year options dynamically
function generateAcademicYears()
{
    $currentYear = date("Y");
    $options = "";

    for ($i = $currentYear; $i <= $currentYear + 10; $i++) {
        $nextYear = $i + 1;
        $academicYear = "SY " . $i . " - " . $nextYear;
        $plotYear = "SY " . $i . " - " . $nextYear;
        $options .= "<option value=\" $plotYear\">$academicYear</option>";
    }

    return $options;
}
?>


            <!-- Start of the contents -->
            <div class="container-fluid px-4">
                <div class="row g-3 my-2">
                    <div class="container">
                        <div class="table-wrapper">
                            <div class="table-title">
                                <div class="row">
                                    <div class="col-sm-7">
                                        <h2>PDF Subjects</h2>
                                    </div>
                                </div>
                            </div>
                            <div class="table-responsive">
                                <table class="table table-hover" id="table">
                                    <thead>
                                        <tr>
                                            <th>No.</th>
                                            <th>Academic Year</th>
                                            <th>Actions</th>
                                        </tr>
                                    </thead>
                                    <tbody id="myTable">
                                        <?php
                                        $program = $_SESSION['program'];
                                        $conditions = [' SubCourse= "'.$program.'" '];
                                        $sql = $db->getAllRowsFromTableWhereGroup('tb_subjects',$conditions,'SubCourse');
                                        $i = 1;
                                        foreach ($sql as $row) {
                                        ?>
                                            <tr>
                                                <td><?php echo $row["subID"] ?></td>
                                                <td><?php echo $row["subYear"] ?></td>
                                                <td>
                                                        <a target="_blank" href="pdf-subjects.php?ay=<?=$row["subYear"]?>" class="text-warning" ><i class="material-icons" title="Status">&#xe415;</i></a>

                                                </td>
                                            </tr>
                                        <?php
                                            $i++;
                                        } ?>
                                    </tbody>
                                </table>
                            </div>
                        </div>
                    </div>
                    <!-- Add Modal HTML -->
                    <div id="addSubj" class="modal fade" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
                        <div class="modal-dialog modal-lg" role="document">
                            <div class="modal-content">
                                <form method="POST" action="subject_all_process.php">
                                    
                                    <div class="modal-header">
                                        <h5 class="modal-title">Add New Subject</h5>
                                        <button type="button" class="close" data-bs-dismiss="modal" aria-label="Close">
                                            <span aria-hidden="true">&times;</span>
                                        </button>
                                    </div>
                                    <div class="modal-body">
                                        <div class="form-group">
                                            <label for="subYear">Academic Year</label>
                                            <select name="subYear" class="upper-form-control" id="subYear" required>
                                                <option value="" disabled selected>Select Academic Year</option>
                                                <!-- Function to generate academic year options -->
                                                <?php echo generateAcademicYears(); ?>
                                            </select>

                                            <label for="subSem" class="upper-label">Semester</label>
                                            <select name="subSem" class="upper-form-control" id="subSem" required>
                                                <option value="" disabled selected>Select Semester</option>
                                                <option value="1st Semester">1st Semester</option>
                                                <?php
                                                // Check if the 1st Semester is selected, and hide the 2nd Semester option
                                                if ($_POST['subSem'] !== "1st Semester") {
                                                ?>
                                                    <option value="2nd Semester">2nd Semester</option>
                                                <?php
                                                }
                                                ?>
                                            </select>
                                        </div>
                                        <div class="form-group" hidden>
                                            <label for="numRows">Enter the number of rows:</label>
                                            <input type="number" class="form-control" id="numRows" placeholder="Number of Rows">
                                            <button type="button" class="btn btn-primary" id="createRowsBtn">Create Rows</button>
                                        </div>
                                        <div id="formInputs">
                                            <!-- Initial form inputs -->
                                            <div class="form-row" id="rowTemplate" style="display: none;">
                                                <div class="label-container">
                                                    <label class="row-label"></label>
                                                </div>
                                                <div class="col">
                                                    <label for="subCode[]">Subject Code</label>
                                                    <input type="text" required placeholder="Subject Code" name="subCode[]" id="subCode" class="form-control" value="<?php echo $subCode ?>">
                                                    <label for="subLabhours[]">Subject Lab Hours</label>
                                                    <input type="number" placeholder="Subject Lab Hours" name="subLabhours[]" id="subLabhours" class="form-control" value="<?php echo $subLabhours ?>">
                                                    <button type="button" class="btn btn-danger remove-btn" disabled>Remove</button>
                                                    <button type="button" class="btn mt-1 btn-primary addrow-btn" >Add Row</button>
                                                </div>
                                                <div class="col">
                                                    <label for="subDesc[]">Subject Description</label>
                                                    <input type="text" required placeholder="Subject Description" name="subDesc[]" id="subDesc" class="form-control" value="<?php echo $subDesc ?>">
                                                    <label for="subLechours[]">Subject Lec Hours</label>
                                                    <input type="number" required placeholder="Subject Lec Hours" name="subLechours[]" id="subLechours" class="form-control" value="<?php echo $subLechours ?>">
                                                </div>
                                                <div class="col">
                                                    <label for="subUnits[]">Subject Hours</label>
                                                    <input type="number" required placeholder="Subject Hours" name="subUnits[]" id="subUnits" class="form-control" value="<?php echo $subUnits ?>">
                                                </div>
                                            </div>
                                        </div>
                                        <div id="addedFormInputs">

                                        </div>
                                    </div>
                                    <div class="modal-footer">
                                        <input type="button" class="btn" data-bs-dismiss="modal" value="Cancel">
                                        <input type="submit" name="sub_add_new" class="btn" value="Add">

                                    </div>
                                </form>
                            </div>
                        </div>
                    </div>
                    <!-- Custom modal for confirmation -->
                    <div class="modal fade" id="confirmModal" tabindex="-1" role="dialog" aria-labelledby="confirmModalLabel" aria-hidden="true">
                        <div class="modal-dialog" role="document">
                            <div class="modal-content">
                                <div class="modal-header">
                                    <h5 class="modal-title" id="confirmModalLabel">Confirmation</h5>
                                    <button type="button" class="close" data-bs-dismiss="modal" aria-label="Close">
                                        <span aria-hidden="true">&times;</span>
                                    </button>
                                </div>
                                <div class="modal-body">
                                    Are you sure you want to remove this row?
                                </div>
                                <div class="modal-footer">
                                    <button type="button" class="btn" data-bs-dismiss="modal">Cancel</button>
                                    <button type="button" class="btn" id="confirmDeleteBtn">OK</button>
                                </div>
                            </div>
                        </div>
                    </div>
                    <!-- Edit Modal HTML -->
                    <div id="editSubj" class="modal fade">
                        <div class="modal-dialog">
                            <div class="modal-content">

                                <form method="POST" action="subject_all_process.php">
                                    <input type="hidden" name="subID" id="edit_id_subID" value="<?php echo $subID; ?>">
                                    <input type="hidden" name="subStatus" value="<?php echo $subStatus; ?>">

                                    <div class="modal-header">
                                        <h5 class="modal-title">Edit Subject</h5>
                                        <button type="button" class="close" data-bs-dismiss="modal" aria-hidden="true">&times;</button>
                                    </div>
                                    <div class="modal-body">
                                        <div class="form-group">
                                            <label>Subject Code</label>
                                            <input type="text" name="subCode" id="edit_subCode" class="form-control" required >
                                        </div>
                                        <div class="form-group">
                                            <label>Subject Description</label>
                                            <input type="text" name="subDesc" id="edit_subDesc" class="form-control" required >
                                        </div>
                                        <div class="form-group">
                                            <label>Subject Units</label>
                                            <input type="number" name="subUnits" id="edit_subUnits" class="form-control" required >
                                        </div>
                                        <div class="form-group">
                                            <label>Subject Lab Hours</label>
                                            <input type="number" name="subLabhours" id="edit_subLabhours" class="form-control" required >
                                        </div>
                                        <div class="form-group">
                                            <label>Subject Lec Hours</label>
                                            <input type="number" name="subLechours" id="edit_subLechours" class="form-control" required >
                                        </div>
                                    </div>
                                    <div class="modal-footer">
                                        <input type="button" class="btn" data-bs-dismiss="modal" value="Cancel">
                                        <input type="submit" name="sub_update" class="btn" value="Update">
                                    </div>
                                </form>
                            </div>
                        </div>
                    </div>
                    <!-- Change Status Modal HTML -->
                    <div id="statusSubj" class="modal fade">
                        <div class="modal-dialog">
                            <div class="modal-content">
                                <form method="POST">
                                    <div class="modal-header">
                                        <h5 class="modal-title">Change Subject Status</h5>
                                        <button type="button" class="close" data-bs-dismiss="modal" aria-hidden="true">&times;</button>
                                    </div>
                                    <div class="modal-body">
                                        <input type="text" name="subID" id="idStatus">
                                        <h6>Are you sure you want to inactivate this Subject?</h6>
                                    </div>
                                    <div class="modal-footer">
                                        <input type="button" class="btn" data-bs-dismiss="modal" value="Cancel">
                                        <input type="submit" class="btn" name="sub_toggle_status" value="Confirm Status">
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

    <!-- creating rows modal -->
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>

    <script>
        $(document).ready(function() {
            var rowToRemove; // Store the row to be removed
            var rowCount = 1; // Initialize row count

            // Initialize the modal with one form row
            var rowTemplate = $('#rowTemplate').clone();
            rowTemplate.removeAttr('id').removeAttr('style');
            rowTemplate.find('.row-label').text('Subject ' + rowCount + ':');
            rowCount++;
            $('#formInputs').append(rowTemplate);

            $('#createRowsBtn').click(function() {
                var numRows = $('#numRows').val();
                if (!numRows || isNaN(numRows) || numRows <= 0) {
                    alert('Please enter a valid number of rows.');
                    return;
                }

                for (var i = 0; i < numRows; i++) {
                    var newRow = rowTemplate.clone();
                    newRow.find('.row-label').text('Subject ' + rowCount + ':');
                    rowCount++;
                    newRow.find('.remove-btn').prop('disabled', false); // Enable the remove button for the new row
                    $('#formInputs').append(newRow);
                }
            });

            // Remove row button functionality (for existing rows and dynamically added rows)
            $(document).on('click', '.remove-btn', function() {
                var formRow = $(this).closest('.form-row');
                var formRows = formRow.siblings('.form-row');
                if (formRows.length === 0) {
                    alert('At least one form is required.');
                } else {
                    // Store the row to be removed
                    rowToRemove = formRow;
                    $('#confirmModal').modal('show'); // Show the custom confirmation modal
                }
            });

            // Handle confirm deletion in the custom modal
            $('#confirmDeleteBtn').click(function() {
                rowToRemove.remove();
                $('#confirmModal').modal('hide'); // Hide the custom modal

                // Update the subject numbers after row deletion
                var subjectRows = $('.form-row');
                // index = 1
                subjectRows.each(function(index) {
                    $(this).find('.row-label').text('Subject ' + (index) + ':');
                });

                // Reset rowCount based on the current number of rows in adding new rows
                rowCount = subjectRows.length + 1;
            });

            // Prevent modal from closing
            var modal = document.getElementById('addSubj');
            modal.addEventListener('hide.bs.modal', function(event) {
                var confirmClose = confirm("Are you sure you want to close the modal? Any unsaved changes will be lost.");
                if (!confirmClose) {
                    event.preventDefault();
                }
            });

        });
    </script>

    <!-- Saving edit modal -->
    <script>
        $(document).ready(function() {

            // display Edit modal

            $('.edit').on('click', function() {

                $('#editSubj').modal('show');

                $tr = $(this).closest('tr');

                var data = $tr.find("td").map(function() {
                    return $(this).text();
                }).get();

                console.log(data);
                $('#edit_id_subID').val(data[0]);
                $('#edit_subCode').val(data[3]);
                $('#edit_subDesc').val(data[4]);
                $('#edit_subUnits').val(data[5]);
                $('#edit_subLabhours').val(data[6]);
                $('#edit_subLechours').val(data[7]);
                // $('#secDay').val(data[4]);
                // $('#secNight').val(data[5]);
                // $('#secStatus').val(data[6]);

                // if (data[4] == 'Day') {


                // }

            });

        });
    </script>
</body>

</html>
<script>
        var rowToRemove; // Store the row to be removed
            var rowCount = 1; // Initialize row count
     $(document).on('click', '#addSubj .addrow-btn', function(){
        var rowCountCurrent = 0;
        $('.row-label').each(function(){
            rowCountCurrent+=1;
            })
        console.log(rowCountCurrent);
        rowCount++;

            // Initialize the modal with one form row
            var rowTemplate = $('#rowTemplate').clone();
            rowTemplate.removeAttr('id').removeAttr('style');
            rowTemplate.find('.row-label').text('Subject ' + rowCountCurrent + ':');

            rowTemplate.find('.remove-btn').prop('disabled', false); // Enable the remove button for the new row
            $('#formInputs').append(rowTemplate);
          
            });

</script>
<link rel="stylesheet" href="https://cdn.datatables.net/2.0.5/css/dataTables.dataTables.min.css">
<script src="https://cdn.datatables.net/2.0.5/js/dataTables.min.js"></script>
<script>
    $('#table').DataTable();
</script>
<script>
  $('.delbtn').click(function(){
    var id = $(this).attr('data-id');
    $('#idStatus').val(id).hide()
});

</script>