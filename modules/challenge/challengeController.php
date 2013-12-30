<?php
	/*
	 * challengeController class
	 */
	
	if (!defined("READY")) exit();

	@require_once("lib/module/ModuleController.php");
	@require_once("lib/module/ModuleModel.php");

	class challengeController extends ModuleController
	{
		public function doAddChallenge()
		{
			if ($_SESSION["sess_info"]->admin !== 'Y')
				return -1;

			$args = array();

			$args["title"] = trim(Context::get("title"));
			$args["desc"]  = trim(Context::get("desc"));
			$args["score"] = (int)Context::get("score");
			$args["flag"] = trim(Context::get("flag"));

			foreach ($args as $val)
			{
				if (empty($val))
					return -1;
			}

			$args["type"] = Context::get("type");

			if ($args["type"] < 0 || $args["type"] > count(challenge::$CHAL_TYPE) - 1)
				return -1;

			$challengeModel = &ModuleModel::getModel("challenge");
			$challengeModel->addChallenge($args);

			logger::logChallengeAdded($args["title"], $args["score"]);
			return 0;
		}

		public function doUpdateChallenge()
		{
			if ($_SESSION["sess_info"]->admin !== 'Y')
				return -1;

			$idx = (int)Context::get("idx");
			if (!isset($idx))
				return -1;

			$args = array();
			
			$args["title"] = trim(Context::get("title"));
			$args["desc"]  = trim(Context::get("desc"));
			$args["score"] = (int)Context::get("score");
			$args["flag"] = trim(Context::get("flag"));

			foreach ($args as $key => $val)
			{
				if (empty($val))
					unset($args[$key]);
			}

			$args["type"] = Context::get("type");

			if ($args["type"] < 0 || $args["type"] > count(challenge::$CHAL_TYPE) - 1)
				return -1;

			$challengeModel = &ModuleModel::getModel("challenge");
			$challengeModel->updateChallenge($idx, $args);

			return 0;
		}

		public function doAjaxToggleOpen()
		{
			if ($_SESSION["sess_info"]->admin !== 'Y')
				exit();

			$ret = array();

			$idx = (int)Context::get("idx");
			$mode = Context::get("mode");

			if (!isset($idx) || !isset($mode))
			{
				$ret["code"] = 1;
				exit(json_encode($ret));
			}

			$challengeModel = &ModuleModel::getModel("challenge");
			$success = $challengeModel->toggleChalOpen($idx, $mode);

			if ($success !== true)
				$ret["code"] = 1;
			else
				$ret["code"] = 0;

			exit(json_encode($ret));
		}

		public function doAjaxGetChallenge()
		{
			if ($_SESSION["sess_info"]->logged !== true)
				exit();

			$ret = array();

			$idx = (int)Context::get("idx");

			$challengeModel = &ModuleModel::getModel("challenge");
			$challengeInfo = $challengeModel->getChallengeByIdx($idx);

			// invalid idx
			if ($challengeInfo === null)
			{
				$ret["code"] = 1;
				exit(json_encode($ret));
			}
			
			$ret["code"] = 0;
			$ret["title"] = $challengeInfo->title;
			$ret["desc"] = $challengeInfo->desc;
			$ret["score"] = $challengeInfo->score;

			// break through
			$ret["brk"] = array();
			$solved = preg_split("@/@", $challengeInfo->solver, 0, PREG_SPLIT_NO_EMPTY);
			$teamModel = &ModuleModel::getModel("team");
			$i = 0;

			foreach ($solved as $t_idx)
			{
				if ($i++ >= challenge::BREAK_THROUGH) break;
				$teamInfo = $teamModel->getTeamByIdx($t_idx);
				$ret["brk"][] = $teamInfo->name;
			}
			
			exit(json_encode($ret));
		}

		public function doAjaxAuth()
		{
			$ret["code"] = 2;
			$ret["msg"]  = "대회 끝 Do Dive!";
			exit(json_encode($ret));

			if ($_SESSION["sess_info"]->logged !== true)
				exit();

			$ret = array();

			$idx = (int)Context::get("idx");
			$flag = Context::get("flag");

			if ($idx < 1 || empty($flag))
			{
				$ret["code"] = 1;
				$ret["tag"] = "flag";
				exit(json_encode($ret));
			}

			$challengeModel = &ModuleModel::getModel("challenge");
			$challengeInfo = $challengeModel->getChallengeByIdx($idx);

			if ($challengeInfo === null)
			{
				$ret["code"] = 2;
				$ret["msg"]  = "Internal error occured";
				exit(json_encode($ret));
			}
			
			$solved = preg_split("@/@", $challengeInfo->solver, 0, PREG_SPLIT_NO_EMPTY);

			// already solved
			if (in_array($_SESSION["sess_info"]->t_idx, $solved))
			{
				$ret["code"] = 0;
				$ret["msg"]  = "Don't be evil ;)";
			}
			else
			{
				// wrong answer
				if ($challengeInfo->open !== 'Y' ||
					$challengeInfo->flag != $challengeModel->hashFlag($flag))
				{
					$ret["code"] = 2;
					$ret["msg"]  = "Far from that...";

					if ($_SESSION["sess_info"]->admin !== 'Y')
					logger::logAuth($challengeInfo->title, $flag, false);
				}
				else
				{
					// Admins can check answer (no points)
					if ($_SESSION["sess_info"]->admin === 'Y')
					{
						$ret["code"] = 0;
						$ret["msg"]  = "Admin: Correct";
						exit(json_encode($ret));
					}

					$addedScore = $challengeInfo->score;

					// check breakthrough
					if (count($solved) < challenge::BREAK_THROUGH)
						$addedScore += challenge::BREAK_THROUGH - count($solved);

					$_SESSION["sess_info"]->score += $addedScore;
					$teamModel = &ModuleModel::getModel("team");
					$teamModel->addScore($_SESSION["sess_info"]->t_idx, $addedScore);
					logger::logTeamScored($_SESSION["sess_info"]->t_name, $addedScore);
					$teamModel->writeScoreCache($_SESSION["sess_info"]->t_idx, $_SESSION["sess_info"]->code, $_SESSION["sess_info"]->score);
					$challengeModel->addSolver($idx, $_SESSION["sess_info"]->t_idx);
					$ret["code"] = 0;
					$ret["msg"]  = "Yeaaahhh Riiight!!";
					logger::logAuth($challengeInfo->title, $flag, true);
				}
			}

			exit(json_encode($ret));
		}

		public function doAjaxAdminGetChallenge()
		{
			if ($_SESSION["sess_info"]->admin !== 'Y')
				exit();

			$ret = array();

			$idx = (int)Context::get("idx");

			$challengeModel = &ModuleModel::getModel("challenge");
			$challengeInfo = $challengeModel->getChallengeByIdx($idx);

			// invalid idx
			if ($challengeInfo === null)
			{
				$ret["code"] = 1;
				exit(json_encode($ret));
			}

			$ret["code"] = 0;
			$ret["chal"] = $challengeInfo;

			exit(json_encode($ret));
		}
	}
?>