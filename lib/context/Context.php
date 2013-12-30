<?php
	/*
	 * Context.php
	 */

	if (!defined("READY")) exit();

	final class Context
	{
		public static function init()
		{
			$GLOBALS["vars"] = array();

			foreach ($_GET as $key => $val)
				Context::set($key, get_magic_quotes_gpc() ? $val : addslashes($val));

			foreach ($_POST as $key => $val)
				Context::set($key, get_magic_quotes_gpc() ? $val : addslashes($val));

			if (!isset($_SESSION["sess_info"]))
				$_SESSION["sess_info"] = array();

			// set default variables
			Context::set("__root", WEB_ROOT);
			Context::set("__sess", $_SESSION["sess_info"]);
		}

		public static function get($var)
		{
			if (isset($GLOBALS["vars"][$var]))
				return $GLOBALS["vars"][$var];

			return null;
		}

		public static function set($name, $value)
		{
			$GLOBALS["vars"][$name] = $value;
		}

		public static function varExists($name)
		{
			return array_key_exists($name, $GLOBALS["vars"]);
		}
	}
?>