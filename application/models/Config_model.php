<?php

class Config_model extends CI_Model {

	var $sql;

	public function __construct() {
		$this->load->database();
		$this->sql =& $this->db->conn_id;
	}

	public function manage($args = []) {

		if ($args == null) {
			$current_config = $this->getconfig(true);
			$current_config["WeeklyLockPassword"] = "";
			$current_config["LoginPassword"] = "";
			return $current_config;
		}


		try {
			$this->sql->beginTransaction();
			$current_config = $this->getconfig();

			if (isset($args["LoginPassword"]) && !empty($args["LoginPassword"])) {
				if (!isset($args["OldLoginPassword"]))
					throw new Exception("Please enter the old password!");

				if ($current_config["LoginPassword"] !== hash("ripemd160", $args["OldLoginPassword"]))
					throw new Exception(hash("ripemd160", $current_config["LoginPassword"]));
			}

			if (isset($args["WeeklyLockPassword"]) && !empty($args["WeeklyLockPassword"])) {
				if (!isset($args["OldWeeklyLockPassword"]))
					throw new Exception("Please enter the old weekly lock password!");

				if ($current_config["WeeklyLockPassword"] !== hash("ripemd160", $args["OldWeeklyLockPassword"]))
					throw new Exception("Old Weekly Lock Password is incorrect!");
			}


			$query = "UPDATE Config SET ClassName=:classname, BusinessName=:businessname, BusinessAddress=:businessaddress, LoginUsername=:username, LoginPassword=:password, WeeklyLockPassword=:wpassword, PrintPreparedBy=:preparedby, PrintCheckedBy=:checkedby, PrintReleasedBy=:releasedby, PrintPreparedByPosition=:preparedby_position, PrintCheckedByPosition=:checkedby_position, PrintReleasedByPosition=:releasedby_position WHERE ConfigID=:configid";
			$stmt = $this->sql->prepare($query);

			$values = [
				":classname"=>(isset($args["ClassName"]) && !empty($args["ClassName"]) ? $args["ClassName"] : $current_config["ClassName"]),
				":businessname"=>(isset($args["BusinessName"]) && !empty($args["BusinessName"]) ? $args["BusinessName"] : $current_config["BusinessName"]),
				":businessaddress"=>(isset($args["BusinessAddress"]) && !empty($args["BusinessAddress"]) ? $args["BusinessAddress"] : $current_config["BusinessAddress"]),
				":username"=>(isset($args["LoginUsername"]) && !empty($args["LoginUsername"]) ? $args["LoginUsername"] : $current_config["LoginUsername"]),
				":password"=>(isset($args["LoginPassword"]) && !empty($args["LoginPassword"]) ? hash("ripemd160", $args["LoginPassword"]) : $current_config["LoginPassword"]),
				":wpassword"=>(isset($args["WeeklyLockPassword"]) && !empty($args["WeeklyLockPassword"]) ? hash("ripemd160", $args["WeeklyLockPassword"]) : $current_config["WeeklyLockPassword"]),
				":preparedby"=>(isset($args["PrintPreparedBy"]) && !empty($args["PrintPreparedBy"]) ? $args["PrintPreparedBy"] : $current_config["PrintPreparedBy"]),
				":checkedby"=>(isset($args["PrintCheckedBy"]) && !empty($args["PrintCheckedBy"]) ? $args["PrintCheckedBy"] : $current_config["PrintCheckedBy"]),
				":releasedby"=>(isset($args["PrintReleasedBy"]) && !empty($args["PrintReleasedBy"]) ? $args["PrintReleasedBy"] : $current_config["PrintReleasedBy"]),
				":preparedby_position"=>(isset($args["PrintPreparedByPosition"]) && !empty($args["PrintPreparedByPosition"]) ? $args["PrintPreparedByPosition"] : $current_config["PrintPreparedByPosition"]),
				":checkedby_position"=>(isset($args["PrintCheckedByPosition"]) && !empty($args["PrintCheckedByPosition"]) ? $args["PrintCheckedByPosition"] : $current_config["PrintCheckedByPosition"]),
				":releasedby_position"=>(isset($args["PrintReleasedByPosition"]) && !empty($args["PrintReleasedByPosition"]) ? $args["PrintReleasedByPosition"] : $current_config["PrintReleasedByPosition"]),
				":configid"=>1
			];
			$stmt->execute($values);

			$new_config = $this->getconfig();
			$new_config["LoginPassword"] = "";
			$new_config["WeeklyLockPassword"] = "";
			$new_config["success"] = true;

			$this->sql->commit();
			return $new_config;

		} catch (Exception $ex) {
			$this->sql->rollBack();
			return ["success"=>false, "reason"=>$ex->getMessage()];
		}
		
	}

	public function getconfig($withcount = false) {
		if ($withcount)
			$query = "SELECT *, (SELECT COUNT(*) FROM Projects) as ProjectCount, (SELECT COUNT(*) FROM Employees) as EmployeeCount, (SELECT COUNT(*) FROM ProjectWeekly) as ProjectWeeklyCount, (SELECT COUNT(*) FROM ProjectWeeklyData) as ProjectWeeklyDataCount FROM Config WHERE ConfigID=?";
		else
			$query = "SELECT * FROM Config WHERE ConfigID=?";

		$stmt = $this->sql->prepare($query);
		$stmt->execute([1]);
		$config = $stmt->fetch(PDO::FETCH_ASSOC);
		return $config;
	}

}