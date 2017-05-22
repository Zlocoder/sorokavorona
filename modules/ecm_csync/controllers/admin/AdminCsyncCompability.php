<?php

if (!defined('_PS_VERSION_'))
    exit;

class AdminCsyncCompabilityController extends AdminController {

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
		$np_ = $this->module->checked($delivery['np']['ecm_newpost']);
		$phonelogin_ = $this->module->checked($phone_login);
		$on_stock_ = $this->module->checked($on_stock);
		$up_ = $this->module->checked($delivery['up']['ecm_ukrposhta']);
		$da_ = $this->module->checked($delivery['da']['ecm_delivery_auto']);
		$it_ = $this->module->checked($delivery['it']['ecm_intime']);
		$al_ = $this->module->checked($delivery['al']['ecm_autolux']);
		$me_ = $this->module->checked($delivery['mexp']['ecm_meest_express']);
		$pjs_ = $this->module->checked($pjs_installed);
		$productrating_ = $this->module->checked($productrating_installed);
		$this->context->smarty->assign(
			array(
				'phonelogin'=> $phonelogin_,
				'on_stock'=> $on_stock_,
				'np'=> $np_,
				'up' => $up_,
				'da' => $da_,
				'it' => $it_,
				'al' => $al_,
				'me' => $me_,
				'pjs' => $pjs_,
				'productrating' => $productrating_,
				)
		);
		$more = $this->module->display($path, 'views/displayCompability.tpl');
		return $more.parent::renderList();
	}

}

?>
