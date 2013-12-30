<?php
	/*
	 * teamController class
	 */

	if (!defined("READY")) exit();

	@require_once("lib/module/ModuleController.php");
	@require_once("lib/module/ModuleModel.php");

	class teamController extends ModuleController
	{
		public function doAjaxGetTeam()
		{
			$code = trim(Context::get("code"));
			$ret = array();

			if (strlen($code) < 4)
			{
				$ret["code"] = -1;
				$ret["msg"] = "팀명은 최소 4글자(한글 2글자) 이상으로!";
				exit(json_encode($ret));
			}

			$teamModel = &ModuleModel::getModel("team");
			$team = $teamModel->getTeamByCode($code);

			if (isset($team->idx))
			{
				$ret["code"] = 1;
				$ret["team_name"] = htmlspecialchars($team->name);
			}
			else
			{
				$ret["code"] = 0;
				$ret["team_name"] = htmlspecialchars($code);
			}

			exit(json_encode($ret));
		}
	}
?>