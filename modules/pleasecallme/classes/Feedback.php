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

class Feedback extends DObjectModelPCM
{
	public $name;
	public $phone;
	public $time_from;
	public $time_to;
	public $id_guest;
	public $id_customer;
	public $ip;
	public $date_time;

	public static $definition = array(
			'table' => 'feedback',
			'primary' => 'id_feedback',
			'fields' => array(
					'name' => array('type' => self::TYPE_STRING, 'validate' => 'isString'),
					'phone' => array('type' => self::TYPE_STRING, 'validate' => 'isPhoneNumber', 'required' => true),
					'time_from' => 	array('type' => self::TYPE_INT, 'validate' => 'isUnsignedInt'),
					'time_to' => 	array('type' => self::TYPE_INT, 'validate' => 'isUnsignedInt'),
					'id_guest'       => array('type' => self::TYPE_INT, 'validate' => 'isUnsignedInt'),
					'id_customer'       => array('type' => self::TYPE_INT, 'validate' => 'isUnsignedInt'),
					'ip'       => array('type' => self::TYPE_STRING, 'validate' => 'isString'),
					'date_time'         => array('type' => self::TYPE_DATE, 'validate' => 'isDateFormat')
			),
	);
}