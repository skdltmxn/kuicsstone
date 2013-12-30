<?php
	/*
	 * challengeModel class
	 */
	
	if (!defined("READY")) exit();

	@require_once("lib/db/DB.php");

	class challengeModel
	{
		public function getChalList($page = 1)
		{
			$db = &DB::getInstance();
			$query = sprintf("select * from `%s`", challenge::TBL_CHALLENGE);
			$count = count($db->select($query, true));

			$offset = ($page - 1) * challenge::CHAL_PER_PAGE;
			$query .= sprintf(" limit %s, %s;", $offset, challenge::CHAL_PER_PAGE);
			$result = $db->select($query, true);

			$list = array();
			$list["list"] = $result;
			$list["count"] = $count;

			return $list;
		}

		public function getOpenChal()
		{
			$db = &DB::getInstance();
			return $db->select(sprintf("select * from `%s` where open = 'Y';", challenge::TBL_CHALLENGE), true);
		}

		public function addChallenge($args)
		{
			$args["flag"] = $this->hashFlag($args["flag"]);
			$args["open"] = 'N';
			$args["solver"] = '';

			$db = &DB::getInstance();
			$db->insert(challenge::TBL_CHALLENGE, $args);
		}

		public function updateChallenge($idx, $args)
		{
			$set = '';

			if (isset($args["flag"]))
				$args["flag"] = $this->hashFlag($args["flag"]);

			foreach ($args as $key => $val)
				$set .= "`" . $key . "` = '" . $val . "', ";

			$set = substr($set, 0, -2);

			$query = sprintf("update `%s` set %s where `idx` = '%s';", challenge::TBL_CHALLENGE,
																	   $set, $idx);

			$db = &DB::getInstance();
			return $db->query($query);
		}

		public function toggleChalOpen($idx, $mode)
		{
			$open = 'N';
			if ($mode === "open")
				$open = 'Y';

			$db = &DB::getInstance();
			return $db->query(sprintf("update `%s` set `open` = '%s' where `idx` = '%s';", 
										challenge::TBL_CHALLENGE, $open, $idx));
		}

		public function getChallengeByIdx($idx)
		{
			$db = &DB::getInstance();
			return $db->select(sprintf("select * from `%s` where `idx` = '%s';", challenge::TBL_CHALLENGE, $idx));
		}

		public function hashFlag($flag)
		{
			return sha1("NO_CHEATING__" . $flag . "__NO_MERCY");
		}

		public function addSolver($idx, $t_idx)
		{
			$db = &DB::getInstance();
			return $db->query(sprintf("update `%s` set `solver` = concat(`solver`, '%s', '/') where `idx` = '%s';",
																					challenge::TBL_CHALLENGE,
																					$t_idx,
																					$idx));
		}
	}
?>