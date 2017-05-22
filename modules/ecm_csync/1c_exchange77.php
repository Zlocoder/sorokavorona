<?php
ini_set('display_errors', '1');
error_reporting(E_ALL);
set_time_limit(0);
libxml_use_internal_errors(true);
// Инициализация
include(dirname(__FILE__) . '/../../config/config.inc.php');
if ( _PS_VERSION_ <= "1.4.11.1")
require_once (dirname(__FILE__) . '/../../images.inc.php');
include(dirname(__FILE__) . '/init/defines_inc.php');
//auth();
require_once(_PS_MODULE_DIR_.'ecm_csync/classes/CsyncTools.php');
require_once(_PS_MODULE_DIR_.'ecm_csync/classes/CKLogger.php');

$csync_log     = new CKLogger("log.txt", CKLogger::DEBUG);
@session_start();
$sid = session_id();
$date= getdate();
$ids = Configuration::get('SESSION_1C');

if (file_exists("log.txt")) {
    $log_time      = filemtime("log.txt");
    $date_log_time = date('d',$log_time);
}

if ($date['mday'] > $date_log_time && $sid != $ids) {

    CsyncTools::archive(_PS_MODULE_DIR_.'ecm_csync/logs/','log.txt' );
    if (file_exists("log.txt")) {
        $fp = fopen("log.txt", "r+");
        $res= ftruncate($fp, 0);
    }

}

$csync_log->LogInfo($_SERVER['QUERY_STRING']);

switch (Tools::getValue('type')) {
    case "catalog":
    switch (Tools::getValue('mode')) {
        case "checkauth":
        if (auth()) {
		    echo "success"."\n";
            echo session_name()."\n";
            echo session_id();
            //$_SESSION["login"] = true;
        }
        break;
        case "init":
        unset($_SESSION['stage']);
        unset($_SESSION['last']);
        //if ($_SESSION["login"] === true && checkcookie())
        echo "zip=". FIX_ZIP ."\n"."file_limit=". FIX_ZIPSIZE;
        break;
        case "export":
        echo "success"."\n";
        break;
        case "file":
        if ($sid != $ids) {
            if (is_file(_PS_UPLOAD_DIR_._DS."import.xml")) rename (_PS_UPLOAD_DIR_._DS."import.xml",_PS_UPLOAD_DIR_._DS."old_import.xml");
            //if(is_file(_PS_UPLOAD_DIR_._DS."import_lang.xml")) rename (_PS_UPLOAD_DIR_._DS."import_lang.xml",_PS_UPLOAD_DIR_._DS."old_import_lang.xml");
            if (is_file(_PS_UPLOAD_DIR_._DS."offers.xml")) rename (_PS_UPLOAD_DIR_._DS."offers.xml",_PS_UPLOAD_DIR_._DS."old_offers.xml");
        }
        //if ($_SESSION["login"] === true && checkcookie()){
        echo CsyncTools::loadfile();
        Configuration::updateValue('SESSION_1C',$sid);
		//}
        break;
        case "import_lang":
        if(!$multilang && $multilang !=1){
		$csync_log->LogInfo("faillure. Не активирован режим MULTILANG.");
		echo "faillure"."\n";
		break;
		}
        $filename = Tools::getValue('filename');
        switch (substr($filename,0,6)) {
            case "import":
            require_once(_PS_MODULE_DIR_.'ecm_csync/classes/lang/CategoryLang.php');
            require_once(_PS_MODULE_DIR_.'ecm_csync/classes/lang/FeaturesLang.php');
            require_once(_PS_MODULE_DIR_.'ecm_csync/classes/lang/ManufacturerLang.php');
            require_once(_PS_MODULE_DIR_.'ecm_csync/classes/lang/SuppliersLang.php');
            require_once(_PS_MODULE_DIR_.'ecm_csync/classes/lang/ProductLang.php');
            require_once(_PS_MODULE_DIR_.'ecm_csync/classes/lang/AttributesGroupLang.php');
            require_once(_PS_MODULE_DIR_.'ecm_csync/classes/lang/AttributesLang.php');
            require_once(_PS_MODULE_DIR_.'ecm_csync/classes/lang/AttributesLangNum.php');
            require_once(_PS_MODULE_DIR_.'ecm_csync/classes/lang/CsyncLang.php');
            require_once(_PS_MODULE_DIR_.'ecm_csync/classes/Clear.php');
            require_once(_PS_MODULE_DIR_.'ecm_csync/classes/CsyncProduct.php');
            require_once(_PS_MODULE_DIR_.'ecm_csync/classes/CsyncFeatures.php');
            if (is_file(_PS_UPLOAD_DIR_._DS.$filename))
            $xml = simplexml_load_file(_PS_UPLOAD_DIR_.$filename);
            else{
            CsyncTools::failure("Файл обмена ".$filename." отсуствует на сервере в папке upload.\nУбедитесь, что передача файлов с 1С завершилась успешно.");
			die("faillure");

			}
           /* $version = $xml->attributes()->ВерсияСхемы;
            if ($version <= 2.04) {
                require_once(_PS_MODULE_DIR_.'ecm_csync/classes/features/204/CsyncFeatures.php');
                require_once(_PS_MODULE_DIR_.'ecm_csync/classes/product/204/CsyncProduct.php');
            }
            else {
                require_once(_PS_MODULE_DIR_.'ecm_csync/classes/features/205/CsyncFeatures.php');
                require_once(_PS_MODULE_DIR_.'ecm_csync/classes/product/205/CsyncProduct.php');
            }*/
            $iso_lang = $xml->attributes()->Lang;
            $id_lang_ = Language::getIdByIso($iso_lang);
            //d($id_lang_);
            //if ($_SESSION["login"] === true && checkcookie())
            CsyncLang::import_xml($xml);
            if (is_file(_PS_UPLOAD_DIR_._DS.$filename)) rename (_PS_UPLOAD_DIR_._DS.$filename,_PS_UPLOAD_DIR_._DS."old_".$filename);
            $csync_log->LogInfo("success");

            echo "success"."\n";
//            Clear::recRMDir(_PS_UPLOAD_DIR_."import_files/");
            break;
        }

        break;
        case "import":
        $filename = Tools::getValue('filename');
        switch (substr($filename,0,6)) {
            case "import":
            require_once (dirname(__FILE__) . '/../../images.inc.php');
            require_once(_PS_MODULE_DIR_.'ecm_csync/classes/CsyncImages.php');
            require_once(_PS_MODULE_DIR_.'ecm_csync/classes/lang/CategoryLang.php');
            require_once(_PS_MODULE_DIR_.'ecm_csync/classes/CsyncCategory.php');
            require_once(_PS_MODULE_DIR_.'ecm_csync/classes/CatalogMode.php');
            require_once(_PS_MODULE_DIR_.'ecm_csync/classes/Attachments.php');
            require_once(_PS_MODULE_DIR_.'ecm_csync/classes/Clear.php');
            require_once(_PS_MODULE_DIR_.'ecm_csync/classes/Tags.php');
            require_once(_PS_MODULE_DIR_.'ecm_csync/classes/CsyncManufacturer.php');
            require_once(_PS_MODULE_DIR_.'ecm_csync/classes/CsyncSuppliers.php');
            require_once(_PS_MODULE_DIR_.'ecm_csync/classes/Import.php');
            require_once(_PS_MODULE_DIR_.'ecm_csync/classes/CsyncProduct.php');
            require_once(_PS_MODULE_DIR_.'ecm_csync/classes/CsyncFeatures.php');
            require_once(_PS_MODULE_DIR_.'ecm_csync/classes/timer.php');
            if (is_file(_PS_UPLOAD_DIR_._DS.$filename))
            $xml = simplexml_load_file(_PS_UPLOAD_DIR_.$filename);
            else{
            CsyncTools::failure("Файл обмена ".$filename." отсутствует на сервере в папке upload.\nУбедитесь, что передача файлов с 1С завершилась успешно.");
            die("faillure");
			}
            if ($xml === false) {
			echo "Ошибка загрузки файла обмена import.xml\n";
			foreach(libxml_get_errors() as $error) {
				echo "\t", $error->message."\n";
				}
				die("faillure");
			}
            $version = $xml->attributes()->ВерсияСхемы;
            $iso_lang= $xml->attributes()->Lang;
            if ($iso_lang)
            $id_lang_ = Language::getIdByIso($iso_lang);
            else
            $id_lang_ = $id_lang;
            /*if ($version <= 2.04) {
                require_once(_PS_MODULE_DIR_.'ecm_csync/classes/features/204/CsyncFeatures.php');
                require_once(_PS_MODULE_DIR_.'ecm_csync/classes/product/204/CsyncProduct.php');
            }
            else {
                require_once(_PS_MODULE_DIR_.'ecm_csync/classes/features/205/CsyncFeatures.php');
                require_once(_PS_MODULE_DIR_.'ecm_csync/classes/product/205/CsyncProduct.php');
            }*/
            //if ($_SESSION["login"] === true && checkcookie())
            Import::import_xml($xml);
            if (is_file(_PS_UPLOAD_DIR_._DS.$filename)) rename (_PS_UPLOAD_DIR_._DS.$filename,_PS_UPLOAD_DIR_._DS."old_".$filename);
            $csync_log->LogInfo("success");
            echo "success"."\n";
            break;
            case "offers":
            require_once(_PS_MODULE_DIR_.'ecm_csync/classes/timer.php');
            require_once(_PS_MODULE_DIR_.'blocknewproducts/blocknewproducts.php');
            require_once(_PS_MODULE_DIR_.'ecm_csync/classes/Sale.php');
            require_once(_PS_MODULE_DIR_.'ecm_csync/classes/lang/AttributesGroupLang.php');
            require_once(_PS_MODULE_DIR_.'ecm_csync/classes/lang/AttributesLang.php');
            require_once(_PS_MODULE_DIR_.'ecm_csync/classes/Price.php');
            require_once(_PS_MODULE_DIR_.'ecm_csync/classes/Atributes.php');
            require_once(_PS_MODULE_DIR_.'ecm_csync/classes/Offers.php');
            require_once(_PS_MODULE_DIR_.'ecm_csync/classes/Currencies.php');
            if (is_file(_PS_UPLOAD_DIR_._DS.$filename))
            $xml = simplexml_load_file(_PS_UPLOAD_DIR_.$filename);
            else{
            CsyncTools::failure("Файл обмена ".$filename." отсутствует на сервере в папке upload.\nУбедитесь, что передача файлов с 1С завершилась успешно.");
            die("faillure");
			}
            if ($xml === false) {
			echo "Ошибка загрузки файла обмена offers.xml\n";
			foreach(libxml_get_errors() as $error) {
				echo "\t", $error->message."\n";
				}
				die("faillure");
			}
            $version = $xml->attributes()->ВерсияСхемы;
            $iso_lang= $xml->attributes()->Lang;
            if ($iso_lang)
            $id_lang_ = Language::getIdByIso($iso_lang);
            else
            $id_lang_ = $id_lang;
           // if ($_SESSION["login"] === true && checkcookie())
            Offers::offers_xml($xml);
            if (is_file(_PS_UPLOAD_DIR_._DS.$filename)) rename (_PS_UPLOAD_DIR_._DS.$filename,_PS_UPLOAD_DIR_._DS."old_".$filename);
            $csync_log->LogInfo("success");
            echo "success"."\n";
            break;
            $csync_log->LogInfo("success");
            echo "success"."\n";
        }
        //session_destroy();
    }
    break;
    case "sale":
    require_once(_PS_MODULE_DIR_.'ecm_csync/classes/Orders.php');
    switch (Tools::getValue('mode')) {
        case "checkauth":
        if (auth()) {
			@session_start();
            echo "success"."\n";
            echo session_name()."\n";
            echo session_id();
            $_SESSION["login"] = true;
        }
        break;
        case "init":
        echo "zip='no'\n"."file_limit=". FIX_ZIPSIZE;
        break;
        case "success":
        $csync_log->LogInfo("success");
        echo "success"."\n";
        break;
        case "query":
       // if (checkcookie())
        Orders::createzakaz();
        break;
        case "file":

        switch (Tools::getValue('filename')) {
            case Tools::getValue('filename'):
            //if ($_SESSION["login"] === true && checkcookie())
            Orders::exchange();
            break;
        }
        break;
    }
    break;
}

function auth()
{
    switch (php_sapi_name()) {
        case "cgi":
        case "cgi-fcgi":
        @$remote_user = $_SERVER["REMOTE_USER"]
        ? $_SERVER["REMOTE_USER"] : $_SERVER["REDIRECT_REMOTE_USER"];
        $strTmp      = base64_decode(substr($remote_user,6));
        if ($strTmp) list($_SERVER['PHP_AUTH_USER'], $_SERVER['PHP_AUTH_PW']) = explode(':', $strTmp);
        break;
        default:
        if (isset($_SERVER['HTTP_AUTHORIZATION'])) {
            $auth_params = explode(":", base64_decode(substr($_SERVER['HTTP_AUTHORIZATION'], 6)));
            $_SERVER['PHP_AUTH_USER'] = $auth_params[0];
            unset($auth_params[0]);
            $_SERVER['PHP_AUTH_PW'] = implode('', $auth_params);
        }
    }

    $c_login = Configuration::get('_ecm_csync_login_1c_');
    $c_pass  = Configuration::get('_ecm_csync_pass_1c_');
    if (!isset($_SERVER['PHP_AUTH_USER'])) {
        unauth_send();
        return FALSE;
    }
    else {
        $auth_user = $_SERVER['PHP_AUTH_USER'];

        $auth_pass = $_SERVER['PHP_AUTH_PW'];
        if (($auth_user != $c_login) || ($auth_pass != $c_pass)) {
            unauth_send();
            return FALSE;
        }
    }

    return TRUE;
}

function unauth_send()
{
    header('WWW-Authenticate: Basic realm="Closed Zone"');
    header('HTTP/1.0 401 Unauthorized');
    //$_SESSION["login"] = false;
    @session_destroy();
    die("failure\n, non auth user");
}

function return_bytes($val)
{
    $val = trim($val);
    $last= strtolower($val[strlen($val) - 1]);
    switch ($last) {
        // Модификатор 'G' доступен, начиная с PHP 5.1.0
        case 'g':
        $val *= 1024;
        case 'm':
        $val *= 1024;
        case 'k':
        $val *= 1024;
    }

    return $val;
}

function getallheaders_()
{
    $headers = '';
    foreach ($_SERVER as $name => $value) {
        if (substr($name, 0, 5) == 'HTTP_') {
            $headers[str_replace(' ', '-', ucwords(strtolower(str_replace('_', ' ', substr($name, 5)))))] = $value;
        }
    }
    return $headers;
}
function checkcookie()
{
    $headers = getallheaders_();
    if ($headers['Cookie'] != session_name()."=".session_id())
    return unauth_send();
    else
    return true;
}
