<?php
	/*
	 * member class
	 */
	
	if (!defined("READY")) exit();

	@require_once("lib/module/Module.php");

	class member extends Module
	{
		const TBL_MEMBER = "member";
	}
?>