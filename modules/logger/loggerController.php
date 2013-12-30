<?php
	/*
	 * loggerController class
	 */
	
	if (!defined("READY")) exit();

	require_once("lib/module/ModuleController.php");

	class loggerController extends ModuleController
	{
		public function doClearCache()
		{
			if ($_SESSION["sess_info"]->admin !== 'Y')
				return -1;

			@require_once("lib/cache/Cache.php");
			Cache::clearCache("cache/tpl/");

			return 0;
		}
	}
?>