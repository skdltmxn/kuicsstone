<?php
	/*
	 * memberViewer class
	 */

	if (!defined("READY")) exit();

	@require_once("lib/module/ModuleViewer.php");
	@require_once("lib/module/ModuleModel.php");

	class memberViewer extends ModuleViewer
	{
		public function __construct()
		{
			$layout = &Layout::getInstance();
			$layout->setLayoutTemplate("main.tpl");
		}

		// Default action
		public function viewLogin()
		{
			$sess_info = $_SESSION["sess_info"];

			if ($sess_info->logged == 'Y')
				header("Location: " . WEB_ROOT);

			$this->setTemplate("login.tpl");
		}

		public function viewJoin()
		{
			$sess_info = $_SESSION["sess_info"];

			if ($sess_info->logged === true)
				header("Location: " . WEB_ROOT);

			$this->setTemplate("join.tpl");
		}

		public function viewLogout()
		{
			$sess_info = $_SESSION["sess_info"];

			if ($sess_info->logged !== true)
				header("Location: " . WEB_ROOT);

			$this->setTemplate("logout.tpl");
		}

		public function viewAdminMember()
		{
			if ($_SESSION["sess_info"]->admin !== 'Y')
				header("Location: " . WEB_ROOT);

			$memberModel = &ModuleModel::getModel("member");
			$memberList = $memberModel->getMembers();

			Context::set("memberList", $memberList);

			$this->setTemplate("member_list.tpl");
		}

		public function viewMemberModify()
		{
			$sess_info = $_SESSION["sess_info"];

			if ($sess_info->logged !== true)
				header("Location: " . WEB_ROOT);

			$this->setTemplate("member_modify.tpl");
		}
	
		protected function _getDefaultView()
		{
			return "viewLogin";
		}
	}
?>