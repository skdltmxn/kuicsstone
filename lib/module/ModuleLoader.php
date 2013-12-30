<?php
	/*
	 * ModuleLoader
	 */

	if (!defined("READY")) exit();
	
	@require_once("lib/Singleton.php");
	@require_once("Module.php");

	final class ModuleLoader extends Singleton
	{
		private $_modulePath;
		private $_defaultModule;

		protected function __construct()
		{
			$this->_modulePath = "modules/";
			$this->_defaultModule = "notice";
		}

		public function &loadModule($moduleName)
		{
			if (empty($moduleName))
				$moduleName = $this->_defaultModule;

			$path = $this->_modulePath . $moduleName;

			if (is_dir($path) === FALSE)
			{
				$path = $this->_modulePath . $this->_defaultModule;
				$moduleName = $this->_defaultModule;

				// Impossible
				if (is_dir($path) === FALSE)
					exit("default module `" . $this->_defaultModule . "` not found");
			}

			Context::set("__module", $moduleName);
			$classFile = $path . "/" . $moduleName . ".php";


			if (!file_exists($classFile))
				exit("class file for `" . $moduleName . "` not found");

			@include_once($classFile);

			if (!class_exists($moduleName))
				exit("class for `" . $moduleName . "` not found");

			$module = new $moduleName();
			
			return $module;
		}

		public function &loadViewer($moduleName)
		{
			if (empty($moduleName))
				$moduleName = $this->_defaultModule;

			$path = $this->_modulePath . $moduleName;

			if (is_dir($path) === FALSE)
			{
				$path = $this->_modulePath . $this->_defaultModule;
				$moduleName = $this->_defaultModule;

				// Impossible
				if (is_dir($path) === FALSE)
					exit("default module `" . $this->_defaultModule . "` not found");
			}

			$className = $moduleName . "Viewer";
			$classFile = $path . "/" . $className . ".php";
			
			if (!file_exists($classFile))
				exit("viewer class file for `" . $moduleName . "` not found");

			@include_once($classFile);

			if (!class_exists($className))
				exit("viewer class for `" . $moduleName . "` not found");

			$viewer = new $className();
			
			return $viewer;
		}

		public function &loadModel($moduleName)
		{
			if (empty($moduleName))
				$moduleName = $this->_defaultModule;

			$path = $this->_modulePath . $moduleName;

			if (is_dir($path) === FALSE)
			{
				$path = $this->_modulePath . $this->_defaultModule;
				$moduleName = $this->_defaultModule;

				// Impossible
				if (is_dir($path) === FALSE)
					exit("default module `" . $this->_defaultModule . "` not found");
			}

			$className = $moduleName . "Model";
			$classFile = $path . "/" . $className . ".php";

			if (!file_exists($classFile))
				exit("model class file for `" . $moduleName . "` not found");

			@include_once($classFile);

			if (!class_exists($className))
				exit("model class for `" . $moduleName . "` not found");

			$model = new $className();
			
			return $model;
		}

		public function &loadController($moduleName)
		{
			if (empty($moduleName))
				$moduleName = $this->_defaultModule;

			$path = $this->_modulePath . $moduleName;

			if (is_dir($path) === FALSE)
			{
				$path = $this->_modulePath . $this->_defaultModule;
				$moduleName = $this->_defaultModule;

				// Impossible
				if (is_dir($path) === FALSE)
					exit("default module `" . $this->_defaultModule . "` not found");
			}

			$className = $moduleName . "Controller";
			$classFile = $path . "/" . $className . ".php";

			if (!file_exists($classFile))
				exit("controller class file for `" . $moduleName . "` not found");

			@include_once($classFile);

			if (!class_exists($className))
				exit("controller class for `" . $moduleName . "` not found");

			$controller = new $className();
			
			return $controller;
		}
	}
?>