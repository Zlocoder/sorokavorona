<?php
class AdminCsyncAboutController extends AdminController
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
		$path = _MODULE_DIR_."ecm_csync";
		$this->context->smarty->assign(
			array(
				'version'=> $this->module->version,
				'author' => $this->module->author,
				'name' => $this->module->displayName,
				)
		);
		$more = $this->module->display($path, 'views/displayAbout.tpl');
		return $more.parent::renderList();
	}

}

