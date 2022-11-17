<?php

class Project_model extends CI_Model {

	var $sql;

	public function __construct() {
		$this->load->database();
		$this->sql =& $this->db->conn_id;
	}

	public function delete_projects($projectids = []) {
		try {
			$this->sql->beginTransaction();
			foreach ($projectids as $projectid) {
				$res = $this->delete_project($projectid, false);
				if (!$res["success"])
					throw new Exception("ProjectID: " . $projectid . " | Error: " . $res["reason"]);
			}
			$this->sql->commit();
			return ["success"=>true];
		} catch (Exception $ex) {
			$this->sql->rollBack();
			return ["success"=>false, "reason"=>$ex->getMessage()];
		}
	}

	public function delete_project($projectid, $transaction = true) {
		try {
			if ($transaction)
				$this->sql->beginTransaction();

			$query = "DELETE FROM ProjectWeekly WHERE ProjectID=:projectid";
			$stmt = $this->sql->prepare($query);
			$stmt->execute([":projectid"=>$projectid]);

			$query = "DELETE FROM ProjectWeeklyData WHERE ProjectID=:projectid";
			$stmt = $this->sql->prepare($query);
			$stmt->execute([":projectid"=>$projectid]);

			$query = "DELETE FROM PayHistoryData WHERE PayHistoryDataID IN (SELECT PayHistoryData2.PayHistoryDataID FROM PayHistoryData as PayHistoryData2 INNER JOIN PayHistory ON PayHistory.PayHistoryID=PayHistoryData2.PayHistoryID WHERE PayHistory.ProjectID=:projectid)";
			$stmt = $this->sql->prepare($query);
			$stmt->execute([":projectid"=>$projectid]);

			$query = "DELETE FROM PayHistory WHERE ProjectID=:projectid";
			$stmt = $this->sql->prepare($query);
			$stmt->execute([":projectid"=>$projectid]);

			$query = "DELETE FROM Projects WHERE ProjectID=:projectid";
			$stmt = $this->sql->prepare($query);
			$stmt->execute([":projectid"=>$projectid]);

			if ($transaction)
				$this->sql->commit();

			return ["success"=>true];
		} catch (Exception $ex) {
			if ($transaction)
				$this->sql->rollBack();
			return ["success"=>false, "reason"=>$ex->getMessage()];
		}
	}
	
	public function fetch($projectid) {
		$query = "SELECT * FROM Projects WHERE ProjectID=:projectid";
		$vals = [
			"projectid"=>$projectid
		];
		try {
			$stmt = $this->sql->prepare($query);
			$stmt->execute($vals);
			$project = $stmt->fetch(PDO::FETCH_ASSOC);
			if (empty($project))
				return [];

			return $project;
		} catch (PDOException $ex) {
			http_response_code(400);
			return [];
		}
	}

	public function save($args) {
		if (isset($args["ProjectID"]) && !empty($args["ProjectID"])) {
			// UPDATE
				$query = "UPDATE Projects SET ProjectName=:project_name, StartDate=:startdate, EndDate=:enddate, ProjectDone=:projectdone WHERE ProjectID=:project_id";
				$vals = [
					"project_id"=>$args["ProjectID"],
					"project_name"=>$args["ProjectName"],
					"startdate"=>$args["StartDate"],
					"enddate"=>$args["EndDate"],
					"projectdone"=>$args["ProjectDone"]
				];
				try {
					$stmt = $this->sql->prepare($query);
					$stmt->execute($vals);
					return ["success"=>true];
				} catch (PDOException $ex) {
					return ["success"=>false, "reason"=>$ex->getMessage()];
				}

			} else {
			// NEW ENTRY
			$query = "INSERT INTO Projects (ProjectName, AddedDate, StartDate, EndDate, ProjectDone) VALUES (:project_name, date('now'), :startdate, :enddate, :projectdone)";
			$vals = [
				"project_name"=>$args["ProjectName"],
				"startdate"=>$args["StartDate"],
				"enddate"=>$args["EndDate"],
				"projectdone"=>$args["ProjectDone"]
			];
			try {
				$stmt = $this->sql->prepare($query);
				$stmt->execute($vals);
				return ["success"=>true, "insert_id"=>$this->sql->lastInsertId()];
			} catch (PDOException $ex) {
				return ["success"=>false, "reason"=>$ex->getMessage()];
			}
		}
	}

	public function list() {
	$this->load->helper("SSP");
		// DB table to use
		$table = 'Projects';
		 
		// Table's primary key
		$primaryKey = 'ProjectID';
		 
		// Array of database columns which should be read and sent back to DataTables.
		// The `db` parameter represents the column name in the database, while the `dt`
		// parameter represents the DataTables column identifier. In this case simple
		// indexes
		/* $columns = array(
		    array( 'db' => 'first_name', 'dt' => 0 ),
		    array( 'db' => 'last_name',  'dt' => 1 ),
		    array( 'db' => 'position',   'dt' => 2 ),
		    array( 'db' => 'office',     'dt' => 3 ),
		    array(
		        'db'        => 'start_date',
		        'dt'        => 4,
		        'formatter' => function( $d, $row ) {
		            return date( 'jS M y', strtotime($d));
		        }
		    ),
		    array(
		        'db'        => 'salary',
		        'dt'        => 5,
		        'formatter' => function( $d, $row ) {
		            return '$'.number_format($d);
		        }
		    )
		); */
		$columns = array(
			array( 'db' => 'ProjectID', 'dt' => 0, "formatter"=> function($d, $row) {
		    	return strip_tags($d);
		    }),
		    array( 'db' => 'ProjectName', 'dt' => 2, "formatter"=> function($d, $row) {
		    	return strip_tags($d);
		    }),
		    array( 'db' => 'AddedDate',  'dt' => 3, 'formatter' => function( $d, $row ) {
		            return date( 'M j, Y', strtotime($d));
		    }),
		    array( 'db' => 'StartDate',   'dt' => 4, 'formatter' => function( $d, $row ) {
		            return date( 'M j, Y', strtotime($d));
		    }),
		    array( 'db' => 'EndDate',     'dt' => 5, 'formatter' => function( $d, $row ) {
		            return date( 'M j, Y', strtotime($d));
		    } ),
		    array( 'db' => 'ProjectDone',     'dt' => 6, 'formatter' => function($d, $row) {
		    	return (!empty($d) ? "Yes" : "No");
		    })
		);
		 
		// SQL server connection information
		$sql_details =& $this->sql;
		 
		/* * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * *
		 * If you just want to use the basic configuration for DataTables with PHP
		 * server-side, there is no need to edit below this line.
		 */
		 echo json_encode(
		    SSP::simple( $_GET, $sql_details, $table, $primaryKey, $columns ), JSON_PRETTY_PRINT
		);
	}

}