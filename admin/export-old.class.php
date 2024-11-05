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
        $this->tableHeader();
        $j=1;
        $html='';
        foreach ($data as $row) {
            $html .= '<tr>';
            //8,22,65,10,10,24,8,38
            $html .= '<td width="8%">' . $j++ . '</td>';
            $html .= '<td width="10%">' . $row['group_id'] . '</td>';
            $html .= '<td width="15%">' . $row['participant'] . '</td>';
            $html .= '<td width="10%">' . $row['gender'] . '</td>';
            $html .= '<td width="10%">' . $row['age'] . '</td>';
            $html .= '<td width="10%">' . $row['uid'] . '</td>';
            $html .= '<td width="8%">' . $row['attd'] . '</td>';
            $html .= '<td width="20%">' . $row['tmz'] . '</td>';
            $html .= '</tr>';
        }
        $html .= '</table>';
        
        // Print the table content (data rows)
        $pdf->writeHTML($html, true, false, false, false);
    }
    public function tableHeader() {
        // Set font
        $this->SetFont('times', 'B', 12);

        // Define the table header
        //8,22,65,10,10,24,8,38
        $headerHTML = '
        <table border="1" cellpadding="4">
            <tr>
                <th width="8%">Column 1</th>
                <th width="10%">Column 2</th>
                <th width="15%">Column 3</th>
                <th width="10%">Column 4</th>
                <th width="10%">Column 5</th>
                <th width="10%">Column 6</th>
                <th width="8%">Column 7</th>
                <th width="20%">Column 8</th>
            </tr>
        </table>';

        // Add the header
        $this->writeHTML($headerHTML, true, false, false, false);
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
$pdf->ColoredTable($pdf, $data);
//print_r($data);
// ---------------------------------------------------------

//close and output PDF document
ob_end_clean();
$pdf->Output('example_011.pdf', 'I');

//============================================================+
// END OF FILE
//============================================================+