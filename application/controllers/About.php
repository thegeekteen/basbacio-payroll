<?php
class About extends CI_Controller {
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
		$page = getviewparts($this->load->view("about", $this->cfg, true));
		$this->load->view("sbadmin2", $page);
	}
}