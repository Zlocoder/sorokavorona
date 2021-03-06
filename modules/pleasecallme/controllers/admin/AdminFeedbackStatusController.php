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

/**
 * Class AdminFeedbackStatus
 */
class AdminFeedbackStatusController extends ModuleAdminController
{
	public function __construct()
	{
		$this->table = 'feedback_status';
		$this->identifier = 'id_feedback_status';
		$this->className = 'FeedbackStatus';
		$this->lang = true;
		$this->bootstrap = true;

		parent::__construct();

		$this->addRowAction('edit');

		$this->fields_list = array(
			'id_feedback_status' => array(
				'title' => $this->l('ID'),
				'search' => false,
				'orderby' => false
			),
			'name' => array(
				'title' => $this->l('Name'),
				'search' => false,
				'orderby' => false,
				'color' => 'color',
			),
			'active' => array(
				'title' => $this->l('Active'),
				'type' => 'bool',
				'search' => false,
				'active' => 'active',
				'orderby' => false
			)
		);
	}

	public function renderForm()
	{
		$this->fields_form = array(
			'legend' => array(
				'title' => $this->l('Edit status')
			),
			'input' => array(
				array(
					'label' => $this->l('Name'),
					'name' => 'name',
					'lang' => true,
					'type' => 'text'
				),
				array(
					'label' => $this->l('Color'),
					'name' => 'color',
					'type' => 'color'
				),
				array(
					'label' => $this->l('Active'),
					'name' => 'active',
					'type' => (_PS_VERSION_ < 1.6 ? 'radio' : 'switch'),
					'class' => 't',
					'values' =>
							array(
								array(
										'id' => 'active_on',
										'value' => 1,
										'label' => $this->l('Enabled')
								),
								array(
										'id' => 'active_off',
										'value' => 0,
										'label' => $this->l('Disabled')
							)),
				)
			),
			'submit' => array(
				'title' => $this->l('Save')
			)
		);
		return parent::renderForm(); // TODO: Change the autogenerated stub
	}
}