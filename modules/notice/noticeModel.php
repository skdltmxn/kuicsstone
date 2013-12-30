<?php
	/*
	 * noticeModel class
	 */

	if (!defined("READY")) exit();

	@require_once("lib/db/DB.php");

	class noticeModel
	{
		public function __construct()
		{
		}

		public function getNoticeList()
		{
			$db = &DB::getInstance();
			return $db->select(sprintf("select * from `%s` order by `regdate` desc;", notice::TBL_NOTICE), true);
		}
	}
?>