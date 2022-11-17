<?php
class Dbconn {
	public static $conn = null;

	public static function load_db($connection) {
		// $this->config->item("sql_connection")
		if (self::$conn == null) {
			self::$conn = new PDO($connection);
			self::$conn->setAttribute(PDO::ATTR_PERSISTENT, true);
			self::$conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
		}
		return self::$conn;
	}

}