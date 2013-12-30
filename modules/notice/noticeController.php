<?php
	/*
	 * noticeController class
	 */
	
	if (!defined("READY")) exit();

	@require_once("lib/module/ModuleController.php");
	@require_once("lib/db/DB.php");

	class noticeController extends ModuleController
	{
		public function doAddNotice()
		{
			if ($_SESSION["sess_info"]->admin !== 'Y')
				return -1;

			$notice = Context::get("notice");
			if (empty($notice))
				return -1;

			$args = array();
			$args["notice"] = $notice;
			$args["regdate"] = time();

			$db = &DB::getInstance();
			$db->insert(notice::TBL_NOTICE, $args);

			return 0;
		}

		// Ajax call
		public function doAjaxDelNotice()
		{
			if ($_SESSION["sess_info"]->admin !== 'Y')
				return -1;

			$idx = Context::get("idx");
			if (!isset($idx))
				exit("0");

			$db = &DB::getInstance();
			$result = $db->query(sprintf("delete from `%s` where `idx` = '%s';", notice::TBL_NOTICE, $idx));

			exit($result === FALSE ? "0" : "1");
		}
	}
?>