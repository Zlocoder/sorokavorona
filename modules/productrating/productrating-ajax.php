<?php
/**
 * Created by PhpStorm.
 * User: Ivan
 * Date: 24.03.2017
 * Time: 9:36
 */

include(dirname(__FILE__) . '/../../config/config.inc.php');
include(dirname(__FILE__) . '/../../init.php');
$productrating = Module::getInstanceByName('productrating');
$result = $productrating->ajaxCall();
echo Tools::jsonEncode($result);