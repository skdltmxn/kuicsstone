<?php
	/*
	 * ModuleController class
	 */

	if (!defined("READY")) exit();

	@require_once("lib/module/ModuleLoader.php");

	abstract class ModuleController
	{
		public static function &getController($moduleName)
		{
			$moduleLoader = &ModuleLoader::getInstance();
			$controller = &$moduleLoader->loadController($moduleName);
			return $controller;
		}

		public function perform($method)
		{
			if (!method_exists($this, $method))
				return false;

			if (call_user_func(array($this, $method)) == -1)
				Util::goBack();

			return true;
		}
	}
?>