<?php
include 'conn/conn.php';
$db = new DatabaseHandler();
require_once ('TCPDF/tcpdf.php');
$title = ('PROGRAM BY ROOM UTILIZATION');
$ay = $_GET['ay'];
$semester = $_GET['semester'];
$room = $_GET['room'];
$program = $_SESSION['program'];
$college = strtoupper($_SESSION['college']);
$course = $_SESSION['program'];

$schoolyear = $ay;
$SY = $semester . ' ' . $schoolyear;

$roomBuild = $db->getIdByColumnValue('tb_room', 'roomID', $room, 'roomBuild');
$roomNum = $db->getIdByColumnValue('tb_room', 'roomID', $room, 'roomNum');
$fullroom = ucwords($roomBuild . ' ' . $roomNum);

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


// ROOM DETAILS
$pdf->SetFont('helvetica', 'B', 10);
$pdf->Cell(0, 8, 'Room: ' . $fullroom, 0, 1, '', 0, '', 0, false, 'M', 'M');



$pdf->SetFont('helvetica', '', 8);


$conditions = [
    'school_yr = "' . $ay . '"',
    'semester = "' . $semester . '"',
    'room_id = "' . $room . '"',
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


foreach ($sql as $row) {
    $profFName = $db->getIdByColumnValue('tb_professor', 'profID', $row['prof_id'], 'profFName');
    $profMname = $db->getIdByColumnValue('tb_professor', 'profID', $row['prof_id'], 'profMname');
    $profLname = $db->getIdByColumnValue('tb_professor', 'profID', $row['prof_id'], 'profLname');
    $fullname = ucwords($profFName . ' ' . $profMname . ' ' . $profLname);


    if ($row['prof_id'] == "TBA") {
        $fullname = "TBA";
    }

    $section = strtoupper($row['secProgram'] . " " . $row['secYearlvl'] . " " . $row['secName'] . " " . $row['secSession']);
    $subject = strtoupper($row['subCode']);

    $subjectArr[] = $row['subCode'];
    $descriptionArr[] = $row['subDesc'];

    $appendedDetails = '<br>' . $subject . '<br>' . $section . '<br>' . $fullname;
    if ($row['day'] == 1) {
        $dayMon[] = $row['start_time'] . '-' . $row['end_time'] . $appendedDetails;
    }
    if ($row['day'] == 2) {
        $dayTues[] = $row['start_time'] . '-' . $row['end_time'] . $appendedDetails;
    }
    if ($row['day'] == 3) {
        $dayWed[] = $row['start_time'] . '-' . $row['end_time'] . $appendedDetails;
    }
    if ($row['day'] == 4) {
        $dayThurs[] = $row['start_time'] . '-' . $row['end_time'] . $appendedDetails;
    }
    if ($row['day'] == 5) {
        $dayFri[] = $row['start_time'] . '-' . $row['end_time'] . $appendedDetails;
    }
    if ($row['day'] == 6) {
        $daySat[] = $row['start_time'] . '-' . $row['end_time'] . $appendedDetails;
    }
    if ($row['day'] == 7) {
        $daySun[] = $row['start_time'] . '-' . $row['end_time'] . $appendedDetails;
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
$tbl1 = <<<EOD
<table border="1" style="align-items:center; table-layout: fixed; width: 100%">
       <thead>
       <tr style="height: 30px;">
       <th style="text-align: center;font-weight:bold;" colspan="1">Time</th>
       <th style="text-align: center;font-weight:bold;" colspan="1">MONDAY</th>
       <th style="text-align: center;font-weight:bold;" colspan="1">TUESDAY</th>
       <th style="text-align: center;font-weight:bold;" colspan="1">WEDNESDAY</th>
       <th style="text-align: center;font-weight:bold;" colspan="1">THURSDAY</th>
       <th style="text-align: center;font-weight:bold;" colspan="1">FRIDAY</th>
       <th style="text-align: center;font-weight:bold;" colspan="1">SATURDAY</th>
       <th style="text-align: center;font-weight:bold;" colspan="1">SUNDAY</th>
   </tr>
       </thead>
        <tbody>
           $rows
        </tbody>
</table>
EOD;






$pdf->writeHTML($tbl1, true, false, false, false, '');
$pdf->AddPage('L');

// FOOTER DETAILS
$pdf->SetFont('helvetica', 'B', 8);
$pdf->MultiCell(60, 5, "Prepared by: ", 0, 'L', false, 0, '55', '', true);
$pdf->MultiCell(75, 5, "Reviewed, Certified True and Correct:", 0, 'L', false, 0, '', '', true);
$pdf->MultiCell(60, 5, "Approved by: ", 0, 'L', false, 0, '', '', true);
$pdf->Ln(10); // Move to the next line

$pdf->SetFont('helvetica', '', 8);

$pdf->MultiCell(60, 5, "______________________________", 0, 'C', false, 0, '55', '', true);
$pdf->MultiCell(75, 5, "______________________________", 0, 'C', false, 0, '', '', true);
$pdf->MultiCell(60, 5, "______________________________", 0, 'C', false, 0, '', '', true);
$pdf->Ln(); // Move to the next line

$pdf->MultiCell(60, 5, "Program Coordinator/Chair", 0, 'C', false, 0, '55', '', true);
$pdf->MultiCell(75, 5, "Dean, " . $college, 0, 'C', false, 0, '', '', true);
$pdf->MultiCell(60, 5, "Campus Director", 0, 'C', false, 0, '', '', true);
$pdf->Ln(10); // Move to the next line


$pdf->MultiCell(195, 5, "______________________________", 0, 'C', false, 0, '55', '', true);
$pdf->Ln(); // Move to the next line
$pdf->MultiCell(195, 5, "Dean, CAS", 0, 'C', false, 0, '55', '', true);

$img_file3 = K_PATH_IMAGES . 'footerlogo.png';
$pdf->Image($img_file3, 75, 175, 150, 0, '', '', '', false, 300, '', false, false, 0);

$pdf->SetFont('helvetica', 'B', 8);
$filename = $fullroom . '_PBRU_' . date('Y-m-d') . '.pdf';
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
        $rowspan = abs($start - $end); //oras kung hanggang ilan
        $rows .= '<td style="text-align:center" rowspan="' . $rowspan . '">' . $timeSlot . '</td>';
    } else if ($timeSlot == '-') {
        $rows .= '';
    } else {
        $rows .= '<td></td>';
    }

    return $rows;
}

?>
