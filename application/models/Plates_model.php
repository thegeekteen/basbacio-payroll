<?php

class Plates_model extends CI_Model {

	public $sql = null;

	public function __construct() {
		$this->load->database();
		$this->sql =& $this->db->conn_id;
	}

	public function searchPlate($search) {
		header("Content-type:application/json");
		$query = "SELECT * FROM LicensePlates WHERE PlateNo LIKE :search";
		$stmt = $this->sql->prepare($query);
		$needle = "%".$search."%";
		$stmt->bindValue(":search", $needle, PDO::PARAM_STR);
		$stmt->execute();
		$data = $stmt->fetchAll(PDO::FETCH_ASSOC);
		return $data;
	}

	public function delete($ids) {
		try {
			$this->sql->beginTransaction();
			foreach ($ids as $id) {
				$query = "DELETE FROM LicensePlates WHERE LicensePlateID=:id";
				$stmt = $this->sql->prepare($query);
				if (!$stmt)
					throw new Exception("Can't prepare for delete command.");
				$res = $stmt->execute([":id"=>$id]);
				if (!$res)
					throw new Exception("Can't delete the data: " . $id);
			}
			$this->sql->commit();
			return true;
		} catch (Exception $ex) {
			$this->sql->rollBack();
			throw $ex;
		}
	}

	public function listall() {
		$this->load->helper("SSP");
		// DB table to use
		$table = 'LicensePlates';
		 
		// Table's primary key
		$primaryKey = 'LicensePlateID';
		 
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
			array( 'db' => 'LicensePlateID', 'dt' => "LicensePlateID", "formatter"=> function($d, $row) {
		    	return strip_tags($d);
		    }),
		    array( 'db' => 'OwnerName', 'dt' => 'OwnerName', "formatter"=> function($d, $row) {
		    	return strip_tags($d);
		    }),
		    array( 'db' => 'PlateNo',  'dt' => 'PlateNo', 'formatter' => function( $d, $row ) {
		         return strip_tags($d);
		    }),
		    array( 'db' => 'Length',   'dt' => 'Length', "formatter"=> function($d, $row) {
		    	return strip_tags($d);
		    }),
		    array( 'db' => 'Width',     'dt' => 'Width', "formatter"=> function($d, $row) {
		    	return strip_tags($d);
		    })
		);
		 
		// SQL server connection information
		 
		/* * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * *
		 * If you just want to use the basic configuration for DataTables with PHP
		 * server-side, there is no need to edit below this line.
		 */
		 return json_encode(
		    SSP::simple( $_GET, $this->sql, $table, $primaryKey, $columns ), JSON_PRETTY_PRINT
		);
	}


	public function get($licenseplateid) {
		$query = "SELECT * FROM LicensePlates WHERE LicensePlates.LicensePlateID=:lid";
		$stmt = $this->sql->prepare($query);
		if (empty($stmt))
			throw new Exception("Can't get the license plate information (1)");
		$stmt->execute([":lid"=>$licenseplateid]);
		$data = $stmt->fetch(PDO::FETCH_ASSOC);
		if (empty($data))
			throw new Exception("Can't get the license plate information (2)");
		return $data;
	}

	public function save($data) {
		if (isset($data["LicensePlateID"]) && !empty($data["LicensePlateID"]) && is_numeric($data["LicensePlateID"]) && $data["LicensePlateID"] > 0) {

			//Update pre-existing data..
			$stmt = $this->sql->prepare("UPDATE LicensePlates SET OwnerName=:ownername, PlateNo=:plateno, Length=:length, Width=:width WHERE LicensePlateID=:licenseplateid");
			if (!$stmt)
				throw new Exception("SQL Error Occured: " . $this->sql->errorInfo()[2]);
			$res = $stmt->execute([":ownername"=>$data["OwnerName"], "plateno"=>$data["PlateNo"], "length"=>$data["Length"], "width"=>$data["Width"], "licenseplateid"=>$data["LicensePlateID"]]);
			if (!$res)
				throw new Exception("Cannot update license plate data.");

			return $data["LicensePlateID"];
		} else {
			// Insert pre-existing data
			$stmt = $this->sql->prepare("INSERT INTO LicensePlates (OwnerName, PlateNo, Length, Width) VALUES (:ownername, :plateno, :length, :width)");
			if (!$stmt)
				throw new Exception("SQL Error Occured: " . $this->sql->errorInfo()[2]);
			$res = $stmt->execute([":ownername"=>$data["OwnerName"], "plateno"=>$data["PlateNo"], "length"=>$data["Length"], "width"=>$data["Width"]]);
			if (!$res)
				throw new Exception("Cannot insert license plate data.");
			return $this->sql->lastInsertId();
		}
	}
}