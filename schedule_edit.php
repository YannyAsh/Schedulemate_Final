<?php
include 'conn/conn.php';
include 'schedule_all_process.php';
include 'include/header.php';

$db = new DatabaseHandler();

$position = $_SESSION['postion'];
$program = $_SESSION['program'];
if ($position != "chairperson" && $position != "admin") {
    header('Location: index.php');
    exit;
}

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

$stmnt = "SELECT * FROM tb_subjects where status = 0 ";
$result_subject = mysqli_query($conn, $stmnt);

$stmnt = "SELECT * FROM tb_section where status = 0 ";
$result_section = mysqli_query($conn, $stmnt);

$stmnt = "SELECT * FROM tb_room where status = 0";
$result_room = mysqli_query($conn, $stmnt);

$stmnt = "SELECT * FROM tb_professor where status = 0 ";
$result_professor = mysqli_query($conn, $stmnt);
?>
<style>
    .noclick {
        pointer-events: none;
        background-color: lightgray !important;
    }
</style>
<!-- Start of the contents -->
<div class="container-fluid px-4">
    <div class="row g-3 my-2">
        <div class="container">
            <div class="table-wrapper">
                <div class="table-title">
                    <div class="row">
                        <div class="col-sm-7">
                            <h2>Manage Schedule Edit</h2>
                        </div>
                    </div>
                    <div class="table-responsive">
                        <h3><?php echo $_GET['section']; ?></h3>
                        <a href="#insertSchedule" class="btn btn-success" data-bs-toggle="modal"><i class="material-icons">&#xE147;</i><span>Insert New Subject</span></a>
                        <table class="table table-hover">
                            <thead>
                                <th>Subject</th>
                                <th>Room</th>
                                <th>Time</th>
                                <th>Day</th>
                                <th>Edit</th>
                            </thead>
                            <tbody>
                                <?php
                                $sql = $db->getMajorToDisplay2($_GET['sy'], $_GET['semester'], $_GET['section'], $_GET['schedId']);
                                ?>
                                <?php
                                $programType = '';
                                foreach ($sql as $row) :
                                ?>
                                    <tr>
                                        <td><?= $row['subCode'] . '-' . $row['subDesc'] ?></td>
                                        <td><?= $row['roomBuild'] . '-' . $row['roomNum'] ?></td>

                                        <?php
                                        $start_time = date('h:i A', strtotime($row['start_time']));
                                        $end_time = date('h:i A', strtotime($row['end_time']));
                                        $timeDetails = $start_time . ' - ' . $end_time;
                                        $day = array(
                                            1 => 'Monday',
                                            2 => 'Tuesday',
                                            3 => 'Wednesday',
                                            4 => 'Thursday',
                                            5 => 'Friday',
                                            6 => 'Saturday',
                                            7 => 'Sunday'
                                        );
                                        // Get the specific day
                                        if (array_key_exists($row['day'], $day)) {
                                            $dayDetails = $day[$row['day']];
                                        }
                                        ?>
                                        <td><?= $timeDetails ?></td>
                                        <td><?= $dayDetails ?></td>
                                        <td>
                                            <?php
                                            if ($_SESSION['college'] == 'cas' && $row['subType'] != 'major') {

                                            ?>
                                                <a href="" onclick="openModal('<?= $row['day'] ?>', '<?= $row['start_time'] ?>', '<?= $row['end_time'] ?>', '<?= $_GET['schedId'] ?>', '<?= $_GET['section'] ?>')" name="editSubject" class="edit" data-bs-toggle="modal"><i class="material-icons" data-bs-toggle="tooltip" title="Edit">&#xe254;</i></a>
                                            <?php
                                            } else if ($_SESSION['college'] != 'cas' && $row['subType'] == 'major') {
                                            ?>
                                                <a href="" onclick="openModal('<?= $row['day'] ?>', '<?= $row['start_time'] ?>', '<?= $row['end_time'] ?>', '<?= $_GET['schedId'] ?>', '<?= $_GET['section'] ?>')" name="editSubject" class="edit" data-bs-toggle="modal"><i class="material-icons" data-bs-toggle="tooltip" title="Edit">&#xe254;</i></a>
                                            <?php
                                            }
                                            ?>
                                        </td>
                                    </tr>
                                <?php endforeach; ?>
                            </tbody>
                        </table>

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
</body>

<!-- MODAL TO INSERT NEW SUBJECT INTO EXISTING SCHEDULE -->
<div id="insertSchedule" class="modal fade" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg" role="document">
        <div class="modal-content">
            <form method="POST" action="php/action_page.php">
                <div class="modal-header">
                    <h5 class="modal-title"><?php echo $_GET['sy'] . " " . $_GET['semester'] . " " . $_GET['section']; ?></h5>
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
                                                <option value="" disabled></option>
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
                                                                <option data-program="<?= $row['subDept'] ?>" data-yearlevel="<?= $row['subYearlvl'] ?>" data-sem="<?= $row['subSem'] ?>" value="<?= $row['subCode'] ?> ">
                                                                    <!-- DISPLAY -->
                                                                    <?= $row['subCode'] ?> - <?= $row['subDesc'] ?>
                                                                    <!-- END DISPLAY -->
                                                                </option>
                                                            <?php
                                                            }
                                                        } else if ($row['subType'] == "major" && $userSection == $subCourses) {
                                                            ?>
                                                            <option data-program="<?= $row['subDept'] ?>" data-yearlevel="<?= $row['subYearlvl'] ?>" data-sem="<?= $row['subSem'] ?>" value="<?= $row['subCode'] ?> ">
                                                                <!-- DISPLAY -->
                                                                <?= $row['subCode'] ?> - <?= $row['subDesc'] ?>
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

                                    <?php $day = array(
                                        1 => 'Monday',
                                        2 => 'Tuesday',
                                        3 => 'Wednesday',
                                        4 => 'Thursday',
                                        5 => 'Friday',
                                        6 => 'Saturday',
                                        7 => 'Sunday'
                                    );
                                    ?>
                                    <div class="row">
                                        <?php foreach ($day as $key => $value) : ?>
                                            <div class="col-sm-3">
                                                <h6 class="day-heading text-dark"><?php echo $value; ?></h6>
                                                <input type="hidden" value="<?= $key ?>" name="day[]">
                                                <label class="text-dark">Time Starts</label>
                                                <input type="time" min="07:00" max="19:00" name="start_time[]" class="form-control">
                                                <label class="text-dark">Time Ends</label>
                                                <input type="time" min="07:00" max="19:00" name="end_time[]" class="form-control">
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

<!-- EDIT SUBJECT -->
<div id="editSubject" class="modal fade" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg" role="document">
        <div class="modal-content">
            <form method="POST" action="php/action_page.php">
                <div class="modal-header">
                    <h5 class="modal-title">Edit Subject</h5>
                    <button type="button" class="btn btn-primary close-2" data-bs-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body editListinputs">
                    <div class="form-group">
                        <div class="row">
                            <div class="col">
                                <label class="text-dark" for="schoolyear">School Year: </label>
                                <label class="form-control" id="plotYear"><?php echo $_GET['sy']; ?></label>
                            </div>
                            <div class="col">
                                <label class="text-dark" for="semester">Semester: </label>
                                <label class="form-control" id="plotSem"><?php echo $_GET['semester']; ?></label>
                            </div>
                            <div class="col">
                                <label class="text-dark" for="section">Section: </label>
                                <?php $section = "SELECT * FROM tb_scheduled as scheduled LEFT JOIN tb_section as section ON scheduled.section_id = section.secID WHERE scheduled.status = 1 AND scheduled.id = " . $_GET['schedId'] . " AND scheduled.section_id = " . $_GET['section'] . " ";?>
                                <?php $result_section = mysqli_query($conn, $section); ?>
                                    <?php while ($row = mysqli_fetch_assoc($result_section)) : ?>
                                        <label class="form-control" id="plotSec"><?php echo $row['secProgram']. ' ' . $row['secYearlvl']. '-' .$row['secName']; ?></label>
                                    <?php endwhile;?>
                            </div>
                            <input type="hidden" name="sec_id" id="sec_id" value="" />
                            <input type="hidden" name="schedID" id="schedID" value="" />
                        </div>
                        <div class="row">
                            <div class="col ">
                                <label class="text-dark" for="subject">Select Subject: </label>
                                <select class="form-control" name="plotSubj2[]" id="plotSubj2">
                                    <option value="" disabled selected>Select Subject</option>
                                    <?php
                                    $stmnt = "SELECT * FROM tb_scheduled as scheduled LEFT JOIN tb_subjects as subjects ON scheduled.subject_id = subjects.subID WHERE scheduled.status = 1 AND scheduled.id = " . $_GET['schedId'] . " ";
                                    $result_subject = mysqli_query($conn, $stmnt);
                                    ?>
                                    <?php if (mysqli_num_rows($result_subject) > 0) : ?>
                                        <?php while ($row = mysqli_fetch_assoc($result_subject)) : ?>
                                            <option data-program="<?= $row['SubCourse'] ?>" data-yearlevel="<?= $row['subYearlvl'] ?>" data-sem="<?= $row['subSem'] ?>" value="<?= $row['subID'] ?> " value="<?php $row['subject_id'] ?>" selected>
                                                <!-- DISPLAY -->
                                                <?= $row['subCode'] ?> - <?= $row['subDesc'] ?>
                                                <!-- END DISPLAY -->
                                            </option>
                                        <?php endwhile; ?>
                                    <?php endif; ?>
                                    <?php
                                    $stmnt = "SELECT * FROM tb_subjects where status = 0 ";
                                    $result_subject = mysqli_query($conn, $stmnt);
                                    ?>
                                    <?php if (mysqli_num_rows($result_subject) > 0) : ?>
                                        <?php while ($row = mysqli_fetch_assoc($result_subject)) : ?>
                                            <?php if ($row['subType'] == "minor") : ?>
                                                <option data-program="<?= $row['subDept'] ?>" data-yearlevel="<?= $row['subYearlvl'] ?>" data-sem="<?= $row['subSem'] ?>" value="<?= $row['subID'] ?> ">
                                                    <!-- DISPLAY -->
                                                    <?= $row['subCode'] ?> - <?= $row['subDesc'] ?>
                                                    <!-- END DISPLAY -->
                                                </option>
                                            <?php endif; ?>
                                            <?php if ($row['subType'] == "major") : ?>
                                                <option data-program="<?= $row['subDept'] ?>" data-yearlevel="<?= $row['subYearlvl'] ?>" data-sem="<?= $row['subSem'] ?>" value="<?= $row['subID'] ?> ">
                                                    <!-- DISPLAY -->
                                                    <?= $row['subCode'] ?> - <?= $row['subDesc'] ?>
                                                    <!-- END DISPLAY -->
                                                <?php endif; ?>
                                            <?php endwhile; ?>
                                        <?php endif; ?>
                                </select>
                            </div>
                            <div class="col">
                                <label class="text-dark" for="Room">Select Professor: </label>
                                <select class="form-control" name="prof_id" id="plotProf">
                                    <option>Select Professor</option>
                                    <?php
                                    $stmnt = "SELECT * FROM tb_scheduled as scheduled LEFT JOIN tb_professor as prof ON scheduled.prof_id = prof.profID where scheduled.status = 1 AND scheduled.id = " . $_GET['schedId'] . " ";
                                    $result_professor = mysqli_query($conn, $stmnt);
                                    ?>
                                    <?php if (mysqli_num_rows($result_professor) > 0) : ?>
                                        <?php while ($row = mysqli_fetch_assoc($result_professor)) : ?>
                                            <option value="<?= $row['prof_id'] ?>" selected><?= $row['profFname'] ?> <?= $row['profLname'] ?></option>
                                        <?php endwhile; ?>
                                    <?php endif; ?>
                                    <?php
                                    $stmnt = "SELECT * FROM tb_professor where status = 0";
                                    $result_professor2 = mysqli_query($conn, $stmnt);
                                    ?>
                                    <?php if (mysqli_num_rows($result_professor2) > 0) : ?>
                                        <?php while ($row = mysqli_fetch_assoc($result_professor2)) : ?>
                                            <option value="<?= $row['profID'] ?>"><?= $row['profFname'] ?> <?= $row['profLname'] ?></option>
                                        <?php endwhile; ?>
                                    <?php endif; ?>
                                    <option value="TBA">TBA ( To be Announce )</option>
                                </select>
                            </div>
                            <div class="col">
                                <label class="text-dark" for="Room">Select Room: </label>
                                <select class="form-control" name="plotRoom2[]" id="plotRoom2">
                                    <option value="" disabled selected>Select Room</option>
                                    <?php
                                    $stmnt = "SELECT * FROM tb_scheduled as scheduled LEFT JOIN tb_room as rooms ON scheduled.room_id = rooms.roomID where scheduled.status = 1 AND scheduled.id = " . $_GET['schedId'] . " ";
                                    $result_room = mysqli_query($conn, $stmnt);
                                    ?>
                                    <?php if (mysqli_num_rows($result_room) > 0) : ?>
                                        <?php while ($row = mysqli_fetch_assoc($result_room)) : ?>
                                            <option value="<?= $row['roomID'] ?>" selected><?= $row['roomBuild'] ?> <?= $row['roomNum'] ?></option>
                                        <?php endwhile; ?>
                                    <?php endif; ?>
                                    <?php
                                    $stmnt = "SELECT roomID, roomBuild, roomNum  FROM tb_room where status = 0";
                                    $result_room = mysqli_query($conn, $stmnt);
                                    ?>
                                    <?php if (mysqli_num_rows($result_room) > 0) : ?>
                                        <?php while ($row = mysqli_fetch_assoc($result_room)) : ?>
                                            <option value="<?= $row['roomID'] ?>"><?= $row['roomBuild'] ?> <?= $row['roomNum'] ?></option>
                                        <?php endwhile; ?>
                                    <?php endif; ?>
                                </select>
                            </div>
                        </div>
                        <?php
                        $stmnt = "SELECT * FROM tb_scheduled as scheduled LEFT JOIN tb_day_time as day_time ON scheduled.id = day_time.sched_id where scheduled.status = 1 AND scheduled.id = " . $_GET['schedId'] . " ";
                        $result_sched = mysqli_query($conn, $stmnt);
                        ?>
                        <?php if (mysqli_num_rows($result_sched) > 0) : ?>
                            <?php while ($row = mysqli_fetch_assoc($result_sched)) : ?>
                                <?php $day = array(
                                    1 => 'Monday',
                                    2 => 'Tuesday',
                                    3 => 'Wednesday',
                                    4 => 'Thursday',
                                    5 => 'Friday',
                                    6 => 'Saturday',
                                    7 => 'Sunday'
                                );
                                ?>
                                <?php
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

                                $time30 = array(
                                    '07:00',
                                    '07:30',
                                    '08:00',
                                    '08:30',
                                    '09:00',
                                    '09:30',
                                    '10:00',
                                    '10:30',
                                    '11:00',
                                    '11:30',
                                    '12:00',
                                    '12:30',
                                    '13:00',
                                    '13:30',
                                    '14:00',
                                    '14:30',
                                    '15:00',
                                    '15:30',
                                    '16:00',
                                    '16:30',
                                    '17:00',
                                    '17:30',
                                    '18:00',
                                    '18:30',
                                    '19:00',
                                );
                                ?>
                                <?php
                                if (array_key_exists($row['day'], $day)) {
                                    $dayDetails = $day[$row['day']];
                                }
                                $start_time = new DateTime($row['start_time']);
                                $end_time = new DateTime($row['end_time']);
                                ?>
                                <div class="row">
                                    <?php foreach ($day as $key => $value) : ?>
                                        <div class="col-sm-4">
                                            <div class="card mt-3 mb-4 shadow">
                                                <div class="card-header">
                                                    <h6 class="day-heading text-start text-dark fw-semibold"><?php echo $value; ?></h6>
                                                </div>
                                                <div class="card-body">
                                                    <?php if ($dayDetails == $value) : ?>
                                                        <div class="row">
                                                            <div class="col-6">
                                                                <input type="hidden" value="<?= $row['day']; ?>" name="day[]" id="days">
                                                                <label class="text-dark">Time Starts</label>
                                                                <select class="form-select select2" name="start_time[]">
                                                                    <?php if (!empty($row['start_time'])) : ?>
                                                                        <option selected value="<?php echo $row['start_time']; ?>">
                                                                            <?= $date = date("h:i:s A", strtotime($row['start_time'])); ?>
                                                                        </option>
                                                                        <?php foreach ($time as $timedisplay) : ?>
                                                                            <?php if (date("h:i:s A", strtotime($timedisplay)) != date("h:i:s A", strtotime($row['start_time']))) : ?>
                                                                                <option value="<?php echo $timedisplay; ?>">
                                                                                    <?= $timer = date("h:i:s A", strtotime($timedisplay)) ?>
                                                                                </option>
                                                                            <?php endif; ?>
                                                                        <?php endforeach; ?>
                                                                    <?php else : ?>
                                                                        <?php foreach ($time as $timedisplay) : ?>
                                                                            <option value="<?php echo $row['start_time']; ?>">
                                                                                <?= $timer = date("h:i:s A", strtotime($timedisplay)) ?>
                                                                            </option>
                                                                        <?php endforeach; ?>
                                                                    <?php endif; ?>
                                                                </select>
                                                            </div>
                                                            <div class="col-6">
                                                                <label class="text-dark">Time Ends</label>
                                                                <select class="form-select select2" name="end_time[]">
                                                                    <?php if (!empty($row['end_time'])) : ?>
                                                                        <option selected value="<?php echo $row['end_time']; ?>">
                                                                            <?= $date = date("h:i:s A", strtotime($row['end_time'])); ?>
                                                                        </option>
                                                                        <?php foreach ($time as $timedisplay) : ?>
                                                                            <?php if (date("h:i:s A", strtotime($timedisplay)) != date("h:i:s A", strtotime($row['end_time']))) : ?>
                                                                                <option value="<?php echo $timedisplay; ?>">
                                                                                    <?= $timer = date("h:i:s A", strtotime($timedisplay)) ?>
                                                                                </option>
                                                                            <?php endif; ?>
                                                                        <?php endforeach; ?>
                                                                    <?php else : ?>
                                                                        <?php foreach ($time as $timedisplay) : ?>
                                                                            <option value="<?php echo $row['end_time']; ?>">
                                                                                <?= $timer = date("h:i:s A", strtotime($timedisplay)) ?>
                                                                            </option>
                                                                        <?php endforeach; ?>
                                                                    <?php endif; ?>
                                                                </select>
                                                            </div>
                                                        </div>
                                                    <?php else : ?>
                                                        <div class="row">
                                                            <div class="col-6">
                                                                <input type="hidden" value="<?= $key; ?>" name="day[]" id="days">
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
                                                    <?php endif; ?>
                                                </div>
                                            </div>
                                        </div>
                                    <?php endforeach; ?>
                                </div>
                            <?php endwhile; ?>
                        <?php endif; ?>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn" data-bs-dismiss="modal" value="Cancel">Cancel</button>
                        <button type="submit" name="sched_edit_new" class="btn" value="update">Update</button>
                    </div>
            </form>
        </div>
    </div>
</div>


</html>
<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.0.0-beta3/dist/js/bootstrap.bundle.min.js"></script>
<script src="JS\select2.min.js"></script>
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
<script>
    $(document).ready(function() {
        var rowToRemove; // Store the row to be removed
        var rowCount = 1; // Initialize row count

        // Initialize the modal with one form row
        var rowTemplate = $('#rowTemplate').clone();
        rowTemplate.removeAttr('id').removeAttr('style');
        rowTemplate.find('.row-label').text('Subject ' + rowCount + ':');
        rowCount++;

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
    $(document).on('click', '#insertSchedule .add-btn', function() {
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
    $('#insertSchedule #plotSubj option').each(function() {
        $(this).hide();
    })

    var programType = '<?php echo ($programType); ?>';

    $(document).on('change', '#insertSchedule #plotSection', function() {
        var selectedSem = ($('#insertSchedule #plotSem').val())
        console.log(selectedSem)
        $('#insertSchedule #plotSubj').val('')
        $('#insertSchedule #plotSubj option').each(function() {
            $(this).show();
        })
        var program = $(this).find('option:selected').data('program');
        var yearlevel = $(this).find('option:selected').data('yearlevel');
        console.log(program);


        $('#insertSchedule #plotSubj option').each(function() {

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
    $(document).on('click', '#insertSchedule .timeInput', function() {
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

<!-- display edit modal -->
<script>
    $(document).ready(function() {

        $('.edit').on('click', function() {

            $('#editSubject').modal('show');

            $tr = $(this).closest('tr');
            id = $(this).data('id')
            var data = $tr.find("td").map(function() {
                return $(this).text();
            }).get();

            data.forEach(function(results) {
                $('#roomID').val(results[0]);
                $('#roomBuild').val(results[1]);
                $('#roomFloornum').val(results[2]);
                $('#roomNum').val(results[3]);
                $('#roomStatus').val('Active');
            });
        });

    });
    // to display the time
    function openModal(day, stime, etime, schedID, section_id) {
        var days = $(".day").val();
        $(".editListinputs").find('input').each(function() {
            $(this).val('');
        });
        console.log(day);
        console.log(section_id);
        console.log(stime);
        console.log(etime);
        console.log(schedID);
        $("#sec_id").val(section_id);
        $("#schedID").val(schedID);
        $("#start_time").text(stime);
        $("#end_time").text(etime);

    }
</script>