<?php
class CsyncTools
{
	//private $csync_log;
	//public function __construct(){

	//$csync_log = new KLogger(_PS_MODULE_DIR_.'Csync / log.txt', KLogger::DEBUG);
	//}
	public static
	function startSession()
	{

		if( session_id() ) return true;
		else return session_start();

	}
	public static
	function destroySession()
	{
		if( session_id() ){
			// Если есть активная сессия, удаляем куки сессии,
			setcookie(session_name(), session_id(), time() - 60 * 60 * 24);
			// и уничтожаем сессию
			session_unset();
			session_destroy();
		}
	}
	public static
	function clearCache()
	{
		if(_PS_VERSION_ > "1.6.1"){
 		$cache_dirs = array(
                _PS_ROOT_DIR_.DIRECTORY_SEPARATOR.'cache'.DIRECTORY_SEPARATOR.'smarty'.DIRECTORY_SEPARATOR.'compile',
                _PS_ROOT_DIR_.DIRECTORY_SEPARATOR.'cache'.DIRECTORY_SEPARATOR.'smarty'.DIRECTORY_SEPARATOR.'cache',

            );
        foreach ($cache_dirs as $dir) {
            if (file_exists($dir)) {

                self::emptydir($dir);
            }
        }
			}
	}


	public static
	function progress($current,$info,$max_item = false)
	{
		global $csync_log;
		@header ( "Content-type: text/html; charset=utf-8" );
		print "\xEF\xBB\xBF";
		print "progress\r\n";
		//print "$current ";
		if(is_int($current)){
			$_SESSION['last'] = $current;
			$stage = $_SESSION['stage'];
		}
		else
		{
			$stage = intval($_SESSION['stage']) - 1;
		}
		print "$info ";
		if(isset($max_item)) print "$max_item ";
		$csync_log->LogInfo("Stage -->".$stage." processed -->". $current );


		exit();
	}

	public static
	function failure($message)
	{
		global $csync_log;
		@header ( "Content-type: text/html; charset=utf-8" );
		print "\xEF\xBB\xBF";
		print "failure\n";
		if($message)
		//print iconv('UTF-8', 'windows-1251', $message) . "\n";
		print ($message) . "\n";
		$csync_log->LogInfo($message);
		session_destroy();
		die();
	}

	public static
	function archive ($file_folder, $file)
	{
		if(extension_loaded('zip')){
			if(file_exists($file)){
				// проверяем выбранные файлы
				$zip      = new ZipArchive(); // подгружаем библиотеку zip
				$zip_name = $file_folder.date('j_m_Y').".zip"; // имя файла
				if($zip->open($zip_name, ZIPARCHIVE::CREATE) !== TRUE){

					self::failure ("* Sorry ZIP creation failed at this time");
				}

					$zip->addFile($file); // добавляем файлы в zip архив

				$zip->close();
			}
			else
			self::failure(" log.txt not found or no have permission to write it! ");
		}
		else{
		self::failure(" You dont have ZIP extension! ");
	}
}
public static
	function emptydir($dir)
    {
        if (is_dir($dir)) {
            $objects = scandir($dir);
            foreach ($objects as $object) {
                if ($object != "." && $object != ".." && $object != 'index.php') {
                    if (is_dir($dir.DIRECTORY_SEPARATOR.$object)) {
                       self::emptydir($dir.DIRECTORY_SEPARATOR.$object);
                    } else {
                        @unlink($dir.DIRECTORY_SEPARATOR.$object);
                    }
                }
            }
            reset($objects);
        }
    }
// ========================================Mass Update========================================
//	CsyncTools::massupdate ("category", "xml", "id_category", $sql, $sql_idx);
//	П°и¬ҐпїЅ з Їо«­е­ЁпїЅ
//	$data[] = "WHEN '$key' THEN '$value' \n";
//	$index[] = $key;

public static
function massupdate ($table, $update_column, $id_column, $data, $index)
{
	if(!is_array ($data) or !is_array ($index)){
		echo "Arguments is not array"; return false;
	};

	$table = _DB_PREFIX_.$table;
	return Db::getInstance()->Execute(
		"UPDATE $table SET $update_column = CASE $id_column \n"
		.implode('', $data).
		"END WHERE $id_column IN (".implode(',', $index).")");
}

// ========================================Trim========================================
public static
function trim ($table, $update_column)
{


	$table = _DB_PREFIX_.$table;
	return Db::getInstance()->Execute(
		"UPDATE $table SET $update_column = TRIM($update_column);
		");
}
public static
	function tempattr_image ($table)
	{
		$table = _DB_PREFIX_.$table;

		return	Db::getInstance()->Execute("
			CREATE TEMPORARY TABLE $table (	id_image int(9),  id_product_attribute int(9)) CHARACTER SET=utf8
			");
	}
  // ========================================TmpTable Like========================================
    public static
    function createtemp ($tmptable, $table)
    {

        $table    = _DB_PREFIX_.$table;
        $tmptable = _DB_PREFIX_.$tmptable;

        Db::getInstance()->Execute(" DROP TEMPORARY TABLE IF EXISTS $tmptable ");
        return    Db::getInstance()->Execute(" CREATE TEMPORARY TABLE $tmptable LIKE $table ");
    }
// ========================================TmpProduct========================================
public static
function tempproduct ($table)
{
	global $version;
	$table = _DB_PREFIX_.$table;
	if($version <= 2.04){
		return	Db::getInstance()->Execute("
			CREATE TEMPORARY TABLE $table (	ean13 varchar(13),reference varchar(32), supplier_reference varchar(32), weight float ,id_category_default varchar(13),xml_cp varchar(73), date_upd datetime, active varchar(2),status TINYINT(2), xml varchar(36)) CHARACTER SET=utf8
			");
	}
	else
	{
		return	Db::getInstance()->Execute("
			CREATE TEMPORARY TABLE $table (	ean13 varchar(13),id_manufacturer varchar(32), reference varchar(32), supplier_reference varchar(32), weight float ,id_category_default varchar(13),xml_cp varchar(73), date_upd datetime, active varchar(2),status TINYINT(2), xml varchar(36)) CHARACTER SET=utf8
			");
	}
}
// ========================================TmpPriceandQantyty========================================
public static
function tempprice_quant ($table)
{
	$table = _DB_PREFIX_.$table;

	return	Db::getInstance()->Execute("
		CREATE TEMPORARY TABLE $table (	tax int(2), price decimal(20,6),wholesale_price decimal(20,6), quantity int(10), date_upd datetime ,xml varchar(73),unity varchar(36), on_stock tinyint(1)) CHARACTER SET=utf8
		");
}
public static
function tempprice_quant_prod ($table)
{
	$table = _DB_PREFIX_.$table;

	return	Db::getInstance()->Execute("
		CREATE TEMPORARY TABLE $table (	id_product int(10),  quantity int(10)) CHARACTER SET=utf8
		");
}
// ========================================TmpPriceandQantyty========================================
public static
function temptags ($table)
{
	$table = _DB_PREFIX_.$table;

	return	Db::getInstance()->Execute("
		CREATE TEMPORARY TABLE $table (	id_tag int(10), id_lang int(10),name varchar(36),xml varchar(73)) CHARACTER SET=utf8
		");
}
// ========================================TmpPriceandQantyty========================================
public static
function tempprice_quant_atr ($table)
{
	$table = _DB_PREFIX_.$table;

	return	Db::getInstance()->Execute("
		CREATE TEMPORARY TABLE $table (	price decimal(20,6),wholesale_price decimal(20,6), quantity int(10),xml varchar(110)) CHARACTER SET=utf8
		");
}
// ========================================TmpPriceandQantyty========================================
public static
function tempprice_quant_atr_mod ($table)
{
	$table = _DB_PREFIX_.$table;

	return	Db::getInstance()->Execute("
		CREATE TEMPORARY TABLE $table (	price decimal(20,6),xml varchar(110)) CHARACTER SET=utf8
		");
}
// ========================================TmpPricenull========================================
public static
function tempprice_null ($table)
{
	$table = _DB_PREFIX_.$table;

	return	Db::getInstance()->Execute("
		CREATE TEMPORARY TABLE $table (	price decimal(20,6),xml varchar(73),unity varchar(36)) CHARACTER SET=utf8
		");
}
// ========================================TmpProductlang========================================
public static
function tempproductlang ($table)
{
	$table = _DB_PREFIX_.$table;

	return	Db::getInstance()->Execute("
		CREATE TEMPORARY TABLE $table (	id_product int(10),id_lang int(10), name varchar(128), description_short text , description text,meta_description text,meta_keywords text,meta_title text, link_rewrite varchar(128)) CHARACTER SET=utf8
		");
}
public static
	function tempproductstock ($table){
	$table = _DB_PREFIX_.$table;

	return	Db::getInstance()->Execute("
			CREATE TEMPORARY TABLE $table (	id_stock_available int(10),id_product int(10), id_product_attribute int(10),id_shop int(10),quantity int(10),out_of_stock tinyint(1)) CHARACTER SET=utf8
			");
	}
// ========================================TmpProductlangsd========================================
public static
function tempproductlangsd ($table)
{
	$table = _DB_PREFIX_.$table;

	return	Db::getInstance()->Execute("
		CREATE TEMPORARY TABLE $table (	id_product int(10),id_lang int(10), description_short text ) CHARACTER SET=utf8
		");
}

// ========================================droptemp========================================
public static
function droptemp ($table, $table1 = false)
{
	$table = _DB_PREFIX_.$table;
	@$table1= _DB_PREFIX_.$table1;
	return	Db::getInstance()->Execute("
		DROP TEMPORARY TABLE IF EXISTS $table , $table1
		");


}
// ========================================Get array========================================
public static
function getarray ($values, $table)
{


	$table = _DB_PREFIX_.$table;
	return Db::getInstance()->ExecuteS(
		"SELECT `$values` FROM $table
		");
}

// ========================================Get ========================================
public static
function get ($value, $table, $column, $index)
{


	$table = _DB_PREFIX_.$table;
	return Db::getInstance()->getValue(
		"SELECT `$value` FROM $table WHERE `$column` = '".$index."'
		");
}
// ========================================Udate ========================================
public static
function update ($table, $column,$value, $index, $index_key)
{


	$table = _DB_PREFIX_.$table;
	return Db::getInstance()->Execute("
		UPDATE $table
		SET `$column`='".$value."'
		WHERE `id_lang`=".Configuration::get('_ID_LANG_')." AND `$index`= ".$index_key
	);
}
public static
	function massinsertignore ($table, $columns, $data)
	{
		if(!is_array ($data))
		{
			echo "Arguments is not array";return false;
		};

		$table = _DB_PREFIX_.$table;
		return Db::getInstance()->Execute(
			"INSERT IGNORE INTO $table ( $columns )\n
			VALUES ".implode(',', $data));
	}
// ========================================insert ========================================
public static
function insert ($table, $column,$values)
{


	$table = _DB_PREFIX_.$table;
	return Db::getInstance()->Execute("
		INSERT INTO $table
		($column)
		VALUES ($values)
		");
}
// ========================================Mass Insert========================================
//	Mass::insert ("category", "`id_product`,`id_lang`", "id_category", $sql);
//	П°и¬ҐпїЅ з Їо«­е­ЁпїЅ
//	$columns = "`id_product`,`id_lang`, `name`,`description_short`, `description`,`link_rewrite`";
//	$data[] = $sql_lang[] = "(".$id_product.", ".Configuration::get('_ID_LANG_').", '".mysql_real_escape_string($product->Н и¬Ґн®ўа­Ёе©®"', '".mysql_real_escape_string($product["П®л­®е ­аЁ¬е­®в ­иҐў])."', '".mysql_real_escape_string($product->ОЇи± нЁҐ)."', '".Tools::link_rewrite($product->Н и¬Ґн®ўа­Ёе©®"')";

public static
function massinsertduplicate ($table, $columns, $id_column, $data)
{
	if(!is_array ($data)){
		echo "Arguments is not array"; return false;
	};

	$table = _DB_PREFIX_.$table;
	$sql = "INSERT INTO $table ( $columns )\n
		VALUES ".implode(',', $data).
		"\nON DUPLICATE KEY UPDATE $id_column = VALUES($id_column)";
		//d($sql);
	return Db::getInstance()->Execute($sql);
}
public static
	function massinsertduplicate_ ($table, $columns, $data_, $data){

		$table = _DB_PREFIX_.$table;
		if(!is_array ($data)){
			return Db::getInstance()->Execute(
			"INSERT INTO $table ( $columns )\n
			VALUES ". $data.
			"\nON DUPLICATE KEY UPDATE $data_");
		}else{

		return Db::getInstance()->Execute(
			"INSERT INTO $table ( $columns )\n
			VALUES ".implode(',', $data).
			"\nON DUPLICATE KEY UPDATE $data_");
	}
}
// ========================================Mass Insert========================================
public static
function massinsert ($table, $columns, $data)
{
	if(!is_array ($data)){
		echo "Arguments is not array"; return false;
	};

	$table = _DB_PREFIX_.$table;
	return Db::getInstance()->Execute(
		"INSERT INTO $table ( $columns )\n
		VALUES ".implode(',', $data));
}
// ========================================Insert category prodykt========================================
public static
function insert_category_product ($table, $columns,$selectvars, $selectable, $condition = NULL)
{
	$table      = _DB_PREFIX_.$table;
	$selectable = _DB_PREFIX_.$selectable;
	$sql = "
		INSERT IGNORE INTO $table( $columns ) SELECT $selectvars FROM $selectable
		";
	if($condition)
	$sql.=$condition;

	return Db::getInstance()->Execute($sql);
}
// ========================================Insert category prodykt========================================
public static
function insert_category_product_w ($table, $columns,$selectvars, $selectable, $var,$my_array)
{
	$table      = _DB_PREFIX_.$table;
	$selectable = _DB_PREFIX_.$selectable;
	return Db::getInstance()->Execute("
		INSERT IGNORE INTO $table( $columns ) SELECT $selectvars FROM $selectable WHERE $var IN('".implode("','",$my_array)."')
		");
}
// ========================================Insert category prodykt========================================
public static
function delete_category_product ($table, $var,$my_array ,$var1,$my_array1)
{
	$table      = _DB_PREFIX_.$table;
		return Db::getInstance()->Execute("
		DELETE  FROM  $table  WHERE $var IN('".implode("','",$my_array)."') AND $var1 NOT IN('".implode("','",$my_array1)."')
		");
}
//========================================"пїЅо°Ёз ¶иїЅ=======================================
public
function auth_send()
{
	header('WWW-Authenticate: Basic realm="Closed Zone"');
	header('HTTP/1.0 401 Unauthorized');

	exit;
}
//==================504=======================================================================
public static
function print_log($message)
{

	global $logb;
	print $message."<br/>";//flush();
	$logb .= $message;
	return $logb;
}
//==========================print array========================================================
public static function printArray( $a )
{

	static $count;
	$count = (isset($count)) ? ++$count : 0;
	$colors = array('#FFCB72','#FFB072','#FFE972','#F1FF72','#92FF69','#6EF6DA','#72D9FE','#77FFFF','#FF77FF');
	if($count > count($colors)){
		$count--;
		return;
	}

	if(!is_array($a)){
		echo "Passed argument is not an array!<p>";
		return;
	}

	echo "<table border=1 cellpadding=0 cellspacing=0 bgcolor=$colors[$count]>";

	while(list($k, $v) = each($a)){
		echo "<tr><td style='padding:1em'>$k</td><td style='padding:1em'>$v</td></tr>\n";
		if(is_array($v)){
			echo "<tr><td> </td><td>";
			printArray($v);
			echo "</td></tr>\n";
		}
	}
	echo "</table>";
	$count--;
}

public static function array2table( & $array, $recursive = false, $return = false, $null = '&nbsp;')

//array  $array - РёСЃС…РѕРґРЅС‹Р№ РјР°СЃСЃРёРІ
//bool   $recursive - СЂРµРєСѓСЂСЃРёРІРЅРѕ РѕР±СЂР°Р±Р°С‚С‹РІР°С‚СЊ РІР»РѕР¶РµРЅРЅС‹Рµ РјР°СЃСЃРёРІС‹
//bool   $return - РІС‹РІРѕРґРёС‚СЊ СЂРµР·СѓР»СЊС‚Р°С‚ РЅР° СЌРєСЂР°РЅ (echo) РёР»Рё РІРѕР·РІСЂР°С‰Р°С‚СЊ СЃС‚СЂРѕРєСѓ
//string $null - СЃС‚СЂРѕРєР°, РєРѕС‚РѕСЂСѓСЋ РЅР°РґРѕ РїРѕРґСЃС‚Р°РІР»СЏС‚СЊ РІРјРµСЃС‚Рѕ РїСѓСЃС‚С‹С… СЏС‡РµРµРє
{
	// РїСЂРѕРІРµСЂРєР° РІС…РѕРґРЅС‹С… РґР°РЅРЅС‹С…
	if(empty($array) || !is_array($array)){
		return false;
	}
	if(!isset($array[0]) || !is_array($array[0])){
		$array = array($array);
	}
	// РЅР°С‡Р°Р»Рѕ С‚Р°РІР»РёС†С‹
	$table = "<center><table border=1 cellpadding=2 cellspacing=0>\n";
	// Р·Р°РіРѕР»РѕРІРєРё СЃС‚РѕР»Р±С†РѕРІ С‚Р°Р±Р»РёС†С‹
	$table .= "\t<tr>";
	foreach(array_keys($array[0]) as $heading){
		$table .= '<th>'.$null.$heading.$null.'</th>';
	}
	$table .= "</tr>\n";
	foreach($array as $row){
		$table .= "\t<tr>" ;
		foreach($row as $cell){
			$table .= '<td>';
			if(is_object($cell)){
				$cell = (array) $cell;
			}
			if($recursive === true && is_array($cell) && !empty($cell)){
				// СЂРµРєСѓСЂСЃРёСЏ
				$table .= "\n" . array2table($cell, true, true) . "\n";
			}
			else
			{
				$table .= (strlen($cell) > 0) ?
				htmlspecialchars((string) $cell) :
				$null;
			}
			$table .= '</td>';
		}
		$table .= "</tr>\n";
	}
	// РєРѕРЅРµС†
	$table .= '</table>';
	// РІС‹РІРѕРґ
	if($return === false){
		echo $table;
	}
	else
	{
		return $table;
	}
}
// ========================================load======================================
public static function loadfile()
{
	//clearDir (_PS_UPLOAD_DIR_);
	$filename_to_save = _PS_UPLOAD_DIR_ ._DS. $_REQUEST ['filename'];
	if(FIX_ZIP == 'no')
	{
		$big_zip = false;


		if( strpos(  $_REQUEST['filename'], 'import_files') !== false )
		{
			$path = stristr( $_REQUEST['filename'], 'import_files');


			foreach( explode('/', $path) as $name)
			{

				if( (substr ( $name, - 4 ) != 'jpeg') || ((substr ( $name, - 3 ) != ('bmp'||'jpg'||'gif'||'png' ))) )//РґРѕР±Р°РІРёС‚СЊ СѓСЃР»РѕРІРёРµ РЅР° РїРЅРі Р¶РїРі Рё РіРёС„ Р±РјРї ('jpeg' || 'bmp' || 'jpg' || 'gif' || 'png' )
				{
					if($name != 'import_files')
					{
						$name = 'import_files'._DS.$name;
					}


					if(!is_dir(  _PS_UPLOAD_DIR_._DS.$name ) )
					{

						mkdir(  _PS_UPLOAD_DIR_._DS.$name, 0777, true );
					}
				}
			}

		}
	}
	else
	{
		$file_zip = scandir ( _PS_UPLOAD_DIR_ );
		foreach( $file_zip as $filename_zip )
		{
			if(substr ( $filename_zip, - 3 ) == 'zip')
			{
				$zip = zip_open ( _PS_UPLOAD_DIR_.$filename_zip );
				if(is_resource($zip))
				{
					zip_close($zip);
					unlink ( _PS_UPLOAD_DIR_.$filename_zip );

				}
			}
		}
	}

	$filename_to_save = _PS_UPLOAD_DIR_ ._DS. $_REQUEST ['filename'];

	$DATA             = file_get_contents("php://input");


	if(isset ( $DATA ) or $DATA !== false)
	{
		$fp = fopen($filename_to_save, "ab");
		if($fp)
		{
			set_file_buffer ( $fp, 20 );

			$result = fwrite($fp, $DATA);

			if($result === strlen($DATA))
			{
				$resultat = "success\n";



				fclose($fp);

				if(!chmod($filename_to_save , 0777))
				{


					echo "failure\n";

					exit;
				}
			}
			else
			{


				echo "failure\n";

				exit;
			}
		}
		else
		{


			echo "failure\n";
			echo "Can not open file: $filename_to_save\n";
			echo _PS_UPLOAD_DIR_;
			exit;
		}
	}
	else
	{

		echo "failure\n";
		echo "No data file\n";
		exit;
	}

	if(FIX_ZIP == 'yes')
	{
		$file_txt = scandir ( _PS_UPLOAD_DIR_ );
		foreach( $file_txt as $filename_save )
		{
			if(substr ( $filename_save, - 3 ) == 'zip')
			{
				$zip = zip_open ( _PS_UPLOAD_DIR_.$filename_save );

				if($zip)
				{
					if(!is_resource($zip))
					{
						$big_zip = true;
						$resultat = "success\n";
					}
					else
					{

						while( $zip_entry = zip_read ( $zip ) )
						{

							$name       = _PS_UPLOAD_DIR_ . zip_entry_name ( $zip_entry );

							$path_parts = pathinfo ( $name );
							# Р РЋР С•Р В·Р Т‘Р ВµР С Р С•РЎвЂљРЎРѓРЎС“РЎвЂљРЎРѓРЎвЂљР Р†РЎС“РЎР‹РЎвЂ°Р С‘Р Вµ Р Т‘Р С‘РЎР‚Р ВµР С”РЎвЂљР С•РЎР‚Р С‘Р С‘

							if(! is_dir ( $path_parts ['dirname'] )){
								mkdir ( $path_parts ['dirname'], 0777, true );
							}

							if(zip_entry_open ( $zip, $zip_entry, "r" )){
								$buf = zip_entry_read ( $zip_entry, zip_entry_filesize ( $zip_entry ) );

								$file= fopen ( $name, "wb" );
								if($file)
								{
									fwrite ( $file, $buf );
								}
								else
								{

									exit;
								}
								fclose ( $file );
								zip_entry_close ( $zip_entry );
							}
						}
						$big_zip = false;
						zip_close ( $zip );
					}

				}
				else
				{

					echo "failure\n";
					exit;
				}
			}
		}
	}


	if($resultat)
	{
		//echo $resultat."File is accepted: ".Tools::getValue('filename')."\n";
		return "success\n";
	}

}
public static function timestamp ($date)
{

	$arr1     = explode("T", $date);

	$arrdate1 = explode("-", $arr1[0]);

	$arrtime1 = explode(":", $arr1[1]);
	$timestamp= @(mktime($arrtime1[0], $arrtime1[1], $arrtime1[2], $arrdate1[1],  $arrdate1[2],  $arrdate1[0]));

	return $timestamp;

}
public static function gmtimestamp ($date)
{

	$arr1       = explode("T", $date);

	$arrdate1   = explode("-", $arr1[0]);

	$arrtime1   = explode(":", $arr1[1]);
	$timestamp1 = @(mktime($arrtime1[0], $arrtime1[1], $arrtime1[2], $arrdate1[1],  $arrdate1[2],  $arrdate1[0]));
	$timestamp  = $timestamp1 - 0;
	return $timestamp;

}
public static  function link_rewrite_cyr($to_url)
	{


    $url = preg_replace("/[^a-яa-z0-9-s,]/ui", " ", trim($to_url));  // Удаляем лишние символы
    $url = stripslashes($url);
    $url = preg_replace("/[,-]/ui", " ", $url);               // Заменяем на пробелы
    $url =  mb_strtolower(preg_replace("|\s+|", "-", $url));                // Заменяем 1 и более пробелов на "-"

    return $url;

	}
	public static function link_rewrite_lat($str)

{

if (function_exists('mb_strtolower'))

$str = mb_strtolower($str, 'utf-8');

// fix

if (preg_match('/[а-я]+/', $str))

$cyr = array('а', 'б', 'в', 'г', 'д', 'е', 'ё', 'ж', 'з', 'и', 'й', 'к', 'л', 'м', 'н', 'о', 'п', 'р', 'с', 'т', 'у', 'ф', 'х', 'ц', 'ч', 'ш', 'щ', 'ъ', 'ы', 'ь', 'э', 'ю', 'я', ' ');

$lat = array('a', 'b', 'v', 'g', 'd', 'e', 'yo', 'zh', 'z', 'i', 'y', 'k', 'l', 'm', 'n', 'o', 'p', 'r', 's', 't', 'u', 'f', 'h', 'c', 'ch', 'sch', 'sh', '', 'y', '', 'e', 'yu', 'ya', '-');

$str = @str_replace($cyr, $lat, $str);

// fix


$str = trim($str);

$str = preg_replace('/[\x{0105}\x{0104}\x{00E0}\x{00E1}\x{00E2}\x{00E3}\x{00E4}\x{00E5}]/u','a', $str);

$str = preg_replace('/[\x{00E7}\x{010D}\x{0107}\x{0106}]/u','c', $str);

$str = preg_replace('/[\x{010F}]/u','d', $str);

$str = preg_replace('/[\x{00E8}\x{00E9}\x{00EA}\x{00EB}\x{011B}\x{0119}\x{0118}]/u','e', $str);

$str = preg_replace('/[\x{00EC}\x{00ED}\x{00EE}\x{00EF}]/u','i', $str);

$str = preg_replace('/[\x{0142}\x{0141}\x{013E}\x{013A}]/u','l', $str);

$str = preg_replace('/[\x{00F1}\x{0148}]/u','n', $str);

$str = preg_replace('/[\x{00F2}\x{00F3}\x{00F4}\x{00F5}\x{00F6}\x{00F8}\x{00D3}]/u','o', $str);

$str = preg_replace('/[\x{0159}\x{0155}]/u','r', $str);

$str = preg_replace('/[\x{015B}\x{015A}\x{0161}]/u','s', $str);

$str = preg_replace('/[\x{00DF}]/u','ss', $str);

$str = preg_replace('/[\x{0165}]/u','t', $str);

$str = preg_replace('/[\x{00F9}\x{00FA}\x{00FB}\x{00FC}\x{016F}]/u','u', $str);

$str = preg_replace('/[\x{00FD}\x{00FF}]/u','y', $str);

$str = preg_replace('/[\x{017C}\x{017A}\x{017B}\x{0179}\x{017E}]/u','z', $str);

$str = preg_replace('/[\x{00E6}]/u','ae', $str);

$str = preg_replace('/[\x{0153}]/u','oe', $str);


// Remove all non-whitelist chars.

$str = preg_replace('/[^a-zA-Z0-9\s\'\:\/\[\]-]/','', $str);

$str = preg_replace('/[\s\'\:\/\[\]-]+/',' ', $str);

$str = preg_replace('/[ ]/','-', $str);

$str = preg_replace('/[\/]/','-', $str);


// If it was not possible to lowercase the string with mb_strtolower, we do it after the transformations.

// This way we lose fewer special chars.

$str = strtolower($str);


return $str;

}
public static function convert_date($date){

	$dt = explode(" ", $date);
    $t = explode(":", $dt[1]);
    $d = explode(".", $dt[0]);
    // mktime(час,минута,секунда,месяц,день,год)

    $data1 = mktime($t[0], $t[1], $t[2], $d[1], $d[0], $d[2]);



    return date("Y-m-d H:i:s", $data1);

}
}

