<?php
	/*
	 * Util class
	 */
	
	if (!defined("READY")) exit();

	class Util
	{
		public static function makeDir($path)
		{
			$dirs = @explode("/", $path);
			$curDir = "";

			foreach ($dirs as $dir)
			{
				$curDir .= $dir;

				if (!is_dir($curDir))
					@mkdir($curDir);

				$curDir .= "/";
			}
		}

		public static function goBack()
		{
			exit("<script>history.back(-1);</script>");
		}
	}
?>