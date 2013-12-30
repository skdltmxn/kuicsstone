<?php
	/*
	 * challengeViewer class
	 */
	
	if (!defined("READY")) exit();

	@require_once("lib/module/ModuleViewer.php");
	@require_once("lib/module/ModuleModel.php");

	class challengeViewer extends ModuleViewer
	{
		public function __construct()
		{
			$layout = &Layout::getInstance();
			$layout->setLayoutTemplate("main.tpl");
		}

		public function viewChalList()
		{
			if ($_SESSION["sess_info"]->logged !== true)
				header("Location: " . WEB_ROOT . "member");

			$challengeModel = &ModuleModel::getModel("challenge");
			$chalList = $challengeModel->getOpenChal();

			$teamModel = &ModuleModel::getModel("team");
			$teamCount = $teamModel->getTeamCount();

			Context::set("chalType", challenge::$CHAL_TYPE);
			Context::set("chalList", $chalList);
			Context::set("teamCount", $teamCount);

			$this->setTemplate("chal_list.tpl");
		}

		public function viewAdminChallenge()
		{
			if ($_SESSION["sess_info"]->admin !== 'Y')
				return $this->viewChalList();

			$page = Context::get("page");
			if ($page < 1) $page = 1;

			$challengeModel = &ModuleModel::getModel("challenge");
			$chalList = $challengeModel->getChalList($page);

			$pageCount = (int)($chalList["count"] / challenge::CHAL_PER_PAGE) + ($chalList["count"] % challenge::CHAL_PER_PAGE > 0 ? 1 : 0);
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

			Context::set("chalType", challenge::$CHAL_TYPE);
			Context::set("chalList", $chalList["list"]);
			Context::set("page", $page);
			Context::set("pageStart", $pageStart);
			Context::set("pageEnd", $pageEnd);

			$this->setTemplate("chal_admin.tpl");
		}

		protected function _getDefaultView()
		{
			return "viewChalList";
		}
	}
?>