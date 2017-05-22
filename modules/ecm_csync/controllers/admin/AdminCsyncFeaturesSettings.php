<?php
include_once(dirname(__FILE__).'/../../ecm_csync.php');
class AdminCsyncFeaturesSettingsController extends AdminController
{
	public $log;
	public function __construct()
	{

		$this->bootstrap = true;
		parent::__construct();


	}

	public function renderList()
	{

		$this->module = new Ecm_csync();
		$path = _PS_MODULE_DIR_."ecm_csync";
		include($path. '/init/defines_inc.php');
		$c_features_ = $this->module->checked($c_features);
		$multifeat_ = $this->module->checked($multifeat);
		$fbools_ = $this->module->checked($fbools);
		$this->context->smarty->assign(
			array(
				'features'=> $c_features_,
				'multifeat'=> $multifeat_,
				'fbools'=> $fbools_,
				)
		);
		//$this->context->smarty->assign('log',$this->readlog());
		$more = $this->module->display($path, 'views/displayFeaturesSettings.tpl');
		return $more.parent::renderList();
	}

}

