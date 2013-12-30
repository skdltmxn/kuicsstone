<?php
	/*
	 * teamModel class
	 */
	
	if (!defined("READY")) exit();

	@require_once("modules/team/team.php");
	@require_once("lib/db/DB.php");

	class teamModel
	{
		public function addTeam($name)
		{
			$args = array();
			$args["name"] = $name;
			$args["code"] = sha1(uniqid());
			$args["last_auth"] = 4539203962; // 2113-11-04

			$db = &DB::getInstance();
			$db->insert(team::TBL_TEAM, $args);
		}

		public function getTeamByCode($code)
		{
			$db = &DB::getInstance();
			return $db->select(sprintf("select * from `%s` where `code` = '%s';", team::TBL_TEAM,
																				  $code));
		}

		public function getTeamByName($name)
		{
			$db = &DB::getInstance();
			return $db->select(sprintf("select * from `%s` where `name` = '%s';", team::TBL_TEAM,
																				  $name));
		}

		public function getTeamByIdx($idx)
		{
			$db = &DB::getInstance();
			return $db->select(sprintf("select * from `%s` where `idx` = '%s';", team::TBL_TEAM,
																				 $idx));
		}

		public function getTeamList()
		{
			$db = &DB::getInstance();
			return $db->select(sprintf("select * from `%s` order by `score` desc, `last_auth` asc;", team::TBL_TEAM), true);
		}

		public function getTeamCount()
		{
			$db = &DB::getInstance();
			$o = $db->select(sprintf("select count(*) as n from `%s`;", team::TBL_TEAM));
			return $o->n;
		}

		public function addScore($idx, $score)
		{
			$db = &DB::getInstance();
			return $db->query(sprintf("update `%s` set `score` = `score` + '%s', last_auth = '%s' where `idx` = '%s';", 
				team::TBL_TEAM, $score, time(), $idx));
		}

		public function writeScoreCache($idx, $code, $score)
		{
			@require_once("lib/cache/Cache.php");

			$cacheFile = $this->_getScoreCacheFile($idx, $code);
			Cache::writeCache($cacheFile, $score);
		}

		public function readScoreCache($idx, $code)
		{
			@require_once("lib/cache/Cache.php");

			$cacheFile = $this->_getScoreCacheFile($idx, $code);
			if (Cache::isExist($cacheFile))
				$_SESSION["sess_info"]->score = Cache::readCache($cacheFile);
		}

		private function _getScoreCacheFile($idx, $code)
		{
			return "score/" . sha1($idx . "-" . $code);
		}
	}
?>