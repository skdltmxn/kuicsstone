<?php
	/*
	 * Cache class
	 */

	if (!defined("READY")) exit();

	@require_once("lib/Util.php");

	class Cache
	{
		private static $_cacheBase = "cache/";
		
		public static function writeCache($dir, $content)
		{
			$path = self::$_cacheBase . $dir;
			Util::makedir(dirname($path));
			file_put_contents($path, $content);
			@chmod($path, 0644);
		}

		public static function readCache($path)
		{
			$path = self::$_cacheBase . $path;
			if (file_exists($path))
				return file_get_contents($path);

			return null;
		}

		public static function isExist($path)
		{
			if ($path[0] == '/')
				$path = substr($path, 1);

			return file_exists(self::$_cacheBase . $path);
		}

		public static function clearCache($path)
		{
			if (@is_file($path))
				return @unlink($path);

			// delete all files in directory
			if (@is_dir($path))
			{
				if (substr($path, -1) !== '/')
					$path .= '/';

				$dh = @opendir($path);

				while (false !== ($file = readdir($dh)))
				{
					if ($file !== "." && $file !== "..")
						@unlink($path . $file);
				}

				@closedir($dh);
			}
		}
	}
?>