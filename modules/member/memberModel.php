<?php
	/*
	 * memberModel class
	 */

	if (!defined("READY")) exit();

	@require_once("modules/member/member.php");
	@require_once("modules/team/team.php");
	@require_once("lib/db/DB.php");

	class memberModel
	{
		public function addMember($args)
		{
			$args["pw"] = $this->_hashPassword($args["id"], $args["pw"]);

			$db = &DB::getInstance();
			$db->insert(member::TBL_MEMBER, $args);
		}

		public function getMember($userid, $userpw)
		{
			$hashpw = $this->_hashPassword($userid, $userpw);
			$db = &DB::getInstance();
			return $db->select(sprintf("select m.*, t.idx as t_idx, t.name as t_name, t.score, t.code, t.last_auth from `%s` 
										as m left join `%s` as t on m.team_idx = t.idx where m.id = '%s' and m.pw = '%s';",
										member::TBL_MEMBER, team::TBL_TEAM, $userid, $hashpw));
		}

		public function getMemberById($userid)
		{
			$db = &DB::getInstance();
			return $db->select(sprintf("select * from `%s` where `id` = '%s';", member::TBL_MEMBER,
																				$userid));
		}

		public function getMembersByTeamIdx($t_idx)
		{
			$db = &DB::getInstance();
			return $db->select(sprintf("select `id`, `name` from `%s` where `team_idx` = '%s';", member::TBL_MEMBER,
																								 $t_idx), true);
		}

		public function getMembers()
		{
			$db = &DB::getInstance();
			return $db->select(sprintf("select m.*, t.name as t_name from `%s` as m left join `%s` as t on m.team_idx = t.idx 
										order by admin desc;",
										member::TBL_MEMBER, team::TBL_TEAM), true);
		}

		public function updateMember($idx, $args)
		{
			if (isset($args["pw"]))
				$args["pw"] = $this->_hashPassword($_SESSION["sess_info"]->id, $args["pw"]);

			$set = '';
			foreach ($args as $key => $val)
				$set .= "`" . $key . "` = '" . $val . "', ";

			$set = substr($set, 0, -2);

			$query = sprintf("update `%s` set %s where `idx` = '%s';", member::TBL_MEMBER,
																	   $set, $idx);

			$db = &DB::getInstance();
			return $db->query($query);
		}

		public function setSessionInfo($member)
		{
			if ($_SESSION["sess_info"]->logged)
				$this->_clearSessionInfo();

			unset($member->pw);
			$member->id = htmlspecialchars($member->id);
			$member->name = htmlspecialchars($member->name);
			$member->t_name = htmlspecialchars($member->t_name);

			// change team info for admins
			if ($member->admin === 'Y')
			{
				$member->t_name = "Administrator";
				$member->score = -1;
				$member->code = '-';
			}
				
			$member->logged = true;

			$_SESSION["sess_info"] = $member;
		}

		public function clearSessionInfo()
		{
			unset($_SESSION["sess_info"]);
			Context::set("__sess", null);
			@session_destroy();
		}

		private function _hashPassword($userid, $userpw)
		{
			return sha1($userid . $userpw . "__SALT__");
		}
	}
?>