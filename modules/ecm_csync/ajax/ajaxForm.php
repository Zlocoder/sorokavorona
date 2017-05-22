<?php
include(dirname(__FILE__) . '/../../../config/config.inc.php');
$rawData = file_get_contents('php://input');
$data    = json_decode($rawData);


if ($data) {

    $name    = $data->val_name;
    $flag = $data->val_flag;
    if(is_array($flag))
    $flag = implode(',',$flag);
   	if($name){
			if($name=='prefix' && $flag && $flag!=' ')
			$flag = preg_replace('/[^a-zA-Zа-яА-Я0-9]/ui', '',$flag )."-";
			Configuration::updateValue('_ecm_csync_'.$name.'_', $flag);
			if($name!='prefix')
   			echo 'OK';
   			else
   			echo $flag;
	}


   }
