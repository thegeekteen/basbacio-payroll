<?php
require('../fpdf.php');

class PDF extends FPDF
{
// Page header
function Header()
{
	// Logo
	$this->Image($_SERVER['DOCUMENT_ROOT'].'/basbacio-payroll/assets/img/letterhead.png',50,10,180);
	// Arial bold 15
	$this->SetFont('Arial','',10);
	// Move to the right
	$this->Ln(25);
	$this->Cell(40,6,"Project Name: DEFCON HARDWARE & SUPPLY",0,0);
	$this->Cell(150);
	$this->Cell(40,6,"Start: AUGUST 15, 2019",0,0);
	$this->Ln();
	$this->Cell(40,6,"Date: OCTOBER 25, 2019",0,0);
	$this->Cell(150);
	$this->Ln();
	$this->Cell(255,10,"_____________________________",0,0, "R");
	$this->Ln();
	$this->Cell(250,0,"Signature over Printed Name",0, 0, "R");
	$this->Ln();
	$this->Cell(40,-3,"Week Date: 12/16/19 - 12-21-19",0,0);
	$this->Ln(5);
	$this->text(140, 50, "Received Amount: Php. 1,000,000");
	
	
	// left, top left position, width, top right position
	$this->Line(5, 65, 280, 65);
	$this->Ln(12);
	
}

// Simple table
function BasicTable($header, $data)
{
	// Header
	foreach($header as $col)
		$this->Cell(40,7,$col,0);
	$this->Ln();
	// Data
	foreach($data as $row)
	{
		foreach($row as $col)
			$this->Cell(100,6,$col,0,0,'L');
		$this->Ln();
	}
}

// Page footer
function Footer()
{
	// Position at 1.5 cm from bottom
	$this->SetY(-15);
	// Arial italic 8
	$this->SetFont('Arial','I',8);
	// Page number
	$this->Cell(0,10,'Page '.$this->PageNo().'/{nb}',0,0,'C');
}
}

// Instanciation of inherited class
$pdf = new PDF();
$pdf->AliasNbPages();
$pdf->AddPage("L");
$pdf->SetFont('Times','',12);
for($i=1;$i<=40;$i++)
	$pdf->Cell(0,10,'Printing line number '.$i,0,1);
$pdf->Output();
?>
