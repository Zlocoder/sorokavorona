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

class AdminFeedbackSettingController extends ModuleAdminController
{
	public function __construct()
	{
		$this->table = 'configuration';
		$this->identifier = 'id_configuration';
		$this->className = 'Configuration';
		$this->bootstrap = true;
		parent::__construct();

		$this->fields_options = array(
			'setting' => array(
				'title' =>	$this->l('Setting'),
				'icon' =>	'weight',
				'fields' => array(
					'PS_PCM_EMAIL' => array(
						'title' => $this->l('Email'),
						'validation' => 'isEmail',
						'type' => 'text',
						'size' => '30'
					)
				),
				'submit' => array(
					'title' => $this->l('Save')
				)
			)
		);
	}

	public function initProcess()
	{
		parent::initProcess();
		if ($this->display == 'options')
			$this->display = '';
	}

	public function renderOptions()
	{
		if ($this->fields_options && is_array($this->fields_options))
		{
			$helper = new HelperOptions($this);
			$this->setHelperDisplay($helper);
			$helper->toolbar_scroll = true;
			$helper->toolbar_btn = array('save' => array(
					'href' => '#',
					'desc' => $this->l('Save')
			));
			$helper->id = $this->id;
			$helper->tpl_vars = $this->tpl_option_vars;
			$options = $helper->generateOptions($this->fields_options);

			return $options;
		}
	}
}