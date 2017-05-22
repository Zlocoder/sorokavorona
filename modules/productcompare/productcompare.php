<?php

/**
 * Created by PhpStorm.
 * User: Ivan
 * Date: 18.01.2017
 * Time: 16:33
 */
class ProductCompare extends Module
{
    public function __construct()
    {
        $this->name = 'productcompare';
        $this->tab = 'front_office_features';
        $this->version = '1.0';
        $this->author = 'Ivan Grymalyuk';
        $this->bootstrap = true;
        $this->need_instance = 0;
        $this->ps_versions_compliancy = array('min' => '1.6', 'max' => _PS_VERSION_);

        parent::__construct();
        $this->displayName = $this->l('Module Compare Product');
        $this->description = $this->l('Compare Product in Category');
        $this->confirmUninstall = $this->l('Are you sure you want to uninstall?');

        if (!Configuration::get('productcompare'))
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
            !Configuration::updateValue('productcompare', 'complite')
        )
            return false;
        return true;
    }

    public function uninstall()
    {
        if (!parent::uninstall() ||
            !Configuration::deleteByName('productcompare')
        )
            return false;

        return true;
    }

    public function hookDisplayHeader()
    {
        if(!$_SESSION)
            session_start();
        $this->context->controller->addJS(($this->_path) . 'js/compare.js');
        $this->context->controller->addCSS(($this->_path) . 'css/compare.css');
    }

    public function hookProductActions($params)
    {
        $this->context->controller->addJS(($this->_path) . 'js/compare.js');
        $this->context->smarty->assign('product', $params['product']);
        return $this->display(__FILE__, 'product-actions.tpl');
    }

    public function clearSessions()
    {
           foreach ($_SESSION['VIEW'][$this->name] as $key => $category){
               if (count($category) == 0)
                   unset($_SESSION['VIEW'][$this->name][$key]);
           }
    }
    public function hookDisplayNav()
    {
        $this->clearSessions();
        $compare_products = $this->getCompareProducts();
        $categories = array();
        $count_products = array();

        $count_all = 0;
        foreach ($compare_products as $id_category => $products) {
            $categories[$id_category]['category'] = new Category($id_category);
            $count_products[$id_category] = count($compare_products[$id_category]);
            $count_all += $count_products[$id_category];
        }
        $count_products['total'] = $count_all;
        $this->context->smarty->assign('id_lang', $this->context->cart->id_lang);
        $this->context->smarty->assign('count_products', $count_products);
        $this->context->smarty->assign('categories', $categories);
        return $this->display(__FILE__, 'productcompare.tpl');
    }

    public function ajaxCall($method = 'getProducts')
    {

        if (!isset($_SESSION)) {
            session_start();
        }
        if(Tools::getValue('method'))
            $method = Tools::getValue('method');
        $id_product = Tools::getValue('id_product');
        if (!Tools::getValue('id_category'))
        {
            $product = new Product($id_product);
            $category = new Category($product->id_category_default);
        }
        else {
            $category = $this->getCategoryLevel(Tools::getValue('id_category'));
        }
        $id_category = $this->getCategoryLevel($category)->id_category;
        $result = false;
        if ($method == 'getProducts') {
            $result = $this->getProducts($id_category, $id_product);
            return $result;
        }
        if ($method == 'deleteCat') {
            $id_category = Tools::getValue('id_category');
            $result = $this->deleteCategory($id_category);
        }
        if ($method == 'add') {
            $result = $this->addProduct($id_category, $id_product);
        }
        if ($method == 'delete') {
            $result = $this->deleteProduct($id_category, $id_product);
        }
        if ($result)
            $result = $this->hookDisplayNav();
        return $result;
    }

    public function getCategoryLevel($category, $level_depth = 2)
    {
        if ($category->level_depth > $level_depth + 1) {
            $category_level = new Category($category->id_parent);
            return $this->getCategoryLevel($category_level, $level_depth);
        }
        return $category;
    }

    public function getCountProducts ()
    {

        $compare_products = $this->getCompareProducts();
        $categories = array();
        $count_products = array();
        $count_all = 0;
        foreach ($compare_products as $id_category => $products) {
            $categories[$id_category]['category'] = new Category($id_category);
            $count_products[$id_category] = count($compare_products[$id_category]);
            $count_all += $count_products[$id_category];
        }
        $count_products['total'] = $count_all;
        return $count_products;
    }
    public function getCompareProducts($id_category = null)
    {
        if($id_category)
            return $_SESSION['VIEW'][$this->name][$id_category];
        return $_SESSION['VIEW'][$this->name];
    }

    public function getProducts()
    {
        $cat_products = $this->getCompareProducts();
        $products = array();
        foreach ($cat_products as $category){
            foreach ($category as $product){
                $products[$product]=$product;
            }
        }
        return $products;
    }

    public function addProduct($id_category, $id_product)
    {
        if (!$id_category || !$id_product)
            return false;
        $_SESSION['VIEW'][$this->name][$id_category][$id_product] = $id_product;
        return true;
    }

    public function deleteProduct($id_category, $id_product)
    {
        if (!$id_category || !$id_product)
            return false;
        unset($_SESSION['VIEW'][$this->name][$id_category][$id_product]);
        return true;
    }

    public function deleteCategory($id_category)
    {
        if (!$id_category)
            return false;
        unset($_SESSION['VIEW'][$this->name][$id_category]);
            return true;
    }


}