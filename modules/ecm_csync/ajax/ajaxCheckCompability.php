<?php
include(dirname(__FILE__) . '/../../../config/config.inc.php');
$rawData = file_get_contents('php://input');
$data    = json_decode($rawData);

function mod_installed($module){
		if ( _PS_VERSION_ > "1.4.11.1"){
		$result = (bool)Db::getInstance()->GetValue("
			SELECT ms.`id_shop` FROM `"._DB_PREFIX_."module` m
			LEFT JOIN `"._DB_PREFIX_."module_shop` ms ON ms.`id_module` = m.`id_module`
			WHERE m.`name` = '$module'");
		}
		else{
		$result = (bool)Db::getInstance()->GetValue("
			SELECT `active` FROM `"._DB_PREFIX_."module`
			WHERE m.`name` = '$module'");
		}
		return $result;
	}
function phone_login(){
				$sql = "describe `"._DB_PREFIX_."customer`";
				$fields = Db::getInstance()->ExecuteS($sql);
				$field = array();
				foreach ($fields as $rec) $field[]=$rec['Field'];
				if (in_array('phone_login', $field))
				return true;
				//else return false;
			}
function on_stock(){
				$sql = "describe `"._DB_PREFIX_."product`";
				$fields = Db::getInstance()->ExecuteS($sql);
				$field = array();
				foreach ($fields as $rec) $field[]=$rec['Field'];
				if (in_array('on_stock', $field))
				return true;
				//else return false;
			}
if ($data) {

    $name    = $data->val_name;
    $flag = $data->val_flag;
    if(is_array($flag))
    $flag = implode(',',$flag);
   	if($name && $name != 'phonelogin' && $name != 'on_stock'){
			if(mod_installed($name)){
			Configuration::updateValue('_ecm_csync_'.$name.'_', $flag);
			echo 'OK';
			}
	}elseif($name && $name == 'phonelogin'){
		if(phone_login()){
			Configuration::updateValue('_ecm_csync_'.$name.'_', $flag);
			echo 'OK';
		}
		}
	elseif($name && $name == 'on_stock'){
		if(on_stock()){
			Configuration::updateValue('_ecm_csync_'.$name.'_', $flag);
			echo 'OK';
		}
	}

   }
