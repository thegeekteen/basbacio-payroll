<?php

class Receipts extends CI_Controller {

	public function __construct() {
		parent::__construct();
		if (!isset($this->session->logininfo)) {
			redirect(base_url()."home");
			return;
		}
		$this->load->model("config_model");
		$this->cfg = $this->config_model->getconfig();
	}

	public function pdftest() {
		$this->load->model("pdf_receipt_model");
		$thePdf = $this->pdf_receipt_model->aggregates("test");
		header("Content-type: application/pdf");
		header("Content-length: " . strlen($thePdf));
		echo $thePdf;
	}

	public function print($id = 0) {
		$this->load->model("receipts_model");
		try {
			$data = $this->receipts_model->print($id);
			$data["Info"] = $this->cfg;
			$this->load->model("pdf_receipt_model");
			$thePdf = $this->pdf_receipt_model->aggregates($data);
			header("Content-type: application/pdf");
			header("Content-length: " . strlen($thePdf));
			echo $thePdf;
		} catch (Exception $ex) {
			http_response_code(400);
			header("Content-type: application/json");
			die(json_encode(["success"=>false, "reason"=>$ex->getMessage()]));
		}
	}

	public function get($id = 0) {
		header("Content-type:application/json");
		$this->load->model("receipts_model");
		try {
			$res = $this->receipts_model->get($id);
			die(json_encode($res));
		} catch (Exception $ex) {
			http_response_code(400);
			$res = ["success"=>false, "reason"=>$ex->getMessage()];
			die(json_encode($res));
		}
	}

	public function delete() {
		header("Content-type:application/json");
		$args = json_decode(file_get_contents("php://input"), true);
		if (empty($args))
			die(json_encode(["success"=>false, "reason"=>"invalid request."]));

		try {
			$this->load->model("receipts_model");
			$deletedRows = $this->receipts_model->deleteids($args);
			die(json_encode(["success"=>true, "deletedRows"=>$deletedRows]));
		} catch (Exception $ex) {
			http_response_code(400);
			die(json_encode(["success"=>false, "reason"=>$ex->getMessage()]));
		}
	}

	public function list() {
		header("Content-type:application/json");
		$this->load->model("receipts_model");
		die($this->receipts_model->listall());
	}

	public function save() {
		header("Content-type:application/json");
		$args = json_decode(file_get_contents("php://input"), true);
		if (empty($args))
			die(json_encode(["success"=>false, "reason"=>"invalid request."]));

		$this->load->model("receipts_model");
		$result = null;
		try {
			$aggID = $this->receipts_model->save($args);
			$result = ["success"=>true, "AggregatesID"=>$aggID, "AutoID"=>$this->receipts_model->autoid()];
		} catch (Exception $ex) {
			http_response_code(400);
			$result = ["success"=>false, "reason"=>$ex->getMessage()];
		}
		die(json_encode($result));
	}
	
	public function index() {
		$this->load->model("receipts_model");
		$page = $this->cfg;
		$page += ["autoId"=>$this->receipts_model->autoid()];
		$page += getviewparts($this->load->view("receipts", $page, true));
		$this->load->view("sbadmin2", $page);
	}
}