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

class FeedbackHistoryStatus extends DObjectModelPCM
{
	public $id_feedback_status;
	public $id_feedback;
	public $id_employee;
	public $message;
	public $date_time;

	public static $definition = array(
			'table' => 'feedback_history_status',
			'primary' => 'id_feedback_history_status',
			'fields' => array(
					'id_feedback_status' => 	array('type' => self::TYPE_INT, 'validate' => 'isUnsignedInt'),
					'id_feedback'       => array('type' => self::TYPE_INT, 'validate' => 'isUnsignedInt'),
					'id_employee'       => array('type' => self::TYPE_INT, 'validate' => 'isUnsignedInt'),
					'message'       => array('type' => self::TYPE_HTML, 'validate' => 'isCleanHtml'),
					'date_time'         => array('type' => self::TYPE_DATE, 'validate' => 'isDateFormat')
			),
	);

	public static function getHistory($id_feedback)
	{
		return Db::getInstance()->executeS('SELECT a.*, f.`color` as status_color,
		fl.`name` as status_name,
		CONCAT(e.`firstname`, " ", e.`lastname`) as employee_name FROM `'.self::getPrefixTable().'` a
		LEFT JOIN '._DB_PREFIX_.'employee e ON e.`id_employee` = a.`id_employee`
		LEFT JOIN '._DB_PREFIX_.'feedback_status f ON f.`id_feedback_status` = a.`id_feedback_status`
		LEFT JOIN '._DB_PREFIX_.'feedback_status_lang fl ON fl.`id_feedback_status` = f.`id_feedback_status`
		AND fl.`id_lang` = '.(int)Context::getContext()->language->id.'
		WHERE a.`id_feedback` = '.(int)$id_feedback.' ORDER BY a.`date_time` DESC');
	}
}