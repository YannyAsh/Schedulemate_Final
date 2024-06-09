<?php
include 'conn/conn.php';
$db = new DatabaseHandler();
require_once ('TCPDF/tcpdf.php');
$title = 'CLASS PROGRAM FOR MIS';
$ay = $_GET['ay'];
$program = $_SESSION['program'];
$college = strtoupper($_SESSION['college']);
$position = strtoupper($_SESSION['postion']);
$course = $_SESSION['program'];
$semester = $_GET['semester'];
$section = $_GET['section'];

$schoolyear = $ay;
$SY = $semester . ' ' . $schoolyear;

$secProgram = $db->getIdByColumnValue('tb_section', 'secID', $section, 'secProgram');
$secYearlvl = $db->getIdByColumnValue('tb_section', 'secID', $section, 'secYearlvl');
$secName = $db->getIdByColumnValue('tb_section', 'secID', $section, 'secName');
$secDay = $db->getIdByColumnValue('tb_section', 'secID', $section, 'secDay');
$fullsection = ucwords($secProgram . ' ' . $secYearlvl . ' ' . $secName . ' ' . $secDay);

$pdf = new TCPDF('L', PDF_UNIT, 'A4', true, 'UTF-8', false);

$pdf->setPrintHeader(false);
$pdf->setPrintFooter(false);

$pdf->AddPage('L');

$img_file1 = K_PATH_IMAGES . 'ctulogo.png';
$pdf->Image($img_file1, 65, 10, 30, '', '', '', '', false, 300, '', false, false, 0);

$pdf->SetFont('helvetica', '', 8);
$pdf->Cell(0, 15, 'Republic of the Philippines', 0, false, 'C', 0, '', 0, false, 'M', 'N');
$pdf->SetY($pdf->GetY() + 5);
$pdf->SetFont('helvetica', 'B', 8);
$pdf->Cell(0, 15, 'CEBU TECHNOLOGICAL UNIVERSITY', 0, false, 'C', 0, '', 0, false, 'M', 'N');
$pdf->SetY($pdf->GetY() + 4);
$pdf->SetFont('helvetica', '', 8);
$pdf->Cell(0, 15, 'MAIN CAMPUS', 0, false, 'C', 0, '', 0, false, 'M', 'N');
$pdf->SetY($pdf->GetY() + 3);
$pdf->Cell(0, 15, 'M. J. Cuenco Avenue Cor. R. Palma Street, Cebu City, Philippines', 0, false, 'C', 0, '', 0, false, 'M', 'N');
$pdf->SetY($pdf->GetY() + 3);
$pdf->SetFont('helvetica', '', 7);
$pdf->Cell(0, 15, 'Website: http://www.ctu.edu.ph E-mail: thepresident@ctu.edu.ph', 0, false, 'C', 0, '', 0, false, 'M', 'N');
$pdf->SetY($pdf->GetY() + 3);
$pdf->Cell(0, 15, 'Phone: +6332 402 4060 loc. 1137', 0, false, 'C', 0, '', 0, false, 'M', 'N');

$pdf->SetY($pdf->GetY() + 5);
$pdf->SetFont('helvetica', 'B', 8);
$pdf->Cell(0, 15, $college, 0, false, 'C', 0, '', 0, false, 'M', 'N');
$pdf->SetY($pdf->GetY() + 5);
$pdf->SetFont('helvetica', 'B', 8);
$pdf->Cell(0, 10, $title, 0, 1, 'C', 0, '', 0, false, 'M', 'M');
$pdf->Cell(0, 15, $SY, 0, 1, 'C', 0, '', 0, false, 'M', 'M');


$img_file2 = K_PATH_IMAGES . 'bplogo.png';
$pdf->Image($img_file2, 200, 8, 38, '', '', '', '', false, 300, '', false, false, 0);
$pdf->Ln(2);


// SEC DETAILS
$pdf->SetFont('helvetica', 'B', 10);
$pdf->Cell(0, 8, strtoupper($fullsection), 0, 1, '', 0, '', false, 'M', 'M');



$pdf->SetFont('helvetica', '', 8);


$conditions = [
    'school_yr = "' . $ay . '"',
    'semester = "' . $semester . '"',
    'section_id = "' . $section . '"',
];
$sql = $db->getAllDataCourse('tb_scheduled_2', $conditions);
// echo '<pre>';
// var_dump($sql);
$dayMon = [];
$dayTues = [];
$dayWed = [];
$dayThurs = [];
$dayFri = [];
$daySat = [];
$daySun = [];

// function getUserFullName($user, $db)
// {
//     $userFname = $db->getIdByColumnValue('tb_register', 'userID', $user, 'userFname');
//     $userMname = $db->getIdByColumnValue('tb_register', 'userID', $user, 'userMname');
//     $userLname = $db->getIdByColumnValue('tb_register', 'userID', $user, 'userLname');
//     return ucwords($userFname . ' ' . $userMname . ' ' . $userLname);
// }

// if ($user['userPosition'] === 'admin') {
//     $userAdminname = getUserFullName($user, $db);
// } elseif ($user['userPosition'] === 'dean') {
//     $userDeanname = getUserFullName($user, $db);
// } elseif ($user['userPosition'] === 'chairperson') {
//     $userCPname = getUserFullName($user, $db);
// }


$rows1 = '';
foreach ($sql as $row) {

    $profFName = $db->getIdByColumnValue('tb_professor', 'profID', $row['prof_id'], 'profFName');
    $profMname = $db->getIdByColumnValue('tb_professor', 'profID', $row['prof_id'], 'profMname');
    $profLname = $db->getIdByColumnValue('tb_professor', 'profID', $row['prof_id'], 'profLname');
    $fullname = ucwords($profFName . ' ' . $profMname . ' ' . $profLname);


    if ($row['prof_id'] == "TBA") {
        $fullname = "TBA";
    }

    $subDesc = $row['subDesc'];
    $SubCourse = $row['SubCourse'];
    $subLechours = $row['subLechours'];
    $subUnits = $row['subUnits'];
    $subLabhours = $row['subLabhours'];

    $room = strtoupper($row['roomBuild'] . " " . $row['roomNum']);
    $subject = strtoupper($row['subCode']);

    $timeDetails = '';
    $dayDetails = '';
    if ($row['day'] == 1) {
        if ($timeDetails != "") {
            $dayDetails .= '/M';
            $timeDetails .= '/' . $row['start_time'] . '-' . $row['end_time'];
        } else {
            $dayDetails .= 'M';
            $timeDetails .= $row['start_time'] . '-' . $row['end_time'];
        }
    }
    if ($row['day'] == 2) {
        if ($timeDetails != "") {
            $dayDetails .= '/T';
            $timeDetails .= '/' . $row['start_time'] . '-' . $row['end_time'];
        } else {
            $dayDetails .= 'T';
            $timeDetails .= $row['start_time'] . '-' . $row['end_time'];
        }

    }
    if ($row['day'] == 3) {
        if ($timeDetails != "") {
            $dayDetails .= '/W';
            $timeDetails .= '/' . $row['start_time'] . '-' . $row['end_time'];
        } else {
            $dayDetails .= 'W';
            $timeDetails .= $row['start_time'] . '-' . $row['end_time'];
        }
    }
    if ($row['day'] == 4) {
        if ($timeDetails != "") {
            $dayDetails .= '/Th';
            $timeDetails .= '/' . $row['start_time'] . '-' . $row['end_time'];
        } else {
            $dayDetails .= 'Th';
            $timeDetails .= $row['start_time'] . '-' . $row['end_time'];
        }
    }
    if ($row['day'] == 5) {
        if ($timeDetails != "") {
            $dayDetails .= '/Fri';
            $timeDetails .= '/' . $row['start_time'] . '-' . $row['end_time'];
        } else {
            $dayDetails .= 'Fri';
            $timeDetails .= $row['start_time'] . '-' . $row['end_time'];
        }
    }
    if ($row['day'] == 6) {
        if ($timeDetails != "") {
            $dayDetails .= '/Sat';
            $timeDetails .= '/' . $row['start_time'] . '-' . $row['end_time'];
        } else {
            $dayDetails .= 'Sat';
            $timeDetails .= $row['start_time'] . '-' . $row['end_time'];
        }
    }
    if ($row['day'] == 7) {
        if ($timeDetails != "") {
            $dayDetails .= '/Sun';
            $timeDetails .= '/' . $row['start_time'] . '-' . $row['end_time'];
        } else {
            $dayDetails .= 'Sun';
            $timeDetails .= $row['start_time'] . '-' . $row['end_time'];
        }
    }

    $rows1 .= '<tr>';
    $rows1 .= '<td style="text-align:center;"></td>';
    $rows1 .= '<td style="text-align:center;">' . $subject . '</td>';
    $rows1 .= '<td colspan="2">' . $subDesc . '</td>';
    $rows1 .= '<td style="text-align:center;" colspan="2">' . $timeDetails . '</td>';
    $rows1 .= '<td style="text-align:center;"s>' . $dayDetails . '</td>';
    $rows1 .= '<td style="text-align:center;">' . $subLechours . '</td>';
    $rows1 .= '<td style="text-align:center;">' . $subUnits . '</td>';
    $rows1 .= '<td style="text-align:center;">' . $subLabhours . '</td>';
    $rows1 .= '<td style="text-align:center;">' . $room . '</td>';
    $rows1 .= '<td colspan="2">' . $fullname . '</td>';
    $rows1 .= '</tr>';
}



$tbl1 = <<<EOD
<table border="1" style="align-items:center; table-layout: fixed; width: 100%">
       <thead>
       <tr style="height: 30px;">
       <th style="text-align:center;font-weight:bold;" colspan="1">MIS CODE</th>
       <th style="text-align:center;font-weight:bold;" colspan="1">Course No.</th>
       <th style="text-align:center;font-weight:bold;" colspan="2">Descriptive Title</th>
       <th style="text-align:center;font-weight:bold;" colspan="2">Time</th>
       <th style="text-align:center;font-weight:bold;" colspan="1">Day</th>
       <th style="text-align:center;font-weight:bold;" colspan="1">Lec</th>
       <th style="text-align:center;font-weight:bold;" colspan="1">Lab</th>
       <th style="text-align:center;font-weight:bold;" colspan="1">Unit</th>
       <th style="text-align:center;font-weight:bold;" colspan="1">Room</th>
       <th style="text-align:center;font-weight:bold;" colspan="2">Instructor</th>
   </tr>
       </thead>
     $rows1
    </tbody>
</table>
EOD;

$pdf->writeHTML($tbl1, true, false, false, false, '');
// FOOTER DETAILS
$pdf->SetFont('helvetica', 'B', 8);
$pdf->MultiCell(60, 5, "Prepared by: ", 0, 'L', false, 0, '20', '130', true);
$pdf->MultiCell(75, 5, "Reviewed, Certified True and Correct:", 0, 'L', false, 0, '', '', true);
$pdf->MultiCell(60, 5, "Approved by: ", 0, 'L', false, 0, '210', '', true);
$pdf->Ln(10); // Move to the next line

$pdf->SetFont('helvetica', '', 8);

$pdf->MultiCell(60, 5, "______________________________", 0, 'C', false, 0, '15', '140', true);
$pdf->MultiCell(75, 5, "______________________________", 0, 'C', false, 0, '', '', true);
$pdf->MultiCell(60, 5, "______________________________", 0, 'C', false, 0, '', '', true);
$pdf->MultiCell(75, 5, "______________________________", 0, 'C', false, 0, '', '', true);
$pdf->Ln(); // Move to the next line

$pdf->SetFont('helvetica', '', 8);
$pdf->MultiCell(70, 5, "Program Coordinator/Chair", 0, 'C', false, 0, '10', '145', true);
$pdf->MultiCell(65, 5, "Dean, " . $college, 0, 'C', false, 0, '', '', true);
$pdf->MultiCell(72, 5, "OIC-Dean, CAS ", 0, 'C', false, 0, '', '', true);
$pdf->MultiCell(63, 5, "Campus Director, CTU-Main Campus ", 0, 'C', false, 0, '', '', true);

$img_file3 = K_PATH_IMAGES . 'footerlogo.png';
$pdf->Image($img_file3, 75, 175, 150, 0, '', '', '', false, 300, '', false, false, 0);

$pdf->SetFont('helvetica', 'B', 8);
$filename = $fullsection . '_MIS_' . date('Y-m-d') . '.pdf';
$pdf->Output($filename, 'I');


?>
