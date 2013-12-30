<?php
	@include_once("conf/config.php");
	@include_once("lib/context/Context.php");
	@include_once("lib/module/ModuleLoader.php");
	
	Context::init();
	
	$moduleName = Context::get("module");	
	$moduleLoader = &ModuleLoader::getInstance();
	$module = &$moduleLoader->loadModule($moduleName);

	$module->execute();

	exit();
?>