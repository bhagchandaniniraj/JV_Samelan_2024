<?php
session_start();
if (!isset($_SESSION['logged_in']) || $_SESSION['logged_in'] !== true) {
    header("Location: index.php");
    exit();
}

// Include the main TCPDF library (search for installation path).
require_once('../tcpdf/tcpdf.php');
require_once('../db_connect.class.php');

class MYPDF extends TCPDF {

    // Load table data from file
    public function LoadData() {
        $mydb = new DBConnect();
        return $mydb->createPDF();
    }
    public function Header() {
        // Logo
        $logoFile = '../logo/au.png'; // Path to the logo
        $this->Image($logoFile, 10, 5, 20, '', 'PNG', '', 'T', false, 300, '', false, false, 0, false, false, false);

        // Set font for header text
        $this->SetFont('times', 'B', 14);

        // Title
        $this->Cell(0, 0, 'ATMIYA UNIVERSITY', 0, 1, 'C', 0, '', 0, false, 'T', 'M');
        
        // Subtitle
        $this->SetFont('times', '', 12);
        $this->Cell(0, 0, 'FACULTY OF ENGINEERING AND TECHNOLOGY', 0, 1, 'C', 0, '', 0, false, 'T', 'M');
        
        // Event Title
        $this->Cell(0, 0, '26TH JIVAN VIDHYA SAMELAN', 0, 1, 'C', 0, '', 0, false, 'T', 'M');

        // Line break after header content
        $this->Ln(5);
    }

    // Colored table
    public function ColoredTable($header,$data) {
        
        // Colors, line width and bold font
        $this->SetFillColor(3, 121, 132);
        $this->SetTextColor(255);
        $this->SetDrawColor(128, 0, 0);
        $this->SetLineWidth(0.3);
        $this->SetFont('', 'B');

        // Header
        $t_head = array("SNo.", "Registration No.","Group ID","Name of Participant", 
                        "Gender", "Age","Mobile Number", "Attendance","Reg Timings");
        $w = array(8,22,65,10,10,24,8,38);
        $num_headers = count($header);
        for($i = 0; $i < $num_headers; ++$i) {
            $this->Cell($w[$i], 7, $header[$i], 1, 0, 'C', 1);
        }
        $this->Ln();
        $num_pages = $this->getNumPages();

        // Color and font restoration
        $this->SetFillColor(224, 235, 255);
        $this->SetTextColor(0);
        $this->SetFont('');

        // Data
        $fill = 0;
        $num_pages = $this->getNumPages();
        $j = 1;
        foreach($data as $row) {
                // for($i = 0; $i < $num_headers; ++$i) {
                //     $this->Cell($w[$i], 7, $header[$i], 1, 0, 'C', 1);
                // }
                // $this->Ln();
                // $num_pages = $this->getNumPages();
                
                if($num_pages != $this->getNumPages()){
                    $this->SetFillColor(3, 121, 132);
                $this->SetTextColor(255);
                $this->SetDrawColor(128, 0, 0);
                $this->SetLineWidth(0.3);
                $this->SetFont('', 'B');
                    for($i = 0; $i < $num_headers; ++$i) {
                        $this->Cell($w[$i], 7, $header[$i], 1, 0, 'C', 1);
                    }
                    $this->Ln();
                    $num_pages = $this->getNumPages();
                    $this->SetFillColor(224, 235, 255);
                    $this->SetTextColor(0);
                    $this->SetFont('');
                }
                $this->Cell($w[0], 6, $j++ , 'LR', 0, 'L', $fill);
                $this->Cell($w[1], 6, $row['group_id'], 'LR', 0, 'L', $fill);
                if(!preg_match('/[^A-Za-z0-9 ]/', $row['participant'])){
                    $this->SetFont('times', '', 11);
                }else{
                    $this->SetFont('freesans', '', 11);
                }
                $this->Cell($w[2], 6, $row['participant'], 'LR', 0, 'L', $fill);
                $this->SetFont('times', '', 11);
                $this->Cell($w[3], 6, $row['gender'], 'LR', 0, 'L', $fill);
                $this->Cell($w[4], 6, $row['age'], 'LR', 0, 'L', $fill);
                $this->Cell($w[5], 6, $row['uid'], 'LR', 0, 'L', $fill);
                $this->Cell($w[6], 6, $row['attd'], 'LR', 0, 'L', $fill);
                $this->Cell($w[7], 6, $row['tmz'] , 'LR', 0, 'L', $fill);
                $this->Ln();
                $fill=!$fill;
        }
        $this->Cell(array_sum($w), 0, '', 'T');
    }
}

// create new PDF document
$pdf = new MYPDF(PDF_PAGE_ORIENTATION, PDF_UNIT, PDF_PAGE_FORMAT, true, 'UTF-8', false);

// set document information
$pdf->SetCreator(PDF_CREATOR);
$pdf->SetAuthor('Niraj Bhagchandani');
$pdf->SetTitle('Atmiya University');
$pdf->SetSubject('26th Jivan Vidhya Samelan - 2024 Registration Details');
$pdf->SetKeywords('26thJV, JV, Jivan Vidhya, 26th, 26th Jivan Vidhya');

// set default header data
$pdf->SetHeaderData(PDF_HEADER_LOGO, PDF_HEADER_LOGO_WIDTH, PDF_HEADER_TITLE.' 011', PDF_HEADER_STRING);

// set header and footer fonts
$pdf->setHeaderFont(Array(PDF_FONT_NAME_MAIN, '', PDF_FONT_SIZE_MAIN));
$pdf->setFooterFont(Array(PDF_FONT_NAME_DATA, '', PDF_FONT_SIZE_DATA));

// set default monospaced font
$pdf->SetDefaultMonospacedFont(PDF_FONT_MONOSPACED);

// set margins
$pdf->SetMargins(PDF_MARGIN_LEFT, PDF_MARGIN_TOP, PDF_MARGIN_RIGHT);
$pdf->SetHeaderMargin(PDF_MARGIN_HEADER);
$pdf->SetFooterMargin(PDF_MARGIN_FOOTER);

// set auto page breaks
$pdf->SetAutoPageBreak(TRUE, PDF_MARGIN_BOTTOM);

// set image scale factor
$pdf->setImageScale(PDF_IMAGE_SCALE_RATIO);

// set some language-dependent strings (optional)
if (@file_exists(dirname(__FILE__).'/lang/eng.php')) {
    require_once(dirname(__FILE__).'/lang/eng.php');
    $pdf->setLanguageArray($l);
}

// ---------------------------------------------------------

// set font
$pdf->SetFont('times', '', 11);

// add a page
$pdf->AddPage();

// column titles
//$header = array('Country', 'Capital', 'Area (sq km)', 'Pop. (thousands)');
$t_head[] = "SNo.";
//$t_head[] .= "Registration No.";
$t_head[] .= "GID";
$t_head[] .= "Name of Participant";
$t_head[] .= "Gen";
$t_head[] .= "Age";
$t_head[] .= "Mobile";
$t_head[] .= "P/A";
$t_head[] .= "Reg Time";
$w = $t_head;
$header = $t_head;

// data loading
$data = $pdf->LoadData();

// print colored table
$pdf->ColoredTable($header, $data);
//print_r($data);
// ---------------------------------------------------------

//close and output PDF document
ob_end_clean();
$pdf->Output('example_011.pdf', 'I');

//============================================================+
// END OF FILE
//============================================================+