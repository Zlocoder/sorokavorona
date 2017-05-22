<?php

if (!defined('_PS_VERSION_'))
    exit;

class AdminCsyncController extends AdminController {

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
		switch(php_sapi_name()){
			case "cgi":
			case "cgi-fcgi": $rule = '<li>RewriteRule .* - [E=REMOTE_USER:%{HTTP:Authorization}]</li>';
			break;
			default: $rule = '<li>RewriteRule .* - [E=HTTP_AUTHORIZATION:%{HTTP:Authorization}]</li>';
		}
		$taxes = Tax::getTaxes($id_lang);

		$tax_rule[0] = 'Без налогов';
		foreach($taxes as $tax){
			$id = $tax['id_tax'];
			$tax_rule["$id"] = $tax['name'];
		}
		$languages = Language::getLanguages();
		//d($languages);
		foreach($languages as $language){
			$id = $language['id_lang'];
			$lang["$id"] = $language['name'];
		}
		$this->ssl_enable = Configuration::get('PS_SSL_ENABLED');
		$base = (($this->ssl_enable) ? 'https://' : 'http://');
		$connector = $base.htmlspecialchars($_SERVER['HTTP_HOST'], ENT_COMPAT, 'UTF-8').__PS_BASE_URI__.'modules/ecm_csync/1c_exchange.php';
		$fix_ = $this->module->checked($fix);
		$multilang_ = $this->module->checked($multilang);
		$clear_cache_ = $this->module->checked($clear_cache);
		$this->context->smarty->assign(
			array(
				'login_1c'=> $login_1c,
				'pass_1c'=> $pass_1c,
				'rule' => $rule,
				'tax_rule' => $tax_rule,
				'tax_rule_selected' => $tax_rule_selected,
				'lang' => $lang,
				'lang_selected' => $id_lang,
				'connector' => $connector,
				'fix' => $fix_,
				'multilang' => $multilang_,
				'clear_cache' => $clear_cache_,
				'qvant' => $qvant_entity,
				)
		);
		$more = $this->module->display($path, 'views/displayMainSettings.tpl');
		return $more.parent::renderList();
	}

}

?>
