<?php
	/*
	 * noticeViewer class
	 */

	if (!defined("READY")) exit();

	@require_once("lib/module/ModuleViewer.php");
	@require_once("lib/module/ModuleModel.php");

	class noticeViewer extends ModuleViewer
	{
		public function __construct()
		{
			$layout = &Layout::getInstance();
			$layout->setLayoutTemplate("main.tpl");
		}

		// Default action
		public function viewList()
		{
			$noticeModel = &ModuleModel::getModel("notice");
			$noticeList = $noticeModel->getNoticeList();

			Context::set("noticeList", $noticeList);

			$this->setTemplate("list.tpl");
		}

		public function viewRule()
		{
			$this->setTemplate("rule.tpl");
		}
	
		protected function _getDefaultView()
		{
			return "viewList";
		}
	}
?>