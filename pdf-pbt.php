<?php
include 'conn/conn.php';
$db = new DatabaseHandler();
require_once ('TCPDF/tcpdf.php');
$title = 'PROGRAM BY TEACHER';
$ay = $_GET['ay'];
$semester = $_GET['semester'];
$prof = $_GET['prof'];
$program = $_SESSION['program'];
$college = strtoupper($_SESSION['college']);
$position = strtoupper($_SESSION['postion']);
// $section = $_GET['section'];

$schoolyear = $ay;
$course = $program;
$SY = $semester . ', ' . $schoolyear;

$profHrs = $db->getIdByColumnValue('tb_professor', 'profID', $prof, 'profHrs');
$profFName = $db->getIdByColumnValue('tb_professor', 'profID', $prof, 'profFName');
$profMname = $db->getIdByColumnValue('tb_professor', 'profID', $prof, 'profMname');
$profLname = $db->getIdByColumnValue('tb_professor', 'profID', $prof, 'profLname');
$fullname = ucwords($profFName . ' ' . $profMname . ' ' . $profLname);
$preperation_count = $db->getCountDataCourse('tb_scheduled_2', ['prof_id' => $prof]);
foreach($preperation_count as $key => $val){
    foreach($val as $key => $vals)
    {
        $preperation_count = $vals;
    }
}
$profEduc = $db->getIdByColumnValue('tb_professor', 'profID', $prof, 'profEduc');
$profEmployStatus = $db->getIdByColumnValue('tb_professor', 'profID', $prof, 'profEmployStatus');

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
$pdf->Cell(0, 10, $title, 0, 1, 'C', 0, '', 0, false, 'M', 'M');
$pdf->Cell(0, 15, $SY, 0, 1, 'C', 0, '', 0, false, 'M', 'M');


$img_file2 = K_PATH_IMAGES . 'bplogo.png';
$pdf->Image($img_file2, 200, 8, 38, '', '', '', '', false, 300, '', false, false, 0);
$pdf->Ln(2);


// PROF DETAILS
$pdf->MultiCell(135, 5, "Name: " . $fullname, 0, 'L', false, 0, '', '', true);
$pdf->MultiCell(135, 5, "Special Training:", 0, 'L', false, 0, '', '', true);
$pdf->Ln(3);

$pdf->MultiCell(135, 5, "Degree: " . $profEduc, 0, 'L', false, 0, '', '', true);
$pdf->MultiCell(135, 5, "Major:", 0, 'L', false, 0, '', '', true);
$pdf->Ln(3);

$pdf->MultiCell(135, 5, "Status: " . $profEmployStatus, 0, 'L', false, 0, '', '', true);
$pdf->MultiCell(135, 5, "Minor:", 0, 'L', false, 0, '', '', true);
$pdf->Ln(); // Move to the next line

$pdf->SetFont('helvetica', '', 8);
$conditions = [
    'school_yr = "' . $ay . '"',
    'semester = "' . $semester . '"',
    'prof_id = "' . $prof . '"',
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
$subjectArr = [];
$descriptionArr = [];

foreach ($sql as $row) {

    $section = strtoupper($row['secProgram'] . " " . $row['secYearlvl'] . " " . $row['secName'] . " " . $row['secSession']);
    $room = strtoupper($row['roomBuild'] . " " . $row['roomNum']);
    $subject = strtoupper($row['subCode']);

    $subjectArr[] = $row['subCode'];
    $descriptionArr[] = $row['subDesc'];


    $appendedDetails = '<br>' . $subject . '<br>' . $section . '<br>' . $room;
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

$rows2 = '';
foreach ($subjectArr as $key => $timeSlot) {
    $rows2 .= '<tr>';
    $rows2 .= '<td style="text-align:center" colspan="2">' . $timeSlot . '</td>';
    $rows2 .= '<td style="text-align:center" colspan="3">' . $descriptionArr[$key] . '</td>';
    $rows2 .= '<td style="text-align:center" colspan="2">' . $section . '</td>';
    $rows2 .= '<td colspan="2"></td>';
    $rows2 .= '</tr>';
}

$tbl1 = <<<EOD

<table style="width:100%">
<tr>
<td style="width: 30%">
<table border="1" style="align-items:center; table-layout: fixed; width: 100%">
<tr >
    <th style="text-align:center;font-weight:bold" colspan="9">SUMMARY OF COURSES</th>
</tr>
       <tr style="height: 30px;">
            <td style="text-align:center;font-weight:bold;" colspan="2">Course Code</td>
            <td style="text-align:center;font-weight:bold;" colspan="3">Descriptive Title</td>
            <td style="text-align:center;font-weight:bold;" colspan="2">Degree Yr & Sec</td>
            <td style="text-align:center;font-weight:bold;" colspan="2">Total No. of Students</td>
       </tr>
    <tbody>
    $rows2
    </tbody>
</table>
<table border="1" style="table-layout: fixed; width: 100%">
    <table>
    <tr>
    <td colspan="9"></td>
    </tr>
    <tr>
    <td colspan="5">No. of Preparation: $preperation_count </td>
    <td colspan="5">Production: __________</td>
    </tr>
    <tr>
    <td colspan="5">No. of Units:   __________</td>
    <td colspan="5">Extension:  __________</td>
    </tr>
    <tr>
    <td colspan="5">No. of Hours/Week: __________</td>
    <td colspan="5">Research: __________</td>
    </tr>
    <tr>
    <td colspan="9">Administrative Desig: <u>$position - $program</u></td>
    </tr>
    <tr>
    <td colspan="9"></td>
    </tr>
    </table>
</table>
</td>
<td style="width: 70%">
<table border="1" style="align-items:center; table-layout: fixed; width: 100%">
       <thead>
       <tr style="height: 30px;">
       <th style="text-align: center;font-weight:bold;">Time</th>
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
</td>
</tr>
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
$pdf->MultiCell(75, 5, "Dean, " . $program, 0, 'C', false, 0, '', '', true);
$pdf->MultiCell(60, 5, "Campus Director", 0, 'C', false, 0, '', '', true);
$pdf->Ln(10); // Move to the next line


$pdf->MultiCell(195, 5, "______________________________", 0, 'C', false, 0, '55', '', true);
$pdf->Ln(); // Move to the next line
$pdf->MultiCell(195, 5, "Dean, CAS", 0, 'C', false, 0, '55', '', true);

$img_file3 = K_PATH_IMAGES . 'footerlogo.png';
$pdf->Image($img_file3, 75, 175, 150, 0, '', '', '', false, 300, '', false, false, 0);

$pdf->SetFont('helvetica', 'B', 8);
$filename = $profLname . '_PBT_' . date('Y-m-d') . '.pdf';
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
