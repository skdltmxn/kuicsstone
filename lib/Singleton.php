<?php
	/*
	 * Singleton class
	 */

	if (!defined("READY")) exit();

	class Singleton
	{
		protected function __construct()
		{
		}

		public static function &getInstance()
		{
			static $_instance = null;

			if ($_instance === null)
				$_instance = new static;

			return $_instance;
		}
	}
?>