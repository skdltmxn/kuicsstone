<?php
	/*
	 * LayoutTemplate class
	 */

	if (!defined("READY")) exit();

	@require_once("lib/template/Template.php");

	class LayoutTemplate extends Template
	{
		private $_moduleContent;
		protected function __construct()
		{
			parent::__construct();
		}

		public function parse($content)
		{
			$parsed = parent::parse($content);
			$content = $parsed[0];

			$content = preg_replace("/\{ *content *\}/", $this->_moduleContent, $content);

			return array($content, $this->_includeList);
		}

		public function bindModuleTemplate($moduleContent)
		{
			$this->_moduleContent = $moduleContent;
		}
	}
?>