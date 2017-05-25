<?php

/**
 * Created by PhpStorm.
 * User: Ivan
 * Date: 13.03.2017
 * Time: 13:16
 */
class ProductRating extends Module
{
    public function __construct()
    {
        $this->name = 'productrating';
        $this->tab = 'front_office_features';
        $this->version = '1.0';
        $this->author = 'Ivan Grymalyuk';
        $this->bootstrap = true;
        $this->need_instance = 0;
        $this->ps_versions_compliancy = array('min' => '1.6', 'max' => _PS_VERSION_);

        parent::__construct();
        $this->displayName = $this->l('Module Product Rating');
        $this->description = $this->l('Users and Administrators can put an estimate for products');
        $this->confirmUninstall = $this->l('Are you sure you want to uninstall?');

        if (!Configuration::get('productrating'))
            $this->warning = $this->l('No name provided');
    }

    public function install()
    {

        if (Shop::isFeatureActive())
            Shop::setContext(Shop::CONTEXT_ALL);

        if (!parent::install() ||
            !$this->registerHook('header') ||
            !$this->registerHook('displaynav') ||
            !$this->registerHook('productsactions') ||
            !Configuration::updateValue('productrating', 'complite')
        )
            return false;
        $this->createTable();
        return true;

    }

    public function uninstall()
    {
        if (!parent::uninstall() ||
            !Configuration::deleteByName('productrating')
        )
            return false;
        $this->deleteTable();
        return true;
    }

    public function hookDisplayHeader()
    {
        $this->context->controller->addCSS(($this->_path) . 'css/rating.css');
        $this->context->controller->addJS(($this->_path) . 'js/rating.js');
        //$this->context->controller->addJqueryUI('ui.progressbar');
    }

    protected function createTable()
    {
        $sql = 'CREATE TABLE ' . _DB_PREFIX_ . '`product_rating_users` ( `id` INT NOT NULL AUTO_INCREMENT , `id_customer` INT NOT NULL ,
               `id_product` INT NOT NULL , `functionality_rate` INT NULL , `quality_rate` INT NULL , `design_rate` INT NULL ,
               `limitations` TEXT NULL , `data_add` DATETIME NOT NULL ,
               `data_upd` DATETIME NOT NULL , PRIMARY KEY (`id`)) ENGINE = InnoDB';
        $res = (bool)DB::getInstance()->execute($sql);
        return $res;
    }

    protected function deleteTable()
    {
        $sql = 'DROP TABLE IF EXISTS `' . _DB_PREFIX_ . 'product_rating_users`';
        return DB::getInstance()->execute($sql);
    }

    public function getProduct()
    {
        $product = new Product(Tools::getValue('id_product'));
        return $product->id;
    }

    public function getAdminVote()
    {
        $sql1 = 'SELECT * FROM ' . _DB_PREFIX_ . 'product_rating WHERE id_product =' . $this->getProduct();
        $avg_admin = DB::getInstance()->executeS($sql1);
        return $avg_admin[0];
    }

    public function getUserVote()
    {
        $sql2 = 'SELECT count(design_rate) as `cd`,count(quality_rate) as `cq`,count(functionality_rate) as `cf`, AVG(functionality_rate) as func_rate, AVG(quality_rate) as quality_rate, AVG(design_rate) as design_rate, id_customer FROM ' . _DB_PREFIX_ . 'product_rating_users
                  WHERE id_product =' . $this->getProduct();
        $avg_customer = DB::getInstance()->executeS($sql2);
        return $avg_customer[0];

    }
    public function checkVotes()
    {
        $sql = 'SELECT * FROM '._DB_PREFIX_.'product_rating_users WHERE id_customer='.$this->context->customer->id.' AND id_product='.Tools::getValue('id_product');
        return DB::getInstance()->executeS($sql)[0];
    }

    public function hookDisplayProductTabContent()
    {
        $this->context->smarty->assign('avg_admin', $this->getAdminVote());
        $this->context->smarty->assign('check', $this->checkVotes());
        $this->context->smarty->assign('avg_customer', $this->getUserVote());
        return $this->display(__FILE__, '/views/templates/hook/rating.tpl');
    }


    public function ajaxCall()
    {
        $id_customer = $this->context->customer->id;
        $id_product = Tools::getValue('id_product');
        if (!DB::getInstance()->executeS('SELECT id_product , id_customer FROM ' . _DB_PREFIX_ . 'product_rating_users 
                WHERE id_product =' . $id_product . ' AND id_customer = ' . $id_customer)
        ) {
            $sql = 'INSERT INTO ' . _DB_PREFIX_ . 'product_rating_users SET 
            id_product =' . $id_product . ',
            id_customer =' . $id_customer;
            if (Tools::getIsset('functionality_rate')) {
                $sql = $sql . ', functionality_rate =' . (int)$_POST['functionality_rate'];
            }
            if (Tools::getIsset('design_rate')) {
                $sql = $sql . ', design_rate =' . (int)$_POST['design_rate'];
            }
            if (Tools::getIsset('quality_rate')) {
                $sql = $sql . ', quality_rate =' . (int)$_POST['quality_rate'];
            }
            DB::getInstance()->execute($sql);
        } else {
            $sql = '';
            if (Tools::getIsset('functionality_rate')) {
                $sql = ' functionality_rate =' . (int)$_POST['functionality_rate'];
            }
            if (Tools::getIsset('design_rate')) {
                $sql = ' design_rate =' . (int)$_POST['design_rate'];
            }
            if (Tools::getIsset('quality_rate')) {
                $sql = ' quality_rate =' . (int)$_POST['quality_rate'];
            }

            DB::getInstance()->execute('UPDATE ' . _DB_PREFIX_ . 'product_rating_users SET ' . $sql . ',data_upd="' . date('Y-m-d H:i:s') . '" 
            WHERE id_product =' . $id_product . ' AND id_customer =' . $id_customer);

        }
        if ($this->context->cart->id_lang == 1)
            $html = '<div class="alert-rating alert alert-success"><p>Спасибо, Ваш голос принят</p></div>';
        else
            $html = '<div class="alert-rating alert alert-success"><p>Дякую, Ваш голос зараховано</p></div>';
        $this->context->smarty->assign('avg_admin', $this->getAdminVote());
        $this->context->smarty->assign('html', $html);
        $this->context->smarty->assign('check', $this->checkVotes());
        $this->context->smarty->assign('avg_customer', $this->getUserVote());
        return $this->display(__FILE__, '/views/templates/hook/rating.tpl');
    }


}