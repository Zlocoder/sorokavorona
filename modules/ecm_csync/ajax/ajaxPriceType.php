<?php
include(dirname(__FILE__) . '/../../../config/config.inc.php');
require_once(_PS_MODULE_DIR_.'/ecm_csync/classes/CsyncTools.php');
require_once(_PS_MODULE_DIR_.'/ecm_csync/controllers/admin/AdminCsyncPrises.php');

$id   = Tools::getValue('id');
$mode = Tools::getValue('mode');
switch($mode)
{
	case "clear":
	$html  = AdminCsyncPrisesController::drawtable();
	break;
	case "reset":
	$sql = Db::getInstance()->Execute("UPDATE `"._DB_PREFIX_."optprice`
		SET `main` = 0");
	$html  = AdminCsyncPrisesController::drawtable();
	break;
	case "cleargroup":
		$id = Tools::getValue('id');
		$sql = Db::getInstance()->Execute("UPDATE `"._DB_PREFIX_."group`
		SET `xml` = ''
		WHERE `id_group` ='".$id."'");
	if($sql){
		$result = array('type'=>'success');
	}else{
		$result = array('type'=>'error');
	}
	die (json_encode($result));
	break;
	case "editgroup":
	$html  = AdminCsyncPrisesController::drawtable($id);
	break;
	case "delgroup":
	$prises = "DELETE FROM `"._DB_PREFIX_."optprice` WHERE `id` = $id";
	Db::getInstance()->Execute($prises);
	$html  = AdminCsyncPrisesController::drawtable();
	break;
	case "addgroup":
	$name   = Tools::getValue('name');
	$guid = Tools::getValue('guid');
	if ($name != '' AND $guid != ''){
		$prises = "INSERT INTO `"._DB_PREFIX_."optprice`
		(`id`, `name`, `guid`)
		VALUES('$id', '$name', '$guid')
		ON DUPLICATE KEY UPDATE `name`='$name', `guid`='$guid'";
		Db::getInstance()->Execute($prises);
	}
	$html  = AdminCsyncPrisesController::drawtable();
	break;
	case "setmain":
	$guid = Tools::getValue('guid');
	if ($guid && $guid != ''){
		Db::getInstance()->Execute("UPDATE `"._DB_PREFIX_."optprice`
		SET `main` = 0
		WHERE `main` = 1 ");
		Db::getInstance()->Execute("UPDATE `"._DB_PREFIX_."optprice`
		SET `main` = 1
		WHERE `guid`='".$guid."'");

	}
	$html  = AdminCsyncPrisesController::drawtable();
	break;
	case "setsale":
	$guid = Tools::getValue('guid');
	if ($guid && $guid != ''){
		Db::getInstance()->Execute("UPDATE `"._DB_PREFIX_."optprice`
		SET `main` = 0
		WHERE `main` = 2 ");
		Db::getInstance()->Execute("UPDATE `"._DB_PREFIX_."optprice`
		SET `main` = 2
		WHERE `guid`='".$guid."'");

	}
	$html  = AdminCsyncPrisesController::drawtable();
	break;
	case "setgroup":
	$guid = Tools::getValue('guid');
	//$id_group = Tools::getValue('id_group');
	$id_group = preg_replace("/[^0-9]/", '', Tools::getValue('id_group'));
	if ($guid && $guid != ''){
		Db::getInstance()->Execute("UPDATE `"._DB_PREFIX_."group`
		SET `xml` = '".$guid."'
		WHERE `id_group`=".$id_group);


	}
	$html  = AdminCsyncPrisesController::drawtable();
	break;
	case "getprises":
	$prises_ = Db::getInstance()->executeS("SELECT `name` ,`guid` FROM `"._DB_PREFIX_."optprice` WHERE `name` !='Акционная'");
	if(sizeof($prises_)>0){
		foreach($prises_ as $prise_)
			{
					$prisess[] = array('id'=>$prise_['guid'], 'title'=>$prise_['name']);
			}
		$result = array('type'=>'success', 'prisess'=>$prisess);
	}else{
		$result = array('type'=>'error');
	}
	die (json_encode($result));

	break;
}
$select =  AdminCsyncPrisesController::drawselect();
exit ($html."!".$select);
