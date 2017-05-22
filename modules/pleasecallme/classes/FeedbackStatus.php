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

class FeedbackStatus extends DObjectModelPCM
{
	public $name;
	public $color;
	public $active = 1;

	public static $definition = array(
			'table' => 'feedback_status',
			'primary' => 'id_feedback_status',
			'multilang' => true,
			'fields' => array(
				'name'   => array('type' => self::TYPE_STRING, 'lang' => true, 'validate' => 'isString', 'required' => true),
				'color'  => array('type' => self::TYPE_STRING, 'validate' => 'isString', 'required' => true),
				'active' => array('type' => self::TYPE_BOOL, 'validate' => 'isBool')
			),
	);

	public static function getFeedbackStatuses()
	{
		return HelperDbPCM::loadClass(__CLASS__)->getAll();
	}

	public static function createStatus($color, $name)
	{
		$feedback_status = new FeedbackStatus();
		$feedback_status->color = $color;
		$feedback_status->active = 1;
		$languages = Context::getContext()->language->getLanguages(false);
		foreach ($languages as $lang)
		{
			if (is_array($name))
			{
				if (array_key_exists($lang['iso_code'], $name))
					$feedback_status->name[$lang['id_lang']] = $name[$lang['iso_code']];
				else
					$feedback_status->name[$lang['id_lang']] = $name['en'];
			}
			else
				$feedback_status->name[$lang['id_lang']] = $name;
		}
		$feedback_status->save();
	}
}