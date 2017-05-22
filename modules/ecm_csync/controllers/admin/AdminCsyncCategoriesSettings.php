<?php
include_once(dirname(__FILE__).'/../../ecm_csync.php');
class AdminCsyncCategoriesSettingsController extends AdminController
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
		$multicat_ = $this->module->checked($multicat);
		$idsc_ = $this->module->checked($idsc);
		$sc_ = $this->module->checked($sc);
		$cp1c_ = $this->module->checked($cp1c);
		$cp_ = $this->module->checked($cp);

		$this->context->smarty->assign(
			array(
				'multicat'=> $multicat_,
				'idsc'=> $idsc_,
				'sc' => $sc_,
				'cp1c' => $cp1c_,
				'cp' => $cp_,
				'category_product_radio' => $category_product,
				'category_product_radios' => array(
                               1 => 'Как в 1C',
                               2 => 'Как в 1C + род. категории',
                               3 => 'Скрытая категория 1С',
                              ),
				)
		);
		$more = $this->module->display($path, 'views/displayCategoriesSettings.tpl');
		return $more.parent::renderList();
	}

}

