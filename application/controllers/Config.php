<?php
class Config extends CI_Controller {
	public function __construct() {
		parent::__construct();
		$this->load->model("config_model");
		$this->cfg = $this->config_model->getconfig(true);
	}

	public function index() {
		$page = getviewparts($this->load->view("config", $this->cfg, true));
		$this->load->view("sbadmin2", $page);
	}

	public function reset() {
		try {
			$args = json_decode(file_get_contents("php://input"), true);
			if (empty($args))
				throw new Exception("invalid request");

			if (!isset($args["Password"]) || empty($args["Password"]))
				throw new Exception("invalid request.");

			if (hash("ripemd160", $args["Password"]) !== $this->cfg["WeeklyLockPassword"])
				throw new Exception("Wrong password supplied in the request.");

			$this->db->conn_id = null;
			$this->db->close();

			$res = unlink(getcwd()."/basbacio-payroll.db");
			if (!$res)
				throw new Exception("Cannot delete the current database.");
			$res = copy(FCPATH."basbacio-payroll.dbdefaults", getcwd()."/basbacio-payroll.db");
			if (!$res)
				throw new Exception("Cannot copy the default database.");

			die(json_encode(["success"=>true]));
		} catch(Exception $ex) {
			die(json_encode(["success"=>false, "reason"=>$ex->getMessage()]));
		}
	
	}

	public function getdefpassword() {
		echo hash("ripemd160", "password");
	}

	public function verifypass() {
		$args = json_decode(file_get_contents("php://input"), true);
		if (empty($args))
			die(json_encode(["success"=>false, "reason"=>"invalid request."]));

		if (!isset($args["password"]) || ($this->cfg["WeeklyLockPassword"] !== hash("ripemd160", $args["password"])))
			die(json_encode(["success"=>false, "reason"=>"Wrong Password"]));
		else
			die(json_encode(["success"=>true]));
	}

	public function dbdownload() {
		try {
			$args = json_decode(file_get_contents("php://input"), true);
			if (empty($args))
				throw new Exception("invalid request");

			if (!isset($args["Password"]) || empty($args["Password"]))
				throw new Exception("invalid request.");

			if (hash("ripemd160", $args["Password"]) !== $this->cfg["WeeklyLockPassword"])
				throw new Exception("Wrong password supplied in the request.");

			header("Content-type: application/x-sqlite3");
			header("Content-length: " . filesize(getcwd()."/basbacio-payroll.db"));
			echo file_get_contents(getcwd()."/basbacio-payroll.db");
			die();
		} catch (Exception $ex) {
			http_response_code(400);
			header("Content-type: text/json");
			die(json_encode(["success"=>false, "reason"=>$ex->getMessage()]));
		}
	}

	public function files() {

		try {
			if (empty($this->input->post("password")))
				throw new Exception("Please include the password in the request");

			if (hash("ripemd160", $this->input->post("password")) !== $this->cfg["WeeklyLockPassword"])
				throw new Exception("Wrong password");

			$isValid = true;
			$reason = "";
			$db = new PDO("sqlite:".$_FILES["backupFile"]["tmp_name"]);

			$query = "SELECT ConfigID, ClassName, BusinessName, BusinessAddress, LoginUsername, LoginPassword, WeeklyLockPassword, PrintPreparedBy, PrintCheckedBy, PrintReleasedBy, PrintPreparedByPosition, PrintCheckedByPosition, PrintReleasedByPosition FROM Config WHERE ConfigID=1";
			$stmt = $db->prepare($query);
			if (!$stmt) {
				$isValid = false;
				$reason = "Config Table: " . json_encode($db->errorInfo());
			} else if (!$stmt) {
				$stmt->execute();
				if (!$stmt->fetch(PDO::FETCH_ASSOC)) {
					$isValid = false;
					$reason = "No Config Available on this database";
				}
			}

			$query = "SELECT EmployeeADID, EmployeeID, Name, Type, Amount FROM EmployeeAD";
			$stmt = $db->prepare($query);
			if (!$stmt) {
				$isValid = false;
				$reason = "EmployeeADID Table: " . json_encode($db->errorInfo());
			}

			$query = "SELECT EmployeeID, ProjectID, Name, AddedDate, Designation, BasicRate, TotalAmount, Active FROM Employees";
			$stmt = $db->prepare($query);
			if (!$stmt) {
				$isValid = false;
				$reason = "Employees Table: " . json_encode($db->errorInfo());
			}


			$query = "SELECT PayHistoryID, ProjectWeeklyDataID, ProjectWeeklyID, ProjectID FROM PayHistory";
			$stmt = $db->prepare($query);
			if (!$stmt) {
				$isValid = false;
				$reason = "PayHistory Table: " . json_encode($db->errorInfo());
			}

			$query = "SELECT PayHistoryDataID, PayHistoryID, PayName, PayType, PayAmount FROM PayHistoryData";
			$stmt = $db->prepare($query);
			if (!$stmt) {
				$isValid = false;
				$reason = "PayHistoryData Table: " . json_encode($db->errorInfo());
			}

			$query = "SELECT ProjectWeeklyID, ProjectID, LockStatus, AddedDate, StartDate, EndDate FROM ProjectWeekly";
			$stmt = $db->prepare($query);
			if (!$stmt) {
				$isValid = false;
				$reason = "ProjectWeekly Table: " . json_encode($db->errorInfo());
			}

			$query = "SELECT ProjectWeeklyDataID, ProjectID, ProjectWeeklyID, EmployeeID, Rate, Saturday, Sunday, Monday, Tuesday, Wednesday, Thursday, Friday, TotalDays, Additional, Deduction, WkAmount, WkAmountDays, WkAmountAdditional, Vale, AdvanceVale, ReceivedAmount, Remarks, PayHistoryID, BasicRate, Arrangement FROM ProjectWeeklyData";
			$stmt = $db->prepare($query);
			if (!$stmt) {
				$isValid = false;
				$reason = "ProjectWeeklyData Table: " . json_encode($db->errorInfo());
			}

			$query = "SELECT ProjectID, AddedDate, ProjectName, StartDate, EndDate, ProjectDone FROM Projects";
			$stmt = $db->prepare($query);
			if (!$stmt) {
				$isValid = false;
				$reason = "Projects Table: " . json_encode($db->errorInfo());
			}


			if (!$isValid)
				throw new Exception("Invalid Database File. [".$reason."]");

			$this->db->conn_id = null;
			$this->db->close();


			if (file_exists(getcwd()."/"."basbacio-payroll.db.old"))
				unlink(getcwd()."/"."basbacio-payroll.db.old");

			$res = rename(getcwd()."/"."basbacio-payroll.db", getcwd()."/"."basbacio-payroll.db.old");
			if (!$res)
				throw new Exception("Cannot rename old database.");
			$res = move_uploaded_file($_FILES["backupFile"]["tmp_name"], getcwd()."/"."basbacio-payroll.db");
			if (!$res)
				throw new Exception("Cannot save new database.");

			die(json_encode(["success"=>true]));
		} catch (Exception $ex) {
			die(json_encode(["success"=>false, "reason"=>$ex->getMessage()]));
		}
	}

	public function manageconfig() {
		// $args = file_get_contents("php://input");
		$this->load->model("config_model");
		if ($_SERVER["REQUEST_METHOD"] == "GET")
			die(json_encode($this->config_model->manage(null)));

		if ($_SERVER["REQUEST_METHOD"] !== "PUT" && $_SERVER["REQUEST_METHOD"] !== "PATCH")
			die(json_encode(["success"=>false, "reason"=>"invalid request"]));

		$args = file_get_contents("php://input");
		if (empty($args))
			die(json_encode(["success"=>false, "reason"=>"no arguments specified!"], JSON_PRETTY_PRINT));

		$args = json_decode($args, true);
		if (json_last_error() !== JSON_ERROR_NONE)
			die(json_encode(["success"=>false, "reason"=>"invalid json!"], JSON_PRETTY_PRINT));

		$res = $this->config_model->manage($args);
		if ($res["success"])
			unset($res["success"]);
		else 
			http_response_code(400);

		die(json_encode($res, JSON_PRETTY_PRINT));
	}
}