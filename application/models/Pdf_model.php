<?php
class Pdf_model extends CI_Model {

	public function printsalary($data) {
		$pdf = new FPDF('P','mm',[215.9, 330.2]);

		/* $data = <<<JSN
[
    {
        "EmployeeName": "Rizelle Joyce Fernandez Macalino",
        "StartDate": "February 29, 2020",
        "EndDate": "March 06, 2020",
        "BasicRate": "400",
        "DailyRate": "400",
        "Additionals": [],
        "TotalDays": "0",
        "WkAmountDays": "0",
        "Additional": "0",
        "GrossPay": "0",
        "Deduction": null,
        "Vale": "0",
        "AdvanceVale": "0",
        "Deductions": [],
        "TotalDeduction": 0,
        "NetPay": 0
    },
    {
        "EmployeeName": "Gian Lorenzo Abano",
        "StartDate": "February 29, 2020",
        "EndDate": "March 06, 2020",
        "BasicRate": "550",
        "DailyRate": "550",
        "Additionals": [],
        "TotalDays": "6",
        "WkAmountDays": "3300",
        "Additional": "0",
        "GrossPay": "3300",
        "Deduction": "0",
        "Vale": "250",
        "AdvanceVale": "0",
        "Deductions": [
            {
                "PayName": "deduction_test",
                "PayAmount": "500"
            }
        ],
        "TotalDeduction": 750,
        "NetPay": 2550
    },
    {
        "EmployeeName": "Gian Lorenzo Abano",
        "StartDate": "February 29, 2020",
        "EndDate": "March 06, 2020",
        "BasicRate": "550",
        "DailyRate": "550",
        "Additionals": [],
        "TotalDays": "6",
        "WkAmountDays": "3300",
        "Additional": "0",
        "GrossPay": "3300",
        "Deduction": "0",
        "Vale": "250",
        "AdvanceVale": "0",
        "Deductions": [
            {
                "PayName": "deduction_test",
                "PayAmount": "500"
            }
        ],
        "TotalDeduction": 750,
        "NetPay": 2550
    }
]
JSN; */

		$pdf->setTitle("Pay Period [".$data["Data"][0]["StartDate"]." - ".$data["Data"][0]["EndDate"]."]");

		$title_size = 14;
		$font_size = 8.5;

		$spacing_superfar = 10;
		$spacing_far = 4.5;
		$spacing_middle = 3.5;
		$spacing_near = 2.5;


		$pdf->AddPage("p");
		$dynamics = 0;
		$groupc = 1;
		for ($d = 0; $d < count($data["Data"]); $d++) {
			$employee = $data["Data"][$d];
			$pdf->Ln($spacing_far);
			$pdf->SetFont('Arial','',$title_size);
			// $pdf->Write(0, utf8_decode($data["Info"]["BusinessName"]), null);
			$pdf->MultiCell(200, 4, utf8_decode($data["Info"]["BusinessName"]), 0, 'l');
			$pdf->SetFont('Arial','',$font_size);
			$pdf->Ln(1);
			// $pdf->Write(0, utf8_decode($data["Info"]["BusinessAddress"]));
			$pdf->MultiCell(200, 4, utf8_decode($data["Info"]["BusinessAddress"]), 0, 'l');
			$pdf->Ln($spacing_middle);
			$pdf->Write(0, "Pay Period: ".$employee["StartDate"]." - ".$employee["EndDate"]);
			$pdf->Ln($spacing_middle);
			$pdf->Write(0, "Pay Slip");
			$pdf->Ln($spacing_far);
			$pdf->SetFont('Arial','B',$font_size);
			$pdf->Write(0, "Name: " . utf8_decode($employee["EmployeeName"]));
			$pdf->SetFont('Arial','',$font_size);
			$pdf->Ln($spacing_far);
			// left side

			$x = $pdf->getX();
			$y = $pdf->getY();
			$pdf->Cell(20, 0, "Total Daily Salary");
			$pdf->Cell(20, 0, ":", 0, 0, "C");
			$pdf->Cell(20, 0, number_format($employee["DailyRate"], 2), 0, 0, "R");
			$pdf->Ln($spacing_middle);
			$pdf->Cell(20, 0, "Rate");
			$pdf->Cell(20, 0, ":", 0, 0, "C");
			$pdf->Cell(20, 0,  number_format($employee["BasicRate"], 2), 0, 0, "R");
			

			foreach ($employee["Additionals"] as $additional) {
				$pdf->Ln($spacing_middle);

				$pdf->Cell(20, 0, utf8_decode($additional["PayName"]));
				$pdf->Cell(20, 0,  ":", 0, 0, "C");
				$pdf->Cell(20, 0, number_format($additional["PayAmount"], 2), 0, 0, "R");
			}

			

			$pdf->Ln($spacing_middle);
			$pdf->Cell(20, 0, "Working Days");
			$pdf->Cell(20, 0, ":", 0, 0, "C");
			$pdf->Cell(20, 0, number_format($employee["TotalDays"], 1), 0, 0, "R");
			$pdf->Ln($spacing_near);
			$pdf->Cell(60, 0, "-------------------------", 0, 0, "R");
			$pdf->Ln($spacing_near);
			$pdf->Cell(60, 0, number_format($employee["WkAmountDays"], 2), 0, 0, "R");
			$pdf->Ln($spacing_near);
			$pdf->Cell(20, 0, "Overtime");
			$pdf->Cell(20, 0, ":", 0, 0, "C");
			$pdf->Cell(20, 0,  number_format($employee["Additional"], 2), 0, 0, "R");
			$pdf->Ln($spacing_near);
			$pdf->Cell(60, 0, "-------------------------", 0, 0, "R");
			$pdf->Ln($spacing_middle);
			$pdf->SetFont('Arial','B',$font_size);
			$pdf->Cell(20, 0, "Gross Pay");
			$pdf->Cell(20, 0, ":", 0, 0, "C");
			$pdf->Cell(20, 0,  number_format($employee["GrossPay"], 2), 0, 0, "R");

			$x_after = $pdf->getX();
			$y_after = $pdf->getY();


			// deduction and net right
			$pdf->setY($y);
			$pdf->setX($x + 80);
			$pdf->SetFont('Arial','IB',$font_size);
			$pdf->Write(0, "Deductions");
			$pdf->SetFont('Arial','',$font_size);
			$pdf->Ln($spacing_far);
			$pdf->setX($x + 80);
			$pdf->Cell(20, 0, "Wed Vale");
			$pdf->Cell(20, 0, ":", 0, 0, "C");
			$pdf->Cell(20, 0, number_format($employee["Vale"], 2), 0, 0, "R");
			$pdf->Ln($spacing_middle);
			$pdf->setX($x + 80);
			$pdf->Cell(20, 0, "Advance Vale");
			$pdf->Cell(20, 0, ":", 0, 0, "C");
			$pdf->Cell(20, 0, number_format($employee["AdvanceVale"], 2), 0, 0, "R");
			$pdf->Ln($spacing_middle);

			$pdf->setX($x + 80);
			$pdf->Cell(20, 0, "Undertime");
			$pdf->Cell(20, 0, ":", 0, 0, "C");
			$pdf->Cell(20, 0, number_format($employee["Deduction"], 2), 0, 0, "R");


			foreach ($employee["Deductions"] as $deduction) {
				$pdf->Ln($spacing_middle);
				$pdf->setX($x + 80);
				$pdf->Cell(20, 0, utf8_decode($deduction["PayName"]));
				$pdf->Cell(20, 0, ":", 0, 0, "C");
				$pdf->Cell(20, 0, $deduction["PayAmount"], 0, 0, "R");

			}

			$pdf->Ln($spacing_near);
			$pdf->setX($x + 80);
			$pdf->Cell(60, 0, "-------------------------", 0, 0, "R");
			$pdf->Ln($spacing_near);
			$pdf->setX($x + 80);
			$pdf->SetFont('Arial','B',$font_size);
			$pdf->Cell(20, 0, "Total Deductions:");
			$pdf->Cell(20, 0, ":", 0, 0, "C");
			$pdf->Cell(20, 0, number_format($employee["TotalDeduction"], 2), 0, 0, "R");
			$pdf->SetFont('Arial','',$font_size);
			$pdf->Ln($spacing_near);
			$pdf->setX($x + 80);
			$pdf->Cell(60, 0, "-------------------------", 0, 0, "R");

			$pdf->Ln($spacing_near);
			$pdf->setX($x + 80);
			$pdf->SetFont('Arial','B', $font_size);
			$pdf->Cell(20, 0, "Net Pay:");
			$pdf->Cell(20, 0, ":", 0, 0, "C");
			$pdf->Cell(20, 0, number_format($employee["NetPay"], 2), 0, 0, "R");
			$pdf->SetFont('Arial','',$font_size);

			$pdf->Ln($spacing_near);
			$pdf->setX($x + 80);
			$pdf->Cell(60, 0, "-------------------------", 0, 0, "R");


			$x_right_after = $pdf->getX();
			$y_right_after = $pdf->getY();

			if ($x_after > $x_right_after)
				$pdf->setX($x_after);
			else
				$pdf->setX($x_right_after);
			
			if ($y_after > $y_right_after)
				$pdf->setY($y_after);
			else
				$pdf->setY($y_right_after);
			


			$pdf->Ln($spacing_far);
			$pdf->Cell(60, 0, "Received By:", 0, 0, "L");
			$pdf->Cell(60, 0, "Date: ", 0, 0, "L");
			$pdf->Ln($spacing_far);
			$pdf->Cell(60, 0, "_________________________", 0, 0, "L");
			$pdf->Cell(60, 0, "_________________________", 0, 0, "L");
		
			$pdf->Ln($spacing_superfar);
			$pdf->Cell(100, 0, "Prepared By: ". utf8_decode($data["Info"]["PrintPreparedBy"]), 0, 0, "L");
			$pdf->Cell(100, 0, "Checked By: " . utf8_decode($data["Info"]["PrintCheckedBy"]), 0, 0, "L");
			$pdf->Ln($spacing_far);
			$pdf->Cell(100, 0, utf8_decode($data["Info"]["PrintPreparedByPosition"]), 0, 0, "l");
			$pdf->Cell(100, 0, utf8_decode($data["Info"]["PrintCheckedByPosition"]), 0, 0, "L");

			$pdf->Ln(5);


			$additionals = count($employee["Additionals"]);
			$deductions = count($employee["Deductions"]);
			$dynamics+= ($additionals > $deductions ? $additionals : $deductions);
			$total_dynamics = $dynamics;
			
			if (isset($data["Data"][$d+1])) {
				$next_addcount = count($data["Data"][$d+1]["Additionals"]);
				$next_dedcount = count($data["Data"][$d+1]["Deductions"]);
				$total_dynamics = $dynamics + ($next_addcount > $next_dedcount ? $next_addcount : $next_dedcount);
			}

			if ($total_dynamics > 17) {
				$dynamics = 0;
				$pdf->addPage("p");
				$groupc = 1;
			} else if ($groupc == 3) {
				$dynamics = 0;
				if ($d !== count($data["Data"])-1)
					$pdf->addPage("p");
				$groupc = 1;
			} else {
				$pdf->Write(0, "______________________________________________________________________________________________________________________________________________________");
				$pdf->Ln(5);
				$groupc++;
			}


			
		}
		
		return $pdf->Output('S');
	}

	public function printweeklydata($wkdata) {

		/* $wkdata = [
			"ProjectName"=>"CUENCA NATIONAL HIGH SCHOOL BATANGAS PROVINCE",
			"StartDate"=>"November 1, 2019",
			"CurrentDate"=>"February 5, 2020",
			"WeekDateRange"=>"November 1, 2019 - January 15, 2020",
			"WkAmountTotal"=>"P. 30,000.00",
			"ValeTotal"=>"P. 10,000.00",
			"AdvanceValeTotal"=>"P. 10,000.00",
			"ReceivedAmountTotal"=>"P. 10,000.00",
			"PreparedBy"=>"Gian Lorenzo Abano",
			"CheckedBy"=>"Rizelle Joyce Macalino",
			"ReleasedBy"=>"Renz Greyson Abano",
			"RowData"=>[ // saturday start

				[
					"Name"=>"Gian Lorenzo Abano",
					"Rate"=>"P 550.00", // center
					"M"=>"0", // center hanggang sunday
					"T"=>"0", //with zero
					"W"=>"0",
					"Th"=>"0",
					"F"=>"0",
					"S"=>"0",
					"Su"=>"0", // red name
					"TotalDays"=>"0", // center
					"Additional"=>"0", //right
					"WkAmount"=>"0", // right
					"Vale"=>"0",
					"AdvancedVale"=>"0",
					"ReceivedAmount"=>"0", // right // numbering center
					"Remarks"=>""
				]
			]
		]; */



		$pdf = new PDF('P','mm',[215.9, 330.2]);
		$pdf->setTitle($wkdata["ProjectName"] . " | " . $wkdata["WeekDateRange"]);
		$pdf->AliasNbPages();
		$pdf->numbering = false;
		$pdf->SetAutoPageBreak(false);
		$pdf->SetDisplayMode("fullwidth", "single");
		$pdf->AddPage("l");
		// $pdf->Image($_SERVER['DOCUMENT_ROOT'].'/basbacio-payroll/assets/img/letterhead.png',50,5,180);
		// Arial bold 15
		$fontSize = 12;
		$pdf->SetFont('Arial','',$fontSize);
		// Move to the right
		$pdf->Ln(14);

		$spacing = 6;
		$pdf->Write(0, "Project Name: ".utf8_decode($wkdata["ProjectName"]));

		$pdf->setX(120);
		if ($wkdata["Info"]["PrintMode"] == 1)
			$pdf->Write(0, "Received Amount (Total): ". $wkdata["ReceivedAmount"]);
		else
			$pdf->Write(0, "Received Amount (Vale): ". $wkdata["ValeTotal"]);

		$pdf->setX(250);
		$pdf->Write(0, "Start: ".$wkdata["StartDate"]);
		$pdf->Ln($spacing);
		$pdf->Write(0,"Week Date: ".$wkdata["WeekDateRange"]);
		$pdf->Ln($spacing);
		$pdf->Write(0, "Date: ".$wkdata["CurrentDate"]);
		$pdf->Ln($spacing);


		$pdf->setY($pdf->getY()-7);
		$pdf->setX(220);
		$pdf->Write(0, "_______________________________");
		$pdf->Ln($spacing);
		$pdf->setX(228);
		$pdf->Write(0,"Signature over Printed Name");
		$pdf->Ln($spacing);
	


		
		/*$x = $pdf->getX();
		$y = $pdf->getY();

		$pdf->setX(100);
		$pdf->setY(20);

		if ($wkdata["Info"]["PrintMode"] == 1)
			$pdf->Write(0, "Received Amount (Total): ". $wkdata["ReceivedAmount"]);
		else
			$pdf->Write(0, "Received Amount (Vale): ". $wkdata["ValeTotal"]);


		$pdf->setX(150);
		$pdf->setY(20);

		$pdf->Write(0, "Start: ".$wkdata["StartDate"]);
		$pdf->Ln(5);
		
		$pdf->Write(0, "_____________________________");
		$pdf->Ln(5);
		$pdf->Write(0,"Signature over Printed Name");
		$pdf->Ln(5);

		$pdf->setX($x);
		$pdf->setY($y);*/
		
		

				// left, top left position, width, top right position
		
		// check if wed or sat
		$datetime = DateTime::createFromFormat("Y-m-d", $wkdata["WeekStartDate"]);
		$day_num = $datetime->format("w");
		$total_m = 0;
		$total_t = 0;
		$total_w = 0;
		$total_th = 0;
		$total_f = 0;
		$total_s = 0;
		$total_su = 0;
		if ($day_num == 3) {
			$header = array(
			array(61, 4, 1, "Name"), 
			array(20, 4, 1, ""),
			array(18, 4, 1, "W"), 
			array(18, 4, 1, "TH"), 
			array(18, 4, 1, "F"),
			array(11, 4, 1, "Total Days"), 
			array(17, 4, 1, "+"), 
			array(17, 4, 1, "-", ["170", "57", "57"]), 
			array(20, 4, 1, "Wk Amount"), 
			array(20, 4, 1, "Vale", ["170", "57", "57"]), 
			array(20, 4, 1, "Advanced Vale", ["170", "57", "57"]), 
			array(20, 4, 1, "Received Amount"),
			array(10, 4, 1, "#"),
			array(50, 4, 1, "Remarks"));



			// max 24
			$data = array();
			$c = 1;
			foreach ($wkdata["RowData"] as $row) {
				$data[] = array(
						array(61, 4, 1, utf8_decode($row["Name"]), "L"), 
						array(20, 4, 1, number_format($row["Rate"], 2), "C"),
						array(18, 4, 1, $row["W"], "C"), 
						array(18, 4, 1, $row["Th"], "C"),
						array(18, 4, 1, $row["F"], "C"), 
						array(11, 4, 1, $row["TotalDays"], "C"), 
						array(17, 4, 1, number_format($row["Additional"], 2), "R"), 
						array(17, 4, 1, number_format($row["Deduction"], 2), "R", ["170", "57", "57"]), 
						array(20, 4, 1, number_format($row["WkAmount"], 2), "R"), 
						array(20, 4, 1, number_format($row["Vale"], 2), "R", ["170", "57", "57"]), 
						array(20, 4, 1, number_format($row["AdvanceVale"], 2), "R", ["170", "57", "57"]), 
						array(20, 4, 1, number_format($row["ReceivedAmount"], 2), "R"), 
						array(10, 4, 1, $c, "C"), 
						array(50, 4, 1, $row["Remarks"], "L")
					);

				if ((float) $row["W"] > 0)
					$total_w++;
				if ((float) $row["Th"] > 0)
					$total_th++;
				if ((float) $row["F"] > 0)
					$total_f++;
				$c++;
			}
		} elseif ($day_num == 6) {
			$header = array(
			array(61, 4, 1, "Name"), 
			array(20, 4, 1, ""), 
			array(14, 4, 1, "S"), 
			array(14, 4, 1, "Su"), 
			array(14, 4, 1, "M"), 
			array(14, 4, 1, "T"),
			array(11, 4, 1, "Total Days"), 
			array(17, 4, 1, "+"), 
			array(17, 4, 1, "-", ["170", "57", "57"]), 
			array(20, 4, 1, "Wk Amount"), 
			array(20, 4, 1, "Vale", ["170", "57", "57"]), 
			array(20, 4, 1, "Advanced Vale", ["170", "57", "57"]), 
			array(20, 4, 1, "Received Amount"),
			array(10, 4, 1, "#"),
			array(50, 4, 1, "Remarks"));



			// max 24
			$data = array();
			$c = 1;
			foreach ($wkdata["RowData"] as $row) {
				$data[] = array(
						array(61, 4, 1, utf8_decode($row["Name"]), "L"), 
						array(20, 4, 1, number_format($row["Rate"], 2), "C"),
						array(14, 4, 1, $row["S"], "C"), 
						array(14, 4, 1, $row["Su"], "C", ["170", "57", "57"]), 
						array(14, 4, 1, $row["M"], "C"), 
						array(14, 4, 1, $row["T"], "C"),
						array(11, 4, 1, $row["TotalDays"], "C"), 
						array(17, 4, 1, number_format($row["Additional"], 2), "R"), 
						array(17, 4, 1, number_format($row["Deduction"], 2), "R", ["170", "57", "57"]), 
						array(20, 4, 1, number_format($row["WkAmount"], 2), "R"), 
						array(20, 4, 1, number_format($row["Vale"], 2), "R", ["170", "57", "57"]), 
						array(20, 4, 1, number_format($row["AdvanceVale"], 2), "R", ["170", "57", "57"]), 
						array(20, 4, 1, number_format($row["ReceivedAmount"], 2), "R"), 
						array(10, 4, 1, $c, "C"), 
						array(50, 4, 1, $row["Remarks"], "L")
					);
				if ((float) $row["S"] > 0)
					$total_s++;
				if ((float) $row["Su"] > 0)
					$total_su++;
				if ((float) $row["M"] > 0)
					$total_m++;
				if ((float) $row["T"] > 0)
					$total_t++;
				$c++;
			}
		}

		

		$pdf->Ln(5);
		$pdf->BasicTable($header, $data);

		$myY = $pdf->page_height;
		if ((int) $myY > 145) {
	  		$pdf->addPage("l");
	  		$pdf->setXY(4, 20);
	  	}
	  	$pdf->setX(4);
		$pdf->SetFillColor(255, 248, 241);
		$pdf->Cell(61, 6, "Total", 1, 0, "l", true);
		$pdf->Cell(20, 6, "", 1, 0, "l", true);


		$pdf->setTextColor("0", "0", "0");

		if ($day_num == 3) {
			$pdf->Cell(18, 6, $total_w, 1, 0, "C", true);
			$pdf->Cell(18, 6, $total_th, 1, 0, "C", true);
			$pdf->Cell(18, 6, $total_f, 1, 0, "C", true);

		} elseif ($day_num == 6) {
			$pdf->Cell(14, 6, $total_s, 1, 0, "C", true);
			$pdf->setTextColor("170", "57", "57");
			$pdf->Cell(14, 6, $total_su, 1, 0, "C", true);
			$pdf->setTextColor("0", "0", "0");
			$pdf->Cell(14, 6, $total_m, 1, 0, "C", true);
			$pdf->Cell(14, 6, $total_t, 1, 0, "C", true);
		}
	



		$pdf->Cell(11, 6, "", 1, 0, "l", true);
		$pdf->Cell(17, 6, "", 1, 0, "l", true);
		$pdf->Cell(17, 6, "", 1, 0, "l", true);
		$pdf->Cell(20, 6, $wkdata["WkAmountTotal"], 1, 0, "R", true);
		$pdf->setTextColor("170", "57", "57");
		$pdf->Cell(20, 6, $wkdata["ValeTotal"], 1, 0, "R", true);
		$pdf->Cell(20, 6, $wkdata["AdvanceValeTotal"], 1, 0, "R", true);
		$pdf->setTextColor("0", "0", "0");
		$pdf->Cell(20, 6, $wkdata["ReceivedAmountTotal"], 1, 0, "R", true);
		$pdf->setTextColor("0", "0", "0");
		$pdf->Cell(10, 6, "", 1, 0, "l", true);
		$pdf->Cell(50, 6, "", 1, 0, "l", true);

		$pdf->SetFont('Arial','', $fontSize);
		$pdf->Ln(20);
		$pdf->Cell(100, 6, "Prepared By: ".utf8_decode($wkdata["Info"]["PrintPreparedBy"]), 0);
		$pdf->Cell(100, 6, "Checked By: ".utf8_decode($wkdata["Info"]["PrintCheckedBy"]), 0);
		$pdf->Cell(100, 6, "Released By: ".utf8_decode($wkdata["Info"]["PrintReleasedBy"]), 0);
		$pdf->Ln(5);
		$pdf->Cell(100, 6, utf8_decode($wkdata["Info"]["PrintPreparedByPosition"]), 0);
		$pdf->Cell(100, 6, utf8_decode($wkdata["Info"]["PrintCheckedByPosition"]), 0);
		$pdf->Cell(100, 6, utf8_decode($wkdata["Info"]["PrintReleasedByPosition"]), 0);

		$pdf->Ln(12);
		return $pdf->Output("S");
	}
}

require_once(APPPATH.'libraries/fpdf182/fpdf.php');

class PDF extends FPDF {
	public $page_height = 0;

	function drawTextBox($strText, $w, $h, $align='L', $valign='T', $border=true)
	{
	    $xi=$this->GetX();
	    $yi=$this->GetY();
	    
	    $hrow=$this->FontSize;
	    $textrows=$this->drawRows($w,$hrow,$strText,0,$align,0,0,0);
	    $maxrows=floor($h/$this->FontSize);
	    $rows=min($textrows,$maxrows);

	    $dy=0;
	    if (strtoupper($valign)=='M')
	        $dy=($h-$rows*$this->FontSize)/2;
	    if (strtoupper($valign)=='B')
	        $dy=$h-$rows*$this->FontSize;

	    $this->SetY($yi+$dy);
	    $this->SetX($xi);

	    $this->drawRows($w,$hrow,$strText,0,$align,false,$rows,1);

	    if ($border)
	        $this->Rect($xi,$yi,$w,$h);
	}

	function drawRows($w, $h, $txt, $border=0, $align='J', $fill=false, $maxline=0, $prn=0)
	{
	    $cw=&$this->CurrentFont['cw'];
	    if($w==0)
	        $w=$this->w-$this->rMargin-$this->x;
	    $wmax=($w-2*$this->cMargin)*1000/$this->FontSize;
	    $s=str_replace("\r",'',$txt);
	    $nb=strlen($s);
	    if($nb>0 && $s[$nb-1]=="\n")
	        $nb--;
	    $b=0;
	    if($border)
	    {
	        if($border==1)
	        {
	            $border='LTRB';
	            $b='LRT';
	            $b2='LR';
	        }
	        else
	        {
	            $b2='';
	            if(is_int(strpos($border,'L')))
	                $b2.='L';
	            if(is_int(strpos($border,'R')))
	                $b2.='R';
	            $b=is_int(strpos($border,'T')) ? $b2.'T' : $b2;
	        }
	    }
	    $sep=-1;
	    $i=0;
	    $j=0;
	    $l=0;
	    $ns=0;
	    $nl=1;
	    while($i<$nb)
	    {
	        //Get next character
	        $c=$s[$i];
	        if($c=="\n")
	        {
	            //Explicit line break
	            if($this->ws>0)
	            {
	                $this->ws=0;
	                if ($prn==1) $this->_out('0 Tw');
	            }
	            if ($prn==1) {
	                $this->Cell($w,$h,substr($s,$j,$i-$j),$b,2,$align,$fill);
	            }
	            $i++;
	            $sep=-1;
	            $j=$i;
	            $l=0;
	            $ns=0;
	            $nl++;
	            if($border && $nl==2)
	                $b=$b2;
	            if ( $maxline && $nl > $maxline )
	                return substr($s,$i);
	            continue;
	        }
	        if($c==' ')
	        {
	            $sep=$i;
	            $ls=$l;
	            $ns++;
	        }
	        $l+=$cw[$c];
	        if($l>$wmax)
	        {
	            //Automatic line break
	            if($sep==-1)
	            {
	                if($i==$j)
	                    $i++;
	                if($this->ws>0)
	                {
	                    $this->ws=0;
	                    if ($prn==1) $this->_out('0 Tw');
	                }
	                if ($prn==1) {
	                    $this->Cell($w,$h,substr($s,$j,$i-$j),$b,2,$align,$fill);
	                }
	            }
	            else
	            {
	                if($align=='J')
	                {
	                    $this->ws=($ns>1) ? ($wmax-$ls)/1000*$this->FontSize/($ns-1) : 0;
	                    if ($prn==1) $this->_out(sprintf('%.3F Tw',$this->ws*$this->k));
	                }
	                if ($prn==1){
	                    $this->Cell($w,$h,substr($s,$j,$sep-$j),$b,2,$align,$fill);
	                }
	                $i=$sep+1;
	            }
	            $sep=-1;
	            $j=$i;
	            $l=0;
	            $ns=0;
	            $nl++;
	            if($border && $nl==2)
	                $b=$b2;
	            if ( $maxline && $nl > $maxline )
	                return substr($s,$i);
	        }
	        else
	            $i++;
	    }
	    //Last chunk
	    if($this->ws>0)
	    {
	        $this->ws=0;
	        if ($prn==1) $this->_out('0 Tw');
	    }
	    if($border && is_int(strpos($border,'B')))
	        $b.='B';
	    if ($prn==1) {
	        $this->Cell($w,$h,substr($s,$j,$i-$j),$b,2,$align,$fill);
	    }
	    $this->x=$this->lMargin;
	    return $nl;
	}

	function Footer()
	{
	     $this->SetY(-15);
	    // Arial italic 8
	    $this->SetFont('Arial','I',8);
	    // Page number
	    $this->Cell(0,10,'Page '.$this->PageNo().'/{nb}',0,0,'C');
	}

	function BasicTable($header, $data)
	{
		$this->SetFont('Arial','',10);
		// Header
		$cur_col = 0;
		$this->setX(4);
		foreach($header as $col) {
			// $this->Cell($col[0],$col[1],$col[3],$col[2], '', "C");

			if (isset($col[4]))
				$this->SetTextColor($col[4][0], $col[4][1], $col[4][2]);
			else
				$this->SetTextColor("0", "0", "0");

			$x=$this->GetX();
        	$y=$this->GetY();
        	$w = $col[0];

        	$this->Rect($x, $y, $w, 10);
        	$this->setXY($x, $y);
			$this->drawTextBox($col[3], $col[0], 10, 'C', 'M', false);

			$this->SetXY($x+$w,$y);
			$cur_col++;
		}
		$this->Ln(10);
		// Data
		$cur_row = 1;
		$this->page_height = 0;
		foreach($data as $row)
		{
			$this->setX(4);
		  	$cur_col = 0;
		  	$dh = [];

	  		foreach ($row as $col) {
    			$calculateHeight = new self;
				$calculateHeight->addPage();
				$calculateHeight->setXY(0, 0);
				$calculateHeight->SetFont('Arial', '', 10);
				$calculateHeight->MultiCell($col[0], $col[1], $col[3], 0, $col[4], false);
				$dh[] = $calculateHeight->getY() + $col[1] - 2;
	  		}

		  	$cur_col = 0;
		  	$dhx = $dh;
		  	$dh = max($dh);
		  	$longer_index = array_search($dh, $dhx);

		   	if($dh > 25)
		   		$dh = 25;

		  	$this->page_height += $dh;
		  	if ($this->PageNo()==1)
		  		$max_height = 125;
		  	else
		  		$max_height = 168;

		  	if ($this->page_height + $dh > $max_height) {
		  		$this->addPage("l");
		  		$this->setXY(4, 20);
		  		$this->page_height = $dh;
		  	}
		  

			foreach($row as $col) {
				if (isset($col[5])) {
					$this->setTextColor($col[5][0], $col[5][1], $col[5][2]);
				} else
					$this->setTextColor("0", "0", "0");

				$x=$this->GetX();
	        	$y=$this->GetY();
	        	$w = $col[0];



	        	$this->Rect($x, $y, $w, $dh);
	        	$this->setXY($x, $y+1);

	        
				$this->drawTextBox($col[3], $col[0], $dh-2, $col[4], 'M', false);

				$this->SetXY($x+$w,$y);
				$cur_col++;
			}
			$this->Ln($dh);
			$cur_row++;
		}
	}
}
