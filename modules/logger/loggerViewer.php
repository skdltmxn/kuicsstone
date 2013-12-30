<?php
	/*
	 * loggerViewer class
	 */
	
	if (!defined("READY")) exit();

	@require_once("lib/module/ModuleViewer.php");

	class loggerViewer extends ModuleViewer
	{
		public function __construct()
		{
			$layout = &Layout::getInstance();
			$layout->setLayoutTemplate("main.tpl");
		}

		public function viewLog()
		{
			if ($_SESSION["sess_info"]->admin !== 'Y')
				header("Location: " . WEB_ROOT);

			$page = Context::get("page");
			if ($page < 1) $page = 1;

			$loggerModel = &ModuleModel::getModel("logger");
			$logList = $loggerModel->getLogList(Context::get("type"), $page);

			$pageCount = (int)($logList["count"] / logger::LOG_PER_PAGE) + ($logList["count"] % logger::LOG_PER_PAGE > 0 ? 1 : 0);
			$maxPage = 7;

			if ($pageCount > $maxPage)                                                                   
			{       
				$pageStart = $page - 3;
				$pageEnd = $page + 3;
			
				if ($pageStart < 1)
				{
						$pageEnd += abs($pageStart) + 1;
						$pageStart = 1;
				}
				else if ($pageEnd > $pageCount)
				{
						$pageStart -= ($pageEnd - $pageCount);
						$pageEnd = $pageCount;
				}
			}
			else
			{
				$pageStart = 1;
				$pageEnd = $pageCount;
			}

			Context::set("logList", $logList["list"]);
			Context::set("logType", logger::$logType);
			Context::set("page", $page);
			Context::set("pageStart", $pageStart);
			Context::set("pageEnd", $pageEnd);
			Context::set("type", Context::get("type"));

			$this->setTemplate("log.tpl");
		}

		protected function _getDefaultView()
		{
			return "viewLog";
		}
	}
?>