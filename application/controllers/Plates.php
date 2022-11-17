<?php

class Plates extends CI_Controller {

	public function __construct() {
		parent::__construct();
		if (!isset($this->session->logininfo)) {
			redirect(base_url()."home");
			return;
		}
		$this->load->model("config_model");
		$this->cfg = $this->config_model->getconfig();
	}

	public function searchPlate($search = "") {
		$this->load->model("plates_model");
		if (empty($search))
			die("[]");
		
		$res = $this->plates_model->searchPlate($search);
		die(json_encode($res, JSON_PRETTY_PRINT));
	}

	public function listall() {
		header("Content-type:application/json");
		$this->load->model("plates_model");
		die($this->plates_model->listall());
	}
	
	public function index() {
		$page = $this->cfg;
		$page += getviewparts($this->load->view("plates", $page, true));
		$this->load->view("sbadmin2", $page);
	}

	public function delete() {

		try {
		$args = json_decode(file_get_contents("php://input"), true);
		if (empty($args))
			throw new Exception("Invalid Request!");

		if (!is_array($args))
			throw new Exception("Request is not an array.");

		foreach ($args as $id) {
			if (!is_numeric($id))
				throw new Exception("Invalid Request Data");
		}

		$this->load->model("plates_model");
		$res = $this->plates_model->delete($args);
		if (!$res)
			throw new Exception("Error Occured in deleting");
		die(json_encode(["success"=>true]));

		} catch (Exception $ex) {
			http_response_code(200);
			die(json_encode(["success"=>false, "reason"=>$ex->getMessage()]));
		}

		return;
	}

	public function manage($id = 0) {
		
		try {

		$this->load->model("plates_model");
		$this->db->conn_id->beginTransaction();
		
		// get data if request is GET.
		if ($_SERVER["REQUEST_METHOD"] == "GET") {
			$json_data = $this->plates_model->get($id);
			header("Content-type:text/json");
			die(json_encode($json_data, JSON_PRETTY_PRINT));
		}


		$args = json_decode(file_get_contents("php://input"), true);
		if (empty($args))
			throw new Exception("Invalid Request!");

		
			$insertid = $this->plates_model->save($args);
			$json_data = $this->plates_model->get($insertid);
			$this->db->conn_id->commit();
			header("Content-type:text/json");
			die(json_encode($json_data, JSON_PRETTY_PRINT));
		} catch (Exception $ex) {
			$this->db->conn_id->rollBack();
			http_response_code(400);
			die($ex->getMessage());
		}
	}
}