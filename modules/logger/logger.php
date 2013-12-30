<?php
	/*
	 * logger class
	 */
	
	if (!defined("READY")) exit();
	
	require_once("lib/module/Module.php");
	require_once("lib/module/ModuleModel.php");

	class logger extends Module
	{
		const TBL_LOG = "log";
		const LOG_PER_PAGE = 15;

		public static $logType = array(
			1 => "auth",
			2 => "member",
			3 => "team",
			4 => "challenge"
		);

		public static function logAuth($chalTitle, $flag, $correct)
		{
			$args = array();
			$args["type"] = 1;
			$args["content"] = sprintf("[Auth Attempt] Chal: %s, Flag: %s (%s)", $chalTitle, $flag, $correct === true ? "O" : "X");
			$args["owner"] = sprintf("%s (%s)", $_SESSION["sess_info"]->t_name, $_SESSION["sess_info"]->name);
			$args["regdate"] = time();

			$loggerModel = &ModuleModel::getModel("logger");
			$loggerModel->addLog($args);
		}

		public static function logMemberLogin($id, $success)
		{
			$args = array();
			$args["type"] = 2;
			$args["content"] = sprintf("[Login Attempt] ID: %s (%s)", $id, $success === true ? "success" : "fail");
			$args["owner"] = $id;
			$args["regdate"] = time();

			$loggerModel = &ModuleModel::getModel("logger");
			$loggerModel->addLog($args);
		}

		public static function logMemberJoin($id, $team)
		{
			$args = array();
			$args["type"] = 2;
			$args["content"] = sprintf("[Member Join] ID: %s, Team: %s", $id, $team);
			$args["owner"] = $id;
			$args["regdate"] = time();

			$loggerModel = &ModuleModel::getModel("logger");
			$loggerModel->addLog($args);
		}

		public static function logMemberNewAdmin($id, $name)
		{
			$args = array();
			$args["type"] = 2;
			$args["content"] = sprintf("[New Admin] ID: %s, Name: %s", $id, $name);
			$args["owner"] = $_SESSION["sess_info"]->id;
			$args["regdate"] = time();

			$loggerModel = &ModuleModel::getModel("logger");
			$loggerModel->addLog($args);
		}

		public static function logTeamScored($team, $score)
		{
			$args = array();
			$args["type"] = 3;
			$args["content"] = sprintf("[%s] scored %s", $team, $score);
			$args["owner"] = $team;
			$args["regdate"] = time();

			$loggerModel = &ModuleModel::getModel("logger");
			$loggerModel->addLog($args);
		}

		public static function logChallengeAdded($chalTitle, $score)
		{
			$args = array();
			$args["type"] = 4;
			$args["content"] = sprintf("[New Challenge] %s (%s)", $chalTitle, $score);
			$args["owner"] = $_SESSION["sess_info"]->name;
			$args["regdate"] = time();

			$loggerModel = &ModuleModel::getModel("logger");
			$loggerModel->addLog($args);
		}
	}
?>