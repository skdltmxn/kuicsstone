<?php
	/*
	 * Module class
	 */

	if (!defined("READY")) exit();

	@require_once("lib/module/ModuleViewer.php");
	@require_once("lib/module/ModuleController.php");

	abstract class Module
	{
		public function execute()
		{
			
			$this->_preExecute();

			if ($_SERVER["REQUEST_METHOD"] === "POST")
			{
				$controller = &ModuleController::getController(Context::get("module"));
				if ($controller->perform(Context::get("doAction")) === true)
				{
					$retModule = Context::get("retModule");
					$retAction = Context::get("retAction");

					if (isset($retModule))
						Context::set("module", $retModule);

					if (isset($retAction))
						Context::set("action", $retAction);
				}
			}
			
			$action = Context::get("action");


			$viewer = &ModuleViewer::getViewer(Context::get("module"));
			$viewer->view($action);

		}

		private function _preExecute()
		{
			@require_once("lib/module/ModuleModel.php");

			// read score cache
			if ($_SESSION["sess_info"]->logged === true)
			{
				$teamModel = &ModuleModel::getModel("team");
				$teamModel->readScoreCache($_SESSION["sess_info"]->t_idx, $_SESSION["sess_info"]->code);
			}
		}
	}
?>