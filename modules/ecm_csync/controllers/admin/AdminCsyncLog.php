<?php
include_once(dirname(__FILE__).'/../../ecm_csync.php');
class AdminCsyncLogController extends AdminController
{
	public $log;
	public function __construct()
	{

		$this->bootstrap = true;
		parent::__construct();


	}
	public function readlog()
	{

		$file = fopen(_PS_MODULE_DIR_."ecm_csync/log.txt", "r");
		$array= array();

		while(!feof($file))
		{
			$array[] = fgets($file);
		}

		fclose($file);

		return $array;
	}

	public function renderList()
	{

		$this->module = new Ecm_csync();
		$path = _MODULE_DIR_."ecm_csync";
		$this->context->smarty->assign('log',$this->readlog());
		$more = $this->module->display($path, 'views/log.tpl');
		return $more.parent::renderList();
	}

}

