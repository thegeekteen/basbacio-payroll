<?php

class Viewproject extends CI_Controller {

	public function __construct() {
		parent::__construct();
		if (!isset($this->session->logininfo)) {
			redirect(base_url()."home");
			return;
		}
		$this->load->model("config_model");
		$this->cfg = $this->config_model->getconfig();
	}

	public function index($projectid = 0) {
		$this->load->model("viewproject_model");
		$project = $this->viewproject_model->fetchall($projectid);
		if (!isset($project["ProjectID"]))
			show_404();

		$data["project"] = $project;
		$data += $this->cfg;
		$data += getviewparts($this->load->view("viewproject", $data, true));
		$this->load->view("sbadmin2", $data);
	}

	public function lockunlock() {
		$cts = file_get_contents("php://input");
		$args = [];
		if (!empty($cts))
			$args = json_decode($cts, true);
		else
			die(json_encode(["success"=>false, "reason"=>"invalid request"]));

		if (json_last_error() !== JSON_ERROR_NONE)
			die(json_encode(["success"=>false, "reason"=>"Invalid request parameters."]));


		$this->load->model("viewproject_model");
		$res = $this->viewproject_model->lockunlock($args["ProjectWeeklyID"], $args["WeeklyLockPassword"]);
		die(json_encode($res, JSON_PRETTY_PRINT));
	}


	public function delete() {
		$cts = file_get_contents("php://input");
		$args = [];
		if (!empty($cts))
			$args = json_decode($cts, true);
		else
			die(json_encode(["success"=>false, "reason"=>"nothing to delete!"]));
		$this->load->model("viewproject_model");
		$res = $this->viewproject_model->deleteids($args);
		die(json_encode($res, JSON_PRETTY_PRINT));
	}

	public function weeklydata($wkid) {
		$this->load->model("viewproject_model");
		return json_encode($this->viewproject_model->weeklydata($wkid), JSON_PRETTY_PRINT);
	}

	public function weekmanage($wkid = 0, $args = []) {
		$cts = file_get_contents("php://input");
		if (!empty($cts))
			$args = json_decode($cts, true);
		$this->load->model("viewproject_model");
		die(json_encode($this->viewproject_model->weekmanage($wkid, $args), JSON_PRETTY_PRINT));
	}

	public function post_test() {
		echo file_get_contents("php://input");
	}

	public function reset() {
		$this->load->model("viewproject_model");
		$query = "DELETE FROM ProjectWeeklyData;
					DELETE FROM ProjectDays;
					DELETE FROM PayHistory;
					DELETE FROM PayHistoryData;";
		$this->viewproject_model->custom_query($query);
	}

	public function empsalary($employeeid, $startdate) {
		$this->load->model("viewproject_model");
		$res = $this->viewproject_model->empsalaries([1,2], $startdate);
		header("Content-type: text/json");
		die(json_encode($res, JSON_PRETTY_PRINT));
	}

	public function test() {
		$this->load->model("pdf_model");
		$this->pdf_model->printsalary();
	}


	public function add_employees($projectweeklydataid) {
		$cts = file_get_contents("php://input");
		if (!empty($cts)) {
			$args = json_decode($cts, true);
			if (count($args) <= 0)
				die(json_encode(["success"=>false, "reason"=>"No employees to add."]));
		}
		else
			die(json_encode(["success"=>false, "reason"=>"No employees to add."]));
		$this->load->model("viewproject_model");
		echo json_encode($this->viewproject_model->add_employees($projectweeklydataid, $args));
	}

	public function printpdf($projectweeklyid = 0, $printmode = 1) {
		$this->load->model("viewproject_model");
		$this->load->model("pdf_model");
		$print_data = [];
		$print_data = $this->viewproject_model->printpdf($projectweeklyid);
		if (count($print_data) <= 0) {
			http_response_code(404);
			header("Content-type: application/json");
			die(json_encode(["success"=>false, "reason"=>"No employees in project data"]));
		}
		$print_data["Info"]["PrintMode"] = $printmode;
		$print_data["Info"]["PrintPreparedBy"] = $this->cfg["PrintPreparedBy"];
		$print_data["Info"]["PrintPreparedByPosition"] = $this->cfg["PrintPreparedByPosition"];

		$print_data["Info"]["PrintCheckedBy"] = $this->cfg["PrintCheckedBy"];
		$print_data["Info"]["PrintCheckedByPosition"] = $this->cfg["PrintCheckedByPosition"];

		$print_data["Info"]["PrintReleasedBy"] = $this->cfg["PrintReleasedBy"];
		$print_data["Info"]["PrintReleasedByPosition"] = $this->cfg["PrintReleasedByPosition"];
		$thepdf = $this->pdf_model->printweeklydata($print_data);

		header("Content-type: application/pdf");
		header("Content-length: " . strlen($thepdf));
		echo $thepdf;
		die();
	}

	public function getdaterange($projectweeklyid) {
		$this->load->model("viewproject_model");
		echo json_encode($this->viewproject_model->datesfromweeklyid($projectweeklyid));
	}


	public function addrange($projectid = 0) {
		$args = json_decode(file_get_contents("php://input"), true);
		if (
			empty($args) || 
			!isset($args["StartDate"]) || 
			empty($args["StartDate"]) || 
			!preg_match("/^[0-9]{4}(-|\/)([1-9]|0[1-9]|1[0-2])(-|\/)([1-9]|0[1-9]|[1-2][0-9]|3[0-1])$/", $args["StartDate"]) || !is_numeric($projectid)
		)
			die(json_encode(["success"=>false, "reason"=>"invalid request."]));

		$this->load->model("viewproject_model");
		$res = $this->viewproject_model->addrange($projectid, $args["StartDate"]);
		die(json_encode($res, JSON_PRETTY_PRINT));
	}
}