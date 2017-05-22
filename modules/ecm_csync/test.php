<?php
include(dirname(__FILE__).'/../../config/config.inc.php');
require_once(dirname(__FILE__).'/../../init.php');
$info = Db::getInstance()->ExecuteS("SELECT TABLE_CATALOG, TABLE_SCHEMA, TABLE_NAME, TABLE_COLLATION
FROM INFORMATION_SCHEMA.TABLES WHERE TABLE_SCHEMA = 'test2'");
d($info);
//require_once(_PS_MODULE_DIR_.'mcsync/classes/CsyncTools.php');
//require_once(_PS_MODULE_DIR_.'mcsync/classes/CsyncProduct.php');
//echo time()."\n";
/* unset($_SESSION['stage']);
unset($_SESSION['last']);
  session_destroy();
  session_destroy('last');
  session_destroy('stage'); */
//echo php_sapi_name();
echo (int) "ДА-10" 
?>
