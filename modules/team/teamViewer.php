<?php
	/*
	 * teamViewer class
	 */
	
	if (!defined("READY")) exit();

	@require_once("lib/module/ModuleViewer.php");
	@require_once("lib/module/ModuleModel.php");

	class teamViewer extends ModuleViewer
	{
		public function __construct()
		{
			$layout = &Layout::getInstance();
			$layout->setLayoutTemplate("main.tpl");
		}

		public function viewTeamInfo()
		{
			if ($_SESSION["sess_info"]->logged !== true)
				header("Location: member" . WEB_ROOT . "member");

			$this->setTemplate("team_info.tpl");
		}

		public function viewRank()
		{
			if ($_SESSION["sess_info"]->logged !== true)
				header("Location: " . WEB_ROOT . "member");

			$teamModel = &ModuleModel::getModel("team");
			$teamList = $teamModel->getTeamList();

			Context::set("teamList", $teamList);

			$this->setTemplate("rank.tpl");
		}

		public function viewAdminTeam()
		{
			if ($_SESSION["sess_info"]->admin !== 'Y')
				header("Location: " . WEB_ROOT);

			$teamModel = &ModuleModel::getModel("team");
			$teamList = $teamModel->getTeamList();

			$memberModel = &ModuleModel::getModel("member");

			if ($teamList !== null)
				foreach ($teamList as $team)
					$team->members = $memberModel->getMembersByTeamIdx($team->idx);

			Context::set("teamList", $teamList);

			$this->setTemplate("team_admin.tpl");
		}

		protected function _getDefaultView()
		{
			return "viewTeamInfo";
		}
	}
?>