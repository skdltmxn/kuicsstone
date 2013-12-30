<?php
	/*
	 * memberController class
	 */
	
	if (!defined("READY")) exit();

	@require_once("lib/module/ModuleController.php");
	@require_once("lib/module/ModuleModel.php");
	@require_once("lib/db/DB.php");

	class memberController extends ModuleController
	{
		public function doAjaxJoin()
		{
			$ret = array();

			$ret["code"] = 1;
			$ret["msg"] = "더 이상 가입은... naver...";
			exit(json_encode($ret));


			$args = array();
			$args["userid"] = trim(Context::get("userid"));
			$args["userpw"] = Context::get("userpw");
			$args["userpw2"] = Context::get("userpw2");
			$args["name"]   = trim(Context::get("name"));
			$args["team"]   = trim(Context::get("team"));

			foreach ($args as $key => $val)
			{
				if (empty($val))
				{
					$ret["code"] = 1;
					$ret["tag"] = $key;
					exit(json_encode($ret));
				}
			}
			
			@extract($args);

			if (strlen($userpw) < 6)
			{
				$ret["code"] = 1;
				$ret["tag"] = "userpw";
				$ret["msg"] = "비밀번호는 6자리 이상으로...";
				exit(json_encode($ret));
			}

			if ($userpw !== $userpw2)
			{
				$ret["code"] = 1;
				$ret["tag"] = "userpw2";
				$ret["msg"] = "비밀번호를 똑같이 입력하세요.";
				exit(json_encode($ret));
			}

			$memberModel = &ModuleModel::getModel("member");
			$member = $memberModel->getMemberById($userid);

			if (isset($member->idx))
			{
				$ret["code"] = 1;
				$ret["tag"] = "userid";
				$ret["msg"] = "이미 존재하는 ID입니다.";
				exit(json_encode($ret));
			}

			if (strlen($team) < 4)
			{
				$ret["code"] = 1;
				$ret["tag"] = "team";
				exit(json_encode($ret));
			}

			$teamModel = &ModuleModel::getModel("team");
			$teamInfo = $teamModel->getTeamByCode($team);
			
			// creating new team
			if (!isset($teamInfo->idx))
			{
				$teamInfo = $teamModel->getTeamByName($team);

				if (isset($teamInfo->idx))
				{
					$ret["code"] = 1;
					$ret["msg"] = "이미 존재하는 팀입니다.";
					exit(json_encode($ret));
				}

				$teamModel->addTeam($team);
				$teamInfo = $teamModel->getTeamByName($team);
			}

			// Impossible
			if (!isset($teamInfo->idx))
			{
				$ret["code"] = 1;
				$ret["msg"] = "Internal error occured";
				exit(json_encode($ret));
			}

			$args = array();
			$args["id"] = $userid;
			$args["pw"] = $userpw;
			$args["name"] = $name;
			$args["team_idx"] = $teamInfo->idx;
			$args["regdate"] = time();
			$args["admin"] = 'N';

			$memberModel->addMember($args);

			$ret["code"] = 0;

			logger::logMemberJoin($userid, $teamInfo->name);
			exit(json_encode($ret));
		}

		public function doAjaxLogin()
		{
			if ($_SESSION["sess_info"]->logged === true)
				exit();

			$args = array();
			$args["userid"] = trim(Context::get("userid"));
			$args["userpw"] = trim(Context::get("userpw"));

			$ret = array();

			foreach ($args as $key => $val)
			{
				if (empty($val))
				{
					$ret["code"] = 1;
					$ret["tag"] = $key;
					exit(json_encode($ret));
				}
			}

			@extract($args);

			$memberModel = &ModuleModel::getModel("member");
			$member = $memberModel->getMember($userid, $userpw);

			if (!isset($member->idx))
			{
				$ret["code"] = 1;
				$ret["msg"] = "Login failed";
				logger::logMemberLogin($userid, false);
				exit(json_encode($ret));
			}

			$memberModel->setSessionInfo($member);

			$ret["code"] = 0;
			logger::logMemberLogin($userid, true);
			exit(json_encode($ret));
		}

		public function doLogout()
		{
			$memberModel = &ModuleModel::getModel("member");
			$memberModel->clearSessionInfo();
		}

		public function doAjaxModifyInfo()
		{
			if ($_SESSION["sess_info"]->logged !== true)
				exit();

			$ret = array();
			$ret["code"] = 1;

			$args = array();

			$name = trim(Context::get("name"));
			if (empty($name))
			{
				$ret["msg"] = "이름을 입력하세요.";
				$ret["tag"] = "name";
				exit(json_encode($ret));
			}
			$args["name"] = $name;

			$oldpw = Context::get("oldpw");
			if (empty($oldpw))
			{
				$ret["msg"] = "기존 비밀번호를 입력하세요.";
				$ret["tag"] = "oldpw";
				exit(json_encode($ret));
			}

			$newpw = Context::get("newpw");
			// password changing
			if (!empty($newpw))
			{
				if (strlen($newpw) < 6)
				{
					$ret["code"] = 1;
					$ret["tag"] = "newpw";
					$ret["msg"] = "비밀번호는 6자리 이상으로...";
					exit(json_encode($ret));
				}

				$newpw2 = Context::get("newpw2");
				if ($newpw !== $newpw2)
				{
					$ret["code"] = 1;
					$ret["tag"] = "newpw2";
					$ret["msg"] = "비밀번호를 똑같이 입력하세요.";
					exit(json_encode($ret));
				}

				$args["pw"] = $newpw;
			}

			// check old password
			$memberModel = &ModuleModel::getModel("member");
			$member = $memberModel->getMember($_SESSION["sess_info"]->id, $oldpw);
			if (!isset($member->idx))
			{
				$ret["code"] = 1;
				$ret["tag"] = "oldpw";
				$ret["msg"] = "비밀번호가 일치하지 않습니다.";
				exit(json_encode($ret));
			}

			// new password must be different!
			if ($newpw === $oldpw)
			{
				$ret["code"] = 1;
				$ret["tag"] = "newpw";
				$ret["msg"] = "새 비번은 다르게...";
				exit(json_encode($ret));
			}

			$memberModel->updateMember($_SESSION["sess_info"]->idx, $args);
			$_SESSION["sess_info"]->name = $args["name"];
			
			$ret["code"] = 0;
			exit(json_encode($ret));
		}

		public function doAddAdmin()
		{
			if ($_SESSION["sess_info"]->admin !== 'Y')
				return -1;

			$userid = trim(Context::get("userid"));
			$userpw = Context::get("userpw");
			$name = trim(Context::get("name"));

			if (empty($userid) || empty($userpw) || empty($name))
				return -1;

			$memberModel = &ModuleModel::getModel("member");
			$member = $memberModel->getMemberById($userid);

			if ($member->idx)
				return -1;

			$args = array();
			$args["id"] = $userid;
			$args["pw"] = $userpw;
			$args["name"] = $name;
			$args["team_idx"] = -1;
			$args["regdate"] = time();
			$args["admin"] = 'Y';

			$memberModel->addMember($args);

			logger::logMemberNewAdmin($userid, $name);

			return 0;
		}
	}
?>