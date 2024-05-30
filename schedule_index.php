<?php
include 'conn/conn.php';
include 'schedule_all_process.php';
include 'include/header.php';

$db = new DatabaseHandler();

// Display data for dropdown
$stmnt = "SELECT subID, subCode,subYearlvl,SubCourse,subType,subSem FROM tb_subjects where status = 0 ";
$result_subject = mysqli_query($conn, $stmnt);

$stmnt = "SELECT secID, secProgram, secYearlvl, secName,secCourse  FROM tb_section where status = 0 ";
$result_section = mysqli_query($conn, $stmnt);

$stmnt = "SELECT roomID, roomBuild, roomNum  FROM tb_room where status = 0";
$result_room = mysqli_query($conn, $stmnt);

$stmnt = "SELECT profID, profFname, profLname  FROM tb_professor where status = 0 ";
$result_professor = mysqli_query($conn, $stmnt);

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
$postionLogic;
if (isset($_SESSION['position'])) {
    $position = $_SESSION['position'];
    if ($position == "dean") {
    } else if ($postion == "admin") {
    }
}

// CONDITIONS FOR SQLs
// var_dump($_SESSION);
$position = $_SESSION['postion'];
$conditions = [];
$hidden = 'hidden';
$program = $_SESSION['program'];
$programType = '';

if ($position == "dean" || $position == "chairperson") {
    //$conditions = ["course = '".$program."'"];
    $hidden = 'hidden';

    if ($position == "chairperson") {
        $hidden = '';
        if (strpos($program, 'CAS') !== false) {
            $conditions = [];
        }
    }
} else if ($position == "admin") {
    $hidden = '';
}
// CAS ONLY
if (strpos($program, 'CAS') !== false) {
    $programType = 'CAS';
    $conditions = [];
}
$programType = json_encode($programType);

// END CONDITIONS FOR SQLs
?>

<!-- Start of the contents -->
<div class="container-fluid px-4">
    <div class="row g-3 my-2">
        <div class="container">
            <div class="table-wrapper">
                <div class="table-title">
                    <div class="row">
                        <div class="col-sm-7">
                            <h2>Manage Schedule Entries</h2>
                        </div>
                        <div class="col">
                            <a <?= $hidden ?> href="#addSchedule" class="btn btn-success" data-bs-toggle="modal"><i class="material-icons">&#xE147;</i><span>Add New Schedule</span></a>
                        </div>
                    </div>
                </div>
                <div class="table-responsive">
                    <table id="myTable" class="table table-hover">
                        <thead>
                            <tr>
                                <th>No.</th>
                                <th>Academic Year</th>
                                <th>Semester</th>
                                <th>Program & Section</th>
                                <th>Actions</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php
                            $sql = $db->getAllRowsFromTableWhereGroup('tb_scheduled', $conditions, ' sy,semester,section ');
                            $i = 0;
                            ?>
                            <?php
                            foreach ($sql as $row) {
                                //CONDITION ONLY FOR CAS CHAIRPERSON 
                                $casCondition = '';
                                if (strpos($program, 'CAS') !== false) {
                                    $courseRow = $row['course'];
                                    if (!strpos($courseRow, 'CAS') !== false) {
                                        $casCondition = 'hidden';
                                    }
                                }
                                // END CONDITION ONLY FOR CAS CHAIRPERSON 

                                $i++;
                                if ($_SESSION['college'] == "cas") {
                                    $yrs = array("first year", "second year", "third year", "fourth year");
                                    $year = $yrs[explode("-", $row['section'])[1] - 1];
                                    if ($db->getMajorCountsOfPlot($row['semester'], $row['section'], $row['course']) == $db->getMajorCountsByYearSem($year, $row['semester'], $row['course'])) {
                            ?>
                                        <tr>
                                            <td><?= $i ?></td>
                                            <td><?= $row['sy'] ?></td>
                                            <td><?= $row['semester'] ?></td>
                                            <td><?= strtoupper($row['course'] . '/' . $row['section']) ?></td>
                                            <td>
                                                <a href="#viewSchedule" class="view viewbtn text-primary" data-bs-toggle="modal" data-sy="<?= $row['sy'] ?>" data-semester="<?= $row['semester'] ?>" data-section="<?= $row['section'] ?>"><i class="material-icons" data-bs-toggle="tooltip" title="View">&#xe8f4;</i></a>

                                                <a <?= $hidden ?> target="_blank" href="schedule_edit.php?sy=<?= $row['sy'] ?>&semester=<?= $row['semester'] ?>&section=<?= $row['section'] ?>" class="text-success "><i class="material-icons" data-bs-toggle="tooltip" title="Status">&#xe3c9;</i></a>

                                                <a <?= $casCondition ?> <?= $hidden ?> href="#statusSchedule" class="status deac" data-bs-toggle="modal" data-sy="<?= $row['sy'] ?>" data-semester="<?= $row['semester'] ?>" data-section="<?= $row['section'] ?>"><i class="material-icons" data-bs-toggle="tooltip" title="Status">&#xe909;</i></a>

                                            </td>
                                        </tr>
                                    <?php
                                        // $i++;
                                    }
                                } else if ($program == $row['course']) {
                                    ?>
                                    <tr>
                                        <td><?= $i ?></td>
                                        <td><?= $row['sy'] ?></td>
                                        <td><?= $row['semester'] ?></td>
                                        <td><?= strtoupper($row['course'] . '/' . $row['section']) ?></td>
                                        <td>
                                            <a href="#viewSchedule" class="view viewbtn text-primary" data-bs-toggle="modal" data-sy="<?= $row['sy'] ?>" data-semester="<?= $row['semester'] ?>" data-section="<?= $row['section'] ?>"><i class="material-icons" data-bs-toggle="tooltip" title="View">&#xe8f4;</i></a>

                                            <a <?= $hidden ?> target="_blank" href="schedule_edit.php?sy=<?= $row['sy'] ?>&semester=<?= $row['semester'] ?>&section=<?= $row['section'] ?>" class="text-success "><i class="material-icons" data-bs-toggle="tooltip" title="Status">&#xe3c9;</i></a>

                                            <a <?= $casCondition ?> <?= $hidden ?> href="#statusSchedule" class="status deac" data-bs-toggle="modal" data-sy="<?= $row['sy'] ?>" data-semester="<?= $row['semester'] ?>" data-section="<?= $row['section'] ?>"><i class="material-icons" data-bs-toggle="tooltip" title="Status">&#xe909;</i></a>

                                        </td>
                                    </tr>
                            <?php
                                }
                            } ?>

                            <?php
                            $sql = $db->getAllRowsFromTableWhereGroup2('tb_scheduled', $conditions, ' sy,semester,section');
                            ?>
                            <?php
                            foreach ($sql as $row) {
                                $i++;
                            ?>
                                <tr>
                                    <td class="text-danger"><?= $i ?></td>
                                    <td class="text-danger"><?= $row['sy'] ?></td>
                                    <td class="text-danger"><?= $row['semester'] ?></td>
                                    <td class="text-danger"><?= strtoupper($row['course'] . '/' . $row['section']) ?></td>
                                    <td class="text-danger">
                                        <a href="#viewSchedule" class="view viewbtn" data-bs-toggle="modal" data-sy="<?= $row['sy'] ?>" data-semester="<?= $row['semester'] ?>" data-section="<?= $row['section'] ?>"><i class="material-icons" data-bs-toggle="tooltip" title="View">&#xe8f4;</i></a>
                                    </td>
                                </tr>
                            <?php
                                // $i++;

                            } ?>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
        
        <!-- Add Modal HTML -->
        <div id="addSchedule" class="modal fade" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
            <div class="modal-dialog modal-lg" role="document">
                <div class="modal-content">
                    <form method="POST" action="php/action_page.php">
                        <div class="modal-header">
                            <h5 class="modal-title">Add New Schedule</h5>
                            <button type="button" class="close-2" data-bs-dismiss="modal" aria-label="Close">
                                <span aria-hidden="true">&times;</span>
                            </button>
                        </div>
                        <div class="modal-body">
                            <div class="form-group">
                                <div class="row">

                                    <div class="col">
                                        <select name="plotYear" required id="plotYear" class="form-control">
                                            <option value="" disabled selected>Select Academic Year</option>
                                            <?php echo generateAcademicYears(); ?>
                                        </select>
                                    </div>
                                    <div class="col">
                                        <select class="form-control" name="plotSem" required id="plotSem">
                                            <option value="" disabled selected>Select Semester</option>
                                            <option value="1st Semester">1st Semester</option>
                                            <?php
                                            // Check if the 1st Semester is selected, and hide the 2nd Semester option
                                            if ($_POST['plotSem'] !== "1st Semester") {
                                            ?>
                                                <option value="2nd Semester">2nd Semester</option>
                                            <?php
                                            }
                                            ?>
                                        </select>
                                    </div>
                                    <div class="col">
                                        <select class="form-control" required name="plotSection" id="plotSection">
                                            <option value="" disabled selected>Select Section</option>
                                            <?php
                                            if (mysqli_num_rows($result_section) > 0) {
                                                while ($row = mysqli_fetch_assoc($result_section)) {
                                            ?>
                                                    <option data-program="<?= $row['secCourse'] ?>" data-yearlevel="<?= $row['secYearlvl'] ?>" value="<?= $row['secProgram'] ?>-<?= $row['secYearlvl'] ?>-<?= $row['secName'] ?> ">
                                                        <!-- DISPLAY -->
                                                        <?= $row['secProgram'] ?> <?= $row['secYearlvl'] ?> <?= $row['secName'] ?>
                                                        <!-- END DISPLAY -->
                                                    </option>
                                            <?php
                                                }
                                            }
                                            ?>
                                        </select>
                                    </div>
                                </div>
                                <div id="formInputs">
                                    <!-- Initial form inputs -->

                                    <div class="form-row" id="rowTemplate" style="display: none;">
                                        <div class="label-container">
                                            <hr>
                                            <label class="row-label"></label>
                                        </div>
                                        <div class="container">

                                            <div class="row">
                                                <div class="col-4">
                                                    <select class="form-control" name="plotSubj[]" id="plotSubj">
                                                        <option value="" disabled selected>Select Subject </option>
                                                        <?php

                                                        if (mysqli_num_rows($result_subject) > 0) {
                                                            while ($row = mysqli_fetch_assoc($result_subject)) {
                                                                if ($_SESSION['userCollege'] == "cas" || $row['subType'] == "minor") {
                                                                    $sec = explode("-", $_GET['section']);
                                                                    $trimmed = trim($sec[0]);
                                                                    $userSection = $trimmed;

                                                                    $sec = explode("-", $row['SubCourse']);
                                                                    $trimmed = trim($sec[0]);
                                                                    $subCourses = $trimmed;

                                                                    if ($row['subType'] == "minor" && $userSection == $subCourses) {
                                                        ?>
                                                                        <option data-program="<?= $row['SubCourse'] ?>" data-yearlevel="<?= $row['subYearlvl'] ?>" data-sem="<?= $row['subSem'] ?>" value="<?= $row['subCode'] ?> ">
                                                                            <!-- DISPLAY -->
                                                                            <?= $row['subCode'] ?> / / <?= $row['subType'] ?> //<?= $row['SubCourse'] ?>
                                                                            <!-- END DISPLAY -->
                                                                        </option>
                                                                    <?php
                                                                    }
                                                                } else if ($row['subType'] == "major" && $userSection == $subCourses) {
                                                                    ?>
                                                                    <option data-program="<?= $row['SubCourse'] ?>" data-yearlevel="<?= $row['subYearlvl'] ?>" data-sem="<?= $row['subSem'] ?>" value="<?= $row['subCode'] ?> ">
                                                                        <!-- DISPLAY -->
                                                                        <?= $row['subCode'] ?> / / <?= $row['subType'] ?> //<?= $row['SubCourse'] ?>
                                                                        <!-- END DISPLAY -->
                                                                    </option>
                                                        <?php
                                                                }
                                                            }
                                                        }
                                                        ?>
                                                    </select>
                                                </div>

                                                <div class="col-4">
                                                    <select class="form-control" name="plotProf[]" id="plotProf">
                                                        <option value="" disabled selected>Select Professor</option>
                                                        <option value="TBA">TBA ( To be Announce )</option>
                                                        <?php
                                                        if (mysqli_num_rows($result_professor) > 0) {
                                                            while ($row = mysqli_fetch_assoc($result_professor)) {
                                                        ?>
                                                                <option value="<?= $row['profID'] ?>"><?= $row['profFname'] ?> <?= $row['profLname'] ?></option>
                                                        <?php
                                                            }
                                                        }
                                                        ?>
                                                    </select>
                                                </div>

                                                <div class="col-4">
                                                    <select class="form-control" name="plotRoom[]" id="plotRoom">
                                                        <option value="" disabled selected>Select Room</option>
                                                        <?php
                                                        if (mysqli_num_rows($result_room) > 0) {
                                                            while ($row = mysqli_fetch_assoc($result_room)) {
                                                        ?>
                                                                <option value="<?= $row['roomBuild'] ?> <?= $row['roomNum'] ?>"><?= $row['roomBuild'] ?> <?= $row['roomNum'] ?></option>
                                                        <?php
                                                            }
                                                        }
                                                        ?>
                                                    </select>
                                                </div>
                                            </div>

                                            <div class="row">
                                                <div class="col-sm-4">
                                                    <h6 class="day-heading">MONDAY</h6>
                                                    <label>Time Starts</label>
                                                    <input type="time" min="07:00" max="19:00" name="tsMon[]" class="form-control">
                                                    <label>Time Ends</label>
                                                    <input type="time" min="07:00" max="19:00" name="teMon[]" class="form-control">
                                                </div>
                                                <div class="col-sm-4">
                                                    <h6 class="day-heading">TUESDAY</h6>
                                                    <label>Time Starts</label>

                                                    <input type="time" min="07:00" max="19:00" name="tsTue[]" class="form-control">
                                                    <label>Time Ends</label>

                                                    <input type="time" min="07:00" max="19:00" name="teTue[]" class="form-control">
                                                </div>
                                                <div class="col-sm-4">
                                                    <h6 class="day-heading">WEDNESDAY</h6>
                                                    <label>Time Starts</label>
                                                    <input type="time" min="07:00" max="19:00" name="tsWed[]" class="form-control">


                                                    <label>Time Ends</label>
                                                    <input type="time" min="07:00" max="19:00" name="teWed[]" class="form-control">
                                                </div>
                                            </div>

                                            <div class="row">
                                                <div class="col">
                                                    <h6 class="day-heading">THURSDAY</h6>
                                                    <label>Time Starts</label>

                                                    <input type="time" min="07:00" max="19:00" name="tsThu[]" class="form-control">

                                                    <label>Time Ends</label>
                                                    <input type="time" min="07:00" max="19:00" name="teThu[]" class="form-control">
                                                </div>
                                                <div class="col">
                                                    <h6 class="day-heading">FRIDAY</h6>
                                                    <label>Time Starts</label>
                                                    <input type="time" min="07:00" max="19:00" name="tsFri[]" class="form-control">

                                                    <label>Time Ends</label>
                                                    <input type="time" min="07:00" max="19:00" name="teFri[]" class="form-control">
                                                </div>
                                                <div class="col">
                                                    <h6 class="day-heading">SATURDAY</h6>
                                                    <label>Time Starts</label>

                                                    <input type="time" min="07:00" max="19:00" name="tsSat[]" class="form-control">

                                                    <label>Time Ends</label>
                                                    <input type="time" min="07:00" max="19:00" name="teSat[]" class="form-control">
                                                </div>
                                                <div class="col">
                                                    <h6 class="day-heading">SUNDAY</h6>
                                                    <label>Time Starts</label>

                                                    <input type="time" min="07:00" max="19:00" name="tsSun[]" class="form-control">

                                                    <label>Time Ends</label>
                                                    <input type="time" min="07:00" max="19:00" name="teSun[]" class="form-control">
                                                </div>
                                            </div>
                                            <button type="button" class="btn btn-danger remove-btn" disabled>Remove</button>
                                            <button type="button" id="addbtn" class="btn btn-primary mt-1 add-btn">Add rows</button>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <div class="modal-footer">
                            <input type="button" class="btn" data-bs-dismiss="modal" value="Cancel">
                            <input type="submit" name="sched_add_new" class="btn" value="Add">
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

        <!-- View Modal HTML -->
        <div id="viewSchedule" class="modal fade">
            <div class=" modal-dialog modal-lg">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title">View Schedule Details</h5>
                        <button type="button" class="close-2" data-bs-dismiss="modal" aria-hidden="true">&times;</button>
                    </div>
                    <div class="modal-body">
                        <div class="table-responsive" id="myTable1">


                        </div>
                    </div>
                    <!-- <div class="modal-footer">
                                    <form method="POST" action="schedule_all_process.php">
                                        <input type="button" class="btn" data-bs-dismiss="modal" value="Close">
                                        <input type="hidden" name="subID" value="<?php echo $row['plotID']; ?>">
                                        <a class="btn btn-default" data-bs-toggle="modal" data-bs-dismiss="modal" href="#editSchedule" role="button">Edit</a>
                                </div> -->
                </div>
            </div>
        </div>


    </div>

</div>
</div>
</div>
<!-- /#page-content-wrapper -->
</div>
</body>

</html>
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
<!-- Bootstrap JS and jQuery -->
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

    });
</script>


<script>
    var rowToRemove; // Store the row to be removed
    var rowCount = 1; // Initialize row count
    $(document).on('click', '#addSchedule .add-btn', function() {
        var rowCountCurrent = 0;
        $('.row-label').each(function() {
            rowCountCurrent += 1;
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
    $('#addSchedule #plotSubj option').each(function() {
        $(this).hide();
    })

    var programType = <?php echo ($programType); ?>;

    $(document).on('change', '#addSchedule #plotSection', function() {
        var selectedSem = ($('#addSchedule #plotSem').val())
        console.log(selectedSem)
        $('#addSchedule #plotSubj').val('')
        $('#addSchedule #plotSubj option').each(function() {
            $(this).show();
        })
        var program = $(this).find('option:selected').data('program');
        var yearlevel = $(this).find('option:selected').data('yearlevel');
        console.log(program);


        $('#addSchedule #plotSubj option').each(function() {

            eachProgram = $(this).attr('data-program');
            eachYearLevel = $(this).attr('data-yearlevel');
            eachSem = $(this).attr('data-sem');

            if (eachYearLevel === "first year") {
                eachYearLevel = 1;
            } else if (eachYearLevel === "second year") {
                eachYearLevel = 2;

            } else if (eachYearLevel === "third year") {
                eachYearLevel = 3;

            } else if (eachYearLevel === "fourth year") {
                eachYearLevel = 4;
            }

            // console.log(eachSem)



            if (program === eachProgram && yearlevel === eachYearLevel && programType != "CAS" && selectedSem == eachSem) {
                $(this).show();
            } else {
                $(this).hide();
                // condition only for cas
                if (yearlevel === eachYearLevel && eachProgram && typeof eachProgram === 'string' && eachProgram.includes("CAS") && programType === "CAS" && selectedSem == eachSem) {
                    // console.log("String contains the substring.");
                    $(this).show();
                }
                // end condition only for cas

            }


        });

    });
</script>
<script>
    // editing
    $(document).on('change', '#addSchedule #plotYear', function() {
        $('#addSchedule #plotSem').val('')
        $('#addSchedule #plotSection').val('')
        $('#addSchedule #plotSubj').val('')

    });
    $(document).on('change', '#addSchedule #plotSem', function() {
        $('#addSchedule #plotSection').val('')
        $('#addSchedule #plotSubj').val('')
    });



    $(document).on('click', '#addSchedule .timeInput', function() {
        console.log(1123123)
        var timeInput = $(this).val();
        var minutes = parseInt(timeInput.split(':')[1]);

        if (minutes !== 0) {
            $(this).hide();
        } else {
            $(this).show();
        }

    });

    $('#appt-time').on('change', function() {
        var timeInput = $(this).val();
        var minutes = parseInt(timeInput.split(':')[1]);

        if (minutes !== 0) {
            $(this).hide();
        } else {
            $(this).show();
        }
    });
</script>
<script>
    $('.viewbtn').click(function() {
        var sy = $(this).data('sy');
        var semester = $(this).data('semester');
        var section = $(this).data('section');

        // AJAX request
        $.ajax({
            url: 'php/schedule.php',
            method: 'POST',
            data: {
                sy: sy,
                semester: semester,
                section: section
            },
            success: function(response) {
                // Handle success response
                $('#myTable1').html(response);
            },
            error: function(xhr, status, error) {
                // Handle error
                console.error(xhr.responseText);
            }
        });
    });

    $('.deac').click(function() {
        var sy = $(this).data('sy');
        var semester = $(this).data('semester');
        var section = $(this).data('section');

        $('#deac_sy').val(sy)
        $('#deac_semester').val(semester)
        $('#deac_section').val(section)
    })
</script>
<link rel="stylesheet" href="https://cdn.datatables.net/2.0.5/css/dataTables.dataTables.min.css">
<script src="https://cdn.datatables.net/2.0.5/js/dataTables.min.js"></script>
<script>
    $('#myTable').DataTable();
</script>