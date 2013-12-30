<?php
	/*
	 * loggerModel class
	 */
	
	if (!defined("READY")) exit();

	@require_once("modules/logger/logger.php");
	@require_once("lib/db/DB.php");

	class loggerModel
	{
		public function addLog($args)
		{
			$db = &DB::getInstance();
			$db->insert(logger::TBL_LOG, $args);
		}

		public function getLogList($type = -1, $page = 1)
		{
			$db = &DB::getInstance();
			$query = sprintf("select * from `%s`", logger::TBL_LOG);

			if ($type > 0)
				$query .= sprintf(" where `type` = '%s'", $type);

			$count = count($db->select($query, true));

			$offset = ($page - 1) * logger::LOG_PER_PAGE;
			$query .= sprintf(" order by `regdate` desc limit %s, %s;", $offset, logger::LOG_PER_PAGE);

			$result = $db->select($query, true);

			$list = array();
			$list["list"] = $result;
			$list["count"] = $count;

			return $list;
		}
	}
?>