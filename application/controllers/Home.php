<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Home extends CI_Controller {

	/**
	 * Index Page for this controller.
	 *
	 * Maps to the following URL
	 * 		http://example.com/index.php/welcome
	 *	- or -
	 * 		http://example.com/index.php/welcome/index
	 *	- or -
	 * Since this controller is set as the default controller in
	 * config/routes.php, it's displayed at http://example.com/
	 *
	 * So any other public methods not prefixed with an underscore will
	 * map to /index.php/welcome/<method_name>
	 * @see https://codeigniter.com/user_guide/general/urls.html
	 */
	public function __construct() {
		parent::__construct();
	}

	public function index()
	{
		if ($_SERVER["REQUEST_METHOD"] == "POST")
			$this->onlogin();
		else {
			if (!isset($this->session->logininfo))
				$this->load->view('login');
			else
				redirect(base_url() . "dashboard");
		}
	}

	private function onlogin() {
		$login_info = $this->input->post(NULL, TRUE);
		$this->load->model("config_model");
		$cfg = $this->config_model->getconfig();
		$errorMessage = [];
		if (!isset($login_info["username"]) || empty($login_info["username"]))
			$errorMessage[] = "Please enter your login username";
		if (!isset($login_info["password"]) || empty($login_info["password"]))
			$errorMessage[] = "Please enter your password";


		if (count($errorMessage) <= 0)
			if (hash("ripemd160", $login_info["password"]) !== $cfg["LoginPassword"] || $login_info["username"] !== $cfg["LoginUsername"])
				$errorMessage[] = "The username or password you entered is incorrect";

		if (count($errorMessage) > 0) {
			$this->session->set_flashdata("errorMessage", $errorMessage);
			$this->load->view("login");
			return;
		}

		$_SESSION["logininfo"] = [
			"loginInfo"=>$login_info["username"],
			"timeStamp"=>date("Y-m-d H:i:s")
		];
		redirect(base_url() . "dashboard");
	}

	public function logout() {
		if (isset($this->session->logininfo)){
			unset($_SESSION["logininfo"]);
			$this->session->sess_destroy();
			redirect(base_url()."home");
		}
	}
}
