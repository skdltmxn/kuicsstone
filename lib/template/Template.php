<?php
	/*
	 * Template class
	 */

	if (!defined("READY")) exit();

	@require_once("lib/Singleton.php");

	class Template extends Singleton
	{
		protected $_templateFile;
		protected $_templateBase;
		protected $_includeList;
		protected $_content;
		protected $_variables;

		protected function __construct()
		{
			$this->_includeList = array();
			$this->_content = "";
			$this->_variables = array();
		}

		public function setTemplateBase($base)
		{
			$this->_templateBase = WEB_ROOT. $this->_getRelativePath($base);
		}

		public function getIncludeList()
		{
			return $this->_includeList;
		}

		public function interpret($tpl)
		{
			if (empty($tpl) || !file_exists($tpl))
				exit("template file `" .$tpl . "` not found");

			$this->_includeList = array();
			$this->_content = file_get_contents($tpl);
			$parsed = $this->parse($this->_content);
			$this->_content = $parsed[0];

			return $parsed;
		}

		public function parse($content)
		{
			// {inc "%s"} -> include file
			$content = preg_replace_callback("/\{ *inc +\"(.+)\" *\}/", array($this, "_parsePath"), $content);

			// {$%s = value} -> declare a local variable
			$content = preg_replace_callback("/\{ *\\\$([_a-zA-Z][a-zA-Z0-9_]*) *= *(.+)\}/", array($this, "_parseDeclare"), $content);

			// Convert resource path for <img> tag
			$content = preg_replace_callback("/(<img.+)(?:src) *= *[\"' ]([^\"' ]+)[\"' ](.*>)/i", array($this, "_parseResource"), $content);

			// {foreach $%s as $%s[ => $%s]} -> foreach loop
			$content = preg_replace_callback("/\{ *foreach +\\\$([_a-zA-Z][a-zA-Z0-9_]*)(->.+|\[.+\])? +as +\\\$([_a-zA-Z][a-zA-Z0-9_]*) *(=> *\\\$([_a-zA-Z][a-zA-Z0-9_]*))?\}/", array($this, "_parseForeach"), $content);
			$content = preg_replace("/\{ *(?:\/foreach) *\}/", "<?php endforeach; ?>", $content);

			// {if <expr> <op> <expr>} -> if
			// <expr> can only be php vars, numbers, booleans, strings and null
			// if <expr> is surrounded by parenthesis, it will be executed without any modification (errors are users' responsibility)
			// <op> can only be among (===|!==|==|!=|>=|<=|>|<)
			$content = preg_replace_callback("/\{ *(else|)?if +(\\\$[_a-zA-Z][a-zA-Z0-9_]*(?:->[_a-zA-Z][^\s\}]+|\[.+\])?|[0-9]+|\".*\"|\'.*\'|true|false|null|\(.+\)) *(===|!==|==|!=|>|<|>=|<=) *(\\\$[_a-zA-Z][a-zA-Z0-9_]*(?:->[_a-zA-Z][^\s\}]+|\[.+\])?|[0-9]+|\".*\"|\'.*\'|true|false|null|\(.+\)) *\}/i", 
				array($this, "_parseIf"), $content);
			$content = preg_replace("/\{ *(?:else) *\}/", "<?php else: ?>", $content);
			$content = preg_replace("/\{ *(?:\/if) *\}/", "<?php endif; ?>", $content);

			$content = preg_replace_callback("/\{ *while *\((.+)\) *\}/", array($this, "_parseWhile"), $content);
			$content = preg_replace("/\{ *(?:\/while) *\}/", "<?php endwhile; ?>", $content);

			// {$%s} -> variable substitution
			$content = preg_replace_callback("/\{ *\\\$([_a-zA-Z][a-zA-Z0-9_]*)(->[_a-zA-Z][^\s\}]+|\[.+\])? *\}/", array($this, "_parseVar"), $content);

			// {%s(...)} -> function call
			$content = preg_replace_callback("/\{ *([_a-zA-Z][a-zA-Z0-9_]*)\((.*)\) *\}/", array($this, "_parseFunction"), $content);

			return array($content, $this->_includeList);
		}

		private function _parseWhile($matches)
		{
			return "<?php while(" . $matches[1] . "): ?>";
		}

		private function _parseDeclare($matches)
		{
			$var = $matches[1];

			if (!$this->_isVariableLocal($var))
				$this->_variables[] = $var;

			$ret = "<?php ";

			$rhs = $matches[2];

			// $a = $b
			if (preg_match("/^\\\$([_a-zA-Z][a-zA-Z0-9_]*)(->.+|\[.+\])?$/", $rhs, $rhs_matches))
			{
				$_var = $rhs_matches[1];

				if ($this->_isVariableLocal($_var))
				{
					$ret .= '$' . $var . " = " . $_var . $rhs_matches[2];
				}
				else
				{
					$__var = Context::get($_var);
					if (Context::varExists($_var))
					{
						$stmt = sprintf("Context::get(\"%s\")", $_var);
						if (gettype($__var) === "object" ||
							gettype($__var) === "array")
						{
							$random_var = '\$_' . rand(20001, 30000);			
							$ret .= $random_var . " = " . $stmt . "; ";
							$ret .= '$' . $var . " = " . $random_var . $rhs_matches[2];
						}
						else
							$ret .= '$' . $var . " = " . $stmt;
					}
				}
			}
			// $a = <const>
			else
			{
				$ret .= '$' . $var . " = " . $rhs;
			}

			$ret .= "; ?>";

			return $ret;
		}

		private function _parseResource($matches)
		{
			return sprintf("%ssrc=\"%s\"%s", $matches[1],
											 $this->_templateBase . '/' . $matches[2],
											 $matches[3]);
		}

		private function _parseFunction($matches)
		{
			return sprintf("<?php echo %s(%s); ?>", $matches[1], $matches[2]);
		}

		private function _parseIf($matches)
		{
			$else = $matches[1];
			$left = $matches[2];
			$op = $matches[3];
			$right = $matches[4];

			if ($left[0] == '$')
				$left = $this->_handleIfVariable(substr($left, 1));

			if ($right[0] == '$')
				$right = $this->_handleIfVariable(substr($right, 1));

			return "<?php " . $else . "if (" . $left . " " . $op . " " . $right . "): ?>";
		}

		private function _handleIfVariable($var)
		{
			if (preg_match("/([_a-zA-Z][a-zA-Z0-9_]*)(->.+|\[.+\])?/", $var, $matches))
				$var = $matches[1];

			if ($this->_isVariableLocal($var))
				return "$" . $var . $matches[2];

			$_var = Context::get($var);
			if (Context::varExists($var))
			{
				$ret = sprintf("Context::get(\"%s\")", $var);

				if (gettype($_var) === "object" ||
					gettype($_var) === "array")
				{
					if (isset($matches[2]))
						$ret .= $matches[2];
				}

				return $ret;
			}

			return "null";
		}

		private function _parseForeach($matches)
		{
			if ($this->_isVariableLocal($matches[1]))
				$list = '$' . $matches[1] . ';';
			else
			{
				$list = Context::get($matches[1]);
				$list = sprintf("Context::get(\"%s\");", $matches[1]);
			}

			$random_var = "_" . rand(10, 10000);
			$statement = "<?php $" . $random_var . " = " . $list . " foreach ($" . $random_var . $matches[2] . " as $" . $matches[3];
			$this->_variables[] = $matches[3];

			// {foreach $a as $b => $c}
			if (count($matches) > 4)
			{
				$statement .= " => $" . $matches[5];
				$this->_variables[] = $matches[5];
			}

			$statement .= "): ?>";
			return $statement;
		}

		private function _parseVar($matches)
		{
			if ($this->_isVariableLocal($matches[1]))
			{
				$ret = "<?php echo $" . $matches[1];

				if (isset($matches[2]))
					$ret .= $matches[2];

				return $ret . "; ?>"; 
			}

			$_var = Context::get($matches[1]);
			if (Context::varExists($matches[1]))
			{
				$ret = "<?php ";

				if (gettype($_var) === "object" ||
					gettype($_var) === "array")
				{
					if (isset($matches[2]))
					{
						$random_var = "_" . rand(10001, 20000);
						$ret .= sprintf("\$%s = Context::get(\"%s\"); ", $random_var, $matches[1]);
						$ret .= sprintf("echo \$%s%s; ", $random_var, $matches[2]);
					}
					else
						$ret .= sprintf("echo var_dump(Context::get(\"%s\")); ", $matches[1]);
				}
				else
					$ret .= sprintf("echo Context::get(\"%s\"); ", $matches[1]);

				$ret .= "?>";
				return $ret;
			}

			return "";
		}

		private function _parsePath($matches)
		{
			$ext = strtolower(substr($matches[1], strrpos($matches[1], '.') + 1));
			
			
			if ($ext === "js")
				$this->_includeList[] = "<script src=\"" . $this->_templateBase . "/" . $matches[1] . "\"></script>";
			else if ($ext === "css")
				$this->_includeList[] = "<link rel=\"stylesheet\" href=\"" . $this->_templateBase . "/" . $matches[1] . "\">";
			/*else if ($ext === "tpl")
			{
				$path = $this->_templateBase . "/" . $matches[1];
				if (file_exists($path))
					return $this->parse(file_get_contents($path));
			}
			else
				return "<?php @include(\"" . $this->_templateBase . "/" . $matches[1] . "\"); ?>";
			*/
			
			return "";
		}

		private function _getRelativePath($path)
		{
			$path = str_replace("\\", "/", $path);
			$relativePath = str_replace(ROOT, "", $path);

			// remove slashes on both sides
			return trim($relativePath, "/");
		}

		private function _isVariableLocal($var)
		{
			return in_array($var, $this->_variables);
		}
	}
?>