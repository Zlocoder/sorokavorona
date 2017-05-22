<?php
include_once(dirname(__FILE__).'/../../ecm_csync.php');
class AdminCsyncStockSettingsController extends AdminController
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
		$cs_ = $this->module->checked($cs);
		$col_ = $this->module->checked($col);
		$aqs_ = $this->module->checked($aqs);
		$this->context->smarty->assign(
			array(
				'cs'=> $cs_,
				'col'=> $col_,
				'aqs' => $aqs_,
				'zero_radio' => $zero,
				'redirect_radio' => $redirect,
				'visibility_radio' => $visibility,
				'qvant0' => $qvant0,
				'qtp_radio' => $qtp,
				'zero_radios' => array(
                               1 => 'Деактивировать товары',
                               2 => 'Установить невидимыми',
                               3 => 'Не управлять поведением',
                              ),
                'redirect_radios' => array(
                               301 => '301',
                               302 => '302',
                               404 => '404',
                              ),
                'visibility_radios' => array(
                               'search' => 'Только поиск',
                               'none' => 'Нигде',
                              ),                
                'qtp_radios' => array(
                               1 => 'Максимальное количество на остатках',
                               2 => 'Минимальная цена и не нулевой остаток',
                               3 => 'Управлять с 1С',
                              ),
				)
		);
		$more = $this->module->display($path, 'views/displayStockSettings.tpl');
		return $more.parent::renderList();
	}

}

