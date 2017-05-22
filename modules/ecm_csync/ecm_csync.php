<?php

class ecm_csync extends Module
{

	private $_html = '';
	function __construct()
	{
		$this->name = 'ecm_csync';
		$this->tab = 'content_management';
		$this->author = 'Elcommerce';
		$this->version = '6.5.4';
		$this->bootstrap = true;
		parent::__construct();
		$this->displayName = 'Синхронизация с  1C УТ';
		$this->description = 'Синхронизация товаров, цен и заказов с 1C УТ';
		//$cprice = Db::getInstance()->getValue("SELECT `guid` FROM `"._DB_PREFIX_."optprice` WHERE `main` = 1");
		//if(empty($cprice)) $this->warning = 'Не установлен тип цены по умолчанию';
		$this->confirmUninstall = 'Удаление модуля в дальнейшем сделает невозможным дифференциальную синхронизацию без проведения полной синхронизации';
	}

	public
	function install()
	{
		//if(_PS_VERSION_ > "1.4.11.1")
        if (!parent::install()
            || !$this->prepareModuleSettings()
            || !$this->update_db()
            || !$this->registerHook('displayBackOfficeHeader')

        )
        return false;

        return true;

		/*$this->prepareModuleSettings();
		if(_PS_VERSION_ > "1.4.11.1")
		$this->registerHook('displayBackOfficeHeader');
		return (parent::install());*/
	}
	public
	function uninstall()
	{
		$this->unregisterHook('displayBackOfficeHeader');
		$sql = array();
        include (dirname(__file__) . '/init/uninstall_sql.php');
        foreach ($sql as $s) {
            if (!Db::getInstance()->Execute($s)) {
               die('ошибка при выполнении запроса '.$s.' возможно поля не существует');
            }
        }
        if ( _PS_VERSION_ > "1.4.11.1"){
		$idTabs = array();
        $idTabs[] = Tab::getIdFromClassName('AdminCsyncMain');
        $idTabs[] = Tab::getIdFromClassName('AdminCsync');
        $idTabs[] = Tab::getIdFromClassName('AdminCsyncLog');
        $idTabs[] = Tab::getIdFromClassName('AdminCsyncOrder');
        $idTabs[] = Tab::getIdFromClassName('AdminCsyncAdditional');
        $idTabs[] = Tab::getIdFromClassName('AdminCsyncAbout');
        $idTabs[] = Tab::getIdFromClassName('AdminCsyncImagesSettings');
        $idTabs[] = Tab::getIdFromClassName('AdminCsyncStockSettings');
        $idTabs[] = Tab::getIdFromClassName('AdminCsyncFeaturesSettings');
        $idTabs[] = Tab::getIdFromClassName('AdminCsyncCategoriesSettings');
        $idTabs[] = Tab::getIdFromClassName('AdminCsyncImportExportSettings');
        $idTabs[] = Tab::getIdFromClassName('AdminCsyncPrises');
        $idTabs[] = Tab::getIdFromClassName('AdminCsyncCompability');
        $idTabs[] = Tab::getIdFromClassName('AdminCsyncProductsSettings');
        foreach ($idTabs as $idTab) {
            if ($idTab) {
                $tab = new Tab($idTab);
                $tab->delete();
            }
        }
		}
		if(!Configuration::deleteByName('_PRICE_ID_') OR !parent::uninstall())			return false;
		return true;
	}
	public
	function addTab($name, $public_name,$parent_tab)
	{
		$tab = new Tab();
		$tab->class_name = $name;
        $tab->name[(int) (Configuration::get('PS_LANG_DEFAULT'))] = $this->l($public_name);
        $tab->id_parent = $parent_tab;
        $tab->module = $this->name;
        $tab->add();
	}
	public
	function update_db()
	{
		$sql = array();
        include (dirname(__file__) . '/init/install_sql.php');
        foreach ($sql as $s) {
            if (!Db::getInstance()->Execute($s)) {
              die('ошибка при выполнении запроса '.$s.' возможно поле уже существует');
            }
        }
         return true;
	}
	public
	function checked($name)
	{
		return $checked = ((isset($name)) && ($name == '1'))?'checked="checked"' : '""';
	}

	  public function prepareModuleSettings()
    {
        // Database
        global $cookie;

        if(!Configuration::get('_ecm_csync_install_date_'))
		Configuration::updateValue('_ecm_csync_install_date_', date("Y-m-d"));
		if(!Configuration::get('_ecm_csync_tax_rule_'))
		Configuration::updateValue('_ecm_csync_tax_rule_', 0);
		if(!Configuration::get('_ecm_csync_qvant_'))
		Configuration::updateValue('_ecm_csync_qvant_',500);
		if(!Configuration::get('_ecm_csync_category_product_'))
		Configuration::updateValue('_ecm_csync_category_product_',1);
		if(!Configuration::get('_ecm_csync_furl_product_'))
		Configuration::updateValue('_ecm_csync_furl_product_',2);
		if(!Configuration::get('_ecm_csync_sdesk_'))
		Configuration::updateValue('_ecm_csync_sdesk_',2);
		if(!Configuration::get('_ecm_csync_status_export_'))
		Configuration::updateValue('_ecm_csync_status_export_',1);
		if(!Configuration::get('_ecm_csync_employee_'))
		Configuration::updateValue('_ecm_csync_employee_',$cookie->id_employee);
		if(!Configuration::get('_ecm_csync_desk_'))
		Configuration::updateValue('_ecm_csync_desk_',2);
		if(!Configuration::get('_ecm_csync_tax_rule_'))
		Configuration::updateValue('_ecm_csync_tax_rule_', 0);
		if(!Configuration::get('_ecm_csync_zero_'))
		Configuration::updateValue('_ecm_csync_zero_', 3);
		if(!Configuration::get('_ecm_csync_zero_del_'))
		Configuration::updateValue('_ecm_csync_zero_del_', 1);
		if(!Configuration::get('_ecm_csync_qtp_'))
		Configuration::updateValue('_ecm_csync_qtp_', 2);
		$default_lang = Configuration::get('PS_LANG_DEFAULT');
		if(!Configuration::get('_ecm_csync_lang_'))
		Configuration::updateValue('_ecm_csync_lang_', $default_lang);
		if(!Configuration::get('_ecm_csync_final_orders_states_'))
		Configuration::updateValue('_ecm_csync_final_orders_states_','4,5,6');
		if ( _PS_VERSION_ > "1.4.11.1"){
		$parent_tab = new Tab();
        $parent_tab->class_name = 'AdminCsyncMain';
        $parent_tab->id_parent = 0;
        $parent_tab->module = $this->name;
        $parent_tab->name[(int) (Configuration::get('PS_LANG_DEFAULT'))] = $this->l('Синхронизация с 1С');
        $parent_tab->add();
		$this->addTab('AdminCsync','Основные настройки',$parent_tab->id);
		$this->addTab('AdminCsyncPrises','Настройка цен',$parent_tab->id);
		$this->addTab('AdminCsyncProductsSettings','Настройка товаров',$parent_tab->id);
		$this->addTab('AdminCsyncCategoriesSettings','Настройка категорий',$parent_tab->id);
		$this->addTab('AdminCsyncImagesSettings','Настройка изображений',$parent_tab->id);
		$this->addTab('AdminCsyncFeaturesSettings','Настройка свойств',$parent_tab->id);
		$this->addTab('AdminCsyncStockSettings','Настройка остатков',$parent_tab->id);
		$this->addTab('AdminCsyncOrder','Настройка заказов',$parent_tab->id);
		$this->addTab('AdminCsyncCompability','Совместимость с модулями',$parent_tab->id);
		$this->addTab('AdminCsyncAdditional','Дополнительные опции',$parent_tab->id);
		$this->addTab('AdminCsyncImportExportSettings','Импорт/Экспорт',$parent_tab->id);
		$this->addTab('AdminCsyncLog','Лог',$parent_tab->id);
		$this->addTab('AdminCsyncAbout','О нас',$parent_tab->id);

		}
        return true;
    }

	public
	function add1c ($name)
	{
		Db::getInstance()->Execute("
			INSERT INTO `" . _DB_PREFIX_ . "category` (`id_parent`,`id_shop_default`,`level_depth`,`date_add`,`date_upd`,`active`)
			VALUES (2, 1, 3 , NOW(), NOW(), 0)
			");
		$id_category = Db::getInstance()->Insert_ID();
		Db::getInstance()->Execute("
			INSERT INTO `" . _DB_PREFIX_ . "category_lang` (`id_category`, `id_shop`,`id_lang`, `name`,`link_rewrite`)
			VALUES (" . $id_category . ", 1,  " . Configuration::get('PS_LANG_DEFAULT') . ", '" . pSQL($name) . "', '" .
			Tools::link_rewrite($name, $utf8_decode = true) . "')
			");
		if ( _PS_VERSION_ > "1.4.11.1"){
		$position = Category::getLastPosition(2,1);
		Db::getInstance()->Execute("
			INSERT INTO `" . _DB_PREFIX_ . "category_shop` (`id_category`, `id_shop`,`position`)
			VALUES (" . $id_category . ", 1,  " . $position . ")
			");
		}
	}


	 public function hookDisplayBackOfficeHeader()
    {
        if (method_exists($this->context->controller, 'addCSS'))
            $this->context->controller->addCSS(($this->_path).'css/ecm_csync.css', 'all');
    }


}

