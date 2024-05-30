<?php
include 'conn/conn.php';
$db = new DatabaseHandler();
require_once ('TCPDF/tcpdf.php');
$title = 'PROGRAM BY SECTION';
$ay = $_GET['ay'];
$program = $_SESSION['program'];
$college = strtoupper($_SESSION['college']);
$userFname = strtoupper($_SESSION['userFname']);
$course = $_SESSION['program'];
$ay = $_GET['ay'];
$semester = $_GET['semester'];
$section = $_GET['section'];


$schoolyear = $ay;
$SY = $semester . ', ' . $schoolyear;

$pdf = new TCPDF('L', PDF_UNIT, 'A4', true, 'UTF-8', false);

$pdf->setPrintHeader(false);
$pdf->setPrintFooter(false);

// set font
$pdf->SetFont('helvetica', 'B', 10);

// add a page
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



// SECs DETAILS
$pdf->MultiCell(135, 5, "Degree Year & Section: " . $section, 0, 'L', false, 0, '', '', true);
$pdf->MultiCell(135, 5, "Major:", 0, 'L', false, 0, '', '', true);
$pdf->Ln(3);

$pdf->MultiCell(135, 5, "Adviser:", 0, 'L', false, 0, '', '', true);
$pdf->MultiCell(135, 5, "Assignment:", 0, 'L', false, 0, '', '', true);
$pdf->Ln();

$pdf->SetFont('helvetica', '', 8);


$conditions = [
    'sy = "' . $ay . '"',
    'semester = "' . $semester . '"',
    'section = "' . $section . '"',
];
$sql = $db->getAllRowsFromTableWhere('tb_scheduled', $conditions);
// echo '<pre>';
// var_dump($sql);
$dayMon = [];
$dayTues = [];
$dayWed = [];
$dayThurs = [];
$dayFri = [];
$daySat = [];
$daySun = [];
$subjectArr = [];
$descriptionArr = [];

foreach ($sql as $row) {
    $profFName = $db->getIdByColumnValue('tb_professor', 'profID', $row['prof'], 'profFName');
    $profMname = $db->getIdByColumnValue('tb_professor', 'profID', $row['prof'], 'profMname');
    $profLname = $db->getIdByColumnValue('tb_professor', 'profID', $row['prof'], 'profLname');
    $subDesc = $db->getIdByColumnValue('tb_subjects', 'subCode', $row['subject'], 'subDesc');
    $fullname = ucwords($profFName . ' ' . $profMname . ' ' . $profLname);


    if ($row['prof'] == "TBA") {
        $fullname = "TBA";
    }

    $subjectArr[] = $row['subject'];
    $descriptionArr[] = $subDesc;

    $room = strtoupper($row['room']);
    $subject = strtoupper($row['subject']);
    $appendedDetails = '<br>' . $subject . '<br>' . $fullname . '<br>' . $room;
    if ($row['sMonday'] !== "" && $row['eMonday'] !== "") {
        $dayMon[] = $row['sMonday'] . '-' . $row['eMonday'] . $appendedDetails;
    }
    if ($row['sTuesday'] !== "" && $row['eTuesday'] !== "") {
        $dayTues[] = $row['sTuesday'] . '-' . $row['eTuesday'] . $appendedDetails;
    }
    if ($row['sWednesday'] !== "" && $row['eWednesday'] !== "") {
        $dayWed[] = $row['sWednesday'] . '-' . $row['eWednesday'] . $appendedDetails;
    }
    if ($row['sThursday'] !== "" && $row['eThursday'] !== "") {
        $dayThurs[] = $row['sThursday'] . '-' . $row['eThursday'] . $appendedDetails;
    }
    if ($row['sFriday'] !== "" && $row['eFriday'] !== "") {
        $dayFri[] = $row['sFriday'] . '-' . $row['eFriday'] . $appendedDetails;
    }
    if ($row['sSaturday'] !== "" && $row['eSaturday'] !== "") {
        $daySat[] = $row['sSaturday'] . '-' . $row['eSaturday'] . $appendedDetails;
    }
    if ($row['sSunday'] !== "" && $row['eSunday'] !== "") {
        $daySun[] = $row['sSunday'] . '-' . $row['eSunday'] . $appendedDetails;
    }
}
$maxCount = max(
    count($dayMon),
    count($dayTues),
    count($dayWed),
    count($dayThurs),
    count($dayFri),
    count($daySat),
    count($daySun)
);
// // echo "The maximum count is: $maxCount";
// echo '<pre>';
// var_dump($dayMon);
// var_dump($dayTues);
(sort($dayMon));

$timeSlots = [
    '7' => ['7:00-8:00', '', '', '', '', '', '', ''],
    '8' => ['8:00-9:00', '', '', '', '', '', '', ''],
    '9' => ['9:00-10:00', '', '', '', '', '', '', ''],
    '10' => ['10:00-11:00', '', '', '', '', '', '', ''],
    '11' => ['11:00-12:00', '', '', '', '', '', '', ''],
    '12' => ['12:00-13:00', '', '', '', '', '', '', ''],
    '13' => ['13:00-14:00', '', '', '', '', '', '', ''],
    '14' => ['14:00-15:00', '', '', '', '', '', '', ''],
    '15' => ['15:00-16:00', '', '', '', '', '', '', ''],
    '16' => ['16:00-17:00', '', '', '', '', '', '', ''],
    '17' => ['17:00-18:00', '', '', '', '', '', '', ''],
    '18' => ['18:00-19:00', '', '', '', '', '', '', '']
];

$timeSlots = returnTD($dayMon, $timeSlots, 1);
$timeSlots = returnTD($dayTues, $timeSlots, 2);
$timeSlots = returnTD($dayWed, $timeSlots, 3);
$timeSlots = returnTD($dayThurs, $timeSlots, 4);
$timeSlots = returnTD($dayFri, $timeSlots, 5);
$timeSlots = returnTD($daySat, $timeSlots, 6);
$timeSlots = returnTD($daySun, $timeSlots, 7);
function returnTD($timeProvided, $timeSlots, $dayNumber)
{

    // var_dump($timeProvided); // Dumping the first argument


    foreach ($timeProvided as $key => $val) {
        $startAndEnd = StartAndEnd($timeProvided[$key]);
        $start = isset($startAndEnd[0]) ? intval($startAndEnd[0]) : 0;
        $end = isset($startAndEnd[1]) ? intval($startAndEnd[1]) : 0;

        $timeSlots[$start][$dayNumber] = $timeProvided[$key];

        $rowspan = abs($start - $end); //oras kung hanggang ilan

        $currentKey = $start;

        if ($rowspan > 1) {
            // hinahabol na row pababa
            $row = $currentKey + $rowspan;
            for ($i = $currentKey + 1; $i < $row; $i++) {
                $timeSlots[$i][$dayNumber] = '-'; //mark - if rowspanded
            }
        }


    }
    return $timeSlots;
}

// var_dump($timeSlots);
$rows = '';
foreach ($timeSlots as $key => $timeSlot) {
    $rows .= '<tr>';
    $rows .= '<td>' . $timeSlot[0] . '</td>';
    $rows .= rowSetter($timeSlot[1]);
    $rows .= rowSetter($timeSlot[2]);
    $rows .= rowSetter($timeSlot[3]);
    $rows .= rowSetter($timeSlot[4]);
    $rows .= rowSetter($timeSlot[5]);
    $rows .= rowSetter($timeSlot[6]);
    $rows .= rowSetter($timeSlot[7]);
    $rows .= '</tr>';
}
// echo '<pre>';
// var_dump($subjectArr);
// var_dump($descriptionArr);
$rows2 = '';
foreach ($subjectArr as $key => $timeSlot) {
    $rows2 .= '<tr>';
    $rows2 .= '<td style="text-align:center">' . $timeSlot . '</td>';
    $rows2 .= '<td style="text-align:center">' . $descriptionArr[$key] . '</td>';
    $rows2 .= '</tr>';
}
$tbl1 = <<<EOD
 
<table style="width:100%">
<tr>
<td style="width: 25%">
<table border="1" style="align-items:center; table-layout: fixed; width: 100%">
<tr >
    <th style="text-align:center;font-weight:bold" colspan="2">SUMMARY COURSES</th>
</tr>
       <tr style="height: 30px;">
            <td style="text-align:center;font-weight:bold;">Course Code</td>
            <td style="text-align:center;font-weight:bold;">Descriptive Title</td>
       </tr>
    <tbody>
    $rows2
    </tbody>
</table>
</td>
<td style="width: 75%">
<table border="1" style="align-items:center; table-layout: fixed; width: 100%">
       <thead>
       <tr style="height: 30px;">
       <th style="text-align:center;font-weight:bold;" colspan="1">Time</th>
       <th style="text-align:center;font-weight:bold;" colspan="1">MONDAY</th>
       <th style="text-align:center;font-weight:bold;" colspan="1">TUESDAY</th>
       <th style="text-align:center;font-weight:bold;" colspan="1">WEDNESDAY</th>
       <th style="text-align:center;font-weight:bold;" colspan="1">THURSDAY</th>
       <th style="text-align:center;font-weight:bold;" colspan="1">FRIDAY</th>
       <th style="text-align:center;font-weight:bold;" colspan="1">SATURDAY</th>
       <th style="text-align:center;font-weight:bold;" colspan="1">SUNDAY</th>
   </tr>
       </thead>
        <tbody>
           $rows
        </tbody>
</table>
</td>
</tr>
</table>
EOD;

$pdf->writeHTML($tbl1, true, false, false, false, '');

// FOOTER DETAILS
$pdf->SetFont('helvetica', 'B', 8);
$pdf->MultiCell(60, 5, "Prepared by: ", 0, 'L', false, 0, '55', '', true);
$pdf->MultiCell(75, 5, "Reviewed, Certified True and Correct:", 0, 'L', false, 0, '', '', true);
$pdf->MultiCell(60, 5, "Approved by: ", 0, 'L', false, 0, '', '', true);
$pdf->Ln(5); // Move to the next line

$pdf->SetFont('helvetica', '', 8);

$pdf->MultiCell(60, 5, "______________________________", 0, 'C', false, 0, '55', '', true);
$pdf->MultiCell(75, 5, "______________________________", 0, 'C', false, 0, '', '', true);
$pdf->MultiCell(60, 5, "______________________________", 0, 'C', false, 0, '', '', true);
$pdf->Ln(); // Move to the next line

$pdf->MultiCell(60, 5, "Program Coordinator/Chair", 0, 'C', false, 0, '55', '', true);
$pdf->MultiCell(75, 5, "Dean, " . $program, 0, 'C', false, 0, '', '', true);
$pdf->MultiCell(60, 5, "Campus Director", 0, 'C', false, 0, '', '', true);
$pdf->Ln(8); // Move to the next line


$pdf->MultiCell(195, 5, "______________________________", 0, 'C', false, 0, '55', '', true);
$pdf->Ln(); // Move to the next line
$pdf->MultiCell(195, 5, "Dean, CAS", 0, 'C', false, 0, '55', '', true);

$img_file3 = K_PATH_IMAGES . 'footerlogo.png';
$pdf->Image($img_file3, 60, 175, 185, 0, '', '', '', false, 300, '', false, false, 0);

$pdf->SetFont('helvetica', 'B', 8);
$filename = $section . '_PBS_' . date('Y-m-d') . '.pdf';
$pdf->Output($filename, 'I');
function StartAndEnd($string)
{
    // Split the string by '-'
    $parts = explode("-", $string);

    // Extract the first part
    $startTime = $parts[0];
    $endTime = $parts[1];

    $first = 0;
    $second = 0;

    // Use preg_match to find the first two numbers in the first part
    preg_match('/(\d{2}):/', $startTime, $matches);
    if (isset($matches[1])) {
        $first = $matches[1];
    }

    // Use preg_match to find the first two numbers in the second part
    preg_match('/(\d{2}):/', $endTime, $matches);
    if (isset($matches[1])) {
        $second = $matches[1];
    }

    return array($first, $second);
}

function rowSetter($timeSlot)
{
    $rows = '';
    if ($timeSlot != "" && $timeSlot != '-') {
        $startAndEnd = StartAndEnd($timeSlot);
        $start = isset($startAndEnd[0]) ? intval($startAndEnd[0]) : 0;
        $end = isset($startAndEnd[1]) ? intval($startAndEnd[1]) : 0;
        // echo '<pre>';
        // var_dump($timeSlot[1]);

        $rowspan = abs($start - $end); //start hr and end hr
        $rows .= '<td style="text-align:center" rowspan="' . $rowspan . '">' . $timeSlot . '</td>';
    } else if ($timeSlot == '-') {
        $rows .= '';
    } else {
        $rows .= '<td></td>';
    }

    return $rows;
}

?>