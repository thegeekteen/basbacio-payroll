<?php

class Pdf_receipt_model {
	public function aggregates($data) {
	// spacing;;
	$title_size = 14;
	$spacing_far = 4.5;
	$font_size = 8.5;

	$pdf = new PDFReceipt('l','mm',[215.9, 330.2]);
	$pdf->setTitle("Payee - " . $data["receiptInfo"]["OwnerName"]);
	$pdf->AliasNbPages();
	$pdf->SetAutoPageBreak(false);
	$pdf->AddPage("p");
			$cData = $data;


			// Header for Receipt
			$pdf->Ln($spacing_far);
			$pdf->SetFont('Arial','',$title_size);
			// $pdf->Write(0, utf8_decode($data["Info"]["BusinessName"]), null);
			$pdf->MultiCell(200, 4, utf8_decode($data["Info"]["BusinessName"]), 0, "l");
			$pdf->SetFont('Arial','',$font_size);
			$pdf->Ln(1);
			$pdf->MultiCell(200, 4, utf8_decode($data["Info"]["BusinessAddress"]), 0, "l");
			// $pdf->Write(0, utf8_decode($data["Info"]["BusinessAddress"]));
			$pdf->Ln($spacing_far);
			$pdf->Ln($spacing_far);
			$pdf->Ln($spacing_far);
			// $pdf->Write(0, utf8_decode("Payment Voucher"));
			$pdf->SetFont('Arial','b',14);
			$pdf->Text(80, $pdf->getY(), utf8_decode("PAYMENT VOUCHER"));
			$pdf->SetFont('Arial','',$font_size);



			$pdf->Ln($spacing_far);
			$pdf->Ln($spacing_far);
			

			$pdf->Write(0, utf8_decode("Voucher No: " . $cData["receiptInfo"]["ReceiptNo"]));
			$pdf->Ln($spacing_far);
			$pdf->Write(0, utf8_decode("Date: " . date( 'M j, Y', strtotime($cData["receiptInfo"]["Date"]))));

			$pdf->SetFont('Arial','',10);
			$pdf->Ln($spacing_far);
			$pdf->Ln($spacing_far);

			$pdf->Write(0, utf8_decode("PAYEE    "));
			$pdf->Write(0, utf8_decode("[   "));

			$pdf->Write(0, utf8_decode($cData["receiptInfo"]["OwnerName"]));

			$pdf->Write(0, utf8_decode("    ]"));

			$pdf->Ln($spacing_far);
			$pdf->Ln($spacing_far);


			$pdf->SetFont('Arial','',7);

			// DATA

			$pdf->Rect($pdf->getX()-6, $pdf->getY()-3, 205, 5);
			$pdf->Cell(150, 0, "PARTICULARS", 0, 0, 'C');
			$pdf->setX(178);
			$pdf->Cell(35, 0, "AMOUNT", 0, 0, 'C');
			$pdf->Ln(5);


			$header = [
				["text"=>"Date", "width"=>"15", "height"=>"4", "border"=>null, "align"=>"C", "fill"=>null],
				["text"=>"Payee", "width"=>"20", "height"=>"4", "border"=>null, "align"=>"C", "fill"=>null],
				["text"=>"PlateNo", "width"=>"15", "height"=>"4", "border"=>null, "align"=>"C", "fill"=>null],
				["text"=>"DRNo", "width"=>"15", "height"=>"4", "border"=>null, "align"=>"C", "fill"=>null],
				["text"=>"Item", "width"=>"15", "height"=>"4", "border"=>null, "align"=>"C","fill"=>null],
				["text"=>"Length", "color"=>"0, 0, 139", "width"=>"15", "height"=>"4", "border"=>null, "align"=>"C","fill"=>null],
				["text"=>"Width", "color"=>"0, 0, 139", "width"=>"15", "height"=>"4", "border"=>null, "align"=>"C", "fill"=>null],
				["text"=>"Height", "color"=>"0, 0, 139", "width"=>"15", "height"=>"4", "border"=>null, "align"=>"C", "fill"=>null],
				["text"=>"Volume", "width"=>"15", "height"=>"4", "color"=>"0, 100, 0", "border"=>null, "align"=>"C", "fill"=>null],
				["text"=>"Difference", "width"=>"15", "color"=>"139, 0, 0", "height"=>"4", "border"=>null, "align"=>"C", "fill"=>null],
				["text"=>"UnitPrice", "width"=>"25", "height"=>"4", "border"=>null, "align"=>"C", "fill"=>null],
				["text"=>"", "width"=>"25", "height"=>"4", "border"=>null, "align"=>"R", "fill"=>null],
			];

			$values = [];
			foreach ($cData["rowData"] as $row) {

					$theRow = array();
					$theRow[] = [ "text"=>$row["Date"], "width"=>"15","height"=>"4", "border"=>null, "align"=>"C", "fill"=>null];
					$theRow[] = ["text"=>$row["OwnerName"], "width"=>"20", "height"=>"4", "border"=>null, "align"=>"C", "fill"=>null];
					$theRow[] = ["text"=>$row["PlateNo"], "width"=>"15", "height"=>"4", "border"=>null, "align"=>"C", "fill"=>null];
					$theRow[] = ["text"=>$row["DRNo"], "width"=>"15", "height"=>"4", "border"=>null, "align"=>"C", "fill"=>null];
					$theRow[] = ["text"=>$row["Item"], "width"=>"15", "height"=>"4", "border"=>null, "align"=>"C", "fill"=>null];
					$theRow[] = ["text"=>number_format($row["Length"], 2), "color"=>"0, 0, 139", "width"=>"15", "height"=>"4", "border"=>null, "align"=>"C", "fill"=>null];
					$theRow[] = ["text"=>number_format($row["Width"], 2), "color"=>"0, 0, 139", "width"=>"15", "height"=>"4", "border"=>null, "align"=>"C", "fill"=>null];
					$theRow[] = ["text"=>number_format($row["Height"], 2), "color"=>"0, 0, 139", "width"=>"15", "height"=>"4", "border"=>null, "align"=>"C", "fill"=>null];
					$theRow[] = ["text"=>number_format($row["Volume"], 2), "color"=>"0, 100, 0", "width"=>"15", "height"=>"4", "border"=>null, "align"=>"C", "fill"=>null ];
					$theRow[] = ["text"=>"(".$row["Difference"].")", "color"=>"139, 0, 0", "width"=>"15", "height"=>"4", "border"=>null, "align"=>"C", "fill"=>null
					];
					$theRow[] = ["text"=>"P. " . number_format($row["UnitPrice"], 2), "width"=>"23", "height"=>"4", "border"=>null, "align"=>"C", "fill"=>null];
					$theRow[] = ["text"=>"P. " . number_format($row["TotalAmount"], 2), "width"=>"25", "height"=>"4", "border"=>null, "align"=>"R", "fill"=>null
					];
					$values[] = $theRow;

			}

			
			

			$last_total_height = $pdf->drawRows($header, $values);

			$current_y = $pdf->GetY();
			$current_x = $pdf->GetX();

			$pdf->Line(4, $pdf->GetY(), 209, $pdf->GetY());


			$pdf->setTextColor(0, 100, 0);


			$pdf->setY($current_y-12);
			$pdf->setX(127);
			$pdf->MultiCell(20, 2.5, "------------------\r\n".number_format($cData["receiptInfo"]["TotalVolume"], 2)."\r\n------------------", 0, 'C', false);
			$pdf->setTextColor(0, 0, 0);

			$pdf->setY($current_y-12);
			$pdf->setX(183);
			$pdf->MultiCell(25, 2.5, "--------------------------\r\nP. " . number_format($cData["receiptInfo"]["TotalAmount"], 2)."\r\n--------------------------", 0, 'R', false);



			if ($pdf->PageNo() > 1 && $last_total_height > 230)
				$pdf->AddPage("p");
			else if ($pdf->PageNo() == 1 && $last_total_height > 180)
				$pdf->AddPage("p");


		    $pdf->SetFont('Arial','',10);
		    // add form
		    $pdf->Ln(10);
		    $pdf->setX(3);
		    $pdf->Write(0, "PESOS: .............................................................................................................................................................................................");
		    $pdf->Ln(5);
		    $pdf->setX(3);
		    $pdf->Write(0, ".................................................................................................................................................................................................................");
		    $pdf->Ln(5);
		    $pdf->setX(3);
		    $pdf->Write(0, ".................................................................................................................................................................................................................");
		    $pdf->Ln(6);
		    $pdf->setX(3);
		    $pdf->Write(0, "Bank: ..................................................................................................................................... Check No: ..........................................");

		    $pdf->Ln(10);
		    $pdf->setX(3);
		    $pdf->Write(0,"Received Payment By:");

		    $pdf->Ln(6);
		    $pdf->setX(3);
		    $pdf->Write(0, "..............................................................................................................................................................................................................");

		     $pdf->Ln(10);
		    $pdf->setX(3);
		    $pdf->Write(0, "Prepared By: ................................................................................ Approved By: ...............................................................................");

	return $pdf->Output('S');

	}
}

require_once(APPPATH.'libraries/fpdf182/fpdf.php');

class PDFReceipt extends FPDF {


	public function Footer()
	{
	     $this->SetY(-15);
	    // Arial italic 8
	    $this->SetFont('Arial','I',8);
	    // Page number
	    $this->Cell(0,10,'Page '.$this->PageNo().'/{nb}',0,0,'C');
	}

	public function drawRows($header, $data) {

		// start line
		$this->Line(4, $this->GetY()-3, 4, $this->getY()+10);
		$this->Line(182, $this->GetY()-8, 182, $this->getY()+10);
		$this->Line(209, $this->GetY()-8, 209, $this->getY()+10);

		// Set default left:
		$this->setX(5);

		// Set Default Font
		$this->SetFont('Arial','',7);

		// loop through header and render...
		foreach ($header as $column) {
			// get current XY;;
			$offset_x = $this->GetX();
			$offset_y = $this->GetY();

			// MultiCell Format:
			// $this->MultiCell($width, $height, $text, $border, $align, $fill);

			// set color
			if (!isset($column["color"]) || empty($column["color"]))
				$column["color"] = "0, 0, 0";

			$color = explode(",", $column["color"]);
			$this->setTextColor(trim($color[0]), trim($color[1]), trim($color[2]));

			$this->MultiCell($column["width"], $column["height"], $column["text"], $column["border"], $column["align"], $column["fill"]);

			// move to another cell
			$this->setXY($offset_x + $column["width"], $offset_y);
		}

		$this->Ln(5);

		// loop through data
		$total_height = 0;
		foreach ($data as $row) {

			// go back to left;
			$this->setX(5);

			$dh = [];
			// get highest height for multi cell
			foreach ($row as $rowData) {
				$calculateHeight = new self;
				$calculateHeight->addPage();
				$calculateHeight->setXY(0, 0);
				$calculateHeight->SetFont('Arial', '', 7);
				$calculateHeight->MultiCell($rowData["width"], $rowData["height"], $rowData["text"], $rowData["border"], $rowData["align"], $rowData["fill"]);
				$dh[] = $calculateHeight->getY() + $rowData["height"] - 2;
			}

			$highest_height = max($dh);
			$total_height += $highest_height;

			foreach ($row as $rowData) {
				// get current XY;;
				$offset_x = $this->GetX();
				$offset_y = $this->GetY();

				// MultiCell Format:
				// $this->MultiCell($width, $height, $text, $border, $align, $fill);

				// set color
				if (!isset($rowData["color"]) || empty($rowData["color"]))
					$rowData["color"] = "0, 0, 0";

				$color = explode(",", $rowData["color"]);
				$this->setTextColor(trim($color[0]), trim($color[1]), trim($color[2]));
				

				$this->MultiCell($rowData["width"], $rowData["height"], $rowData["text"], $rowData["border"], $rowData["align"], $rowData["fill"]);

				// move to another cell
				$this->setXY($offset_x + $rowData["width"], $offset_y);
			}

			// add lines:
			$this->Line(4, $this->GetY(), 4, $this->getY()+$highest_height);
			$this->Line(182, $this->GetY(), 182, $this->getY()+$highest_height);
			$this->Line(209, $this->GetY(), 209, $this->getY()+$highest_height);

			// create new ln from highest row
			$this->Ln($highest_height);

			// check if overflow
			if ($this->PageNo() == 1)
				$max_height = 235;
			else
				$max_height = 295;

			if ($total_height > $max_height) {
				$this->AddPage("p");
				$total_height = 0;
			}



		}

		$this->Line(4, $this->GetY(), 4, $this->getY()+12);
		$this->Line(182, $this->GetY(), 182, $this->getY()+12);
		$this->Line(209, $this->GetY(), 209, $this->getY()+12);
		$this->setY($this->getY()+12);

		return $total_height;
	
	}
}
