<?php
	/*
	 * ModuleModel class
	 */

	if (!defined("READY")) exit();

	@require_once("lib/module/ModuleLoader.php");

	class ModuleModel
	{
		public static function getModel($moduleName)
		{
			$moduleLoader = &ModuleLoader::getInstance();
			$model = &$moduleLoader->loadModel($moduleName);
			return $model;
		}
	}
?>