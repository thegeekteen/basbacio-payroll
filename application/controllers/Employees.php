<?php
class Employees extends CI_Controller {

	public function __construct() {
		parent::__construct();
		if (!isset($this->session->logininfo)) {
			redirect(base_url()."home");
			return;
		}
		$this->load->model("config_model");
		$this->cfg = $this->config_model->getconfig();
	}

	public function index() {
		$page = getviewparts($this->load->view("employees", $this->cfg, true));
		$this->load->view("sbadmin2", $page);
	}

	public function mathparse() {
		$this->load->helper("mathparser");
		$evaluator = new \Matex\Evaluator();
		echo $evaluator->execute('(5*2)+(5*2)');
	}

	public function fetch($employeeid = 0) {
		header("Content-type: text/json");
		$this->load->model("employees_model");
		die(json_encode($this->employees_model->fetch($employeeid), JSON_PRETTY_PRINT));
	}

	public function payslipall($args) {
		$this->load->model("employees_model");

		if (!is_base64_encoded($args))
			show_404();

		$params = json_decode(base64_decode($args), true);
		if (!(json_last_error() === JSON_ERROR_NONE))
			show_404();

		$print_data = [];
		$print_data["Data"] = $this->employees_model->empallsalaries($params["StartDate"]);
		if (count($print_data["Data"]) <= 0) {
			http_response_code(404);
			header("Content-type: text/json");
			die(json_encode(["success"=>false, "reason"=>"No payslips to print!"]));
		}

		$print_data["Info"]["PrintPreparedBy"] = $this->cfg["PrintPreparedBy"];
		$print_data["Info"]["PrintPreparedByPosition"] = $this->cfg["PrintPreparedByPosition"];

		$print_data["Info"]["PrintCheckedBy"] = $this->cfg["PrintCheckedBy"];
		$print_data["Info"]["PrintCheckedByPosition"] = $this->cfg["PrintCheckedByPosition"];

		$print_data["Info"]["PrintReleasedBy"] = $this->cfg["PrintReleasedBy"];
		$print_data["Info"]["PrintReleasedByPosition"] = $this->cfg["PrintReleasedByPosition"];

		$print_data["Info"]["BusinessName"] = $this->cfg["BusinessName"];
		$print_data["Info"]["BusinessAddress"] = $this->cfg["BusinessAddress"];

		$this->load->model("pdf_model");
		$thePdf = $this->pdf_model->printsalary($print_data);
		header("Content-type: application/pdf");
		header("Content-length: " . strlen($thePdf));
		echo $thePdf;
	}

	public function payslipselected($args) {
		if (!is_base64_encoded($args))
			show_404();

		$params = json_decode(base64_decode($args), true);
		if (!(json_last_error() === JSON_ERROR_NONE))
			show_404();

		$this->load->model("employees_model");

		$print_data = [];
		$print_data["Data"] = $this->employees_model->empsalaries($params["EmployeeIDs"], $params["StartDate"]);
			if (count($print_data["Data"]) <= 0) {
			http_response_code(404);
			header("Content-type: text/json");
			die(json_encode(["success"=>false, "reason"=>"No payslips to print!"]));
		}
		$print_data["Info"]["PrintPreparedBy"] = $this->cfg["PrintPreparedBy"];
		$print_data["Info"]["PrintPreparedByPosition"] = $this->cfg["PrintPreparedByPosition"];

		$print_data["Info"]["PrintCheckedBy"] = $this->cfg["PrintCheckedBy"];
		$print_data["Info"]["PrintCheckedByPosition"] = $this->cfg["PrintCheckedByPosition"];

		$print_data["Info"]["PrintReleasedBy"] = $this->cfg["PrintReleasedBy"];
		$print_data["Info"]["PrintReleasedByPosition"] = $this->cfg["PrintReleasedByPosition"];

		$print_data["Info"]["BusinessName"] = $this->cfg["BusinessName"];
		$print_data["Info"]["BusinessAddress"] = $this->cfg["BusinessAddress"];


		$this->load->model("pdf_model");
		$thePdf = $this->pdf_model->printsalary($print_data);
		header("Content-type: application/pdf");
		header("Content-length: " . strlen($thePdf));
		echo $thePdf;
	}

	public function save() {
		$args = json_decode(file_get_contents("php://input"), true);
		if (empty($args))
			die(json_encode(["success"=>false, "reason"=>"invalid request."]));
		$this->load->model("employees_model");
		$this->employees_model->save($args);
	}

	public function delete() {
		$args = json_decode(file_get_contents("php://input"), true);
		if (empty($args))
			die(json_encode(["success"=>false, "reason"=>"invalid request."]));
		$this->load->model("employees_model");
		$res = $this->employees_model->delete_emps($args);
		die(json_encode($res, JSON_PRETTY_PRINT));
	}

	public function list() {
		$this->load->model("employees_model");
		die($this->employees_model->listall());
	}
}