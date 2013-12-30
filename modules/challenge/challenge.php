<?php
	/*
	 * challenge class
	 */
	
	if (!defined("READY")) exit();

	@require_once("lib/module/Module.php");

	class challenge extends Module
	{
		const TBL_CHALLENGE = "challenge";
		const BREAK_THROUGH = 3;
		const CHAL_PER_PAGE = 15;
		public static $CHAL_TYPE = array(
			"Misc",
			"Web",
			"Binary",
			"Network",
			"Pwnable",
			"Forensic",
			"Recon"
		);
	}
?>