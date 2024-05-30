<?php
include 'conn/conn.php';
include 'prof_all_process.php';
include 'include/header.php';
$db = new DatabaseHandler();
if (isset($_GET['prof_edit'])) {
    $profID = $_GET['prof_edit'];
    $prof_edit_state = true;
    $record = mysqli_query($conn, "SELECT * FROM tb_professor WHERE profID=$profID");
    $data = mysqli_fetch_array($record);
    $profEmployID = $data['profEmployID'];
    $profFname  = $data['profFname'];
    $profMname  = $data['profMname'];
    $profLname = $data['profLname'];
    $profMobile = $data["profMobile"];
    $profAddress = $data["profAddress"];
    $profEduc = $data["profEduc"];
    $profExpert = $data["profExpert"];
    $profRank = $data["profRank"];
    $profHrs = $data["profHrs"];
    $profEmployStatus = $data["profEmployStatus"];
    $profStatus = $data["profStatus"];
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
                            <h2>Manage Professor Entries</h2>
                        </div>
                        <div class="col">
                            <a href="#addProf" class="btn btn-success" data-bs-toggle="modal"><i
                                    class="material-icons">&#xE147;</i><span>Add New Professor</span></a>
                        </div>
                    </div>
                </div>
                <!-- makes the table responsive -->
                <div class="table-responsive">
                    <table id="table" class="table table-hover">
                        <thead>
                            <tr>
                                <th>No.</th>
                                <th>Employee ID</th>
                                <th>First Name</th>
                                <th>Middle Name</th>
                                <th>Last Name</th>
                                <th>Rank</th>
                                <th>Employment Status</th>
                                <th>Actions</th>
                            </tr>
                        </thead>
                        <tbody id="myTable">
                            <?php
                                        $i=1;
                                        $sql1 = $db->getAllRowsFromTable('tb_professor');
                                        foreach ($sql1 as $row) {
                                        ?>
                            <tr>
                                <td><?php echo $i; ?></td>
                                <td><?php echo $row["profEmployID"] ?></td>
                                <td><?php echo $row["profFname"] ?></td>
                                <td><?php echo $row["profMname"] ?></td>
                                <td><?php echo $row["profLname"] ?></td>
                                <td><?php echo $row["profRank"] ?></td>
                                <td><?php echo $row["profEmployStatus"] ?></td>

                                <td>
                                    <a href="#viewProf" class="view text-primary" data-bs-toggle="modal"
                                        data-id="<?=$row['profEmployID']?>" data-fname="<?=$row['profFname']?>"
                                        data-mname="<?=$row['profMname']?>" data-lname="<?=$row['profLname']?>"
                                        data-mobile="<?=$row['profMobile']?>" data-address="<?=$row['profAddress']?>"
                                        data-educational="<?=$row['profEduc']?>"
                                        data-expertise="<?=$row['profExpert']?>" data-rank="<?=$row['profRank']?>"
                                        data-max="<?=$row['profMax']?>"
                                        data-employmentstatus="<?=$row['profEmployStatus']?>"><i class="material-icons"
                                            data-bs-toggle="tooltip" title="View"
                                            data-id='<?php echo $row['profID']; ?>'>&#xe8f4;</i></a>

                                    <a href="#statusProf" class="status" data-bs-toggle="modal"
                                        data-profid="<?php echo $row['profID']; ?>"><i class="material-icons"
                                            data-bs-toggle="tooltip" title="Status">&#xe909;</i></a>
                                </td>
                            </tr>
                            <?php
                                            $i++;
                                        }
                                        ?>

                            <?php
                                        $condition = 'status = 1';
                                        $sql2 =  $db->getAllRowsFromTableWhere2('tb_professor',$condition);
                                        foreach ($sql2 as $row) {
                                        ?>
                            <tr>
                                <td class="text-danger"><?php echo $i; ?></td>
                                <td class="text-danger"><?php echo $row["profEmployID"] ?></td>
                                <td class="text-danger"><?php echo $row["profFname"] ?></td>
                                <td class="text-danger"><?php echo $row["profMname"] ?></td>
                                <td class="text-danger"><?php echo $row["profLname"] ?></td>
                                <td class="text-danger"><?php echo $row["profRank"] ?></td>
                                <td class="text-danger"><?php echo $row["profEmployStatus"] ?></td>

                                <td>
                                    <a href="#viewProf" class="view text-primary" data-bs-toggle="modal"
                                        data-id="<?=$row['profEmployID']?>" data-fname="<?=$row['profFname']?>"
                                        data-mname="<?=$row['profMname']?>" data-lname="<?=$row['profLname']?>"
                                        data-mobile="<?=$row['profMobile']?>" data-address="<?=$row['profAddress']?>"
                                        data-educational="<?=$row['profEduc']?>"
                                        data-expertise="<?=$row['profExpert']?>" data-rank="<?=$row['profRank']?>"
                                        data-max="<?=$row['profMax']?>"
                                        data-employmentstatus="<?=$row['profEmployStatus']?>"><i class="material-icons"
                                            data-bs-toggle="tooltip" title="View"
                                            data-id='<?php echo $row['profID']; ?>'>&#xe8f4;</i></a>

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
        <div id="addProf" class="modal fade">
            <div class="modal-dialog modal-lg">
                <div class="modal-content">
                    <form method="POST" action="php/action_page.php">
                        <input type="hidden" name="profID">
                        <div class="modal-header">
                            <h5 class="modal-title">Add New Professor</h5>
                            <button type="button" class="close" data-bs-dismiss="modal"
                                aria-hidden="true">&times;</button>
                        </div>
                        <div class="modal-body">
                            <div class="form-group">
                                <div class="row">
                                    <div class="col">
                                        <label>Employee ID</label>
                                        <input type="text" name="profEmployID" class="form-control"
                                            value="<?php echo $profEmployID; ?>">
                                    </div>
                                    <div class="col">
                                        <label>First Name</label>
                                        <input type="text" name="profFname" class="form-control"
                                            value="<?php echo $profFname; ?>">
                                    </div>
                                    <div class=" col">
                                        <div class="form-group">
                                            <label>Middle Name</label>
                                            <input type="text" name="profMname" class="form-control"
                                                value="<?php echo $profMname; ?>">
                                        </div>
                                    </div>
                                    <div class="col">
                                        <div class="form-group">
                                            <label>Last Name</label>
                                            <input type="text" name="profLname" class="form-control"
                                                value="<?php echo $profLname; ?>">
                                        </div>
                                    </div>
                                </div>

                                <div class="row">
                                    <div class="col">
                                        <div class="form-group">
                                            <label>Mobile No.</label>
                                            <input type="number" name="profMobile" class="form-control"
                                                value="<?php echo $profMobile; ?>">
                                        </div>
                                    </div>
                                    <div class="col">
                                        <div class="form-group">
                                            <label>Address</label>
                                            <input type="text" name="profAddress" class="form-control"
                                                value="<?php echo $profAddress; ?>">
                                        </div>
                                    </div>
                                </div>

                                <div class="row">
                                    <div class="col">
                                        <div class="form-group">
                                            <label>Educational Attainment</label>
                                            <textarea class="form-control" name="profEduc"
                                                rows="4"><?php echo $profEduc; ?></textarea>
                                        </div>
                                    </div>
                                    <div class="col">
                                        <div class="form-group">
                                            <label>Expertise/Specialization</label>
                                            <textarea class="form-control" name="profExpert"
                                                rows="4"><?php echo $profExpert; ?></textarea>
                                        </div>
                                    </div>
                                </div>

                                <div class="row">
                                    <div class="col">
                                        <div class="form-group">
                                            <label>Professor's Rank</label>
                                            <select class="form-control" required name="profRank" id="profRank">
                                                <option value="" disabled>Select Professors Rank</option>
                                                <option value="Instructor 1"
                                                    <?= ($profRank == "Instructor 1") ? "selected" : ""; ?>>
                                                    Instructor 1</option>
                                                <option value="Instructor 2"
                                                    <?= ($profRank == "Instructor 2") ? "selected" : ""; ?>>
                                                    Instructor 2</option>
                                                <option value="Instructor 3"
                                                    <?= ($profRank == "Instructor 3") ? "selected" : ""; ?>>
                                                    Instructor 3</option>
                                                <option value="Instructor 4"
                                                    <?= ($profRank == "Instructor 4") ? "selected" : ""; ?>>
                                                    Instructor 4</option>
                                                <option value="Instructor 5"
                                                    <?= ($profRank == "Instructor 5") ? "selected" : ""; ?>>
                                                    Instructor 5</option>
                                                <option value="Instructor 6"
                                                    <?= ($profRank == "Instructor 6") ? "selected" : ""; ?>>
                                                    Instructor 6</option>
                                                <option value="Instructor 7"
                                                    <?= ($profRank == "Instructor 7") ? "selected" : ""; ?>>
                                                    Instructor 7</option>
                                                <option value="Assistant Prof. 1"
                                                    <?= ($profRank == "Assistant Prof. 1") ? "selected" : ""; ?>>
                                                    Assistant Prof. 1</option>
                                                <option value="Assistant Prof. 2"
                                                    <?= ($profRank == "Assistant Prof. 2") ? "selected" : ""; ?>>
                                                    Assistant Prof. 2</option>
                                                <option value="Assistant Prof. 3"
                                                    <?= ($profRank == "Assistant Prof. 3") ? "selected" : ""; ?>>
                                                    Assistant Prof. 3</option>
                                                <option value="Assistant Prof. 4"
                                                    <?= ($profRank == "Assistant Prof. 4") ? "selected" : ""; ?>>
                                                    Assistant Prof. 4</option>
                                                <option value="Assistant Prof. 5"
                                                    <?= ($profRank == "Assistant Prof. 5") ? "selected" : ""; ?>>
                                                    Assistant Prof. 5</option>
                                                <option value="Assistant Prof. 6"
                                                    <?= ($profRank == "Assistant Prof. 6") ? "selected" : ""; ?>>
                                                    Assistant Prof. 6</option>
                                                <option value="Assistant Prof. 7"
                                                    <?= ($profRank == "Assistant Prof. 7") ? "selected" : ""; ?>>
                                                    Assistant Prof. 7</option>
                                                <option value="Associate Prof. 1"
                                                    <?= ($profRank == "Associate Prof. 1") ? "selected" : ""; ?>>
                                                    Associate Prof. 1</option>
                                                <option value="Associate Prof. 2"
                                                    <?= ($profRank == "Associate Prof. 2") ? "selected" : ""; ?>>
                                                    Associate Prof. 2</option>
                                                <option value="Associate Prof. 3"
                                                    <?= ($profRank == "Associate Prof. 3") ? "selected" : ""; ?>>
                                                    Associate Prof. 3</option>
                                                <option value="Associate Prof. 4"
                                                    <?= ($profRank == "Associate Prof. 4") ? "selected" : ""; ?>>
                                                    Associate Prof. 4</option>
                                                <option value="Associate Prof. 5"
                                                    <?= ($profRank == "Associate Prof. 5") ? "selected" : ""; ?>>
                                                    Associate Prof. 5</option>
                                                <option value="Associate Prof. 6"
                                                    <?= ($profRank == "Associate Prof. 6") ? "selected" : ""; ?>>
                                                    Associate Prof. 6</option>
                                                <option value="Associate Prof. 7"
                                                    <?= ($profRank == "Associate Prof. 7") ? "selected" : ""; ?>>
                                                    Associate Prof. 7</option>
                                                <option value="Professor 1"
                                                    <?= ($profRank == "Professor 1") ? "selected" : ""; ?>>
                                                    Professor 1</option>
                                                <option value="Professor 2"
                                                    <?= ($profRank == "Professor 2") ? "selected" : ""; ?>>
                                                    Professor 2</option>
                                                <option value="Professor 3"
                                                    <?= ($profRank == "Professor 3") ? "selected" : ""; ?>>
                                                    Professor 3</option>
                                                <option value="Professor 4"
                                                    <?= ($profRank == "Professor 4") ? "selected" : ""; ?>>
                                                    Professor 4</option>
                                                <option value="Professor 5"
                                                    <?= ($profRank == "Professor 5") ? "selected" : ""; ?>>
                                                    Professor 5</option>
                                                <option value="Professor 6"
                                                    <?= ($profRank == "Professor 6") ? "selected" : ""; ?>>
                                                    Professor 6</option>
                                                <option value="Professor 7"
                                                    <?= ($profRank == "Professor 7") ? "selected" : ""; ?>>
                                                    Professor 7</option>
                                                <option value="Professor 8"
                                                    <?= ($profRank == "Professor 8") ? "selected" : ""; ?>>
                                                    Professor 8</option>
                                                <option value="Professor 9"
                                                    <?= ($profRank == "Professor 9") ? "selected" : ""; ?>>
                                                    Professor 9</option>
                                                <option value="Professor 10"
                                                    <?= ($profRank == "Professor 10") ? "selected" : ""; ?>>
                                                    Professor 10</option>
                                                <option value="Professor 11"
                                                    <?= ($profRank == "Professor 11") ? "selected" : ""; ?>>
                                                    Professor 11</option>
                                                <option value="Professor 12"
                                                    <?= ($profRank == "Professor 12") ? "selected" : ""; ?>>
                                                    Professor 12</option>
                                                <option value="Univ. Professor"
                                                    <?= ($profRank == "Univ. Professor") ? "selected" : ""; ?>>
                                                    Univ. Professor</option>
                                            </select>
                                        </div>
                                    </div>
                                    <div class="col">
                                        <div class="form-group">
                                            <label>Maximum Units</label>
                                            <input type="number" name="profHrs" class="form-control"
                                                value="<?php echo $profHrs; ?>">
                                        </div>
                                    </div>

                                    <div class="col">
                                        <div class="form-group">
                                            <label>Employment Status</label>
                                            <select class="form-control" name="profEmployStatus">
                                                <option value="" disabled>Select Professor'sEmployment Status</option>
                                                <option id="profPart" value="Part-Time"
                                                    <?= ($profEmployStatus == "Part-Time") ? "selected" : ""; ?>>
                                                    Part-Time</option>
                                                <option id="profFull" value="Full-Time"
                                                    <?= ($profEmployStatus == "Full-Time") ? "selected" : ""; ?>>
                                                    Full-Time</option>
                                            </select>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="modal-footer">
                            <input type="button" class="btn" data-bs-dismiss="modal" value="Cancel">
                            <input type="submit" name="prof_add_new" class="btn" value="Add">
                        </div>
                    </form>
                </div>
            </div>
        </div>

        <!-- View Modal HTML -->
        <div id="viewProf" class="modal fade">
            <div class=" modal-dialog modal-xl">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title">View Professor Details</h5>
                        <button type="button" class="close" data-bs-dismiss="modal" aria-hidden="true">&times;</button>
                    </div>
                    <div class="modal-body">
                        <div class="table-responsive">
                            <table class="table table-hover">
                                <tbody>
                                    <tr>
                                        <th>Employee ID</th>
                                        <td id="data_1"></td>
                                    </tr>
                                    <tr>
                                        <th>First Name</th>
                                        <td id="data_2"></td>
                                    </tr>
                                    <tr>
                                        <th>Middle Name</th>
                                        <td id="data_3"></td>
                                    </tr>
                                    <tr>
                                        <th>Last Name</th>
                                        <td id="data_4"></td>
                                    </tr>
                                    <tr>
                                        <th>Mobile No.</th>
                                        <td id="data_5"></td>
                                    </tr>
                                    <tr>
                                        <th>Address</th>
                                        <td id="data_6"></td>
                                    </tr>
                                    <tr>
                                        <th>Educational Attaintment</th>
                                        <td id="data_7"></td>
                                    </tr>
                                    <tr>
                                        <th>Expertise/Specialization</th>
                                        <td id="data_8"></td>
                                    </tr>
                                    <tr>
                                        <th>Rank</th>
                                        <td id="data_9"></td>
                                    </tr>
                                    <tr>
                                        <th>Maximum Units</th>
                                        <td id="data_10"></td>
                                    </tr>
                                    <tr>
                                        <th>Employment Status</th>
                                        <td id="data_11"></td>
                                    </tr>
                                    </tr>
                                </tbody>
                            </table>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <input type="button" class="btn" data-bs-dismiss="modal" value="Close">
                        <a class="btn btn-default" data-bs-toggle="modal" data-bs-dismiss="modal" href="#editProf"
                            id="editBtn_prof" role="button">Edit</a>
                    </div>
                </div>
            </div>
        </div>

        <!-- Edit Modal HTML -->
        <div id="editProf" class="modal fade">
            <div class="modal-dialog modal-lg">
                <div class="modal-content">
                    <form method="POST" action="php/action_page.php">
                        <input type="hidden" name="profID" id="profID" value="<?php echo $profID; ?>">
                        <input type="hidden" name="profStatus" value="1">

                        <div class="modal-header">
                            <h5 class="modal-title">Edit Professor</h5>
                            <button type="button" class="close" data-bs-dismiss="modal"
                                aria-hidden="true">&times;</button>
                        </div>
                        <div class="modal-body">
                            <div class="row">
                                <div class="col">
                                    <div class="form-group">
                                        <label>Employee ID</label>
                                        <input type=" text" name="profEmployID" id="profEmployID" class="form-control"
                                            required value="<?php echo $profEmployID; ?>">
                                    </div>
                                </div>
                                <div class="col">
                                    <div class="form-group">
                                        <label>First Name</label>
                                        <input type=" text" name="profFname" id="profFname" class="form-control"
                                            required value="<?php echo $profFname; ?>">
                                    </div>
                                </div>
                                <div class="col">
                                    <div class="form-group">
                                        <label>Middle Name</label>
                                        <input type="text" name="profMname" id="profMname" class="form-control" required
                                            value="<?php echo $profMname; ?>">
                                    </div>
                                </div>
                                <div class="col">
                                    <div class="form-group">
                                        <label>Last Name</label>
                                        <input type="text" name="profLname" id="profLname" class="form-control" required
                                            value="<?php echo $profLname; ?>">
                                    </div>
                                </div>
                            </div>

                            <div class="row">
                                <div class="col">
                                    <div class="form-group">
                                        <label>Mobile No.</label>
                                        <input type="number" name="profMobile" id="profMobile" class="form-control"
                                            required value="<?php echo $profMobile; ?>">
                                    </div>
                                </div>
                                <div class="col">
                                    <div class="form-group">
                                        <label>Address</label>
                                        <textarea class="form-control" name="profAddress" id="profAddress"
                                            required><?php echo $profAddress; ?></textarea>
                                    </div>
                                </div>
                            </div>

                            <div class="row">
                                <div class="col">
                                    <div class="form-group">
                                        <label>Educational Attainment</label>
                                        <textarea class="form-control" name="profEduc" id="profEduc" rows="4"
                                            required><?php echo $profEduc; ?><?php echo $profEduc; ?></textarea>
                                    </div>
                                </div>
                                <div class="col">
                                    <div class="form-group">
                                        <label>Expertise/Specialization</label>
                                        <textarea class="form-control" name="profExpert" id="profExpert" rows="4"
                                            required><?php echo $profExpert; ?></textarea>
                                    </div>
                                </div>
                            </div>

                            <div class="row">
                                <div class="col">
                                    <div class="form-group">
                                        <label>Rank</label>
                                        <select class="form-control" required name="profRank" id="profRank">
                                            <option value="" disabled>Select Professors Rank</option>
                                            <option value="Instructor 1"
                                                <?= ($profRank == "Instructor 1") ? "selected" : ""; ?>>
                                                Instructor 1
                                            </option>
                                            <option value="Instructor 2"
                                                <?= ($profRank == "Instructor 2") ? "selected" : ""; ?>>
                                                Instructor 2
                                            </option>
                                            <option value="Instructor 3"
                                                <?= ($profRank == "Instructor 3") ? "selected" : ""; ?>>
                                                Instructor 3
                                            </option>
                                            <option value="Instructor 4"
                                                <?= ($profRank == "Instructor 4") ? "selected" : ""; ?>>
                                                Instructor 4
                                            </option>
                                            <option value="Instructor 5"
                                                <?= ($profRank == "Instructor 5") ? "selected" : ""; ?>>
                                                Instructor 5
                                            </option>
                                            <option value="Instructor 6"
                                                <?= ($profRank == "Instructor 6") ? "selected" : ""; ?>>
                                                Instructor 6
                                            </option>
                                            <option value="Instructor 7"
                                                <?= ($profRank == "Instructor 7") ? "selected" : ""; ?>>
                                                Instructor 7
                                            </option>
                                            <option value="Assistant Prof. 1"
                                                <?= ($profRank == "Assistant Prof. 1") ? "selected" : ""; ?>>
                                                Assistant Prof. 1</option>
                                            <option value="Assistant Prof. 2"
                                                <?= ($profRank == "Assistant Prof. 2") ? "selected" : ""; ?>>
                                                Assistant Prof. 2</option>
                                            <option value="Assistant Prof. 3"
                                                <?= ($profRank == "Assistant Prof. 3") ? "selected" : ""; ?>>
                                                Assistant Prof. 3</option>
                                            <option value="Assistant Prof. 4"
                                                <?= ($profRank == "Assistant Prof. 4") ? "selected" : ""; ?>>
                                                Assistant Prof. 4</option>
                                            <option value="Assistant Prof. 5"
                                                <?= ($profRank == "Assistant Prof. 5") ? "selected" : ""; ?>>
                                                Assistant Prof. 5</option>
                                            <option value="Assistant Prof. 6"
                                                <?= ($profRank == "Assistant Prof. 6") ? "selected" : ""; ?>>
                                                Assistant Prof. 6</option>
                                            <option value="Assistant Prof. 7"
                                                <?= ($profRank == "Assistant Prof. 7") ? "selected" : ""; ?>>
                                                Assistant Prof. 7</option>
                                            <option value="Associate Prof. 1"
                                                <?= ($profRank == "Associate Prof. 1") ? "selected" : ""; ?>>
                                                Associate Prof. 1</option>
                                            <option value="Associate Prof. 2"
                                                <?= ($profRank == "Associate Prof. 2") ? "selected" : ""; ?>>
                                                Associate Prof. 2</option>
                                            <option value="Associate Prof. 3"
                                                <?= ($profRank == "Associate Prof. 3") ? "selected" : ""; ?>>
                                                Associate Prof. 3</option>
                                            <option value="Associate Prof. 4"
                                                <?= ($profRank == "Associate Prof. 4") ? "selected" : ""; ?>>
                                                Associate Prof. 4</option>
                                            <option value="Associate Prof. 5"
                                                <?= ($profRank == "Associate Prof. 5") ? "selected" : ""; ?>>
                                                Associate Prof. 5</option>
                                            <option value="Associate Prof. 6"
                                                <?= ($profRank == "Associate Prof. 6") ? "selected" : ""; ?>>
                                                Associate Prof. 6</option>
                                            <option value="Associate Prof. 7"
                                                <?= ($profRank == "Associate Prof. 7") ? "selected" : ""; ?>>
                                                Associate Prof. 7</option>
                                            <option value="Professor 1"
                                                <?= ($profRank == "Professor 1") ? "selected" : ""; ?>>
                                                Professor
                                                1
                                            </option>
                                            <option value="Professor 2"
                                                <?= ($profRank == "Professor 2") ? "selected" : ""; ?>>
                                                Professor
                                                2
                                            </option>
                                            <option value="Professor 3"
                                                <?= ($profRank == "Professor 3") ? "selected" : ""; ?>>
                                                Professor
                                                3
                                            </option>
                                            <option value="Professor 4"
                                                <?= ($profRank == "Professor 4") ? "selected" : ""; ?>>
                                                Professor
                                                4
                                            </option>
                                            <option value="Professor 5"
                                                <?= ($profRank == "Professor 5") ? "selected" : ""; ?>>
                                                Professor
                                                5
                                            </option>
                                            <option value="Professor 6"
                                                <?= ($profRank == "Professor 6") ? "selected" : ""; ?>>
                                                Professor
                                                6
                                            </option>
                                            <option value="Professor 7"
                                                <?= ($profRank == "Professor 7") ? "selected" : ""; ?>>
                                                Professor
                                                7
                                            </option>
                                            <option value="Professor 8"
                                                <?= ($profRank == "Professor 8") ? "selected" : ""; ?>>
                                                Professor
                                                8
                                            </option>
                                            <option value="Professor 9"
                                                <?= ($profRank == "Professor 9") ? "selected" : ""; ?>>
                                                Professor
                                                9
                                            </option>
                                            <option value="Professor 10"
                                                <?= ($profRank == "Professor 10") ? "selected" : ""; ?>>
                                                Professor 10
                                            </option>
                                            <option value="Professor 11"
                                                <?= ($profRank == "Professor 11") ? "selected" : ""; ?>>
                                                Professor 11
                                            </option>
                                            <option value="Professor 12"
                                                <?= ($profRank == "Professor 12") ? "selected" : ""; ?>>
                                                Professor 12
                                            </option>
                                            <option value="Univ. Professor"
                                                <?= ($profRank == "Univ. Professor") ? "selected" : ""; ?>>
                                                Univ.
                                                Professor</option>
                                        </select>
                                    </div>
                                </div>
                                <div class="col">
                                    <div class="form-group">
                                        <label>Maximum Units</label>
                                        <input type="number" name="profHrs" id="profHrs" class="form-control" required
                                            value="<?php echo $profHrs; ?>">
                                    </div>
                                </div>
                                <div class="col">
                                    <div class="form-group">
                                        <label for="profEmployStatus">Employment Status</label>
                                        <select name="profEmployStatus" class="form-control" required>
                                            <option value="" disabled>Select Professor's Employment Status</option>
                                            <option id="profPart" value="Part-Time"
                                                <?= ($profEmployStatus == "Part-Time") ? "selected" : ""; ?>>Part-Time
                                            </option>
                                            <option id="profFull" value="Full-Time"
                                                <?= ($profEmployStatus == "Full-Time") ? "selected" : ""; ?>>Full-Time
                                            </option>
                                        </select>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="modal-footer">
                            <a class="btn btn-default" data-bs-toggle="modal" data-bs-dismiss="modal" href="#viewProf"
                                role="button">Cancel</a>
                            <input type="submit" name="prof_update" class="btn" value="Update">
                        </div>
                    </form>
                </div>
            </div>
        </div>


        <!-- Change Status Modal HTML -->
        <div id="statusProf" class="modal fade">
            <div class="modal-dialog">
                <div class="modal-content">
                    <form method="POST" action="php/action_page.php">
                        <input type="" name="del_profID" id="del_profID" value="<?php echo $profID; ?>">

                        <div class="modal-header">
                            <h5 class="modal-title">Change Status</h5>
                            <button type="button" class="close" data-bs-dismiss="modal"
                                aria-hidden="true">&times;</button>
                        </div>

                        <div class="modal-body">
                            <h6>Are you sure you want to change the Status of this Professor?</h6>
                            <input type="hidden" name="profID" id="status_profID" value="">
                        </div>
                        <div class="modal-footer">
                            <input type="button" class="btn" data-bs-dismiss="modal" value="Cancel">
                            <input type="submit" class="btn" name="prof_toggle_status" value="Confirm Status">
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

        $('#editProf').modal('show');

        $tr = $(this).closest('tr');

        var data = $tr.find("td").map(function() {
            return $(this).text();
        }).get();

        console.log(data);
        $('#profID').val(data[0]);
        $('#profEmployID').val(data[1]);
        $('#profFname').val(data[2]);
        $('#profMname').val(data[3]);
        $('#profLname').val(data[4]);
        $('#profMobile').val(data[5]);
        $('#profAddress').val(data[6]);
        $('#profEduc').val(data[7]);
        $('#profExpert').val(data[8]);
        $('#profRank').val(data[9]);
        $('#profHrs').val(data[10]);
        // $('#profPart').val(data[11]);
        // $('#profFull').val(data[12]);
    });

});
</script>

<script>
$(document).ready(function() {

    // display Edit modal

    $('.view').on('click', function() {

        $('#viewProf').modal('show');

        $tr = $(this).closest('tr');

        var data = $tr.find("td").map(function() {
            return $(this).text();
        }).get();

        console.log(data);
        $('#profID').val(data[0]);

        $('#profEmployID').val(data[1]);
        $('#profFname').val(data[2]);
        $('#profMname').val(data[3]);
        $('#profLname').val(data[4]);
        $('#profMobile').val(data[5]);
        $('#profAddress').val(data[6]);
        $('#profEduc').val(data[7]);
        $('#profExpert').val(data[8]);
        $('#profRank').val(data[9]);
        $('#profHrs').val(data[10]);
    });

});
</script>

<script>
//this script is for the secStatus 

// JavaScript to set secID when opening status modal
$('.status').on('click', function() {
    // del_profID
    var profID = $(this).data('profid');
    $('#status_profID').val(profID); // Set secID to the hidden input field
    $('#statusProf').modal('show');
    $('#del_profID').val(profID).hide()
});
</script>

</body>

</html>

<script>
$(document).ready(function() {
    $('.view').click(function() {
        var profEmployID = $(this).data('id');
        var profFname = $(this).data('fname');
        var profMname = $(this).data('mname');
        var profLname = $(this).data('lname');
        var profMobile = $(this).data('mobile');
        var profAddress = $(this).data('address');
        var profEduc = $(this).data('educational');
        var profExpert = $(this).data('expertise');
        var profRank = $(this).data('rank');
        var profMax = $(this).data('max');
        var profEmployStatus = $(this).data('employmentstatus');

        // Now you can use these variables as per your requirements
        $('#editBtn_prof').data('id', profEmployID)
        $('#editBtn_prof').data('fname', profFname)
        $('#editBtn_prof').data('mname', profMname)
        $('#editBtn_prof').data('lname', profLname)
        $('#editBtn_prof').data('mobile', profMobile)
        $('#editBtn_prof').data('address', profAddress)
        $('#editBtn_prof').data('educational', profEduc)
        $('#editBtn_prof').data('expertise', profExpert)
        $('#editBtn_prof').data('rank', profRank)
        $('#editBtn_prof').data('max', profMax)
        $('#editBtn_prof').data('employmentstatus', profEmployStatus)


        $('#data_1').text(profEmployID)
        $('#data_2').text(profFname)
        $('#data_3').text(profMname)
        $('#data_4').text(profLname)
        $('#data_5').text(profMobile)
        $('#data_6').text(profAddress)
        $('#data_7').text(profEduc)
        $('#data_8').text(profExpert)
        $('#data_9').text(profRank)
        $('#data_10').text(profMax)
        $('#data_11').text(profEmployStatus)

        $('#editBtn_prof').click(function() {
            var profEmployID = $(this).data('id');
            var profFname = $(this).data('fname');
            var profMname = $(this).data('mname');
            var profLname = $(this).data('lname');
            var profMobile = $(this).data('mobile');
            var profAddress = $(this).data('address');
            var profEduc = $(this).data('educational');
            var profExpert = $(this).data('expertise');
            var profRank = $(this).data('rank');
            var profMax = $(this).data('max');
            var profEmployStatus = $(this).data('employmentstatus');

            $('#profMobile').val(profMobile)
            $('#profAddress').val(profAddress)
            $('#profAddress').val(profAddress)
            $('#profEduc').val(profEduc)
            $('#profExpert').val(profExpert)
            $('#profRank').val(profRank)
            $('#profHrs').val(profMax)

        })

    });
});
</script>
<link rel="stylesheet" href="https://cdn.datatables.net/2.0.5/css/dataTables.dataTables.min.css">
<script src="https://cdn.datatables.net/2.0.5/js/dataTables.min.js"></script>
<script>
$('#table').DataTable();
</script>