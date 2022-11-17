<?php
class Dashboard_model extends CI_Model {
	public $sql = null;

	public function __construct() {
		parent::__construct();
		get_instance()->load->database();
		$this->sql =& $this->db->conn_id;
	}

	public function getvals() {
		$query = "SELECT coalesce((SELECT COUNT(*) FROM Projects WHERE Projects.ProjectDone=0), 0) as PendingProject, coalesce((SELECT COUNT(*) FROM Projects WHERE Projects.ProjectDone=1), 0) as ProjectDone, coalesce((SELECT COUNT(*) FROM Employees WHERE Employees.Active = 0 OR Employees.Active = ''), 0) as InactiveEmployees, coalesce((SELECT COUNT(*) FROM Employees WHERE Employees.Active=1), 0) as ActiveEmployees";
		$stmt = $this->sql->query($query);
		return $stmt->fetch(PDO::FETCH_ASSOC);
	}
}