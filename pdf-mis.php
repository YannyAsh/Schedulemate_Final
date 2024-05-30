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
$pdf->Cell(0, 15, $title, 0, 1, 'C', 0, '', 0, false, 'M', 'M');
$pdf->Cell(0, 15, $SY, 0, 1, 'C', 0, '', 0, false, 'M', 'M');


$img_file2 = K_PATH_IMAGES . 'bplogo.png';
$pdf->Image($img_file2, 200, 8, 38, '', '', '', '', false, 300, '', false, false, 0);
$pdf->Ln(2);


// PROF DETAILS
$pdf->SetFont('helvetica', 'B', 10);
$pdf->Cell(0, 8, strtoupper($section), 0, 1, '', 0, '', false, 'M', 'M');



$pdf->SetFont('helvetica', '', 8);


$conditions = [
    'sy = "' . $ay . '"',
    'semester = "' . $semester . '"',
    'section = "' . $section . '"',
];
$sql = $db->getAllRowsFromTableWhere('tb_scheduled', $conditions);
// echo '<pre>';
// var_dump($sql);


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

    $profFName = $db->getIdByColumnValue('tb_professor', 'profID', $row['prof'], 'profFName');
    $profMname = $db->getIdByColumnValue('tb_professor', 'profID', $row['prof'], 'profMname');
    $profLname = $db->getIdByColumnValue('tb_professor', 'profID', $row['prof'], 'profLname');
    $subDesc = $db->getIdByColumnValue('tb_subjects', 'subCode', $row['subject'], 'subDesc');
    $fullname = ucwords($profFName . ' ' . $profMname . ' ' . $profLname);


    if ($row['prof'] == "TBA") {
        $fullname = "TBA";
    }

    $subDesc = $db->getIdByColumnValue('tb_subjects', 'subCode', $row['subject'], 'subDesc');
    $SubCourse = $db->getIdByColumnValue('tb_subjects', 'subCode', $row['subject'], 'SubCourse');
    $subLechours = $db->getIdByColumnValue('tb_subjects', 'subCode', $row['subject'], 'subLechours');
    $subUnits = $db->getIdByColumnValue('tb_subjects', 'subCode', $row['subject'], 'subUnits');
    $subLabhours = $db->getIdByColumnValue('tb_subjects', 'subCode', $row['subject'], 'subLabhours');
    $room = strtoupper($row['room']);
    $subject = strtoupper($row['subject']);

    $timeDetails = '';
    $dayDetails = '';
    if ($row['sMonday'] != "") {
        if ($timeDetails != "") {
            $dayDetails .= '/M';
            $timeDetails .= '/' . $row['sMonday'] . '-' . $row['eMonday'];
        } else {
            $dayDetails .= 'M';
            $timeDetails .= $row['sMonday'] . '-' . $row['eMonday'];
        }
    }
    if ($row['sTuesday'] != "") {
        if ($timeDetails != "") {
            $dayDetails .= '/T';
            $timeDetails .= '/' . $row['sTuesday'] . '-' . $row['eTuesday'];
        } else {
            $dayDetails .= 'T';
            $timeDetails .= $row['sTuesday'] . '-' . $row['eTuesday'];
        }

    }
    if ($row['sWednesday'] != "") {
        if ($timeDetails != "") {
            $dayDetails .= '/W';
            $timeDetails .= '/' . $row['sWednesday'] . '-' . $row['eWednesday'];
        } else {
            $dayDetails .= 'W';
            $timeDetails .= $row['sWednesday'] . '-' . $row['eWednesday'];
        }
    }
    if ($row['sThursday'] != "") {
        if ($timeDetails != "") {
            $dayDetails .= '/Th';
            $timeDetails .= '/' . $row['sThursday'] . '-' . $row['eThursday'];
        } else {
            $dayDetails .= 'Th';
            $timeDetails .= $row['sThursday'] . '-' . $row['eThursday'];
        }
    }
    if ($row['sFriday'] != "") {
        if ($timeDetails != "") {
            $dayDetails .= '/Fri';
            $timeDetails .= '/' . $row['sFriday'] . '-' . $row['eFriday'];
        } else {
            $dayDetails .= 'Fri';
            $timeDetails .= $row['sFriday'] . '-' . $row['eFriday'];
        }
    }
    if ($row['sSaturday'] != "") {
        if ($timeDetails != "") {
            $dayDetails .= '/Sat';
            $timeDetails .= '/' . $row['sSaturday'] . '-' . $row['eSaturday'];
        } else {
            $dayDetails .= 'Sat';
            $timeDetails .= $row['sSaturday'] . '-' . $row['eSaturday'];
        }
    }
    if ($row['sSunday'] != "") {
        if ($timeDetails != "") {
            $dayDetails .= '/Sun';
            $timeDetails .= '/' . $row['sSunday'] . '-' . $row['eSunday'];
        } else {
            $dayDetails .= 'Sun';
            $timeDetails .= $row['sSunday'] . '-' . $row['eSunday'];
        }
    }

    $rows1 .= '<tr>';
    $rows1 .= '<td style="text-align:center;"></td>';
    $rows1 .= '<td style="text-align:center;">' . $row['subject'] . '</td>';
    $rows1 .= '<td colspan="2">' . $subDesc . '</td>';
    $rows1 .= '<td style="text-align:center;" colspan="2">' . $timeDetails . '</td>';
    $rows1 .= '<td style="text-align:center;"s>' . $dayDetails . '</td>';
    $rows1 .= '<td style="text-align:center;">' . $subLechours . '</td>';
    $rows1 .= '<td style="text-align:center;">' . $subUnits . '</td>';
    $rows1 .= '<td style="text-align:center;">' . $subLabhours . '</td>';
    $rows1 .= '<td style="text-align:center;">' . $row['room'] . '</td>';
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
$pdf->MultiCell(60, 5, "Prepared by: ", 0, 'L', false, 0, '20', '100', true);
$pdf->MultiCell(75, 5, "Reviewed, Certified True and Correct:", 0, 'L', false, 0, '', '', true);
$pdf->MultiCell(60, 5, "Approved by: ", 0, 'L', false, 0, '210', '', true);
$pdf->Ln(10); // Move to the next line

$pdf->SetFont('helvetica', 'BU', 8);

$pdf->MultiCell(60, 5, "______________________________", 0, 'C', false, 0, '15', '', true);
$pdf->MultiCell(75, 5, "______________________________", 0, 'C', false, 0, '', '', true);
$pdf->MultiCell(60, 5, "______________________________", 0, 'C', false, 0, '', '', true);
$pdf->MultiCell(75, 5, "______________________________", 0, 'C', false, 0, '', '', true);
$pdf->Ln(); // Move to the next line

$pdf->SetFont('helvetica', '', 8);
$pdf->MultiCell(70, 5, "Program Coordinator/Chair", 0, 'C', false, 0, '10', '', true);
$pdf->MultiCell(65, 5, "Dean, " . $program, 0, 'C', false, 0, '', '', true);
$pdf->MultiCell(72, 5, "OIC-Dean, CAS ", 0, 'C', false, 0, '', '', true);
$pdf->MultiCell(63, 5, "Campus Director, CTU-Main Campus ", 0, 'C', false, 0, '', '', true);

$img_file3 = K_PATH_IMAGES . 'footerlogo.png';
$pdf->Image($img_file3, 75, 175, 150, 0, '', '', '', false, 300, '', false, false, 0);

$pdf->SetFont('helvetica', 'B', 8);
$filename = $section . '_MIS_' . date('Y-m-d') . '.pdf';
$pdf->Output($filename, 'I');


?>