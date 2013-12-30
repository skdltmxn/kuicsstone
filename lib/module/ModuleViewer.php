<?php
	/*
	 * ModuleViewer class
	 */

	if (!defined("READY")) exit();
	
	@require_once("lib/module/ModuleLoader.php");
	@require_once("lib/layout/Layout.php");
	@require_once("lib/document/Document.php");

	abstract class ModuleViewer
	{
		private $_moduleBase = "";
		private $_cacheBase = "tpl/";
		protected $_moduleTemplate = "";


		public static function &getViewer($moduleName)
		{
			$moduleLoader = &ModuleLoader::getInstance();
			$viewer = &$moduleLoader->loadViewer($moduleName);
			return $viewer;
		}

		public function view($method)
		{
			if (!method_exists($this, $method))
			{
				$method = $this->_getDefaultView();

				// Impossible
				if (!method_exists($this, $method))
					exit("correct view action not found");
			}

			Context::set("__action", $method);
			call_user_func(array($this, $method));

			$cacheFile = $this->_getTemplateCacheName();
			$document = &Document::getInstance();

			// no cache, interpret new
			if (!Cache::isExist($cacheFile))
			{
				$template = &Template::getInstance();
				$template->setTemplateBase($this->_moduleBase);

				// interpret module first
				$moduleInterpreted = $template->interpret($this->_moduleTemplate);

				$layout = &Layout::getInstance();
				$layoutInterpreted = $layout->interpretLayout($moduleInterpreted[0]);
				$layoutInterpreted[0] = preg_replace("/\t|[\x0d\x0a]+(?=<)|(?<=>)[\r\n]+/", "", $layoutInterpreted[0]);
				//$layoutInterpreted[0] = preg_replace("/\r\n\r\n/", "", $layoutInterpreted[0]);

				$document->writeHead("KUICS CTF 2013", array($layoutInterpreted[1], $moduleInterpreted[1]));
				$document->writeBody($layoutInterpreted[0]);
				$document->writeCache($cacheFile);
				$document->sendDocument();
			}
			// cache found, just read it
			else
			{
				$document->fromCache($cacheFile);
				$document->sendDocument();
			}

			//echo $layoutInterpreted;
		}

		public function setTemplate($tpl)
		{
			$className = str_replace("Viewer", "", get_class($this));
			$this->_moduleTemplate = MODULE_BASE . "/" . $className . "/tpl/" . $tpl;
			$this->_moduleBase = dirname($this->_moduleTemplate);
		}

		abstract protected function _getDefaultView();

		private function _getTemplateCacheName()
		{
			$layout = &Layout::getInstance();
			$layoutName = $layout->getLayoutTemplate();

			$cacheName = sha1($this->_moduleTemplate . max(filemtime($layoutName), filemtime($this->_moduleTemplate)));
			return $this->_cacheBase . $cacheName;
		}
	}
?>