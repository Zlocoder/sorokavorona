<?php
include_once(dirname(__FILE__).'/../../ecm_csync.php');
class AdminCsyncImagesSettingsController extends AdminController
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
		$gen_ = $this->module->checked($yn1);
		$gen_pr_ = $this->module->checked($gen_pr);
		$dwim_ = $this->module->checked($dwim);
		$wm_ = $this->module->checked($use_watermark);

		$this->context->smarty->assign(
			array(
				'gen'=> $gen_,
				'gen_pr'=> $gen_pr_,
				'dwim' => $dwim_,
				'wm' => $wm_,
				)
		);
		$more = $this->module->display($path, 'views/displayImageSettings.tpl');
		return $more.parent::renderList();
	}

}

