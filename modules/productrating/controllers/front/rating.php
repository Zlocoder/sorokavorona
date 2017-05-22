<?php
/**
 * Created by PhpStorm.
 * User: MrD1rk
 * Date: 18.03.2017
 * Time: 16:54
 */

class ProductRatingRatingModuleFrontController extends ModuleFrontController
{

    public function initContent()
    {
        if (!isset($_SESSION)) {
            session_start();
        }
        parent::initContent();
        $productrating = Module::getInstanceByName('productrating');

        $id_lang = $this->context->cart->id_lang;
        $sql_func = 'SELECT id_product,functionality_rate FROM '._DB_PREFIX_.'product_rating_users pru 
                     WHERE functionality_rate > 7';
        $sql_qual = 'SELECT * FROM '._DB_PREFIX_.'product_rating_users pru
                     LEFT JOIN '._DB_PREFIX_.'product_shop ps USING(id_product)
                     LEFT JOIN '._DB_PREFIX_.'product_lang  pl USING (id_product) WHERE pru.quality_rate > 7';
        $sql_des = 'SELECT * FROM '._DB_PREFIX_.'product_rating_users pru
                     LEFT JOIN '._DB_PREFIX_.'product_shop ps USING(id_product)
                     LEFT JOIN '._DB_PREFIX_.'product_lang  pl USING (id_product) WHERE pru.design_rate > 7';
        $functionality_prod = DB::getInstance()->executeS($sql_func);
        $products = Product::getProducts($id_lang,0,0,'id_product','ASC','',true, $this->context);
        $products = Product::getProductsProperties($id_lang, $products);
        foreach($products as &$product) {
            $sql = 'SELECT id_image FROM ' . _DB_PREFIX_ . 'image WHERE cover = 1 AND id_product = ' . $product['id_product'];
            $sql_func = 'SELECT id_product,functionality_rate FROM '._DB_PREFIX_.'product_rating_users pru 
                     WHERE functionality_rate > 7 AND id_product = '.$product['id_product'];
            $product['id_image'] = Db::getInstance()->executeS($sql)[0]['id_image'];
            $product['rating'] = Db::getInstance()->executeS($sql_func)[0]['functionality_rate'];
        }
        $this->context->smarty->assign('products', $products);
        $this->setTemplate('productrating.tpl');

    }

    public function setMedia()
    {
        parent::setMedia();
        $path = __PS_BASE_URI__ . 'modules/productcompare';
        $this->context->controller->addCSS($path . '/css/rating.css');
        $this->context->controller->addCSS(_PS_THEME_DIR_.'/css/product_list.css');
    }




}