<?php
include 'conn/conn.php';
include 'schedule_all_process.php';
include 'include/header.php';

$db = new DatabaseHandler();

// Display data for dropdown
$stmnt = "SELECT * FROM tb_subjects where status = 0 ";
$result_subject = mysqli_query($conn, $stmnt);

$stmnt = "SELECT * FROM tb_section where status = 0 ";
$result_section = mysqli_query($conn, $stmnt);

$stmnt = "SELECT * FROM tb_room where status = 0";
$result_room = mysqli_query($conn, $stmnt);

$stmnt = "SELECT * FROM tb_professor where status = 0 ";
$result_professor = mysqli_query($conn, $stmnt);

function generateAcademicYears()
{
    $currentYear = date("Y");
    $options = "";

    for ($i = $currentYear; $i <= $currentYear + 10; $i++) {
        $nextYear = $i + 1;
        $academicYear = "A.Y. " . $i . " - " . $nextYear;
        $plotYear = "A.Y. " . $i . " - " . $nextYear;
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
$program = $_SESSION['program'];
$conditions = [];
$hidden = 'hidden';
$college = '';

if ($position == "dean" || $position == "chairperson") {
    //$conditions = ["course = '".$program."'"];
    $hidden = 'hidden';

    if ($position == "chairperson") {
        $hidden = '';
        if (strpos($program, 'COLLEGE OF ARTS AND SCIENCES') !== false) {
            $conditions = [];
        }
    }
} else if ($position == "admin") {
    $hidden = '';
}
// CAS ONLY
if (strpos($program, 'COLLEGE OF ARTS AND SCIENCES') !== false) {
    $college = 'COLLEGE OF ARTS AND SCIENCES';
    $conditions = [];
}
$college = json_encode($college);

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
                    <table id="myTable" class="table-striped">
                        <thead>
                            <tr>
                                <th>No.</th>
                                <th>Academic Year</th>
                                <th>Semester</th>
                                <th>Section</th>
                                <th>Actions</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php
                            $sql = $db->getAllRowsFromTableWhereGroup('tb_scheduled', $conditions);
                            $i = 0;
                            ?>
                            <?php
                            foreach ($sql as $row) {
                                //CONDITION ONLY FOR CAS CHAIRPERSON 
                                $casCondition = '';
                                if (strpos($program, 'COLLEGE OF ARTS AND SCIENCES') !== false) {
                                    $courseRow = $row['course'];
                                    if (!strpos($courseRow, 'COLLEGE OF ARTS AND SCIENCES') !== false) {
                                        $casCondition = 'hidden';
                                    }
                                }
                                // END CONDITION ONLY FOR CAS CHAIRPERSON 

                                $i++;
                                if ($_SESSION['college'] == "COLLEGE OF ARTS AND SCIENCES") {
                                    $yrs = array("first year", "second year", "third year", "fourth year");
                                    $year = $yrs[explode("-", $row['secID'])[1] - 1];
                                    if ($db->getMajorCountsOfPlot($row['semester'], $row['secID'], $row['course']) == $db->getMajorCountsByYearSem($year, $row['semester'], $row['course'])) {
                            ?>
                                        <tr>
                                            <td><?= $i ?></td>
                                            <td><?= $row['school_yr'] ?></td>
                                            <td><?= $row['semester'] ?></td>
                                            <td><?= strtoupper($row['secProgram'] . ' ' . $row['secYearlvl'] . '-' . $row['secName']) ?></td>
                                            <td>
                                                <a href="#viewSchedule" class="view viewbtn text-primary" data-bs-toggle="modal" data-sy="<?= $row['school_yr'] ?>" data-semester="<?= $row['semester'] ?>" data-section="<?= $row['secID'] ?>"><i class="material-icons" data-bs-toggle="tooltip" title="View">&#xe8f4;</i></a>
                                                <a <?= $hidden ?> target="_blank" href="schedule_edit.php?sy=<?= $row['school_yr'] ?>&semester=<?= $row['semester'] ?>&section=<?= $row['secID'] ?>&schedId=<?= $row['id']; ?>" class="text-success "><i class="material-icons" data-bs-toggle="tooltip" title="Status">&#xe3c9;</i></a>
                                                <a <?= $casCondition ?> <?= $hidden ?> href="#statusSchedule" class="status deac" data-bs-toggle="modal" data-sy="<?= $row['sy'] ?>" data-semester="<?= $row['semester'] ?>" data-section="<?= $row['secID'] ?>"><i class="material-icons" data-bs-toggle="tooltip" title="Status">&#xe909;</i></a>
                                            </td>
                                        </tr>
                                    <?php
                                        $i++;
                                    }
                                } else if ($program == $row['course']) {
                                    ?>
                                    <tr>
                                        <td><?= $i ?></td>
                                        <td><?= $row['school_yr'] ?></td>
                                        <td><?= $row['semester'] ?></td>
                                        <td><?= strtoupper($row['secProgram'] . ' ' . $row['secYearlvl'] . '-' . $row['secName']) ?></td>
                                        <td>
                                            <a href="#viewSchedule" class="view viewbtn text-primary" data-bs-toggle="modal" data-sy="<?= $row['school_yr'] ?>" data-semester="<?= $row['semester'] ?>" data-section="<?= $row['secID'] ?>"><i class="material-icons" data-bs-toggle="tooltip" title="View">&#xe8f4;</i></a>

                                            <a <?= $hidden ?> target="_blank" href="schedule_edit.php?sy=<?= $row['school_yr'] ?>&semester=<?= $row['semester'] ?>&section=<?= $row['secID'] ?>&schedId=<?= $row['id']; ?>" class="text-success "><i class="material-icons" data-bs-toggle="tooltip" title="Status">&#xe3c9;</i></a>

                                            <a <?= $casCondition ?> <?= $hidden ?> href="#statusSchedule" class="status deac" data-bs-toggle="modal" data-sy="<?= $row['school_yr'] ?>" data-semester="<?= $row['semester'] ?>" data-section="<?= $row['secID'] ?>"><i class="material-icons" data-bs-toggle="tooltip" title="Status">&#xe909;</i></a>

                                        </td>
                                    </tr>
                            <?php
                                }
                            } ?>

                            <?php
                            $sql = $db->getAllRowsFromTableWhereGroup2('tb_scheduled', $conditions, ' school_yr');
                            ?>
                            <?php
                            foreach ($sql as $row) {
                                $i++;
                            ?>
                                <tr>
                                    <td class="text-danger"><?= $i ?></td>
                                    <td class="text-danger"><?= $row['school_yr'] ?></td>
                                    <td class="text-danger"><?= $row['semester'] ?></td>
                                    <td class="text-danger"><?= strtoupper($row['secProgram'] . ' ' . $row['secYearlvl'] . '-' . $row['secName']) ?></td>
                                    <td class="text-danger">
                                        <a href="#viewSchedule" class="view viewbtn" data-bs-toggle="modal" data-sy="<?= $row['school_yr'] ?>" data-semester="<?= $row['semester'] ?>" data-section="<?= $row['secID'] ?>"><i class="material-icons" data-bs-toggle="tooltip" title="View">&#xe8f4;</i></a>
                                    </td>
                                </tr>
                            <?php
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
                                            <option value="Trimester">Trimester</option>
                                        </select>
                                    </div>
                                    <div class="col">
                                        <select class="form-control" required name="plotSection" id="plotSection">
                                            <option value="" disabled selected>Select Section</option>
                                            <?php
                                            if (mysqli_num_rows($result_section) > 0) {
                                                while ($row = mysqli_fetch_assoc($result_section)) {
                                                    if ($row['secProgram'] == $_SESSION['program']) {
                                            ?>
                                                        <option data-college="<?= $row['secCollege'] ?>" data-program="<?= $row['secProgram'] ?>" data-yearlevel="<?= $row['secYearlvl'] ?>" value="<?= $row['secID'] ?>">
                                                            <!-- DISPLAY -->
                                                            <?= $row['secProgram'] ?> <?= $row['secYearlvl'] ?> <?= $row['secName'] ?>
                                                            <!-- END DISPLAY -->
                                                        </option>
                                            <?php
                                                    }
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
                                            <label class="row-label"></label>
                                        </div>
                                        <div class="container-fluid-xl">

                                            <div class="row">
                                                <div class="col-4">
                                                    <select class="form-select" name="plotSubj[]" id="plotSubj">
                                                        <option value="" disabled selected>Select Subject</option>
                                                        <?php if (mysqli_num_rows($result_subject) > 0) : ?>
                                                            <?php while ($row = mysqli_fetch_assoc($result_subject)) : ?>
                                                                <?php if ($college == '"COLLEGE OF ARTS AND SCIENCES"' && $row['subProgram'] == $_SESSION['program'] && $row['subType'] == "minor") : ?>
                                                                    <option data-college="<?= $row['subCollege'] ?>" data-program="<?= $row['subProgram'] ?>" data-yearlevel="<?= $row['subYearlvl'] ?>" data-sem="<?= $row['subSem'] ?>" value="<?= $row['subID'] ?>">
                                                                        <?= $row['subCode'] ?> - <?= $row['subDesc'] ?>
                                                                    </option>
                                                                <?php elseif ($college != '"COLLEGE OF ARTS AND SCIENCES"' && $row['subProgram'] == $_SESSION['program'] && $row['subType'] == "major") : ?>
                                                                    <option data-college="<?= $row['subCollege'] ?>" data-program="<?= $row['subProgram'] ?>" data-yearlevel="<?= $row['subYearlvl'] ?>" data-sem="<?= $row['subSem'] ?>" value="<?= $row['subID'] ?>">
                                                                        <?= $row['subCode'] ?> - <?= $row['subDesc'] ?>
                                                                    </option>
                                                                <?php endif; ?>
                                                            <?php endwhile; ?>
                                                        <?php endif; ?>
                                                    </select>
                                                </div>

                                                <div class="col-4">
                                                    <select class="form-control" name="plotProf[]" id="plotProf">
                                                        <option value="" disabled selected>Select Professor</option>
                                                        <option value="TBA">TBA (To be Assigned)</option>
                                                        <?php
                                                        if (mysqli_num_rows($result_professor) > 0) {
                                                            while ($row = mysqli_fetch_assoc($result_professor)) {
                                                                if ($row['profCourse'] == $_SESSION['college']) { // Check if professor's college matches user's college
                                                        ?>
                                                                    <option data-college="<?php echo $row['profCourse']; ?>" data-program="<?php echo $row['profProgram']; ?>" value="<?php echo $row['profID']; ?>">
                                                                        <?php echo $row['profFname'] . ' ' . $row['profLname']; ?>
                                                                    </option>
                                                        <?php
                                                                }
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
                                                                if ($row['roomCollege'] == $_SESSION['college']) { // Check the rooms college matches user's college
                                                        ?>
                                                                    <option data-college="<?php echo $row['roomCollege']; ?>" value="<?= $row['roomID'] ?>">
                                                                        <?= $row['roomBuild'] ?> <?= $row['roomNum'] ?>
                                                                    </option>
                                                        <?php
                                                                }
                                                            }
                                                        }
                                                        ?>
                                                    </select>
                                                </div>
                                            </div>
                                            <!-- Days of the weeks -->
                                            <?php $day = array(
                                                1 => 'Monday',
                                                2 => 'Tuesday',
                                                3 => 'Wednesday',
                                                4 => 'Thursday',
                                                5 => 'Friday',
                                                6 => 'Saturday',
                                                7 => 'Sunday'
                                            );

                                            $time = array(
                                                '07:00',
                                                '08:00',
                                                '09:00',
                                                '10:00',
                                                '11:00',
                                                '12:00',
                                                '13:00',
                                                '14:00',
                                                '15:00',
                                                '16:00',
                                                '17:00',
                                                '18:00',
                                                '19:00',
                                            );
                                            ?>
                                            <div class="row">
                                                <?php foreach ($day as $key => $value) : ?>
                                                    <div class="col-sm-3">
                                                        <div class="card mt-3 mb-4">
                                                            <div class="card-header">
                                                                <h6 class="day-heading text-dark"><?php echo $value; ?></h6>
                                                            </div>
                                                            <div class="card-body">
                                                                <div class="row">
                                                                    <div class="col-6">
                                                                        <input type="hidden" value="<?= $key ?>" name="day[]" id="days">
                                                                        <label class="text-dark">Time Starts</label>
                                                                        <select class="form-select select2" name="start_time[]">
                                                                            <option value="" selected disabled>Select Start Time</option>
                                                                            <?php foreach ($time as $timedisplay) : ?>
                                                                                <option value="<?php echo $timedisplay; ?>">
                                                                                    <?= $timer = date("h:i:s A", strtotime($timedisplay)) ?>
                                                                                </option>
                                                                            <?php endforeach; ?>
                                                                        </select>
                                                                    </div>
                                                                    <div class="col-6">
                                                                        <label class="text-dark">Time Ends</label>
                                                                        <select class="form-select select2" name="end_time[]">
                                                                            <option value="" selected disabled>Select End Time</option>
                                                                            <?php foreach ($time as $timedisplay) : ?>
                                                                                <option value="<?php echo $timedisplay; ?>">
                                                                                    <?= $timer = date("h:i:s A", strtotime($timedisplay)) ?>
                                                                                </option>
                                                                            <?php endforeach; ?>
                                                                        </select>
                                                                    </div>
                                                                </div>
                                                            </div>
                                                        </div>
                                                    </div>
                                                <?php endforeach; ?>
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
                        <div class="table-responsive " id="myTable1">


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

    // Event handler to add a new schedule row
    $(document).on('click', '#addSchedule .add-btn', function() {
        var rowCountCurrent = $('.row-label').length;
        rowCount++;

        // Clone and initialize the new row
        var rowTemplate = $('#rowTemplate').clone();
        rowTemplate.removeAttr('id').removeAttr('style');
        rowTemplate.find('.row-label').text('Subject ' + rowCountCurrent + ':');
        rowTemplate.find('.remove-btn').prop('disabled', false); // Enable the remove button for the new row
        $('#formInputs').append(rowTemplate);
    });

    // Hide all subject options initially
    $('#addSchedule #plotSubj option').each(function() {
        $(this).hide();
    });

    // Retrieve the college information from the server-side script
    var college = <?php echo json_encode($college); ?>;

    // Event handler for when the section dropdown changes
    $(document).on('change', '#addSchedule #plotSection', function() {
        var selectedSem = $('#addSchedule #plotSem').val();
        $('#addSchedule #plotSubj').val('');
        $('#addSchedule #plotSubj option').each(function() {
            $(this).show();
        });

        var selectedCollege = $(this).find('option:selected').data('college');
        var selectedProgram = $(this).find('option:selected').data('program');
        var selectedYearLevel = $(this).find('option:selected').data('yearlevel');

        console.log('college:', selectedCollege);
        console.log('program:', selectedProgram);
        console.log('yearlevel:', selectedYearLevel);

        // Filter subjects based on the selected criteria
        $('#addSchedule #plotSubj option').each(function() {
            var eachCollege = $(this).attr('data-college');
            var eachProgram = $(this).attr('data-program');
            var eachYearLevel = $(this).attr('data-yearlevel');
            var eachSem = $(this).attr('data-sem');

            console.log('eachCollege:', eachCollege);
            console.log('eachProgram:', eachProgram);
            console.log('eachYearLevel:', eachYearLevel);
            console.log('eachSem:', eachSem);

            // Convert year level text to numeric value
            if (eachYearLevel === "first year") eachYearLevel = 1;
            else if (eachYearLevel === "second year") eachYearLevel = 2;
            else if (eachYearLevel === "third year") eachYearLevel = 3;
            else if (eachYearLevel === "fourth year") eachYearLevel = 4;

            // Filter subjects based on program, year level, and semester
            if (selectedProgram === eachProgram && selectedYearLevel == eachYearLevel && selectedCollege != "COLLEGE OF ARTS AND SCIENCES" && selectedSem == eachSem) {
                $(this).show();
            } else {
                $(this).hide();

                // Additional condition for College of Arts and Sciences
                if (selectedYearLevel == eachYearLevel && eachProgram && typeof eachProgram === 'string' && eachCollege.includes("COLLEGE OF ARTS AND SCIENCES") && selectedCollege === "COLLEGE OF ARTS AND SCIENCES" && selectedSem == eachSem) {
                    $(this).show();
                }
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