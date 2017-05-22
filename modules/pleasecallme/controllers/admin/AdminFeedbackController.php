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

class AdminFeedbackController extends ModuleAdminController
{
	public function __construct()
	{
		$this->table = 'feedback';
		$this->identifier = 'id_feedback';
		$this->className = 'Feedback';
		$this->bootstrap = true;
		$this->addRowAction('edit');
		parent::__construct();
		$this->fields_list = array(
			'id_feedback' => array(
				'title' => $this->l('ID'),
				'search' => false
			),
			'name' => array(
				'title' => $this->l('Name')
			),
			'phone' => array(
				'title' => $this->l('Phone')
			),
			'time_from' => array(
				'title' => $this->l('Time from')
			),
			'time_to' => array(
				'title' => $this->l('Time to')
			),
			'status' => array(
				'title' => $this->l('Status'),
				'type' => 'color',
				'color' => 'color',
				'filter_key' => 'c!name'
			),
			'date_time' => array(
				'title' => $this->l('Date create'),
				'type' => 'datetime'
			)
		);

		$this->_select .= 'c.`id_feedback`, c.`color`, c.`name` as status';
		$this->_join .= 'LEFT JOIN
			(SELECT fsl.`id_feedback_status`, fhs.`id_feedback`, fsl.`name`, fs.`color`
				FROM '._DB_PREFIX_.'feedback_history_status fhs 
				LEFT JOIN '._DB_PREFIX_.'feedback_status fs ON fs.`id_feedback_status` = fhs.`id_feedback_status`
				LEFT JOIN '._DB_PREFIX_.'feedback_status_lang fsl
				ON fsl.`id_feedback_status` = fhs.`id_feedback_status` AND fsl.`id_lang` = '.(int)$this->context->language->id.'
				ORDER BY fhs.`id_feedback_history_status` DESC LIMIT 18446744073709551615) c
		ON a.`id_feedback` = c.`id_feedback`';
		$this->_where .= ' GROUP BY a.`id_feedback`';
//        var_dump($this->_select);
//        var_dump($this->_join);
//        var_dump($this->_where);
//        die;
	}

	public function postProcess()
	{
		if (Tools::isSubmit('submitStatus'))
		{
			$history = new FeedbackHistoryStatus();
			$this->module->copyFromPost($history);
			$this->errors = $this->module->validateObject($history);
			if (!count($this->errors))
			{
				$history->id_feedback = Tools::getValue($this->identifier);
				$history->date_time = date('Y-m-d H:i:s');
				$history->id_employee = $this->context->employee->id;
				$history->save();
				Tools::redirectAdmin($this->context->link->getAdminLink('AdminFeedback', true)
						.'&'.$this->identifier.'='.Tools::getValue($this->identifier).'&update'.$this->table);
			}
		}
		return parent::postProcess();
	}

	public function renderForm()
	{
		$title = sprintf($this->l('Feedback № %s from %s'), $this->object->id, $this->object->name);
		$this->fields_form = array(
			'legend' => array(
				'title' => $title
			)
		);
		$this->tpl_form_vars['feedback'] = $this->object;
		$this->tpl_form_vars['title'] = $title;
		$this->tpl_form_vars['history'] = FeedbackHistoryStatus::getHistory($this->object->id);
		$this->tpl_form_vars['statuses'] = FeedbackStatus::getFeedbackStatuses();
		$this->tpl_form_vars['customer'] = new Customer($this->object->id_customer);
		return parent::renderForm();
	}

	public function renderList()
	{
		$this->toolbar_btn = array();
		return parent::renderList();
	}
}