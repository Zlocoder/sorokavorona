<?php
// Инициализация
include(dirname(__FILE__) . '/../../config/config.inc.php');
require_once(_PS_MODULE_DIR_.'ecm_csync/ecm_csync.php');
require_once(_PS_MODULE_DIR_.'ecm_csync/classes/CsyncTools.php');
$module = new ecm_csync();
$sql    = "UPDATE `" . _DB_PREFIX_ . "module`
SET `version` = '".$module->version."',
`name`='ecm_csync'
WHERE `name`='Csync'";
if (!Db::getInstance()->Execute($sql)) {
    $sql = "UPDATE `" . _DB_PREFIX_ . "module`
    SET `version` = '".$module->version."',
    `name`='ecm_csync'
    WHERE `name`='csync'";
    Db::getInstance()->Execute($sql);
}

$sql = array();
include (dirname(__file__) . '/init/install_sql.php');
foreach ($sql as $s) {
    if (!Db::getInstance()->Execute($s)) {
        continue;

    }
}
        if ( _PS_VERSION_ > "1.4.11.1"){
		$idTabs = array();
        $idTabs[] = Tab::getIdFromClassName('AdminCsyncMain');
        $idTabs[] = Tab::getIdFromClassName('AdminCsync');
        $idTabs[] = Tab::getIdFromClassName('AdminCsyncLog');
        $idTabs[] = Tab::getIdFromClassName('AdminCsyncOrder');
        $idTabs[] = Tab::getIdFromClassName('AdminCsyncAdditional');
        $idTabs[] = Tab::getIdFromClassName('AdminCsyncAbout');
        $idTabs[] = Tab::getIdFromClassName('AdminCsyncImagesSettings');
        $idTabs[] = Tab::getIdFromClassName('AdminCsyncStockSettings');
        $idTabs[] = Tab::getIdFromClassName('AdminCsyncFeaturesSettings');
        $idTabs[] = Tab::getIdFromClassName('AdminCsyncCategoriesSettings');
        $idTabs[] = Tab::getIdFromClassName('AdminCsyncImportExportSettings');
        $idTabs[] = Tab::getIdFromClassName('AdminCsyncPrises');
        $idTabs[] = Tab::getIdFromClassName('AdminCsyncCompability');
        $idTabs[] = Tab::getIdFromClassName('AdminCsyncProductsSettings');
        foreach ($idTabs as $idTab) {
            if ($idTab) {
                $tab = new Tab($idTab);
                $tab->delete();
            }
        }
		}
$module->prepareModuleSettings();
$module->hookDisplayBackOfficeHeader();

echo ('Обновление модуля успешно завершено');

?>


