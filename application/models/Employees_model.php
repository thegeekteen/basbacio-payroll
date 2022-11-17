<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Employees_model extends CI_Model {

	public $sql = null;

	public function __construct() {
		$this->load->database();
		$this->sql =& $this->db->conn_id;
	}

	public function listall() {
		$this->load->helper("SSP");
		// DB table to use
		$table = 'Employees';
		 
		// Table's primary key
		$primaryKey = 'EmployeeID';
		 
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
			array( 'db' => 'EmployeeID', 'dt' => 1, "formatter"=> function($d, $row) {
		    	return strip_tags($d);
		    }),
		    array( 'db' => 'Name', 'dt' => 2, "formatter"=> function($d, $row) {
		    	return strip_tags($d);
		    }),
		    array( 'db' => 'AddedDate',  'dt' => 3, 'formatter' => function( $d, $row ) {
		            return date( 'M j, Y', strtotime($d));
		    }),
		    array( 'db' => 'Designation',   'dt' => 4, "formatter"=> function($d, $row) {
		    	return strip_tags($d);
		    }),
		    array( 'db' => 'BasicRate',     'dt' => 5, "formatter"=> function($d, $row) {
		    	return strip_tags($d);
		    }),
		    array( 'db' => 'TotalAmount',     'dt' => 6, "formatter"=> function($d, $row) {
		    	return strip_tags($d);
		    }),
		    array( 'db' => 'TotalDeduction',     'dt' => 7, "formatter"=> function($d, $row) {
		    	return strip_tags($d);
		    }),
		    array( 'db' => 'Active',     'dt' => 8, 'formatter' => function($d, $row) {
		    	return (!empty($d) ? "Yes" : "No");
		    })
		);
		 
		// SQL server connection information
		 
		/* * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * *
		 * If you just want to use the basic configuration for DataTables with PHP
		 * server-side, there is no need to edit below this line.
		 */
		 return json_encode(
		    SSP::simple( $_GET, $this->sql, $table, $primaryKey, $columns ), JSON_PRETTY_PRINT
		);
	}

	public function delete_emps($employeeids = []) {
		try {
			$this->sql->beginTransaction();
			foreach ($employeeids as $employeeid) {
				$res = $this->delete_emp($employeeid, false);
				if (!$res["success"])
					throw new Exception("Error Deleteing E_ID:" . $employeeid . " | Reason: " . $res["reason"]);
			}
			$this->sql->commit();
			return ["success"=>true];
		} catch (Exception $ex) {
			return ["success"=>false, "reason"=>$ex->getMessage()];
		}
	}

	public function delete_emp($employeeid, $transaction = true) {
		// get employee from weeklyid
		try {
			if ($transaction)
				$this->sql->beginTransaction();

			$ci =& get_instance();
			$ci->load->model("viewproject_model");

			$employee_info = $this->fetch($employeeid);
			if (empty($employee_info))
				throw new Exception("EmployeeID don't exist!");

			$query = "SELECT ProjectWeeklyDataID FROM ProjectWeeklyData WHERE ProjectWeeklyData.EmployeeID=:employeeid";
			$stmt = $this->sql->prepare($query);
			$stmt->execute([":employeeid"=>$employeeid]);
			$weeklyids = $stmt->fetchAll(PDO::FETCH_ASSOC);

			foreach ($weeklyids as $weeklyid) {
				$ci->viewproject_model->delete($weeklyid["ProjectWeeklyDataID"], false);
			}

			$query = "DELETE FROM EmployeeAD WHERE EmployeeID=:employeeid";
			$stmt = $this->sql->prepare($query);
			$stmt->execute([":employeeid"=>$employeeid]);

			$query = "DELETE FROM Employees WHERE EmployeeID=:employeeid";
			$stmt = $this->sql->prepare($query);
			$stmt->execute([":employeeid"=>$employeeid]);

			$query = "DELETE FROM EmployeeRemarks WHERE EmployeeID=:employeeid";
			$stmt = $this->sql->prepare($query);
			$stmt->execute([":employeeid"=>$employeeid]);

			if ($transaction)
				$this->sql->commit();
			return ["success"=>true];
		} catch (Exception $ex) {
			if ($transaction)
				$this->sql->rollBack();
			return ["success"=>false, "reason"=>$ex->getMessage()];
		}
	
	}

	public function empallsalaries($startdate = "0000-00-00") {
		$query = "SELECT ProjectWeeklyData.EmployeeID FROM ProjectWeeklyData INNER JOIN ProjectWeekly ON ProjectWeeklyData.ProjectWeeklyID=ProjectWeekly.ProjectWeeklyID INNER JOIN Employees ON Employees.EmployeeID=ProjectWeeklyData.EmployeeID WHERE ProjectWeeklyData.TotalDays > 0 AND date(ProjectWeekly.StartDate)=:startdate AND Employees.Active=1 GROUP BY ProjectWeeklyData.EmployeeID";
		$stmt = $this->sql->prepare($query);
		$stmt->execute([":startdate"=>$startdate]);
		$employees = $stmt->fetchAll(PDO::FETCH_ASSOC);
		$employeeids = [];
		foreach ($employees as $employee) {
			$employeeids[] = $employee["EmployeeID"];
		}
		return $this->empsalaries($employeeids, $startdate);
	}

	public function empsalaries($employeeids = [], $startdate = "0000-00-00", $validate = true) {
		$payment_datas = [];
		if ($validate) {
			foreach ($employeeids as $employeeid) {
				$query = "SELECT ProjectWeeklyData.ProjectWeeklyDataID FROM ProjectWeeklyData INNER JOIN ProjectWeekly ON ProjectWeekly.ProjectWeeklyID=ProjectWeeklyData.ProjectWeeklyID WHERE ProjectWeeklyData.EmployeeID=:employeeid AND ProjectWeeklyData.TotalDays > 0 AND date(ProjectWeekly.StartDate)=:startdate LIMIT 1";
				$stmt = $this->sql->prepare($query);
				$stmt->execute([
					":employeeid"=>$employeeid,
					":startdate"=>$startdate
				]);
				if (!$stmt->fetch(PDO::FETCH_ASSOC))
					continue;

				$payment_datas[] = $this->empsalary($employeeid, $startdate);
			}
		} else {
			foreach ($employeeids as $employeeid) {
				$payment_datas[] = $this->empsalary($employeeid, $startdate);
			}
		}
		return $payment_datas;
	}


	private function getpayhistory($payhistoryid, $type) {
		$query = "SELECT PayHistoryData.PayName, PayHistoryData.PayAmount FROM PayHistoryData INNER JOIN PayHistory ON PayHistoryData.PayHistoryID=PayHistory.PayHistoryID WHERE PayHistory.PayHistoryID=:payhistoryid AND PayHistoryData.PayType=:paytype";
		$stmt = $this->sql->prepare($query);
		$stmt->execute([
			":payhistoryid"=>$payhistoryid,
			":paytype"=>$type
		]);
		return $stmt->fetchAll(PDO::FETCH_ASSOC);
	}

	public function empsalary($employeeid, $startdate) {
		$query = "SELECT Employees.Name, ProjectWeekly.StartDate, ProjectWeekly.EndDate, SUM(TotalDays) as TotalDaysSUM,  SUM(WkAmount) as WkAmountSUM, SUM(WkAmountDays) as WkAmountDaysSUM, SUM(WkAmountAdditional) as WkAmountAdditionalSUM, SUM(Additional) as AdditionalSUM, SUM(Deduction) as DeductionSUM, SUM(ReceivedAmount) as ReceivedAmountSUM, SUM(Vale) as ValeSUM, SUM(AdvanceVale) as AdvanceValeSUM, ProjectWeeklyData.BasicRate, ProjectWeeklyData.TotalDeduction, PayHistoryID, Rate FROM ProjectWeeklyData INNER JOIN ProjectWeekly ON ProjectWeekly.ProjectWeeklyID=ProjectWeeklyData.ProjectWeeklyID INNER JOIN Employees ON Employees.EmployeeID=ProjectWeeklyData.EmployeeID WHERE ProjectWeeklyData.EmployeeID=:employeeid AND date(ProjectWeekly.StartDate)=:startdate";
		$stmt = $this->sql->prepare($query);
		$stmt->execute([
			":employeeid"=>$employeeid,
			":startdate"=>$startdate
		]);
		$salary = $stmt->fetch(PDO::FETCH_ASSOC);

		$query = "SELECT PayName, PayAmount FROM PayHistoryData INNER JOIN PayHistory ON PayHistory.PayHistoryID=PayHistoryData.PayHistoryID WHERE PayHistory.PayHistoryID=:payhistoryid AND PayHistoryData.PayType=:paytype";
		$stmt = $this->sql->prepare($query);
		$stmt->execute([
			":payhistoryid"=>$salary["PayHistoryID"],
			":paytype"=>"Additional"
		]);
		$additionals = $stmt->fetchAll(PDO::FETCH_ASSOC);
		$stmt->execute([
			":payhistoryid"=>$salary["PayHistoryID"],
			":paytype"=>"Deduction"
		]);
		$deductions = $stmt->fetchAll(PDO::FETCH_ASSOC);

		$payment_data = [];
		/* $payment_data["WkAmount"] = $salary["WkAmountSUM"];
		$payment_data["ReceivedAmount"] = $salary["ReceivedAmountSUM"]; */

		// Employee Info
		$payment_data["EmployeeName"] = $salary["Name"];
		$payment_data["StartDate"] = date("F d, Y", strtotime($salary["StartDate"]));
		$payment_data["EndDate"] =  date("F d, Y", strtotime($salary["EndDate"]));

		// FOR GROSS PAY

		$payment_data["BasicRate"] = $salary["BasicRate"];
		$payment_data["DailyRate"] = $salary["Rate"];
		$payment_data["Additionals"] = $this->getpayhistory($salary["PayHistoryID"], "Additional");
		
		$payment_data["TotalDays"] = $salary["TotalDaysSUM"];
		$payment_data["WkAmountDays"] = $salary["WkAmountDaysSUM"];
		$payment_data["Additional"] = $salary["AdditionalSUM"];
		$payment_data["GrossPay"] = $salary["WkAmountAdditionalSUM"];

		// FOR DEDUCTION

		$payment_data["Deduction"] = $salary["DeductionSUM"];
		$payment_data["Vale"] = $salary["ValeSUM"];
		$payment_data["AdvanceVale"] = $salary["AdvanceValeSUM"];
		$payment_data["Deductions"] = $this->getpayhistory($salary["PayHistoryID"], "Deduction");
		$payment_data["TotalDeduction"] = $salary["DeductionSUM"] + $salary["ValeSUM"] + $salary["AdvanceValeSUM"] + $salary["TotalDeduction"];

		$payment_data["NetPay"] = $salary["ReceivedAmountSUM"] - $salary["TotalDeduction"];

		return $payment_data;
	}

	public function fetch($employeeid) {
		$sql =& $this->sql;
		$query = "SELECT EmployeeID, Name as name, AddedDate, Designation as position, BasicRate as basic_pay, Active as active, TotalAmount as totalamount, TotalDeduction as totaldeduction FROM Employees WHERE EmployeeID=:e_id";
		$stmt = $sql->prepare($query);
		$stmt->execute([":e_id"=>$employeeid]);
		$employee = $stmt->fetch(PDO::FETCH_ASSOC);
		if (empty($employee))
			return array();

		$employee_additionals = $sql->query("SELECT EmployeeADID as _id, Name as name,  Amount as amount FROM EmployeeAD WHERE EmployeeID=".$employee["EmployeeID"]. " AND Type='Additional'")->fetchAll(PDO::FETCH_ASSOC);

		$employee_deductions = $sql->query("SELECT EmployeeADID as _id, Name as name,  Amount as amount FROM EmployeeAD WHERE EmployeeID=".$employee["EmployeeID"]. " AND Type='Deduction'")->fetchAll(PDO::FETCH_ASSOC);
		$employee_remarks = $sql->query("SELECT * FROM EmployeeRemarks WHERE EmployeeID='".$employee["EmployeeID"]."'")->fetchAll(PDO::FETCH_ASSOC);

		$employee["additionals"] = $employee_additionals;
		$employee["deductions"] = $employee_deductions;
		$employee["remarks"] = $employee_remarks;

		return $employee;
	}

	public function remove_ads($args) {
		$sql =& $this->sql;
		$delete_query = "";
		foreach ($args as $a) {
			if (is_numeric($a))
				$delete_query .= "EmployeeADID=".$a." OR ";
		}
		$delete_query = substr($delete_query, 0, -4);
		return $sql->query("DELETE FROM EmployeeAD WHERE ".$delete_query)->rowCount();
	}

	public function save($args) {
		$sql =& $this->sql;
		$stmt = $sql->prepare("SELECT COUNT(*) FROM Employees WHERE EmployeeID=:e_id");
		$stmt->execute([":e_id" => $args["EmployeeID"]]);
		$rCount = $stmt->fetchColumn();
		$result = [];

			// UPDATE
		try {
			
			$sql->beginTransaction();

			if (isset($args["remove_from_server"]) && !empty($args["remove_from_server"]))
				$this->remove_ads($args["remove_from_server"]);

			if ($rCount < 1) {
				$sql->query("INSERT INTO Employees (AddedDate) VALUES (date())");
				$args["EmployeeID"] = $sql->lastInsertId();
			}

			if (isset($args["client_additionals"])) {
				foreach ($args["client_additionals"] as $additional) {
					$stmt = $sql->prepare("INSERT INTO EmployeeAD (EmployeeID, Name, Type, Amount) VALUES (:e_id, :name, 'Additional', :amount)");

					$stmt->execute([":e_id"=>$args["EmployeeID"], ":name" => $additional["name"], ":amount"=>$additional["amount"]]);	
				}
			}

			if (isset($args["client_deductions"])) {
				foreach ($args["client_deductions"] as $deduction) {
					$stmt = $sql->prepare("INSERT INTO EmployeeAD (EmployeeID, Name, Type, Amount) VALUES (:e_id, :name, 'Deduction', :amount)");
					$stmt->execute([":e_id"=>$args["EmployeeID"], ":name" =>$deduction["name"], ":amount"=>$deduction["amount"]]);
				}
			}

			if (isset($args["remarks_client"])) {
				foreach ($args["remarks_client"] as $remarks) {
					$stmt = $sql->prepare("INSERT INTO EmployeeRemarks (EmployeeID, RemarksDate, RemarksText) VALUES (:e_id, :date, :text)");
					$stmt->execute(["e_id"=>$args["EmployeeID"], ":date"=>$remarks["date"], ":text"=>$remarks["text"]]);
				}
			}

			$query = "UPDATE Employees SET Name=:name, Designation=:designation, BasicRate=:basicrate, TotalAmount=(:basicrate + (SELECT COALESCE(SUM(EmployeeAD.Amount), 0) FROM EmployeeAD WHERE EmployeeAD.Type='Additional' AND EmployeeAD.EmployeeID=Employees.EmployeeID)), TotalDeduction=(SELECT COALESCE(SUM(EmployeeAD.Amount), 0) FROM EmployeeAD WHERE EmployeeAD.Type='Deduction' AND EmployeeAD.EmployeeID=Employees.EmployeeID), Active=:active WHERE EmployeeID=:e_id";

			$values = [":name" => $args["name"], ":designation" => $args["position"], ":basicrate" => $args["basic_pay"], ":e_id"=>$args["EmployeeID"], ":active"=>$args["active"]];

			$stmt = $sql->prepare($query);
			$stmt->execute($values);

				
			$this->sql->commit();
			$result = array("success"=>true, "e_id"=>$args["EmployeeID"]);
			die(json_encode($result, JSON_PRETTY_PRINT));
		} catch (Exception $ex) {
			http_response_code(400);
			$this->sql->rollBack();
			$result = ["success"=>false, "reason"=>$ex->getMessage()];
			die(json_encode($result, JSON_PRETTY_PRINT));
		}
				
	}

}