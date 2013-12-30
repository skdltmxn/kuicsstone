<?php
	/*
	 * DB class
	 */
	
	if (!defined("READY")) exit();

	@require_once("lib/Singleton.php");

	class DB extends Singleton
	{
		private $_handle = null;
		private $_inTransaction;

		protected function __construct()
		{
			if (!file_exists("conf/access.php"))
				exit("DB access info not found");

			@include_once("conf/access.php");

			$this->_handle = @mysqli_connect($accountInfo["host"],
											 $accountInfo["id"],
											 $accountInfo["pw"],
											 $accountInfo["db"]);

			if (mysqli_connect_errno())
				exit(mysqli_connect_error());

			$this->_query("set names 'utf8';");
			$this->_inTransaction = false;
		}

		public function __destruct()
		{
			if ($this->_handle !== null)
				@mysqli_close($this->_handle);
		}

		public function query($query)
		{
			return $this->_query($query);
		}

		public function insert($table, $args)
		{
			$query = "insert into `" . $table . "` (`";
			$query .= implode("`, `", array_keys($args)) . "`) ";
			$query .= "values ('";
			$query .= implode("', '", array_values($args)) . "');";

			$this->_query($query);
		}

		public function select($query, $alwaysArray = false)
		{
			$result = $this->_query($query);
			$ret = array();

			while ($obj = @mysqli_fetch_object($result))
				$ret[] = $obj;

			@mysqli_free_result($result);

			if (count($ret) == 0)
				return null;

			if (count($ret) == 1 && !$alwaysArray)
				return $ret[0];

			return $ret;
		}

		public function begin()
		{
			@mysqli_autocommit($this->_handle, false);
			$this->_inTransaction = true;
			register_shutdown_function(array($this, "shutdown"));
		}

		public function commit()
		{
			@mysqli_commit($this->_handle);
			$this->_inTransaction = false;
			@mysqli_autocommit($this->_handle, true);
		}

		public function rollback()
		{
			@mysqli_rollback($this->_handle);
			$this->_inTransaction = false;
			@mysqli_autocommit($this->_handle, true);
		}

		public function shutdown()
		{
			if ($this->_inTransaction)
				$this->rollback();
		}

		private function _query($query)
		{
			$result = @mysqli_query($this->_handle, $query);
			if ($result === false)
			{
				if ($this->_inTransaction)
					$this->rollback();

				exit(sprintf("failed to query <strong>%s</strong>", $query));
			}

			return $result;
		}
	}

?>