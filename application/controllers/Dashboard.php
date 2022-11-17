<?php

class Dashboard extends CI_Controller {

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
		$this->load->model("dashboard_model");
		$page = $this->dashboard_model->getvals();
		$page += $this->cfg;
		$page += getviewparts($this->load->view("dashboard", $page, true));
		$this->load->view("sbadmin2", $page);
	}
}