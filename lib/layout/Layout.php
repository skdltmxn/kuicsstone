<?php
	/*
	 * Layout class
	 */
	
	if (!defined("READY")) exit();

	@require_once("lib/Singleton.php");
	@require_once("lib/template/LayoutTemplate.php");
	@require_once("lib/Util.php");

	class Layout extends Singleton
	{
		private $_layoutBase = "";
		private $_layoutTemplate = "";

		protected function __construct()
		{
			$this->_layoutBase = "layout/";
		}

		public function setLayoutTemplate($tpl)
		{
			$this->_layoutTemplate = $this->_layoutBase . $tpl;
		}

		public function interpretLayout($moduleContent)
		{
			if (empty($this->_layoutTemplate)
				|| !file_exists($this->_layoutTemplate))
			{
				exit("layout template not found");
			}

			$layout = "";
			$template = &LayoutTemplate::getInstance();
			$template->setTemplateBase($this->_layoutBase);
			$template->bindModuleTemplate($moduleContent);
			$layout = $template->interpret($this->_layoutTemplate);

			return $layout;			
		}

		public function getLayoutTemplate()
		{
			return $this->_layoutTemplate;
		}
	}
?>