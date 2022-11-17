<?php

class Viewproject_model extends CI_Model {

	var $sql;

	public function __construct() {
		$this->load->database();
		$this->sql =& $this->db->conn_id;
	}

	public function custom_query($query) {
		return $this->sql->exec($query);
	}

	public function lockunlock($projectweeklyid, $password) {
		$this->load->model("config_model");
		$cfg = $this->config_model->getconfig();

		try {
			$this->sql->beginTransaction();
			if ($cfg["WeeklyLockPassword"] !== hash("ripemd160", $password))
				throw new Exception("Wrong Password.");

			$query = "UPDATE ProjectWeekly SET LockStatus=CASE LockStatus WHEN 0 THEN 1 ELSE 0 END WHERE ProjectWeeklyID=:projectweeklyid";
			$stmt = $this->sql->prepare($query);
			$stmt->execute([":projectweeklyid"=>$projectweeklyid]);

			$this->sql->commit();
			return ["success"=>true];

		} catch (Exception $ex) {
			$this->sql->rollBack();
			return ["success"=>false, "reason"=>$ex->getMessage()];
		}
		
	}

	public function printpdf($projectweeklyid) {

		$query = "SELECT Projects.ProjectName as ProjectName, Projects.StartDate as ProjectStartDate, ProjectWeekly.StartDate as ProjectWeeklyStartDate, ProjectWeekly.EndDate as ProjectWeeklyEndDate FROM ProjectWeekly INNER JOIN Projects ON Projects.ProjectID=ProjectWeekly.ProjectID WHERE ProjectWeeklyID=:projectweeklyid";
		$stmt = $this->sql->prepare($query);
		$stmt->execute([":projectweeklyid"=>$projectweeklyid]);
		$project_info = $stmt->fetch(PDO::FETCH_ASSOC);
		if (!$project_info)
			die("Project Weekly don't exist!");

		$query = "SELECT Employees.Name as Name, Rate, Saturday as S, Sunday as Su, Monday as M, Tuesday as T, Wednesday as W, Thursday as Th, Friday as F, TotalDays, WkAmount, Vale, AdvanceVale, ReceivedAmount, Remarks, Additional, Deduction FROM ProjectWeeklyData INNER JOIN Employees ON Employees.EmployeeID=ProjectWeeklyData.EmployeeID WHERE ProjectWeeklyID=:projectweeklyid ORDER BY Arrangement ASC";
		$stmt = $this->sql->prepare($query);
		$stmt->execute([":projectweeklyid"=>$projectweeklyid]);
		$rowData = $stmt->fetchAll(PDO::FETCH_ASSOC);

		if (count($rowData) <= 0)
			return [];

		$received_amount_total = 0;
		$wk_amount_total = 0;
		$vale_amount_total = 0;
		$advancevale_amount_total = 0;

		foreach ($rowData as $row) {
			$received_amount_total += $row["ReceivedAmount"];
			$wk_amount_total += $row["WkAmount"];
			$vale_amount_total += $row["Vale"];
			$advancevale_amount_total += $row["AdvanceVale"];
		}

		$wkdata = [
			"ProjectName"=>$project_info["ProjectName"],
			"StartDate"=>date("F d, Y", strtotime($project_info["ProjectStartDate"])),
			"CurrentDate"=>date("F d, Y"),
			"WeekStartDate"=>$project_info["ProjectWeeklyStartDate"],
			"WeekDateRange"=>date("F d, Y", strtotime($project_info["ProjectWeeklyStartDate"])). " - " . date("M d, Y", strtotime($project_info["ProjectWeeklyEndDate"])),
			"WkAmountTotal"=>number_format($wk_amount_total, 2),
			"ValeTotal"=>number_format($vale_amount_total, 2),
			"AdvanceValeTotal"=>number_format($advancevale_amount_total, 2),
			"ReceivedAmount"=>number_format($received_amount_total, 2),
			"ReceivedAmountTotal"=>number_format($received_amount_total, 2),
			"PreparedBy"=>"Gian Lorenzo Abano",
			"CheckedBy"=>"Rizelle Joyce Macalino",
			"ReleasedBy"=>"Renz Greyson Abano",
			"RowData"=>$rowData
		];

		return $wkdata;
	}

	public function fetchweekdata($wkid = 0) {
		$query = "SELECT ProjectWeeklyData.ProjectWeeklyDataID, ProjectWeeklyData.ProjectWeeklyID, ProjectWeeklyData.EmployeeID, Employees.Active, Employees.Name, ProjectWeeklyData.Rate, ProjectWeeklyData.Monday, ProjectWeeklyData.Tuesday, ProjectWeeklyData.Wednesday, ProjectWeeklyData.Thursday, ProjectWeeklyData.Friday, ProjectWeeklyData.Saturday, ProjectWeeklyData.Sunday, ProjectWeeklyData.TotalDays, ProjectWeeklyData.Additional, ProjectWeeklyData.Deduction, ProjectWeeklyData.WkAmount, ProjectWeeklyData.WkAmountDays, ProjectWeeklyData.WkAmountAdditional, ProjectWeeklyData.Vale, ProjectWeeklyData.AdvanceVale, ProjectWeeklyData.ReceivedAmount, ProjectWeeklyData.Remarks, ProjectWeeklyData.PayHistoryID, ProjectWeeklyData.Arrangement, ProjectWeekly.StartDate, ProjectWeekly.EndDate, ProjectWeekly.LockStatus FROM ProjectWeeklyData INNER JOIN Employees ON Employees.EmployeeID=ProjectWeeklyData.EmployeeID INNER JOIN ProjectWeekly ON ProjectWeekly.ProjectWeeklyID=ProjectWeeklyData.ProjectWeeklyID WHERE ProjectWeeklyData.ProjectWeeklyDataID=:projectweeklydataid";
			$stmt = $this->sql->prepare($query);
			$stmt->execute([
				":projectweeklydataid"=>$wkid
			]);
			return $stmt->fetch(PDO::FETCH_ASSOC);
	}

	public function deleteids($projectweeklydataid = []) {
		
		try  {
			$this->sql->beginTransaction();
			if (!isset($projectweeklydataid[0]))
				throw new Exception("Nothing to Delete!");

			$week_data = $this->fetchweekdata($projectweeklydataid[0]);
			if (!$week_data)
				throw new Exception("week to delete don't exist");

			foreach ($projectweeklydataid as $pid) {
				$res = $this->delete($pid, false);
				if (!$res["success"])
					throw new Exception("Failure to Delete: " . $pid . " | ".$res["reason"]);
			}

			$rearrange = $this->reorder(false, $week_data["ProjectWeeklyID"]);
			if (!$rearrange["success"])
				throw new Exception($rearrange["reason"]);

			$this->sql->commit();
			return ["success"=>true];
		} catch (Exception $ex) {
			$this->sql->rollBack();
			return ["success"=>false, "reason"=>$ex->getMessage()];
		}

	}

	public function delete($projectweeklydataid, $transaction = true) {

			try {
				if ($transaction)
					$this->sql->beginTransaction();

				$week_data = $this->fetchweekdata($projectweeklydataid);
				if (!$week_data)
					throw new Exception("week to delete don't exist");
				
				$query = "DELETE FROM PayHistoryData WHERE PayHistoryID=:payhistoryid";
				$stmt = $this->sql->prepare($query);
				$stmt->execute([":payhistoryid"=>$week_data["PayHistoryID"]]);

				$query = "DELETE FROM PayHistory WHERE PayHistoryID=:payhistoryid";
				$stmt = $this->sql->prepare($query);
				$stmt->execute([":payhistoryid"=>$week_data["PayHistoryID"]]);

				$query = "DELETE FROM ProjectWeeklyData WHERE ProjectWeeklyDataID=:projectweeklydataid";
				$stmt = $this->sql->prepare($query);
				$stmt->execute([":projectweeklydataid"=>$week_data["ProjectWeeklyDataID"]]);

				$query = "UPDATE Employees SET ProjectID=0 WHERE EmployeeID=:employeeid";
				$stmt = $this->sql->prepare($query);
				$stmt->execute([":employeeid"=>$week_data["EmployeeID"]]);

				if ($transaction)
					$this->sql->commit();
				return ["success"=>true];
			} catch (Exception $ex) {
				if ($transaction)
					$this->sql->rollBack();
				return ["success"=>false, "reason"=>$ex->getMessage()];
			}
			
	}

	public function reorder($transaction = true, $projectweeklyid = 0) {
		try {
			if ($transaction)
				$this->sql->beginTransaction();

			$query = "SELECT ProjectWeeklyDataID FROM ProjectWeeklyData WHERE ProjectWeeklyID=".$projectweeklyid." ORDER BY Arrangement ASC";
			$stmt = $this->sql->query($query);
			$toUpdate = $stmt->fetchAll(PDO::FETCH_ASSOC);
			$count = 1;

			foreach ($toUpdate as $update) {
				$query = "UPDATE ProjectWeeklyData SET Arrangement=".$count." WHERE ProjectWeeklyDataID=".$update["ProjectWeeklyDataID"];
				$res = $this->sql->exec($query);
				if (!$res)
					throw new Exception("Error updating arrangement PWDID: " . $update["ProjectWeeklyDataID"]);
				$count++;
			}

			if ($transaction)
				$this->sql->commit();
			return ["success"=>true];
		} catch (Exception $ex) {
			if ($transaction)
				$this->sql->rollBack();
			return ["success"=>false, "reason"=>$ex->getMessage()];
		}
	}

	public function weekmanage($wkid = 0, $args = []) {
		if ($_SERVER["REQUEST_METHOD"] == "GET") {
			// fetch only
			return $this->fetchweekdata($wkid);
		} else if ($_SERVER["REQUEST_METHOD"] == "PUT") {
			// update & save

			try {
				$this->sql-> beginTransaction();
				$defs = $this->fetchweekdata($wkid);
				if (count($defs) <= 0)
					return [];

				if ($defs["LockStatus"] > 0)
					throw new Exception("This weekly data is locked!");

				if (isset($args["Arrangement"]) && is_numeric($args["Arrangement"])) {
					if ($args["Arrangement"] < 1)
						throw new Exception("Negative # and zeroes is not allowed!");

					$query = "SELECT MAX(ProjectWeeklyData.Arrangement) as Arrangement FROM ProjectWeeklyData WHERE ProjectWeeklyID=:projectweeklyid";
					$stmt = $this->sql->prepare($query);
					$stmt->execute([":projectweeklyid"=>$defs["ProjectWeeklyID"]]);
					$max_arrangement = $stmt->fetch(PDO::FETCH_ASSOC);
					if ($max_arrangement & ($max_arrangement["Arrangement"] < $args["Arrangement"]))
						throw new Exception("Number is more than the total sequence #");

					$query = "update ProjectWeeklyData
								set Arrangement =
								    case    
								        when :newPosition > :oldPosition then
								            case when Arrangement = min(:oldPosition, :newPosition)
								                then :newPosition else Arrangement - 1 end
								        else /* newPosition < oldPosition */
								            case when Arrangement = max(:oldPosition, :newPosition)
								                then :newPosition else Arrangement + 1 end
								    end    
								where (Arrangement between
								           min(:oldPosition, :newPosition)
								    and max(:oldPosition, :newPosition)
								    and :oldPosition <> :newPosition) and ProjectWeeklyID=:projectweeklyid";

					$stmt = $this->sql->prepare($query);
					$stmt->execute([
						":oldPosition"=>$defs["Arrangement"], 
						":newPosition"=>$args["Arrangement"], 
						":projectweeklyid"=>$defs["ProjectWeeklyID"]
					]);
				
				}

				// Check if present on other projects;;

				$query = "SELECT SUM(ProjectWeeklyData.Sunday) as SundaySUM, SUM(ProjectWeeklyData.Monday) as MondaySUM, SUM(ProjectWeeklyData.Tuesday) as TuesdaySUM, SUM(ProjectWeeklyData.Wednesday) as WednesdaySUM, SUM(ProjectWeeklyData.Thursday) as ThursdaySUM, SUM(ProjectWeeklyData.Friday) as FridaySUM, SUM(ProjectWeeklyData.Saturday) as SaturdaySUM FROM ProjectWeeklyData INNER JOIN ProjectWeekly ON ProjectWeekly.ProjectWeeklyID=ProjectWeeklyData.ProjectWeeklyID WHERE date(ProjectWeekly.StartDate)=date(:startdate) AND ProjectWeeklyData.EmployeeID=:employeeid AND NOT ProjectWeeklyData.ProjectWeeklyDataID=:projectweeklydataid";
				$stmt = $this->sql->prepare($query);
				$stmt->execute([
					":startdate"=>$defs["StartDate"], 
					":employeeid"=>$defs["EmployeeID"], 
					":projectweeklydataid"=>$defs["ProjectWeeklyDataID"]
				]);
				$daysum = $stmt->fetch(PDO::FETCH_ASSOC);

				if ($daysum) {
					if (isset($args["Sunday"]) && is_numeric($args["Sunday"])) {
						if (($daysum["SundaySUM"] + $args["Sunday"]) > 1)
							throw new Exception("Employee is already present on Sunday from other projects!");
					}
					if (isset($args["Monday"]) && is_numeric($args["Monday"])) {
						if (($daysum["MondaySUM"] + $args["Monday"]) > 1)
							throw new Exception("Employee is already present on Monday from other projects!");
					}
					if (isset($args["Tuesday"]) && is_numeric($args["Tuesday"])) {
						if (($daysum["TuesdaySUM"] + $args["Tuesday"]) > 1)
							throw new Exception("Employee is already present on Tuesday from other projects!");
					}
					if (isset($args["Wednesday"]) && is_numeric($args["Wednesday"])) {
						if (($daysum["WednesdaySUM"] + $args["Wednesday"]) > 1)
							throw new Exception("Employee is already present on Wednesday from other projects!");
					}
					if (isset($args["Thursday"]) && is_numeric($args["Thursday"])) {
						if (($daysum["ThursdaySUM"] + $args["Thursday"]) > 1)
							throw new Exception("Employee is already present on Thursday from other projects!");
					}
					if (isset($args["Friday"]) && is_numeric($args["Friday"])) {
						if (($daysum["FridaySUM"] + $args["Friday"]) > 1)
							throw new Exception("Employee is already present on Friday from other projects!");
					}
					if (isset($args["Saturday"]) && is_numeric($args["Saturday"])) {
						if (($daysum["SaturdaySUM"] + $args["Saturday"]) > 1)
							throw new Exception("Employee is already present on Saturday from other projects!");
					}
				}

				if ((isset($args["Monday"]) && $args["Monday"] > 1) || (isset($args["Tuesday"]) && $args["Tuesday"] > 1) || (isset($args["Wednesday"]) && $args["Wednesday"] > 1) || (isset($args["Thursday"]) && $args["Thursday"] > 1) || (isset($args["Friday"]) && $args["Friday"] > 1) || (isset($args["Saturday"]) && $args["Saturday"] > 1) || (isset($args["Sunday"]) && $args["Sunday"] > 1))
					throw new Exception("Please enter a valid attendance no.");

				$query = "UPDATE ProjectWeeklyData SET Saturday=:sat, Sunday=:sun, Monday=:mon, Tuesday=:tue, Wednesday=:wed, Thursday=:thu, Friday=:fri, Additional=:additional, Deduction=:deduction, Vale=:vale, AdvanceVale=:advancevale, Remarks=:remarks WHERE ProjectWeeklyDataID=:projectweeklydataid";
				$stmt = $this->sql->prepare($query);
				$stmt->execute([
					":sat"=>(!isset($args["Saturday"]) || !is_numeric($args["Saturday"]) ? $defs["Saturday"] : $args["Saturday"]),
					":sun"=>(!isset($args["Sunday"]) || !is_numeric($args["Sunday"]) ? $defs["Sunday"] : $args["Sunday"]),
					":mon"=>(!isset($args["Monday"]) || !is_numeric($args["Monday"]) ? $defs["Monday"] : $args["Monday"]),
					":tue"=>(!isset($args["Tuesday"]) || !is_numeric($args["Tuesday"]) ? $defs["Tuesday"] : $args["Tuesday"]),
					":wed"=>(!isset($args["Wednesday"]) || !is_numeric($args["Wednesday"]) ? $defs["Wednesday"] : $args["Wednesday"]),
					":thu"=>(!isset($args["Thursday"]) || !is_numeric($args["Thursday"]) ? $defs["Thursday"] : $args["Thursday"]),
					":fri"=>(!isset($args["Friday"]) || !is_numeric($args["Friday"]) ? $defs["Friday"] : $args["Friday"]),
					":additional"=>(!isset($args["Saturday"]) || !is_numeric($args["Additional"]) ? $defs["Additional"] : $args["Additional"]),
					":vale"=>(!isset($args["Vale"]) || !is_numeric($args["Vale"]) ? $defs["Vale"] : $args["Vale"]),
					":advancevale"=>(!isset($args["AdvanceVale"]) || !is_numeric($args["AdvanceVale"]) ? $defs["AdvanceVale"] : $args["AdvanceVale"]),
					":remarks"=>(!isset($args["Remarks"]) || empty((string) $args["Remarks"]) ? $defs["Remarks"] : $args["Remarks"]),
					":deduction"=>(!isset($args["Deduction"]) || !is_numeric($args["Deduction"]) ? $defs["Deduction"] : $args["Deduction"]),
					":projectweeklydataid"=>$wkid
				]);
				

				$updated_defs = array();
				$updated_defs = $this->fetchweekdata($wkid);
				$total_days = $updated_defs["Saturday"] + $updated_defs["Sunday"] + $updated_defs["Monday"] + $updated_defs["Tuesday"] + $updated_defs["Wednesday"] + $updated_defs["Thursday"] + $updated_defs["Friday"];
				$wkamount = ($updated_defs["Additional"] + ($total_days * $updated_defs["Rate"])) - $updated_defs["Deduction"];
				$wkamount_additional = $updated_defs["Additional"] + ($total_days * $updated_defs["Rate"]);
				$wkamountdays = $total_days * $updated_defs["Rate"];
				$received_amount = $wkamount - ($updated_defs["Vale"] + $updated_defs["AdvanceVale"]);

				$query = "UPDATE ProjectWeeklyData SET TotalDays=:totaldays, WkAmount=:wkamount, ReceivedAmount=:receivedamount, WkAmountDays=:wkamountdays, WkAmountAdditional=:wkamountadditional WHERE ProjectWeeklyDataID=:projectweeklydataid";
				$stmt = $this->sql->prepare($query);
				$stmt->execute([
					":totaldays"=>$total_days,
					":wkamount"=>$wkamount,
					":wkamountadditional"=>$wkamount_additional,
					":wkamountdays"=>$wkamountdays,
					":receivedamount"=>$received_amount,
					":projectweeklydataid"=>$wkid
				]);

				/*$query = "UPDATE ProjectDays SET Attendance=:attendance WHERE ProjectWeeklyDataID=:projectweeklydataid AND Day=:daynum";
				$stmt = $this->sql->prepare($query);
				if (isset($args["Sunday"]) && is_numeric($args["Sunday"]))
					$stmt->execute([":projectweeklydataid"=>$wkid, ":daynum"=>0, ":attendance"=>$args["Sunday"]]);
				if (isset($args["Monday"]) && is_numeric($args["Monday"]))
					$stmt->execute([":projectweeklydataid"=>$wkid, ":daynum"=>1, ":attendance"=>$args["Monday"]]);
				if (isset($args["Tuesday"]) && is_numeric($args["Tuesday"]))
					$stmt->execute([":projectweeklydataid"=>$wkid, ":daynum"=>2, ":attendance"=>$args["Tuesday"]]);
				if (isset($args["Wednesday"]) && is_numeric($args["Wednesday"]))
					$stmt->execute([":projectweeklydataid"=>$wkid, ":daynum"=>3, ":attendance"=>$args["Wednesday"]]);
				if (isset($args["Thursday"]) && is_numeric($args["Thursday"]))
					$stmt->execute([":projectweeklydataid"=>$wkid, ":daynum"=>4, ":attendance"=>$args["Thursday"]]);
				if (isset($args["Friday"]) && is_numeric($args["Friday"]))
					$stmt->execute([":projectweeklydataid"=>$wkid, ":daynum"=>5, ":attendance"=>$args["Friday"]]);
				if (isset($args["Saturday"]) && is_numeric($args["Saturday"]))
					$stmt->execute([":projectweeklydataid"=>$wkid, ":daynum"=>6, ":attendance"=>$args["Saturday"]]);*/


				$updated_defs["TotalDays"] = $total_days;
				$updated_defs["WkAmount"] = $wkamount;
				$updated_defs["ReceivedAmount"] = $received_amount;
				$this->sql->commit();

				return $updated_defs;
			} catch (Exception $ex) { 
				http_response_code(400);
				$this->sql->rollBack();
				return ["success"=>false, "reason"=>$ex->getMessage()]; 
			}
		}
	}

	public function add_employees($projectweeklyid, $employeeids) {
		try {
			$this->sql->beginTransaction();

			$query = "SELECT EmployeeID FROM ProjectWeeklyData WHERE ProjectWeeklyID=:projectweeklyid AND EmployeeID=:employeeid";
			$stmt = $this->sql->prepare($query);

			foreach($employeeids as $employeeid) {
				$stmt->execute([":projectweeklyid"=>$projectweeklyid, ":employeeid"=>$employeeid]);
				if(!$stmt->fetch(PDO::FETCH_ASSOC))
					$this->add_employee($projectweeklyid, $employeeid);
				continue;
			}
			$this->sql->commit();
		} catch (PDOException $pex) {
			$this->sql->rollBack();
			return ["success"=>false, "reason"=>$pex->getMessage()];
		} catch (ErrorException $ee) {
			$this->sql->rollBack();
			return ["success"=>false, "reason"=>"Line No: " . $ee->getLine() . " | " . $ee->getMessage()];
		}
		return ["success"=>true];
	}

	private function add_employee($projectweeklyid, $employeeid, $arrangement = '') {

		// STEP ONE(1):: GET PROJECT INFORMATION FOR FUTURE USE.
		$query = "SELECT ProjectWeekly.ProjectID, ProjectWeekly.StartDate as StartDate, ProjectWeekly.EndDate as EndDate FROM ProjectWeekly WHERE ProjectWeeklyID=:projectweeklyid";
		$stmt = $this->sql->prepare($query);
		$stmt->execute([
			":projectweeklyid"=>$projectweeklyid
		]);
		$project_info = $stmt->fetch(PDO::FETCH_ASSOC);
		if (is_null($project_info) || empty($project_info))
			throw new PDOException("No ProjectWeekly ID Available!");

		$projectid = $project_info["ProjectID"];

		// STEP ONE(1.3):: GET EMPLOYEE INFORMATION
		$query = "SELECT * FROM Employees WHERE EmployeeID=:employeeid";
		$stmt = $this->sql->prepare($query);
		$stmt->execute([
			":employeeid"=>$employeeid
		]);
		$employee_info = $stmt->fetch(PDO::FETCH_ASSOC);
		if (is_null($employee_info) || empty($employee_info))
			throw new PDOException("Employee don't exist");

		$basicrate = $employee_info["BasicRate"];
		$total_amount = $employee_info["TotalAmount"];

		// STEP ONE(1.3):: UPDATE EMPLOYEE ON PROJECT ID
		$query = "UPDATE Employees SET ProjectID=:projectid WHERE EmployeeID=:employeeid";
		$stmt = $this->sql->prepare($query);
		$res = $stmt->execute([
			":projectid"=>$project_info["ProjectID"],
			":employeeid"=>$employeeid
		]);
		if (!$res)
			throw new PDOException("Cannot update employee: project id");

		// STEP TWO (2.0) :: GET PREVIOUS PROJECT DAYS
		$ads = null;
		/*$query = "SELECT ProjectDays.BasicRate, ProjectDays.TotalAmount, ProjectDays.PayHistoryID FROM ProjectDays WHERE date(ProjectDays.Date)>=date(:startdate) AND ProjectDays.EmployeeID=:employeeid ORDER BY ProjectDays.PayHistoryID DESC LIMIT 1";
		$stmt = $this->sql->prepare($query);
		$pdays_data = $stmt->execute([
			":startdate"=>$project_info["StartDate"],
			":employeeid"=>$employeeid
		]);*/

		$query = "SELECT ProjectWeeklyData.Rate as TotalAmount, ProjectWeeklyData.BasicRate, ProjectWeeklyData.TotalDeduction, MAX(ProjectWeeklyData.PayHistoryID) as PayHistoryID FROM ProjectWeeklyData INNER JOIN ProjectWeekly ON ProjectWeekly.ProjectWeeklyID=ProjectWeeklyData.ProjectWeeklyID WHERE date(ProjectWeekly.StartDate)=date(:startdate) AND ProjectWeeklyData.EmployeeID=:employeeid";
		$stmt = $this->sql->prepare($query);
		$pdays_data = $stmt->execute([
			":startdate"=>$project_info["StartDate"],
			":employeeid"=>$employeeid
		]);


		$pdays_data = $stmt->fetch(PDO::FETCH_ASSOC);
		if (!is_null($pdays_data["PayHistoryID"])) {

			// STEP TWO(2.1):: GET PREVIOUS USED EMPLOYEE SALARY DATA
			$query = "SELECT PayHistoryData.PayHistoryID, PayHistoryData.PayName, PayHistoryData.PayType, PayHistoryData.PayAmount FROM PayHistoryData INNER JOIN PayHistory ON PayHistory.PayHistoryID=PayHistoryData.PayHistoryID WHERE PayHistory.PayHistoryID=:payhistoryid";
			$stmt = $this->sql->prepare($query);
			$ads = $stmt->execute([
				":payhistoryid"=>$pdays_data["PayHistoryID"]
			]);

			$ads = $stmt->fetchAll(PDO::FETCH_ASSOC);
			$basicrate = $pdays_data["BasicRate"];
			$total_amount = $pdays_data["TotalAmount"];

		} else {
			// STEP THREE(3.1):: EMPLOYEE HAS NO PREVIOUS SALARY DATA. GET SALARY DATA FROM DEFAULT CONFIG AND COMPUTE TOTAL PAY AMOUNT
			$query = "SELECT Name as PayName, Amount as PayAmount, Type as PayType FROM EmployeeAD WHERE EmployeeID=:employeeid";
			$stmt = $this->sql->prepare($query);
			$stmt->execute([":employeeid"=>$employeeid]);
		 	$ads = $stmt->fetchAll(PDO::FETCH_ASSOC);
		}

		// STEP FOUR(4):: Calculate total deduction
		$total_deduction = 0;

		foreach ($ads as $pay) {
			if ($pay["PayType"] == "Deduction") {
				$total_deduction += $pay["PayAmount"];
			}
		}

		// STEP FOUR(4.1):: INSERT INTO PAYHISTORY
		$query = "INSERT INTO PayHistory DEFAULT VALUES";
			$inserted = $this->sql->exec($query);
			if (!$inserted)
				throw new PDOException("PayHistory not inserted!");

			$payhistoryid = $this->sql->lastInsertId();

			$query = "INSERT INTO PayHistoryData (PayHistoryID, PayName, PayAmount, PayType) VALUES (:payhistoryid, :payname, :payamount, :paytype)";
			$stmt = $this->sql->prepare($query);

			foreach ($ads as $pay) {
				$inserted = $stmt->execute([
					":payhistoryid"=>$payhistoryid,
					":payname"=>$pay["PayName"],
					":payamount"=>$pay["PayAmount"],
					":paytype"=>$pay["PayType"]
				]);
				if (!$inserted)
					throw new Exception("PayHistoryData not inserted!");
			}


		// STEP FOUR(4.2):: INSERT EMPLOYEE ON PROJECTWEEKLY DATA

		if (is_numeric($arrangement) && $arrangement > 0) {
			$query = "INSERT INTO ProjectWeeklyData (ProjectWeeklyID, ProjectID, EmployeeID, Rate, Saturday, Sunday, Monday, Tuesday, Wednesday, Thursday, Friday, TotalDays, Additional, WkAmount, Vale, AdvanceVale, ReceivedAmount, Remarks, BasicRate, TotalDeduction, PayHistoryID, Arrangement) VALUES (:projectweeklyid, :projectid, :employeeid, :rate, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, '', :basicrate, :totaldeduction, :payhistoryid, :arrangement)";
			$params = [
				":projectweeklyid"=>$projectweeklyid,
				":employeeid"=>$employeeid,
				":rate"=>$total_amount,
				":basicrate"=>$basicrate,
				":totaldeduction"=>$total_deduction,
				":payhistoryid"=>$payhistoryid,
				":projectid"=>$project_info["ProjectID"],
				":arrangement"=>$arrangement
			];
		}
		else {
			$query = "INSERT INTO ProjectWeeklyData (ProjectWeeklyID, ProjectID, EmployeeID, Rate, Saturday, Sunday, Monday, Tuesday, Wednesday, Thursday, Friday, TotalDays, Additional, WkAmount, Vale, AdvanceVale, ReceivedAmount, Remarks, BasicRate, TotalDeduction, PayHistoryID, Arrangement) SELECT :projectweeklyid, :projectid, :employeeid, :rate, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, '', :basicrate, :totaldeduction, :payhistoryid, CASE WHEN MAX(ProjectWeeklyData.Arrangement) NOT NULL THEN MAX(ProjectWeeklyData.Arrangement)+1 ELSE 1 END FROM ProjectWeekly INNER JOIN ProjectWeeklyData ON ProjectWeeklyData.ProjectWeeklyID=ProjectWeekly.ProjectWeeklyID WHERE ProjectWeekly.ProjectWeeklyID=:projectweeklyid";
				$params = [
					":projectweeklyid"=>$projectweeklyid,
					":employeeid"=>$employeeid,
					":rate"=>$total_amount,
					":basicrate"=>$basicrate,
					":totaldeduction"=>$total_deduction,
					":payhistoryid"=>$payhistoryid,
					":projectid"=>$project_info["ProjectID"],
				];
		}

		$stmt = $this->sql->prepare($query);
		$stmt->execute($params);
		$projectweeklydataid = $this->sql->lastInsertId();


		$query = "UPDATE PayHistory SET ProjectID=:projectid, ProjectWeeklyID=:projectweeklyid, ProjectWeeklyDataID=:projectweeklydataid WHERE PayHistoryID=:payhistoryid";
		$stmt = $this->sql->prepare($query);
		$stmt->execute([
			":projectid"=>$project_info["ProjectID"],
			":projectweeklyid"=>$projectweeklyid,
			":projectweeklydataid"=>$projectweeklydataid,
			":payhistoryid"=>$payhistoryid
		]);


		/*
		// STEP FIVE(5):: GET DATE RANGE OF PROJECT START DATE AND INSERT PROJECT DAYS
		$date_range = $this->getdatearray($project_info["StartDate"]);
		foreach ($date_range as $date) {


			// STEP FIVE(5.1) :: ADD PAYHISTORY

			$query = "INSERT INTO PayHistory DEFAULT VALUES";
			$inserted = $this->sql->exec($query);
			if (!$inserted)
				throw new PDOException("PayHistory not inserted!");

			$payhistoryid = $this->sql->lastInsertId();

			$query = "INSERT INTO PayHistoryData (PayHistoryID, PayName, PayAmount, PayType) VALUES (:payhistoryid, :payname, :payamount, :paytype)";
			$stmt = $this->sql->prepare($query);

			foreach ($ads as $pay) {
				$inserted = $stmt->execute([
					":payhistoryid"=>$payhistoryid,
					":payname"=>$pay["PayName"],
					":payamount"=>$pay["PayAmount"],
					":paytype"=>$pay["PayType"]
				]);
				if (!$inserted)
					throw new Exception("PayHistoryData not inserted!");
			}

		

			// STEP FIVE(5.2) :: INSERT PROJECT DAYS
			$query = "INSERT INTO ProjectDays (ProjectID, ProjectWeeklyID, ProjectWeeklyDataID, EmployeeID, 'Date', Day, Attendance, PayHistoryID, BasicRate, TotalAmount) VALUES (:projectid, :projectweeklyid, :projectweeklydataid, :employeeid, :thedate, :theday, 0, :payhistoryid, :basicrate, :totalamount)";
			$stmt = $this->sql->prepare($query);
			$inserted = $stmt->execute([
				":projectid"=>$projectid,
				":projectweeklyid"=>$projectweeklyid,
				":projectweeklydataid"=>$projectweeklydataid,
				":employeeid"=>$employeeid,
				":thedate"=>$date["date"],
				":theday"=>$date["dayofweek"],
				":payhistoryid"=>$payhistoryid,
				":basicrate"=>$basicrate,
				":totalamount"=>$total_amount
			]);
			if (!$inserted)
				throw new PDOException("Cannot insert project days");

			$ProjectDayID = $this->sql->lastInsertId();
		}*/

	}

	public function datesfromweeklyid($projectweeklyid) {
			$query = "SELECT ProjectWeekly.StartDate, ProjectWeekly.LockStatus FROM ProjectWeekly WHERE ProjectWeeklyID=:projectweeklyid";
			$vals = [
				":projectweeklyid"=>$projectweeklyid
			];
			$stmt = $this->sql->prepare($query);
			$stmt->execute($vals);
			$pweekly = $stmt->fetch(PDO::FETCH_ASSOC);
			if (!$pweekly)
				return [];

			$ret = [];
			$ret["dates"] = $this->getdatearray($pweekly["StartDate"], "M d, Y");
			$ret["info"]["LockStatus"] = $pweekly["LockStatus"];
			$ret["info"]["StartDate"] = $pweekly["StartDate"];
			return $ret;
	}

	public function getdatearray($startdate, $dateformat = "Y-m-d") {
		$before = new DateTime($startdate);
		$after = new DateTime($startdate);
		$after->add(new DateInterval("P7D"));

		$period = new DatePeriod(
		     $before,
		     new DateInterval('P1D'),
		     $after
		);

		$date_array = [];

		foreach ($period as $key => $value) {
			$date_array[] = [
				"dayofweek"=>$value->format("w"),
				"date"=>$value->format($dateformat)
			];
		}
		return $date_array;
	}


	public function fetch($projectid) {
		$query = "SELECT * FROM Projects WHERE ProjectID=:projectid";
		$vals = [
			"projectid"=>$projectid
		];
		try {
			$stmt = $this->sql->prepare($query);
			$stmt->execute($vals);
			$project = $stmt->fetch(PDO::FETCH_ASSOC);
			if (empty($project))
				return [];

			return $project;
		} catch (PDOException $ex) {
			http_response_code(400);
			return [];
		}
	}

	public function weeklydata($wkid) {
		$this->load->helper("SSP");
		// DB table to use
		$table = 'WeeklyData';
		 
		// Table's primary key
		$primaryKey = 'ProjectWeeklyDataID';
		 
		// Array of database columns which should be read and sent back to DataTables.
		// The `db` parameter represents the column name in the database, while the `dt`
		// parameter represents the DataTables column identifier. In this case simple
		// indexes
		/* $columns = array(
		    array( 'db' => 'first_name', 'dt' => 0 ),
		    array( 'db' => 'last_name',  'dt' => 1 ),
		    array( 'db' => 'position',   'dt' => 2 ),
		    array( 'db' => 'office',     'dt' => 3 ),
		    array(
		        'db'        => 'start_date',
		        'dt'        => 4,
		        'formatter' => function( $d, $row ) {
		            return date( 'jS M y', strtotime($d));
		        }
		    ),
		    array(
		        'db'        => 'salary',
		        'dt'        => 5,
		        'formatter' => function( $d, $row ) {
		            return '$'.number_format($d);
		        }
		    )
		); */
		$columns = array(
			array( 'db' => 'ProjectWeeklyDataID', 'dt' => 0, "formatter"=> function($d, $row) {
		    	return strip_tags($d);
		    }),
		    array( 'db' => 'EmployeeID', 'dt' => 1, "formatter"=> function($d, $row) {
		    	return strip_tags($d);
		    }),
		    array( 'db' => 'Active', 'dt' => 2, "formatter"=> function($d, $row) {
		    	return strip_tags($d);
		    }),
		    array( 'db' => 'Arrangement', 'dt' => 3, "formatter"=> function($d, $row) {
		    	return strip_tags($d);
		    }),
		    array( 'db' => 'Name',  'dt' => 4, "formatter"=> function($d, $row) {
		    	return strip_tags($d);
		    }),
		    array( 'db' => 'Rate',   'dt' => 5, "formatter"=> function($d, $row) {
		    	return strip_tags($d);
		    }),

		    array( 'db' => 'Saturday',     'dt' => 6, "formatter"=> function($d, $row) {
		    	return strip_tags($d);
		    }),
		    array( 'db' => 'Sunday',     'dt' => 7, "formatter"=> function($d, $row) {
		    	return strip_tags($d);
		    }),
		    array( 'db' => 'Monday',     'dt' => 8, "formatter"=> function($d, $row) {
		    	return strip_tags($d);
		    }),
		    array( 'db' => 'Tuesday',     'dt' => 9, "formatter"=> function($d, $row) {
		    	return strip_tags($d);
		    }),
		    array( 'db' => 'Wednesday',     'dt' => 10, "formatter"=> function($d, $row) {
		    	return strip_tags($d);
		    }),
		    array( 'db' => 'Thursday',     'dt' => 11, "formatter"=> function($d, $row) {
		    	return strip_tags($d);
		    }),
		    array( 'db' => 'Friday',     'dt' => 12, "formatter"=> function($d, $row) {
		    	return strip_tags($d);
		    }),

		    array( 'db' => 'TotalDays',     'dt' => 13, "formatter"=> function($d, $row) {
		    	return strip_tags($d);
		    }),
		    array( 'db' => 'Additional',     'dt' => 14, "formatter"=> function($d, $row) {
		    	return strip_tags($d);
		    }),
		    array( 'db' => 'Deduction',     'dt' => 15, "formatter"=> function($d, $row) {
		    	return strip_tags($d);
		    }),
		    array( 'db' => 'WkAmount',     'dt' => 16, "formatter"=> function($d, $row) {
		    	return strip_tags($d);
		    }),
		    array( 'db' => 'Vale',     'dt' => 17, "formatter"=> function($d, $row) {
		    	return strip_tags($d);
		    }),
		    array( 'db' => 'AdvanceVale',     'dt' => 18, "formatter"=> function($d, $row) {
		    	return strip_tags($d);
		    }),
		    array( 'db' => 'ReceivedAmount',     'dt' => 19, "formatter"=> function($d, $row) {
		    	return strip_tags($d);
		    }),
		    array( 'db' => 'Remarks',     'dt' => 20, "formatter"=> function($d, $row) {
		    	return strip_tags($d);
		    }),

		);
		 
		// SQL server connection information
		$sql_details =& $this->sql;

		$sql_details->query("CREATE TEMP TABLE WeeklyData AS SELECT ProjectWeeklyData.ProjectWeeklyDataID, ProjectWeeklyData.EmployeeID, Employees.Active, Employees.Name, ProjectWeeklyData.Rate, ProjectWeeklyData.Monday, ProjectWeeklyData.Tuesday, ProjectWeeklyData.Wednesday, ProjectWeeklyData.Thursday, ProjectWeeklyData.Friday, ProjectWeeklyData.Saturday, ProjectWeeklyData.Sunday, ProjectWeeklyData.TotalDays, ProjectWeeklyData.Additional, ProjectWeeklyData.Deduction, ProjectWeeklyData.WkAmount, ProjectWeeklyData.Vale, ProjectWeeklyData.AdvanceVale, ProjectWeeklyData.ReceivedAmount, ProjectWeeklyData.Remarks, ProjectWeeklyData.Arrangement FROM ProjectWeeklyData INNER JOIN Employees ON Employees.EmployeeID=ProjectWeeklyData.EmployeeID WHERE ProjectWeeklyData.ProjectWeeklyID=".$wkid);

		 
		 
		/* * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * *
		 * If you just want to use the basic configuration for DataTables with PHP
		 * server-side, there is no need to edit below this line.
		 */
		 echo json_encode(
		    SSP::simple( $_GET, $sql_details, $table, $primaryKey, $columns ), JSON_PRETTY_PRINT
		);
	}


	public function fetchall($projectid) {
		try {
			$query = "SELECT * FROM Projects WHERE ProjectID=:projectid";
			$vals = [
				"projectid"=>$projectid
			];
			$stmt = $this->sql->prepare($query);
			$stmt->execute($vals);
			$project = $stmt->fetch(PDO::FETCH_ASSOC);
			if (empty($project))
				return [];

			$query = "SELECT * FROM ProjectWeekly WHERE ProjectID=:projectid ORDER BY StartDate DESC";
			$vals = [
				":projectid"=>$projectid
			];
			$stmt = $this->sql->prepare($query);
			$stmt->execute($vals);
			$weeklytable = $stmt->fetchAll(PDO::FETCH_ASSOC);
			$project["WeeklyData"] = (!empty($weeklytable) ? $weeklytable : []);
		} catch (PDOException $pex) {
			return [];
		}
		return $project;
	}


	public function addrange($projectid, $startdate) {
		$enddate = date('Y-m-d', strtotime($startdate . ' + 6 days'));
		$date_range = $this->getdatearray($startdate, $enddate);

		// get if wednesday or saturday..
		$start_date = DateTime::createFromFormat("Y-m-d", $startdate);
		$day_num = $start_date->format("w");

		if ($day_num == 3)
			$end_date = date("Y-m-d", strtotime($startdate . " + 2 days"));
		elseif ($day_num == 6)
			$end_date = date("Y-m-d", strtotime($startdate . " + 3 days"));



		try {
			$this->sql->beginTransaction();

			$project_info = $this->fetchall($projectid);
			if (empty($project_info))
				throw new PDOException("Project don't exist");

			// verify if range not added already (temporary sql);
			$query = "SELECT ProjectWeekly.ProjectWeeklyID FROM ProjectWeekly WHERE ProjectWeekly.StartDate=:startdate AND ProjectID=:projectid";
			$stmt = $this->sql->prepare($query);
			$stmt->execute([ ":startdate"=>$startdate, ":projectid"=>$project_info["ProjectID"]]);
			$res = $stmt->fetch(PDO::FETCH_ASSOC);
			if ($res)
				throw new PDOException("Range already added!");


			// $query = "SELECT EmployeeID FROM Employees WHERE ProjectID=:projectid";

			// $query = "SELECT PrjWeekly.ProjectWeeklyID FROM ProjectWeekly as PrjWeekly WHERE PrjWeekly.ProjectID=:prj_id ORDER BY PrjWeekly.EndDate DESC LIMIT 1";

			$query = "SELECT ProjectWeeklyID FROM ProjectWeekly WHERE ProjectWeekly.ProjectID=:prj_id ORDER BY ProjectWeekly.StartDate DESC LIMIT 1";
			$stmt = $this->sql->prepare($query);
			$res = $stmt->execute(["prj_id"=>$projectid]);
			if (!$res)
				throw new Exception("There is an error on getting the last project weekly data.");

			$projectweeklyid = $stmt->fetch(PDO::FETCH_ASSOC);

			// if ($projectweeklyid["ProjectWeeklyID"])
			// 	$projectweeklyid = $projectweeklyid["ProjectWeeklyID"];
			// else
			// 	$projectweeklyid = 0;

			$projectweeklyid = $projectweeklyid["ProjectWeeklyID"] ?? 0;




			$query = "SELECT ProjectWeeklyData.EmployeeID as EmployeeID, ProjectWeeklyData.Arrangement as Arrangement, :projectid as prj_id FROM ProjectWeeklyData WHERE ProjectWeeklyData.ProjectID=prj_id AND ProjectWeeklyData.ProjectWeeklyID=:projectweeklyid";

			$stmt = $this->sql->prepare($query);
			$res = $stmt->execute([":projectid"=>$project_info["ProjectID"], ":projectweeklyid"=>$projectweeklyid]);
			$employees = $stmt->fetchAll(PDO::FETCH_ASSOC);

			$query = "INSERT INTO ProjectWeekly (ProjectID, AddedDate, StartDate, EndDate) VALUES (:projectid, date('now'), :startdate, :enddate)";
			$stmt = $this->sql->prepare($query);
			$res = $stmt->execute([
				":projectid"=>$projectid,
				":startdate"=>$startdate,
				":enddate"=>$end_date
			]);
			if (!$res)
				throw new PDOException("Cannot add ProjectWeekly: " . var_export($stmt->errorInfo(), true));

			$projectweeklyid = $this->sql->lastInsertId();

			foreach ($employees as $employee) {
				$this->add_employee($projectweeklyid, $employee["EmployeeID"], $employee["Arrangement"]);
			}
			$this->sql->commit();

			return ["success"=>true, "ProjectWeeklyID"=>$projectweeklyid];

			
		} catch(PDOException $ex) {
			$this->sql->rollBack();
			return ["success"=>false, "reason"=>$ex->getMessage()];
		}
	}



}