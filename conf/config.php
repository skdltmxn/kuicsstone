<?php
	/*
	 * config.php
	 */

	if (version_compare(PHP_VERSION, "5.0.0", "<"))
		exit("only supported for PHP version 5.x");

	define("VERSION", "1.0");
	define("ROOT", str_replace("/conf/config.php", "", str_replace("\\", "/", __FILE__)));
	define("WEB_ROOT", str_replace("\\", "/", dirname($_SERVER["SCRIPT_NAME"])) . (dirname($_SERVER["SCRIPT_NAME"]) === '/' ? "" : "/"));
	define("MODULE_BASE", ROOT . "/modules");
	define("READY", true);

	@require_once("modules/logger/logger.php");

	@session_start();
?>