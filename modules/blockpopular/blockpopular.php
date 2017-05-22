<?php
if (!defined('_PS_VERSION_'))
    exit;

class BlockPopular extends Module
{
    protected static $cached_products;

    public function __construct()
    {
        $this->name = 'blockpopular';
        $this->tab = 'front_office_features';
        $this->version = '1.0.0';
        $this->author = 'PrestaShop';
        $this->need_instance = 0;

        $this->bootstrap = true;
        parent::__construct();

        $this->displayName = $this->l('Popular products block');
        $this->description = $this->l('Displays a block featuring your store\'s most popular products.');
        $this->ps_versions_compliancy = array('min' => '1.6', 'max' => '1.6.99.99');
    }

    public function install()
    {
        $success = (parent::install()
            && Configuration::updateValue('POPULAR_PRODUCTS_NBR', 5)
            && $this->registerHook('header')
            && $this->registerHook('displayHomeTab')
            && $this->registerHook('displayHomeTabContent')
        );

        $this->_clearCache('*');

        return $success;
    }

    public function uninstall()
    {
        $this->_clearCache('*');

        return parent::uninstall();
    }

    public function getContent()
    {
        $output = '';
        if (Tools::isSubmit('submitBlockPopular'))
        {
            if (!($productNbr = Tools::getValue('POPULAR_PRODUCTS_NBR')) || empty($productNbr))
                $output .= $this->displayError($this->l('Please complete the "products to display" field.'));
            elseif ((int)($productNbr) == 0)
                $output .= $this->displayError($this->l('Invalid number.'));
            else
            {
                Configuration::updateValue('PS_BLOCK_POPULAR_DISPLAY', (int)(Tools::getValue('PS_BLOCK_POPULAR_DISPLAY')));
                Configuration::updateValue('POPULAR_PRODUCTS_NBR', (int)($productNbr));
                $this->_clearCache('*');
                $output .= $this->displayConfirmation($this->l('Settings updated'));
            }
        }
        return $output.$this->renderForm();
    }

    protected function getPopularProducts()
    {
        if (!Configuration::get('POPULAR_PRODUCTS_NBR'))
            return;

        $newProducts = false;

        $newProducts = Product::getPopularProducts((int) $this->context->language->id, 0, (int)Configuration::get('NEW_PRODUCTS_NBR'));

        if (!$newProducts && Configuration::get('PS_BLOCK_POPULAR_DISPLAY'))
            return;

        return $newProducts;
    }

    protected function getCacheId($name = null)
    {
        if ($name === null)
            $name = 'blockpopular';

        return parent::getCacheId($name.'|'.date('Ymd'));
    }

    public function hookdisplayHomeTab($params)
    {
        if (!$this->isCached('tab.tpl', $this->getCacheId('blockpopular-tab')))
            BlockPopular::$cached_products = $this->getPopularProducts();

        if (BlockPopular::$cached_products === false)
            return false;

        return $this->display(__FILE__, 'tab.tpl', $this->getCacheId('blockpopular-tab'));
    }

    public function hookdisplayHomeTabContent($params)
    {
        if (!$this->isCached('blockpopular_home.tpl', $this->getCacheId('blockpopular-home')))
        {
            $this->smarty->assign(array(
                'popular_products' => BlockPopular::$cached_products,
                'mediumSize' => Image::getSize(ImageType::getFormatedName('medium')),
                'homeSize' => Image::getSize(ImageType::getFormatedName('home'))
            ));
        }

        if (BlockPopular::$cached_products === false)
            return false;

        return $this->display(__FILE__, 'blockpopular_home.tpl', $this->getCacheId('blockpopular-home'));
    }

    public function hookHeader($params)
    {
        if (isset($this->context->controller->php_self) && $this->context->controller->php_self == 'index')
            $this->context->controller->addCSS(_THEME_CSS_DIR_.'product_list.css');
    }

    public function _clearCache($template, $cache_id = NULL, $compile_id = NULL)
    {
        parent::_clearCache('blockpopular.tpl');
        parent::_clearCache('blockpopular_home.tpl', 'blockpopular-home');
        parent::_clearCache('tab.tpl', 'blockpopular-tab');
    }

    public function renderForm()
    {
        $fields_form = array(
            'form' => array(
                'legend' => array(
                    'title' => $this->l('Settings'),
                    'icon' => 'icon-cogs'
                ),
                'input' => array(
                    array(
                        'type' => 'text',
                        'label' => $this->l('Products to display'),
                        'name' => 'POPULAR_PRODUCTS_NBR',
                        'class' => 'fixed-width-xs',
                        'desc' => $this->l('Define the number of products to be displayed in this block.')
                    ),
                    array(
                        'type' => 'switch',
                        'label' => $this->l('Always display this block'),
                        'name' => 'PS_BLOCK_POPULAR_DISPLAY',
                        'desc' => $this->l('Show the block even if no popular products are available.'),
                        'values' => array(
                            array(
                                'id' => 'active_on',
                                'value' => 1,
                                'label' => $this->l('Enabled')
                            ),
                            array(
                                'id' => 'active_off',
                                'value' => 0,
                                'label' => $this->l('Disabled')
                            )
                        ),
                    )
                ),
                'submit' => array(
                    'title' => $this->l('Save'),
                )
            ),
        );

        $helper = new HelperForm();
        $helper->show_toolbar = false;
        $helper->table =  $this->table;
        $lang = new Language((int)Configuration::get('PS_LANG_DEFAULT'));
        $helper->default_form_language = $lang->id;
        $helper->allow_employee_form_lang = Configuration::get('PS_BO_ALLOW_EMPLOYEE_FORM_LANG') ? Configuration::get('PS_BO_ALLOW_EMPLOYEE_FORM_LANG') : 0;
        $helper->identifier = $this->identifier;
        $helper->submit_action = 'submitBlockPopular';
        $helper->currentIndex = $this->context->link->getAdminLink('AdminModules', false).'&configure='.$this->name.'&tab_module='.$this->tab.'&module_name='.$this->name;
        $helper->token = Tools::getAdminTokenLite('AdminModules');
        $helper->tpl_vars = array(
            'fields_value' => $this->getConfigFieldsValues(),
            'languages' => $this->context->controller->getLanguages(),
            'id_language' => $this->context->language->id
        );

        return $helper->generateForm(array($fields_form));
    }

    public function getConfigFieldsValues()
    {
        return array(
            'PS_BLOCK_POPULAR_DISPLAY' => Tools::getValue('PS_BLOCK_POPULAR_DISPLAY', Configuration::get('PS_BLOCK_POPULAR_DISPLAY')),
            'POPULAR_PRODUCTS_NBR' => Tools::getValue('POPULAR_PRODUCTS_NBR', Configuration::get('POPULAR_PRODUCTS_NBR')),
        );
    }
}
