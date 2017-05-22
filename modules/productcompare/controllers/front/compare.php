<?php

/**
 * Created by PhpStorm.
 * User: Ivan
 * Date: 19.01.2017
 * Time: 9:51
 */
class ProductCompareCompareModuleFrontController extends ModuleFrontController
{

    public function initContent()
    {
        if (!isset($_SESSION)) {
            session_start();
        }
        parent::initContent();
        $productcompare = Module::getInstanceByName('productcompare');
        $compare_products = $productcompare->getCompareProducts();
        if (!$compare_products) {
            $this->setTemplate('no-products.tpl');
            return true;
        }
        $id_category = Tools::getValue('id_category');
        if ($id_category) {
            $this->compareProductPage();
        } else {
            $categories = array();
            foreach ($compare_products as $id_category => $products) {
                $categories[$id_category]['category'] = new Category($id_category);
                $c_products = array();
                foreach ($products as $product) {
                    if (!$product) {
                        $productcompare->deleteProduct($id_category, $product);
                        continue;
                    }
                    $c_product = new Product($product);
                    if ($c_product->id_category_default == 79) {
                        $id_shop = $this->context->shop->id;
                        $c_product->price = SpecificPriceCore::getSpecificPrice($c_product->id, $id_shop, null, null, 0, 2)['price'];
                    }
                    $sql = 'SELECT id_image FROM ' . _DB_PREFIX_ . 'image WHERE id_product = ' . $c_product->id . ' AND cover = 1';
                    $images[$c_product->id]['id_image'] = Db::getInstance()->executeS($sql)[0]['id_image'];
                    $c_products[$c_product->id] = $c_product;
                }
                $categories[$id_category]['compare_products'] = $c_products;
            }
            $this->context->smarty->assign('categories', $categories);
            $this->context->smarty->assign('images', $images);
            $this->setTemplate('compare.tpl');

        }
    }

    public function setMedia()
    {
        parent::setMedia();
        $path = __PS_BASE_URI__ . 'modules/productcompare';
        $path2 = __PS_BASE_URI__ . 'themes/aqua_test';
        $this->context->controller->addCSS($path . '/css/compare.css');
        $this->context->controller->addCSS($path2 . '/css/product-news.css');
    }

    public function compareProductPage()
    {
        $id_category = Tools::getValue('id_category');
        if ($id_category == 'all') {
            return $this->compareAllProducts();
        }
        $id_lang = $this->context->cart->id_lang;
        $id_shop = $this->context->cart->id_shop;
        $productcompare = Module::getInstanceByName('productcompare');
        $compare_products = $productcompare->getCompareProducts($id_category);
        $features_all = array();
        $products = array();
        foreach ($compare_products as $compare_product) {
            $sql = "SELECT * FROM ps_product_shop ps LEFT JOIN ps_product_lang pl USING(id_product) WHERE ps.id_shop = $id_shop AND pl.id_lang = $id_lang AND ps.id_product = $compare_product";
            $product = Db::getInstance()->executeS($sql)[0];
            $product = Product::getProductProperties($id_lang, $product);
            $features = $product['features'];
            $product['features'] = array();
            $sql = 'SELECT id_image FROM ' . _DB_PREFIX_ . 'image WHERE id_product = ' . $compare_product . ' AND cover = 1';
            $product['id_image'] = Db::getInstance()->executeS($sql)[0]['id_image'];
            foreach ($features as $feature) {
                $product['features'][$feature['id_feature']] = $feature;
                $features_all[$feature['id_feature']] = $feature;
            }

            $products[$product['id_product']] = $product;
            if ($product['id_category_default'] == 79) {
                $id_shop = $this->context->shop->id;
                $product['price'] = SpecificPriceCore::getSpecificPrice($product['id_product'], $id_shop, null, null, 0, 2)['price'];
                $products[$product['id_product']] = $product;
            }
        }
        $category = new Category($id_category);
        $this->context->smarty->assign('category', $category);
        $this->context->smarty->assign('products', $products);
        $this->context->smarty->assign('features_all', $features_all);
        $this->setTemplate('category-compare.tpl');
    }

    public function compareAllProducts()
    {
        $productcompare = Module::getInstanceByName('productcompare');
        $compare_products = $productcompare->getCompareProducts();
        $products = array();
        $id_lang = $this->context->cart->id_lang;
        $id_shop = $this->context->cart->id_shop;
        $features_all = array();
        foreach ($compare_products as $category) {
//            var_dump($category);
            foreach ($category as $compare_product) {
                if(!isset($products[$compare_product])) {
                    $sql = "SELECT * FROM ps_product_shop ps LEFT JOIN ps_product_lang pl USING(id_product) WHERE ps.id_shop = $id_shop AND pl.id_lang = $id_lang AND ps.id_product = $compare_product";
                    $product = Db::getInstance()->executeS($sql)[0];
                    $product = Product::getProductProperties($id_lang, $product);
                    $features = $product['features'];
                    $product['features'] = array();
                    $sql = 'SELECT id_image FROM ' . _DB_PREFIX_ . 'image WHERE id_product = ' . $compare_product . ' AND cover = 1';
                    $product['id_image'] = Db::getInstance()->executeS($sql)[0]['id_image'];
                    foreach ($features as $feature) {
                        $product['features'][$feature['id_feature']] = $feature;
                        $features_all[$feature['id_feature']] = $feature;
                    }
                    $products[$product['id_product']] = $product;
                    if ($product['id_category_default'] == 79) {
                        $id_shop = $this->context->shop->id;
                        $product['price'] = SpecificPriceCore::getSpecificPrice($product['id_product'], $id_shop, null, null, 0, 2)['price'];
                        $products[$product['id_product']] = $product;
                    }
                }
            }
        }
        $this->context->smarty->assign('products', $products);
        $this->context->smarty->assign('features_all', $features_all);
        $this->setTemplate('category-compare-all.tpl');
    }

}