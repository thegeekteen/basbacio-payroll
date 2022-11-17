<?php

class Receipts_model extends CI_Model {
	public $sql = null;

	public function __construct() {
		$this->load->database();
		$this->sql =& $this->db->conn_id;
	}

	public function autoid() {
		$this->sql->sqliteCreateFunction('regexp',
		    function ($pattern, $data, $delimiter = '~', $modifiers = 'isuS')
		    {
		        if (isset($pattern, $data) === true)
		        {
		            return (preg_match(sprintf('%1$s%2$s%1$s%3$s', $delimiter, $pattern, $modifiers), $data) > 0);
		        }

		        return null;
		    }
		);

		$year = date('y');
		$last_id = 1;
		$query = "SELECT MAX(ReceiptNo) as LatestID FROM Aggregates WHERE ReceiptNo REGEXP '^PV-".$year."-\\d{4}$'";
		$res = $this->sql->query($query);
		$res = $res->fetch(PDO::FETCH_ASSOC);


		if (!empty($res) && isset($res) && !is_null($res["LatestID"]) && !empty($res["LatestID"])) {
			$number = (int) explode("-", $res["LatestID"])[2];
			$last_id = $number+1;
		}

		$new_id = "PV-".$year."-".sprintf("%04d", $last_id);
		return $new_id;
	}

	public function print($id) {
		$query = "SELECT * FROM Aggregates WHERE AggregatesID=:aid";
		$stmt = $this->sql->prepare($query);
		if (!$stmt)
			throw new Exception("Aggregates Prepare Error: " . $this->sql->errorInfo()[2]);
		$res = $stmt->execute([":aid"=>$id]);
		if (!$res)
			throw new Exception("Aggregates cannot execute error: ". $this->sql->errorInfo()[2]);

		$receiptInfo = $stmt->fetch(PDO::FETCH_ASSOC);
		if (empty($receiptInfo))
			throw new Exception("Wrong Aggregates ID.");

		$query = "SELECT * FROM AggregatesData WHERE AggregatesID=:aid ORDER BY 'Date' ASC";
		$stmt = $this->sql->prepare($query);
		if (!$stmt)
			throw new Exception("Aggregates Data Prepare Error: " . $this->sql->errorInfo()[2]);
		$res = $stmt->execute([":aid"=>$id]);
		if (!$res)
			throw new Exception("Aggregates Data cannot execute error: ". $this->sql->errorInfo()[2]);

		$rowData = $stmt->fetchAll(PDO::FETCH_ASSOC);

		return ["receiptInfo"=>$receiptInfo, "rowData"=>$rowData];
	}

	public function deleteids($aggregates) {
		try {
			$this->sql->beginTransaction();
			$deletedRows = 0;

			foreach ($aggregates as $aggregatesid) {
				$deleted = $this->delete($aggregatesid);
				$deletedRows += $deleted;
			}

			if ($deletedRows <= 0)
				throw new Exception("There is nothing to delete!");

			$this->sql->commit();
			return $deletedRows;
		} catch (Exception $ex) {
			$this->sql->rollBack();
			throw $ex;
		}
	}

	public function delete($aggregatesid) {
		try {
			$deletedRows = 0;

			$query = "DELETE FROM Aggregates WHERE AggregatesID=:aggregatesid";
			$stmt = $this->sql->prepare($query);
			if (!$stmt)
				throw new Exception("Can't prepare delete statement.");
			$res = $stmt->execute([":aggregatesid"=>$aggregatesid]);
			if (!$res)
				throw new Exception("Failed to delete aggregates");
			$deletedRows += $stmt->rowCount();

			$query = "DELETE FROM AggregatesData WHERE AggregatesID=:aggregatesid";
			$stmt = $this->sql->prepare($query);
			if (!$stmt)
				throw new Exception("Can't prepare delete statement.");
			$res = $stmt->execute([":aggregatesid"=>$aggregatesid]);
			if (!$res)
				throw new Exception("Failed to delete aggregates data");
			$deletedRows += $stmt->rowCount();

			return $deletedRows;
		} catch (Exception $ex) {
			throw $ex;
		}
	}

	public function get($aggregatesid) {

		// get maininfo
		$query = "SELECT * FROM Aggregates WHERE AggregatesID=:aggregatesid";
		$stmt = $this->sql->prepare($query);
		if (!$stmt)
			throw new Exception("We cannot prepare the get statement.");
		$stmt->execute([":aggregatesid"=>$aggregatesid]);
		$mainInfo = $stmt->fetch(PDO::FETCH_ASSOC);

		if (empty($mainInfo))
			throw new Exception("We cannot get main info.");

		// get aggregates data
		$query = "SELECT * FROM AggregatesData WHERE AggregatesID=:aggregatesid";
		$stmt = $this->sql->prepare($query);
		if (!$stmt)
			throw new Exception("We cannot prepare the aggregates data");
		$stmt->execute([":aggregatesid"=>$mainInfo["AggregatesID"]]);

		$aggData = $stmt->fetchAll(PDO::FETCH_ASSOC);

		return ["receiptInfo"=>$mainInfo, "rowData"=>$aggData];
	}

	public function listall() {
		$this->load->helper("SSP");
		// DB table to use
		$table = 'Aggregates';
		 
		// Table's primary key
		$primaryKey = 'AggregatesID';
		 
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
			array( 'db' => 'AggregatesID', 'dt' => "AggregatesID", "formatter"=> function($d, $row) {
		    	return strip_tags($d);
		    }),
		    array( 'db' => 'OwnerName', "dt" => "OwnerName", "formatter"=> function($d, $row) {
		    	return strip_tags($d);
		    }),
		    array( 'db' => 'ReceiptNo', 'dt' => "ReceiptNo", "formatter"=> function($d, $row) {
		    	return strip_tags($d);
		    }),
		    array( 'db' => 'Date', 'dt' => "Date", "formatter"=> function($d, $row) {
		    	return date( 'M j, Y', strtotime($d));
		    }),
		    array( 'db' => 'TotalVolume', 'dt' => "TotalVolume", "formatter"=> function($d, $row) {
		    	return strip_tags($d);
		    }),
		     array( 'db' => 'TotalAmount', 'dt' => "TotalAmount", "formatter"=> function($d, $row) {
		    	return strip_tags($d);
		    }),
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

	public function save($data) {
		try {
			$aggregatesID = $data["receiptInfo"]["AggregatesID"];
			$query = "";

			// start transaction
			$this->sql->beginTransaction();
			// update or insert
			if (!empty($aggregatesID) && is_numeric($aggregatesID) && $aggregatesID > 0)
				$query = "UPDATE Aggregates SET 'OwnerName'=:ownername, ReceiptNo=:receiptno, 'Date'=:date, 'TotalAmount'=:totalamount, 'TotalVolume'=:totalvolume WHERE AggregatesID=".$aggregatesID;
			else
				$query = "INSERT INTO Aggregates ('OwnerName', 'ReceiptNo', 'Date', 'TotalAmount', 'TotalVolume') VALUES (:ownername, :receiptno, :date, :totalamount, :totalvolume)";

			$stmt = $this->sql->prepare($query);
			if (!$stmt)
				throw new Exception("Error in preparing the transaction.");

			$date = date("Y-m-d");
			$res = $stmt->execute([
				":ownername"=>$data["receiptInfo"]["OwnerName"],
				":receiptno"=>$data["receiptInfo"]["ReceiptNo"],
				":date"=>$data["receiptInfo"]["Date"],
				":totalamount"=>$data["receiptInfo"]["TotalAmount"],
				":totalvolume"=>$data["receiptInfo"]["TotalVolume"]
			]);
			if (!$res)
				throw new Exception("Error inserting/updating the data.");

			// get insert id if inserted
			if (!(!empty($aggregatesID) && is_numeric($aggregatesID) && $aggregatesID > 0))
				$aggregatesID = $this->sql->lastInsertId();

			// now lets insert/update the aggregates data.
			foreach ($data["rowData"] as $rowData) {
				$aggregatesdataID = $rowData["AggregatesDataID"];
				$query = "";
				if (!empty($aggregatesdataID) && is_numeric($aggregatesdataID) && $aggregatesdataID > 0)
					$query = "UPDATE AggregatesData SET 'AggregatesID'=:aggregatesid, 'Date'=:date, 'OwnerName'=:ownername, 'PlateNo'=:plateno, 'DRNo'=:drno, 'Item'=:item, 'Length'=:length, 'Width'=:width, 'Height'=:height, 'Volume'=:volume, 'Difference'=:difference, 'UnitPrice'=:unitprice, 'TotalAmount'=:totalamount WHERE AggregatesDataID=".$aggregatesdataID;
				else
					$query = "INSERT INTO AggregatesData ('AggregatesID', 'Date', 'OwnerName', 'PlateNo', 'DRNo', 'Item', 'Length', 'Width', 'Height', 'Volume', 'Difference', 'UnitPrice', 'TotalAmount') VALUES (:aggregatesid, :date, :ownername, :plateno, :drno, :item, :length, :width, :height, :volume, :difference, :unitprice, :totalamount)";
				$stmt = $this->sql->prepare($query);
				if (!$stmt)
					throw new Exception($this->sql->errorInfo()[2]);

				$res = $stmt->execute([
					":aggregatesid"=>$aggregatesID,
					":date"=>$rowData["Date"],
					":ownername"=>$rowData["OwnerName"],
					":plateno"=>$rowData["PlateNo"],
					":drno"=>$rowData["DRNo"],
					":item"=>$rowData["Item"],
					":length"=>$rowData["Length"],
					":width"=>$rowData["Width"],
					":height"=>$rowData["Height"],
					":volume"=>$rowData["Volume"],
					":difference"=>$rowData["Difference"],
					":unitprice"=>$rowData["UnitPrice"],
					":totalamount"=>$rowData["TotalAmount"]
				]);
				if (!$res)
					throw new Exception("Can't insert the aggregates data.");
			}

			// time to delete some aggregates data

			$query = "DELETE FROM AggregatesData WHERE AggregatesDataID=:aggdataid";
			$stmt = $this->sql->prepare($query);
			if (!$res)
				throw new Exception("Can't prepare delete query.");

			foreach ($data["remove_from_server"] as $aggregatesdataid) {
				$res = $stmt->execute([":aggdataid"=>$aggregatesdataid]);
				if (!$res)
					throw new Exception("Cannot execute delete statement");
			}
			
			$this->sql->commit();
			return $aggregatesID;
		} catch (Exception $ex) {
			$this->sql->rollBack();
			throw $ex;
		}
	}
}