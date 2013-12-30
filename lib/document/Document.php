<?php
	/*
	 * Document class
	 */
	
	if (!defined("READY")) exit();

	@require_once("lib/Singleton.php");
	@require_once("lib/cache/Cache.php");

	class Document extends Singleton
	{
		private $_content;

		protected function __construct($args = null)
		{
			$this->_content  = "<!doctype html>";
			$this->_content .= "<html>";
		}

		public function writeHead($title, $includeList)
		{
			$this->_content .= "<head>";
			$this->_content .= "<meta charset=\"utf-8\">";
			$this->_content .= "<title>" . $title . "</title>";
			foreach ($includeList as $list)
				foreach ($list as $item)
					$this->_content .= $item;

			$this->_content .= "</head>";
		}

		public function writeBody($body)
		{
			$this->_content .= "<body>";
			$this->_content .= $body;
			$this->_content .= "</body>";
			$this->_content .= "</html>";
		}

		public function fromCache($cacheFile)
		{
			$this->_content = Cache::readCache($cacheFile);
		}

		public function sendDocument()
		{
			ob_start();
			eval("?>" . $this->_content . "<?");
			$doc = ob_get_clean();

			print $doc;
		}

		public function writeCache($path)
		{
			Cache::writeCache($path, $this->_content);
		}
	}
?>