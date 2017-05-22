<?php
include_once(dirname(__FILE__).'/../../ecm_csync.php');
include_once(dirname(__FILE__).'/../../classes/CsyncTools.php');
include_once(dirname(__FILE__).'/../../classes/Clear.php');
class AdminCsyncAdditionalController extends AdminController
{
   
    public function __construct()
    {
        $this->module = new Ecm_csync();
        $this->bootstrap = true;
        parent::__construct();
    }

    public function postProcess()
    {
        if (Tools::isSubmit('submitClearZakaz')) {
            Db::getInstance()->Execute('TRUNCATE TABLE `'._DB_PREFIX_.'orders');
            Db::getInstance()->Execute('TRUNCATE TABLE `'._DB_PREFIX_.'order_detail');
            //Db::getInstance()->Execute('TRUNCATE TABLE `'._DB_PREFIX_.'order_discount');
            Db::getInstance()->Execute('TRUNCATE TABLE `'._DB_PREFIX_.'order_history');
            Db::getInstance()->Execute('TRUNCATE TABLE `'._DB_PREFIX_.'order_message');
            Db::getInstance()->Execute('TRUNCATE TABLE `'._DB_PREFIX_.'order_carrier');
            Db::getInstance()->Execute('TRUNCATE TABLE `'._DB_PREFIX_.'order_message_lang');
            $this->context->smarty->assign(array(
                    'success'=> $this->succes_('Заказы удалены')
                ));
        }
        if (Tools::isSubmit('submitClear')) {
            Clear::ClearBase();
            $this->context->smarty->assign(array(
                    'success'=> $this->succes_('Каталог номенклатуры очищен')
                ));
        }
        if (Tools::isSubmit('submitClearShDesc')) {
            Clear::ClearShortDesc();
            $this->context->smarty->assign(array(
                    'success'=> $this->succes_('Короткие описания удалены!!!')
                ));
        }
        if (Tools::isSubmit('submitClearDesc')) {
            Clear::ClearDesc();
            $this->context->smarty->assign(array(
                    'success'=> $this->succes_('Описания удалены!!!')
                ));
        }
        if (Tools::isSubmit('submitActiv')) {
            Db::getInstance()->Execute("
                UPDATE `" . _DB_PREFIX_ . "product`
                SET `active`=1");
            if ( _PS_VERSION_ > "1.4.11.1")
            Db::getInstance()->Execute("
                UPDATE `" . _DB_PREFIX_ . "product_shop`
                SET `active`=1");
            $this->context->smarty->assign(array(
                    'success'=> $this->succes_('Все товары активированы')
                ));
        }
        if (Tools::isSubmit('submitBoth')) {
            Db::getInstance()->Execute("
                UPDATE `" . _DB_PREFIX_ . "product`
                SET `visibility`='both'");
            if ( _PS_VERSION_ > "1.4.11.1")
            Db::getInstance()->Execute("
                UPDATE `" . _DB_PREFIX_ . "product_shop`
                SET `visibility`='both'");
            $this->context->smarty->assign(array(
                    'success'=> $this->succes_('Все товары видимы')
                ));
        }
        if (Tools::isSubmit('submitTypePrice')) {
            Clear::ClearTypePrice();
            Configuration::deleteByName('_price_');
            Configuration::deleteByName('_price0_');
            $this->context->smarty->assign(array(
                    'success'=> $this->succes_('Типы цен удалены')
                ));
        }
        if (Tools::isSubmit('submitClearFeatures')) {
            Clear::ClearFeatures();
            $this->context->smarty->assign(array(
                    'success'=> $this->succes_('Свойства удалены!!!')
                ));
        }
        if (Tools::isSubmit('submitClearImages')) {
            Clear::ClearImages();
            $this->context->smarty->assign(array(
                    'success'=> $this->succes_('Картинки удалены!!!')
                ));
        }
        if (Tools::isSubmit('submitClearAttrib')) {
            Clear::ClearAttributes();
            $this->context->smarty->assign(array(
                    'success'=> $this->succes_('Комбинации и характеристики удалены!!!')
                ));
        }
        if (Tools::isSubmit('submitClearAttach')) {
            Clear::ClearAttach();
            $this->context->smarty->assign(array(
                    'success'=> $this->succes_('Вложения удалены!!!')
                ));
        }
        if (Tools::isSubmit('submitClearDuplicate')) {
            Clear::deleteImages(Tools::getValue('id_dubl'));
            Clear::delete_Product_by_id(Tools::getValue('id_dubl'));
            $this->context->smarty->assign(array(
                    'success'=> $this->succes_('Товар с id '.Tools::getValue('id_dubl').' удален')
                ));
        }
        if (Tools::isSubmit('submitClearUpload')) {
            Clear::recRMDir(_PS_UPLOAD_DIR_.'import_files/');
            $this->context->smarty->assign(array(
                    'success'=> $this->succes_('Папка <strong>Upload</strong> очищена')
                ));
        }
        if (Tools::isSubmit('submitDebugMod')) {
        	$col = (_PS_MODE_DEV_ == 'true')?'false':'true';
            chmod('../config/defines.inc.php', 0777);
			$str = '';
			if ($fh = fopen('../config/defines.inc.php', 'r'))
			{
				while (!feof($fh))
						$str .= fgets($fh);
				$err    = array(
					'define(\'_PS_MODE_DEV_\', true);',
					'define(\'_PS_MODE_DEV_\', false);'
				);
				$str = str_replace($err, 'define(\'_PS_MODE_DEV_\', '.$col.');', $str);
				fclose($fh);
				@chmod('../config/defines.inc.php', 0644);
				$x42 = fopen('../config/defines.inc.php', 'w');
				fwrite($x42, $str);
			}
            $this->context->smarty->assign(array(
                    'success'=> $this->succes_('Успешно обновлено!!!')
                ));
        }

    }
    public function initHeader()
    {
        parent::initHeader();
        $smarty = $this->context->smarty;
        $value = (_PS_MODE_DEV_ == 'true')?"'Выключить режим'":"'Включить режим'"; 
        $smarty->assign(array(
               'value'=> $value,
            ));
         
        
    }
    public function initContent()
    {
        parent::initContent();
        $smarty = $this->context->smarty;
       
        $content= $smarty->fetch(_PS_MODULE_DIR_ . 'ecm_csync/views/displayAdditional.tpl');
        $smarty->assign(array(
                'content'=> $this->content . $content,
               
            ));
         
        
    }
    public
    function succes_($text)
    {
        return '
        <div class="bootstrap">
        <div class="alert alert-success">
        <btn btn-default button type="btn btn-default button" class="close" data-dismiss="alert">×</btn btn-default button>
        '.$text.'
        </div>
        </div>
        ';
    }

}

