<?php
/**
 * Created by PhpStorm.
 * User: Ivan
 * Date: 20.01.2017
 * Time: 10:50
 */


include(dirname(__FILE__) . '/../../config/config.inc.php');
include(dirname(__FILE__) . '/../../init.php');
$productcompare = Module::getInstanceByName('productcompare');
$result = $productcompare->ajaxCall();
echo Tools::jsonEncode($result);