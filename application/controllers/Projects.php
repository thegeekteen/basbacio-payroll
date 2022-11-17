<?php

class Projects extends CI_Controller {
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
		$page = getviewparts($this->load->view("projects", $this->cfg, true));
		$this->load->view("sbadmin2", $page);
	}

	public function delete() {
		$args = json_decode(file_get_contents("php://input"), true);
		if (empty($args))
			die(json_encode(["success"=>false, "reason"=>"invalid request."]));
		$this->load->model("project_model");
		$res = $this->project_model->delete_projects($args);
		die(json_encode($res, JSON_PRETTY_PRINT));
	}

	public function list() {
		$this->load->model("project_model");
		$this->project_model->list();
	}

	public function fetch($projectid = 0) {
		$this->load->model("project_model");
		$project = $this->project_model->fetch($projectid);
		die(json_encode($project, JSON_PRETTY_PRINT));
	}

	public function save() {
		$args = json_decode(file_get_contents("php://input"), true);
		if (empty($args))
			die(json_encode(["success"=>false, "reason"=>"invalid request."]));
		$this->load->model("project_model");
		$saveinfo = $this->project_model->save($args);
		if (!$saveinfo["success"])
			http_response_code(400);
		die(json_encode($saveinfo, JSON_PRETTY_PRINT));
	}
}