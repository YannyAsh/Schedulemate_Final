<?php
include 'conn/conn.php';
$db = new DatabaseHandler();
require_once ('TCPDF/tcpdf.php');
$ay = $_GET['ay'];
$program = $_SESSION['program'];


$curriculum = 'asdasd';
$schoolyear = $ay;
$courseID = 'course 1';
$course = $program;
$SY = 'Effective As of ' . $schoolyear;

$pdf = new TCPDF(PDF_PAGE_ORIENTATION, PDF_UNIT, PDF_PAGE_FORMAT, true, 'UTF-8', false);

$pdf->setPrintHeader(false);
$pdf->setPrintFooter(false);

$pdf->AddPage();

$img_file1 = K_PATH_IMAGES . 'ctulogo.png';
$pdf->Image($img_file1, 35, 10, 20, '', '', '', '', false, 300, '', false, false, 0);

$pdf->SetFont('helvetica', '', 8);
$pdf->Cell(0, 15, 'Republic of the Philippines', 0, false, 'C', 0, '', 0, false, 'M', 'N');
$pdf->SetY($pdf->GetY() + 5);
$pdf->SetFont('helvetica', 'B', 8);
$pdf->Cell(0, 15, 'CEBU TECHNOLOGICAL UNIVERSITY', 0, false, 'C', 0, '', 0, false, 'M', 'N');
$pdf->SetY($pdf->GetY() + 4);
$pdf->SetFont('helvetica', '', 8);
$pdf->Cell(0, 15, 'MAIN CAMPUS', 0, false, 'C', 0, '', 0, false, 'M', 'N');

$pdf->SetY($pdf->GetY() + 5);
$pdf->SetFont('helvetica', 'B', 8);
$pdf->Cell(0, 15, $course, 0, false, 'C', 0, '', 0, false, 'M', 'N');
$pdf->SetY($pdf->GetY() + 5);
$pdf->Cell(0, 15, $SY, 0, 1, 'C', 0, '', 0, false, 'M', 'M');

$img_file2 = K_PATH_IMAGES . 'bplogo.png';
$pdf->Image($img_file2, 150, 8, 25, '', '', '', '', false, 300, '', false, false, 0);
$pdf->Ln(2);

// FIRST YEAR-----------------------------------------------------------------------------
$pdf->SetFont('helvetica', 'B', 10);
$pdf->Cell(20, 4, 'FIRST YEAR', 0, 1, 'L', 0, '', 0, false, 'M', 'N');

$course = $_SESSION['program'];
$row1 = rows($db, $schoolyear, '1st semester', 'first year', $course);
$row2 = rows($db, $schoolyear, '2nd semester', 'first year', $course);
$tbl1 = <<<EOD
<table border="1" style="align-items:center; table-layout: fixed; width: 100%">
   
    $row1
    <br/>
    $row2
</table>
EOD;

// SECOND YEAR-----------------------------------------------------------------------------
$title2 = "SECOND YEAR";
$pdf->SetFont('helvetica', '', 10);
$row1 = rows($db, $schoolyear, '1st semester', $title2, $course);
$row2 = rows($db, $schoolyear, '2nd semester', $title2, $course);
$tbl2 = <<<EOD
<table border="1" style="align-items:center; table-layout: fixed; width: 100%">
    $row1
    <br/>
    $row2
</table>
EOD;

// THIRD YEAR-----------------------------------------------------------------------------
$title3 = "THIRD YEAR";
$pdf->SetFont('helvetica', '', 10);
$row1 = rows($db, $schoolyear, '1st semester', $title3, $course);
$row2 = rows($db, $schoolyear, '2nd semester', $title3, $course);
$tbl3 = <<<EOD
<table border="1" style="align-items:center; table-layout: fixed; width: 100%">
    $row1
    <br/>
    $row2
</table>
EOD;

// FOURTH YEAR-----------------------------------------------------------------------------
$title4 = "FOURTH YEAR";
$pdf->SetFont('helvetica', '', 10);
$row1 = rows($db, $schoolyear, '1st semester', $title4, $course);
$row2 = rows($db, $schoolyear, '2nd semester', $title4, $course);
$tbl4 = <<<EOD
<table border="1" style="align-items:center; table-layout: fixed; width: 100%">
    $row1
    <br/>
    $row2
</table>
EOD;






$pdf->writeHTML($tbl1, true, false, false, false, '');
$pdf->SetFont('helvetica', 'B', 10);
$pdf->Cell(20, 4, $title2, 0, 1, 'L', 0, '', 0, false, 'M', 'N');
$pdf->SetFont('helvetica', '', 10);
$pdf->writeHTML($tbl2, true, false, false, false, '');
$pdf->SetFont('helvetica', 'B', 10);
$pdf->Cell(20, 4, $title3, 0, 1, 'L', 0, '', 0, false, 'M', 'N');
$pdf->SetFont('helvetica', '', 10);
$pdf->writeHTML($tbl3, true, false, false, false, '');
$pdf->SetFont('helvetica', 'B', 10);
$pdf->Cell(20, 4, $title4, 0, 1, 'L', 0, '', 0, false, 'M', 'N');
$pdf->SetFont('helvetica', '', 10);
$pdf->writeHTML($tbl4, true, false, false, false, '');




$pdf->Output('subjects.pdf', 'I');


function rows($db, $sy, $sem, $yearlevel, $course)
{
    $sql = $db->pdf_data_subjects($sy, $sem, $course, $yearlevel);
    $returnRows = '
    <tr style="height: 30px;">
    <th style="text-align: center; border: 1px solid #333;font-weight:bold;" colspan="12">' . ucwords($sem) . '</th>
    </tr>
    <tr style="height: 30px;">
        <th style="text-align: center;font-weight:bold;" colspan="2 " rowspan="2">Course Code</th>
        <th style="text-align: center;font-weight:bold;" colspan="3" rowspan="2">Descriptive Title</th>
        <th style="text-align: center;font-weight:bold;" colspan="3" rowspan="2">Co-/Prerequisite</th>
        <th style="text-align: center;font-weight:bold; border-right: 1px solid #333;" colspan="1" rowspan="2">Units</th>
        <th style="text-align: center;font-weight:bold;" colspan="3" rowspan="1">Hours</th>
    </tr>
    <tr style="height: 30px;">
        <th style="text-align: center;font-weight:bold;">Lec</th>
        <th style="text-align: center;font-weight:bold;">Lab</th>
        <th style="text-align: center;font-weight:bold;">Total</th>
    </tr>
    ';
    $subUnits = 0;
    $subLechours = 0;
    $subLabhours = 0;
    $total = 0;
    foreach ($sql as $row) {
        $subUnits += $row['subUnits'];
        $subLechours += $row['subLechours'];
        $subLabhours += $row['subLabhours'];
        $total += $row['subLechours'] + $row['subLabhours'];
        $returnRows .= '
        <tr style="height: 30px;">
        <th style="text-align: center;" colspan="2">' . $row['subCode'] . '</th>
        <th style="text-align: center;" colspan="3">' . $row['subDesc'] . '</th>
        <th style="text-align: center;" colspan="3">' . $row['subPrerequisite'] . '</th>
        <th style="text-align: center;">' . $row['subUnits'] . '</th>
        <th style="text-align: center;">' . $row['subLechours'] . '</th>
        <th style="text-align: center;">' . $row['subLabhours'] . '</th>
        <th style="text-align: center;">' . $row['subLechours'] + $row['subLabhours'] . '</th>
        </tr>';
    }
    $returnRows .= '
        <tr style="height: 30px;">
        <th style="text-align: right;font-weight:bold" colspan="8">TOTAL</th>
        <th style="text-align: center;">' . $subUnits . '</th>
        <th style="text-align: center;">' . $subLechours . '</th>
        <th style="text-align: center;">' . $subLabhours . '</th>
        <th style="text-align: center;">' . $total . '</th>
        </tr>';

    return $returnRows;
}