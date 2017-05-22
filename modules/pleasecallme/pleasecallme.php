<?php
/**
 * 2007-2015 PrestaShop
 *
 * NOTICE OF LICENSE
 *
 * This source file is subject to the Academic Free License (AFL 3.0)
 * that is bundled with this package in the file LICENSE.txt.
 * It is also available through the world-wide-web at this URL:
 * http://opensource.org/licenses/afl-3.0.php
 * If you did not receive a copy of the license and are unable to
 * obtain it through the world-wide-web, please send an email
 * to license@prestashop.com so we can send you a copy immediately.
 *
 * DISCLAIMER
 *
 * Do not edit or add to this file if you wish to upgrade PrestaShop to newer
 * versions in the future. If you wish to customize PrestaShop for your
 * needs please refer to http://www.prestashop.com for more information.
 *
 * @author    Goryachev Dmitry    <dariusakafest@gmail.com>
 * @copyright 2007-2015 Goryachev Dmitry
 * @license   http://opensource.org/licenses/afl-3.0.php  Academic Free License (AFL 3.0)
 * International Registered Trademark & Property of PrestaShop SA
 */

require_once(_PS_MODULE_DIR_.basename(dirname(__FILE__)).'/classes/PCMFolderAutoloader.php');
PCMFolderAutoloader::create(_PS_MODULE_DIR_.basename(dirname(__FILE__)).'/classes/');

//$this->l('phone')
//$this->l('name')
class PleaseCallMe extends Module
{
	private $install_classes = array('Feedback', 'FeedbackHistoryStatus', 'FeedbackStatus');
	public function __construct()
	{
		$this->name = 'pleasecallme';
		$this->tab = 'front_office_features';
		$this->version = '1.0.5';
		$this->author = 'SeoSa';
		$this->need_instance = 0;
		$this->bootstrap = true;
		parent::__construct();
		$this->displayName = $this->l('PLease call me');
		$this->description = $this->l('View callback from in front');
		$this->module_key = '35ffa0ef06ad571dce6bbb7db7d7f55e';
		if (Tools::isSubmit('ajax_pcm'))
			$this->callAjax();
	}

	public function install()
	{
		$this->createTab('AdminFeedbackParent', null, array(
			'en' => 'Call me',
			'ru' => 'Позвоните мне'
		));
		$this->createTab('AdminFeedbackSetting', 'AdminFeedbackParent', array(
			'en' => 'Setting',
			'ru' => 'Настройки'
		));
		$this->createTab('AdminFeedback', 'AdminFeedbackParent', array(
			'en' => 'Feedback',
			'ru' => 'Обратная связь'
		));
		$this->createTab('AdminFeedbackStatus', 'AdminFeedbackParent', array(
				'en' => 'Statuses feedback',
				'ru' => 'Статусы обратной связи'
		));
		$this->installClasses();
		$this->setDefaultContent();
		if (!parent::install()
				|| !$this->registerHook('displayHeader')
				|| !$this->registerHook('displayBackOfficeHeader'))
			return false;
		return true;
	}

	public function copyTabIconInRoot($icon)
	{
		$icon = $icon.'.gif';
		$path = _PS_MODULE_DIR_.basename(dirname(__FILE__)).'/';
		if (!file_exists($path.$icon) && file_exists($path.'views/img/'.$icon) && _PS_VERSION_ < 1.6)
			copy($path.'views/img/'.$icon, $path.$icon);
	}

	public function setDefaultContent()
	{
		FeedbackStatus::createStatus('#c2c0ff', array(
			'en' => 'in waiting',
			'ru' => 'в ожидании'
		));
		FeedbackStatus::createStatus('#ff402c', array(
				'en' => 'not answered',
				'ru' => 'не отвечено'
		));
		FeedbackStatus::createStatus('#8cdf00', array(
				'en' => 'answered',
				'ru' => 'отвечено'
		));
	}

	public function uninstall()
	{
		$this->deleteTab('AdminFeedbackStatus');
		$this->deleteTab('AdminFeedback');
		$this->deleteTab('AdminFeedbackSetting');
		$this->deleteTab('AdminFeedbackParent');
		$this->uninstallClasses();
		if (!parent::uninstall())
			return false;
		return true;
	}

	public function installClasses()
	{
		foreach ($this->install_classes as $class)
			HelperDbPCM::loadClass($class)->installDb();
	}

	public function uninstallClasses()
	{
		foreach ($this->install_classes as $class)
			HelperDbPCM::loadClass($class)->uninstallDb();
	}

	public function callAjax()
	{
		$method = 'ajaxProcess'.Tools::toCamelCase(Tools::getValue('method'));
		if (method_exists($this, $method))
			call_user_func(array($this, $method));
	}

	public function ajaxProcessSubmit()
	{
		$feedback = new Feedback();
		$this->copyFromPost($feedback);
		$errors = $this->validateObject($feedback);

		if (count($errors))
			$this->ajaxSendError($errors);
		else
		{
			$feedback->date_time = date('Y-m-d H:i:s');

			if (!empty($_SERVER['HTTP_CLIENT_IP']))
				$ip = $_SERVER['HTTP_CLIENT_IP'];
			elseif (!empty($_SERVER['HTTP_X_FORWARDED_FOR']))
				$ip = $_SERVER['HTTP_X_FORWARDED_FOR'];
			else
				$ip = $_SERVER['REMOTE_ADDR'];

			$feedback->ip = $ip;
			if ($this->context->cookie->logged)
				$feedback->id_customer = $this->context->customer->id;
			else
				$feedback->id_guest = $this->context->cookie->id_guest;
			if ($feedback->save())
			{
				$feedback_history_status = new FeedbackHistoryStatus();
				$feedback_history_status->id_feedback = $feedback->id;
				$feedback_history_status->id_feedback_status = 1;
				$feedback_history_status->id_employee = 0;
				$feedback_history_status->date_time = date('Y-m-d H:i:s');
				$feedback_history_status->save();

				$email = Configuration::get('PS_PCM_EMAIL');
				if ($email && Validate::isEmail($email))
					$this->sendMail('new_call_me', $email, $this->displayName, array(
						'{name}' => $feedback->name,
						'{phone}' => $feedback->phone,
						'{time_from}' => $feedback->time_from,
						'{time_to}' => $feedback->time_to
					));
			}
		}
		$this->ajaxSendSuccess($this->l('Your message send successfully!'));
	}


	/**
	 * @param $request_array Array
	 * @return void
	 */
	public function ajaxSendSuccess($request_array)
	{
		header('HTTP/1.1 200 Success validate form');
		die(Tools::jsonEncode($request_array));
	}

	/**
	 * @param $request_array Array
	 * @return void
	 */
	public function ajaxSendError($request_array)
	{
		header('HTTP/1.1 500 Error validate form');
		die(Tools::jsonEncode($request_array));
	}

	public function hookDisplayHeader()
	{
		if(Tools::getValue('content_only') == 1)
			return '';
		$this->addCSS('font-awesome.min.css');
		$this->addCSS('iThing.css');
		$this->addCSS('front.css');
//
		$this->context->controller->addJqueryUI('ui.mouse');
		$this->addJS('jQAllRangeSliders-min.js');
		$this->addJS('front.js');
		return $this->display(__FILE__, 'form.tpl');
	}

	public function hookDisplayBackOfficeHeader()
	{
		$this->addCSS('admin.css');
		$this->addCSS('admin-theme.css');
		$this->addCSS('font-awesome.min.css');
	}

	public $languages = array();

	/**
	 * @param $active bool
	 * @return Array
	 */
	public function getLanguages($active = true)
	{
		$cache_id = md5($active);
		if (array_key_exists($cache_id, $this->languages))
			return $this->languages[$cache_id];
		$languages = Language::getLanguages($active);
		foreach ($languages as &$l)
			$l['is_default'] = (Configuration::get('PS_LANG_DEFAULT') == $l['id_lang']);
		$this->languages[$cache_id] = $languages;
		return $languages;
	}

	/**
	 * @param $class_name string
	 * @param $parent string
	 * @param $name mixed
	 * @return void
	 */
	public function createTab($class_name, $parent = null, $name)
	{
		if (!is_array($name))
			$name = array('en' => $name);
		elseif (is_array($name) && !count($name))
			$name = array('en' => $class_name);
		elseif (is_array($name) && count($name) && !isset($name['en']))
			$name['en'] = current($name);

		$tab = new Tab();
		$tab->class_name = $class_name;
		$tab->module = $this->name;
		$tab->id_parent = (!is_null($parent) ? Tab::getIdFromClassName($parent) : 0);
		if (is_null($parent))
			$this->copyTabIconInRoot($class_name);
		$tab->active = true;
		foreach ($this->getLanguages() as $l)
			$tab->name[$l['id_lang']] = (isset($name[$l['iso_code']]) ? $name[$l['iso_code']] : $name['en']);
		$tab->save();
	}

	/**
	 * @param $class_name string
	 * @return void
	 */
	public function deleteTab($class_name)
	{
		$tab = Tab::getInstanceFromClassName($class_name);
		$tab->delete();
	}

	public function addCSS($file)
	{
		$this->context->controller->addCSS($this->_path.'views/css/'.$file);
	}

	public function copyFromPost(&$object)
	{
		$definition = ObjectModel::getDefinition($object);
		$table = $definition['table'];
		/* Classical fields */
		foreach ($_POST as $key => $value)
			if (key_exists($key, $object) && $key != 'id_'.$table)
			{
				/* Do not take care of password field if empty */
				if ($key == 'passwd' && Tools::getValue('id_'.$table) && empty($value))
					continue;
				/* Automatically encrypt password in MD5 */
				if ($key == 'passwd' && !empty($value))
					$value = Tools::encrypt($value);
				$object->{$key} = $value;
			}

		/* Multilingual fields */
		$rules = call_user_func(array(get_class($object), 'getValidationRules'), get_class($object));
		if (count($rules['validateLang']))
		{
			$languages = $this->getLanguages(false);
			foreach ($languages as $language)
				foreach (array_keys($rules['validateLang']) as $field)
					if (Tools::getIsset($field.'_'.(int)$language['id_lang']))
						$object->{$field}[(int)$language['id_lang']] = Tools::getValue($field.'_'.(int)$language['id_lang']);
		}
	}

	public function validateObject($object, $definition_fields = null)
	{
		$errors = array();
		$definition = ObjectModel::getDefinition($object);
		if (is_null($definition_fields))
			$definition_fields = $definition['fields'];
		$languages = $this->getLanguages(true);

		$empty_field = $this->l('%s is empty');
		$empty_lang_field = $this->l('%s for lang %s is empty');

		$wrong_field = $this->l('%s wrong');
		$wrong_lang_field = $this->l('%s for lang %s wrong');

		$max_length_field = $this->l('%s size more %s');
		$max_length_lang_field = $this->l('%s for lang %s size more %s');

		$fields = array_keys($definition_fields);
		foreach ($fields as $field)
		{
			$l_field = $this->l($field);
			if (array_key_exists($field, $definition_fields))
			{
				$object_field = $object->{$field};
				if (array_key_exists('lang', $definition_fields[$field]) && $definition_fields[$field]['lang'])
				{
					foreach ($languages as $lang)
					{
						if (isset($definition_fields[$field]['required']) && $definition_fields[$field]['required'] && empty($object_field[$lang['id_lang']]))
							$errors[] = sprintf($empty_lang_field, $l_field, $lang['name']);

						if (!empty($object_field[$lang['id_lang']]) && !forward_static_call_array(array('Validate', $definition_fields[$field]['validate']), array(
								$object_field[$lang['id_lang']]
							)))
							$errors[] = sprintf($wrong_lang_field, $l_field, $lang['name']);
						if (!empty($object_field[$lang['id_lang']]) && forward_static_call_array(array('Validate', $definition_fields[$field]['validate']), array(
								$object_field[$lang['id_lang']]
							)) &&
							array_key_exists('size', $definition_fields[$field]) && Tools::strlen($object_field[$lang['id_lang']]) > $definition_fields[$field]['size'])
							$errors[] = sprintf($max_length_lang_field, $l_field, $lang['name'], $definition_fields[$field]['size']);
					}
				}
				else
				{
					if (isset($definition_fields[$field]['required']) && $definition_fields[$field]['required'] && empty($object_field))
						$errors[] = sprintf($empty_field, $l_field);

					if (!empty($object_field) && !forward_static_call_array(array('Validate', $definition_fields[$field]['validate']), array(
							$object_field
						)))
						$errors[] = sprintf($wrong_field, $l_field);

					if (!empty($object_field) && forward_static_call_array(array('Validate', $definition_fields[$field]['validate']), array(
							$object_field
						)) &&
						array_key_exists('size', $definition_fields[$field]) && Tools::strlen($object_field) > $definition_fields[$field]['size'])
						$errors[] = sprintf($max_length_field, $l_field, $definition_fields[$field]['size']);

				}
			}
		}
		return $errors;
	}

	public function getMailTemplatePath()
	{
		return _PS_MODULE_DIR_.$this->name.'/mails/';
	}

	public function sendMail($template, $email_to, $theme, $template_vars = array())
	{
		$this->checkAndFixEmailTemplateForLang($this->context->language, $template);
		Mail::Send($this->context->language->id, $template, $theme,
			$template_vars,
			$email_to,
			null,
			Configuration::get('PS_SHOP_EMAIL'),
			Configuration::get('PS_SHOP_NAME'),
			null,
			null,
			$this->getMailTemplatePath()
		);
	}

	public function fixEmailTemplateForLang($lang, $template_filename)
	{
		if (!file_exists($template_path = $this->getMailTemplatePath().$lang->iso_code))
			mkdir($template_path = $this->getMailTemplatePath().$lang->iso_code);
		$default_template_path = $this->getMailTemplatePath().'en/';
		$template_path = $this->getMailTemplatePath().$lang->iso_code.'/'.$template_filename;
		if (file_exists($default_template_path.$template_filename))
			call_user_func_array('copy', array(
				$default_template_path.$template_filename,
				$template_path
			));
	}

	public function checkAndFixEmailTemplateForLang($lang, $template)
	{
		$template_path = $this->getMailTemplatePath().$lang->iso_code.'/'.$template;
		if (!file_exists($template_path.'.txt'))
			$this->fixEmailTemplateForLang($lang, $template.'.txt');
		if (!file_exists($template_path.'.html'))
			$this->fixEmailTemplateForLang($lang, $template.'.html');
	}

	public function addJS($file)
	{
		$this->context->controller->addJS($this->_path.'views/js/'.$file);
	}
}