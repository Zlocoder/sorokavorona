<?php
include(dirname(__FILE__).'/../../config/config.inc.php');
require_once(dirname(__FILE__).'/../../init.php');
require_once(_PS_MODULE_DIR_.'ecm_csync/classes/CsyncTools.php');
//require_once(_PS_MODULE_DIR_.'ecm_csync / classes / Cat.php');
require_once(_PS_MODULE_DIR_.'ecm_csync/classes/CmlUid.php');
////========================================Авторизация========================================

$lang = Configuration::get('_ecm_csync_lang_');
$shop = Configuration::get('PS_SHOP_DEFAULT');
$sql = "
	SELECT `domain`, `physical_uri` FROM `"._DB_PREFIX_."shop_url`
	WHERE `id_shop` = '$shop'
	";
$url = Db::getInstance()->ExecuteS($sql);
$uri = "http://".$url[0]['domain'].$url[0]['physical_uri'];

function unauth_send()
{
	header('WWW-Authenticate: Basic realm="Closed Zone"');
	header('HTTP/1.0 401 Unauthorized');
}


function xmlentities($string)
{
	//$string = nbsp($string);
	$string = utf8_for_xml($string);
	return str_replace(array("&","<",">","\"","'"),
		array("&amp;","&lt;","&gt;","&quot;","&apos;"), $string);
}
function utf8_for_xml($string)
{
    return preg_replace ('/[^\x{0009}\x{000a}\x{000d}\x{0020}-\x{D7FF}\x{E000}-\x{FFFD}]+/u', ' ', $string);
}
function nbsp($string)
{
    return str_replace("&nbsp;","&#160" ,$string);
}
// ========================================Очистка HTML сущностей===============================
function html_clear($document)
{
	$search = array ("'<script[^>]*?>.*?</script>'si",// Вырезает javaScript
		"'<[\/\!]*?[^<>]*?>'si",// Вырезает HTML - теги
		"'([\r\n])[\s]+'",// Вырезает пробельные символы
		"'&(quot|#34);'i",// Заменяет HTML - сущности
		"'&(amp|#38);'i",
		"'&(lt|#60);'i",
		"'&(gt|#62);'i",
		"'&(nbsp|#160);'i",
		"'&(iexcl|#161);'i",
		"'&(cent|#162);'i",
		"'&(pound|#163);'i",
		"'&(copy|#169);'i",
		"'&#(\d+);'e");                    // интерпретировать как php - код

	$replace = array ("",
		"",
		"\\1",
		"\"",
		"&",
		"<",
		">",
		" ",
		chr(161),
		chr(162),
		chr(163),
		chr(169),
		"chr(\\1)");

	$text = preg_replace($search, $replace, $document);
	return $text;
}

// ========================================Рекурсивная обработка массива=======================
function arraytoxml($categTree, $xml, & $sql, & $sql_idx)
{

	global $first,$lang,$shop,$ii,$uri;
	$ii++;
	/*$sql = "
	SELECT `domain`, `physical_uri` FROM `"._DB_PREFIX_."shop_url`
	WHERE `id_shop` = '$shop'
	";
	$url = Db::getInstance()->ExecuteS($sql);
	d($url);
	$uri = "http://".$url[0]['domain'].$url[0]['physical_uri'];*/

	foreach($categTree as $cat => $key)
	{
		switch($cat)
		{
			case "id":
			$xml_1       = $xml->addChild("Группа");//отладка на группы
			$id_category = $key;
			$id          = CsyncTools::get("xml", "category", "id_category", $key);
			$active      = (int)CsyncTools::get("active", "category", "id_category", $key);
			$xml_2 = $xml_1->addChild("Ид", 		$id);
			if($active == 0) $xml_2 = $xml_1->addChild("Статус", "Удален");

			break;

			case "name":
			$xml_2 = $xml_1->addChild("Наименование", 			 xmlentities(trim($key)));

			break;

			case "desc":

			if(!empty($key))
			$xml_2 = $xml_1->addChild("Описание", 			 xmlentities($key));

			$sql   = "
			SELECT `meta_title`,`meta_keywords`,`meta_description`
			FROM `"._DB_PREFIX_."category_lang`
			WHERE `id_category` = '$id_category'
			AND `id_lang` = '$lang'
			AND `id_shop` = '$shop'
			";
			$meta  = Db::getInstance()->ExecuteS($sql);
			if(!empty($meta))
			{
				if(!empty($meta[0]['meta_title'])) $xml_2 = $xml_1->addChild("МетаЗаголовок", xmlentities(trim($meta[0]['meta_title'])));
				if(!empty($meta[0]['meta_keywords'])) $xml_2 = $xml_1->addChild("МетаКлючевыеСлова", xmlentities(trim($meta[0]['meta_keywords'])));
				if(!empty($meta[0]['meta_description'])) $xml_2 = $xml_1->addChild("МетаОписание", xmlentities(trim($meta[0]['meta_description'])));
			}

			$sql         = "
			SELECT `position` FROM `"._DB_PREFIX_."category_shop`
			WHERE `id_category` = '$id_category' AND `id_shop` = '$shop'
			";
			//p($sql);
			$level_depth = Db::getInstance()->ExecuteS($sql);
			if(!empty($level_depth[0]['position'])) $xml_2 = $xml_1->addChild("УровеньКатегории", trim($level_depth[0]['position']));

			if(file_exists ( dirname(__FILE__)."/../../img/c/".$id_category.".jpg" )) $xml_2 = $xml_1->addChild("Картинка", $uri."img/c/".$id_category.".jpg");

			break;


			case "children":
			if( count($key) > 0)
			{
				$xml_2 = $xml_1->addChild("Группы");
				foreach($key as $cat_1 => $key_1)
				{
					arraytoxml($key_1, $xml_2, $sql, $sql_idx);
				}
			}
			break;
		}
	}
}

function remote_file_exists($url)
{
	return (bool)preg_match('~HTTP/1\.\d\s+200\s+OK~', @current(get_headers($url)));
}

if(Tools::getValue('type') == 'genuid')
{
	CmlUid::generateCML ();
	exit;
}


if(Tools::getValue('type') == 'catalog')
{
	ob_end_flush();
	echo "Begin catalog processing ..."."<br>";
	flush();

	$first     = Configuration::get('_first_');

	CsyncTools::trim ("category_lang", "name");
	$categTree = Category::getRootCategory()->recurseLiteCategTree(10, 0, $lang);
	$timechange= time();
	$no_spaces = '<?xml version="1.0" encoding="utf-8"?><КоммерческаяИнформация ВерсияСхемы="2.05" ДатаФормирования="' . date( 'Y-m-d', $timechange) . 'T' . date( 'H:m:s',$timechange) .  '"></КоммерческаяИнформация>';
	$xml       = new SimpleXMLElement($no_spaces );
	$doc       = $xml->addChild("Классификатор");
	$id        = CmlUid::create_guid('Классификатор');
	$doc_1     = $doc->addChild("Ид",$id );
	Configuration::updateValue('1C_CAT_CLASS',$id );
	$doc_1     = $doc->addChild("Наименование",'Классификатор товаров' );
	$doc_1     = $doc->addChild("Владелец");
	$id        = CmlUid::create_guid('Владелец');
	$doc_2     = $doc_1->addChild("Ид",$id );
	Configuration::updateValue('1C_CAT_OVNER',$id );
	$doc_2     = $doc_1->addChild("Наименование",Configuration::get('PS_SHOP_NAME') );
	$doc_2     = $doc_1->addChild("ОфициальноеНаименование",Configuration::get('PS_SHOP_NAME') );

	$ii        = 0;
	foreach($categTree as $cat => $key)
	{
		$ii++;

		switch($cat)
		{
			case "children":
			if( count($key) > 0)
			{
				$doc_1 = $doc->addChild("Группы");
				foreach($key as $cat_ => $key_)
				{
					arraytoxml($key_, $doc_1, $sql, $sql_idx);
				}
			}
		}
	}
	echo "Cat : $ii done<br>";
	flush();
	$doc_1    = $doc->addChild("Свойства");
	$features = Db::getInstance()->ExecuteS( '
		SELECT  	f.id_feature,
		f.xml,
		fl.name
		FROM `'._DB_PREFIX_.'feature` f
		LEFT JOIN `'._DB_PREFIX_.'feature_lang` fl ON f.id_feature = fl.id_feature
		AND  fl.id_lang = "'.Configuration::get('_ecm_csync_lang_').'"
		ORDER BY f.id_feature

		');
	if(!empty($features))
	{
		foreach($features as $feature){
			if(!empty($feature))
			{
				$doc_2     = $doc_1->addChild("Свойство");
				$doc_3     = $doc_2->addChild("Ид", $feature['xml']);
				$doc_3     = $doc_2->addChild("Наименование", $feature['name']);
				$doc_3     = $doc_2->addChild("ВариантыЗначений");
				$featuresv = Db::getInstance()->ExecuteS( '
					SELECT  	f.id_feature_value,
					f.xml,
					fl.value
					FROM `'._DB_PREFIX_.'feature_value` f
					LEFT JOIN `'._DB_PREFIX_.'feature_value_lang` fl ON f.id_feature_value = fl.id_feature_value
					AND  fl.id_lang = "'.Configuration::get('_ecm_csync_lang_').'"
					WHERE f.id_feature = "'.$feature['id_feature'].'"
					ORDER BY f.id_feature_value
					');
				if(!empty($featuresv))
				foreach($featuresv as $fv)
				{
					$doc_4 = $doc_3->addChild("Справочник");
					$doc_5 = $doc_4->addChild("ИдЗначения", $fv['xml']);
					$doc_5 = $doc_4->addChild("Значение", $fv['value']);
				}
			}
		}
	}
	$doc_1    = $doc->addChild("Производители");
	$manufacturers = Db::getInstance()->ExecuteS( '
		SELECT  	m.id_manufacturer,
		m.xml,
		m.name,
		ml.short_description,
		ml.description
		FROM `'._DB_PREFIX_.'manufacturer` m
		LEFT JOIN `'._DB_PREFIX_.'manufacturer_lang` ml ON m.id_manufacturer = ml.id_manufacturer
		AND  ml.id_lang = "'.Configuration::get('_ecm_csync_lang_').'"
		ORDER BY m.id_manufacturer

		');
	if(sizeof($manufacturers))
	{
		foreach($manufacturers as $manufacturer){
			if(!empty($manufacturer))
			{
				$doc_2     = $doc_1->addChild("Производитель");
				$doc_3     = $doc_2->addChild("Ид", $manufacturer['xml']);
				$doc_3     = $doc_2->addChild("Наименование", xmlentities($manufacturer['name']));
				if(file_exists ( dirname(__FILE__)."/../../img/m/".$manufacturer['id_manufacturer'].".jpg" ))
				$doc_3     = $doc_2->addChild("Картинка", $uri."img/m/".$manufacturer['id_manufacturer'].".jpg");
				$doc_3     = $doc_2->addChild("КороткоеОписание", xmlentities($manufacturer['short_description']));
				$doc_3     = $doc_2->addChild("Описание", xmlentities($manufacturer['description']));
			}
		}
	}
	CsyncTools::trim ("product_lang", "name");
	$sqlprod = array();
	$sqlprod_idx = array();

	$start = 0;
	$step  = 0;

	if(Tools::getValue('part') == '1')
	{
		$start = 0;
		$step  = 12000;
	}

	if(Tools::getValue('part') == '2')
	{
		$start = 11999;
		$step  = 12000;
	}

	if(Tools::getValue('part') == '3')
	{
		$start = 23999;
		$step  = 12000;
	}



	$products = Product::getProducts($lang, $start, $step, 'id_product', 'ASC', false, false);
	//d($products);
	$doc   = $xml->addChild("Каталог");
	$id    = CmlUid::create_guid('Каталог');
	$doc_1 = $doc->addChild("Ид",$id );
	Configuration::updateValue('1C_CAT_ID',$id );
	$doc_1 = $doc->addChild("Наименование",'Каталог товаров от ' . date( 'd.m.y'));
	$doc_1 = $doc->addChild("Владелец");
	$doc_2 = $doc_1->addChild("Ид",Configuration::get('1C_CAT_OVNER') );
	$doc_2 = $doc_1->addChild("Наименование",Configuration::get('PS_SHOP_NAME') );
	$doc_2 = $doc_1->addChild("ОфициальноеНаименование",Configuration::get('PS_SHOP_NAME') );
	$doc_1 = $doc->addChild("Товары");
	$ii    = 0;
	foreach($products as $tovar)
	{
		$ii++;
		$doc_2 = $doc_1->addChild("Товар");
		$key   = $tovar['id_product'];
		$id    = $tovar['xml'];
		$doc_3 = $doc_2->addChild("Ид", $id);
		$doc_3 = $doc_2->addChild("ИдМагазин", $key);
		if(!empty($tovar['width']))
		{
			$doc_3 = $doc_2->addChild("Ширина", $tovar['width']);
		}
		if(!empty($tovar['height']))
		{
			$doc_3 = $doc_2->addChild("Высота", $tovar['height']);
		}
		if(!empty($tovar['depth']))
		{
			$doc_3 = $doc_2->addChild("Глубина", $tovar['depth']);
		}
		if(!empty($tovar['weight']))
		{
			$doc_3 = $doc_2->addChild("Вес", $tovar['weight']);
		}
		$available_for_order = (isset($tovar['available_for_order']))?$tovar['available_for_order']:0;
		$doc_3 = $doc_2->addChild("ДоступенКПродаже", $available_for_order);
		$doc_3 = $doc_2->addChild("ВидимостьНаСайте", $tovar['visibility']);
		if(!empty($tovar['ean13']))
		{
			$doc_3 = $doc_2->addChild("Штрихкод", $tovar['ean13']);
		}
		if(!empty($tovar['reference']))
		{
			$doc_3 = $doc_2->addChild("Артикул", $tovar['reference']);
		}
		if(!empty($tovar['id_manufacturer']))
		{
			$manufacturer_xml = Db::getInstance()->GetValue('SELECT `xml` FROM `'._DB_PREFIX_.'manufacturer` WHERE `id_manufacturer` = '.$tovar['id_manufacturer'].' ');
			$doc_3            = $doc_2->addChild("Изготовитель");
			$doc_4            = $doc_3->addChild("Ид", $manufacturer_xml);
			$doc_4            = $doc_3->addChild("Наименование", xmlentities($tovar['manufacturer_name']));
		}
		$doc_3 = $doc_2->addChild("Наименование", xmlentities($tovar['name']));
		$doc_3 = $doc_2->addChild("БазоваяЕдиница", "шт");
		$doc_3->addAttribute("Код", "796");
		$doc_3->addAttribute("НаименованиеПолное", "Штука");
		$doc_3->addAttribute("МеждународноеСокращение", "PCE");
		$doc_4 = $doc_3->addChild("Пересчет");
		$doc_5 = $doc_4->addChild("Единица", "шт");
		$doc_5 = $doc_4->addChild("Коэффициент", 1);
		$doc_5 = $doc_4->addChild("ДополнительныеДанные");
		$doc_6   = $doc_5->addChild("ЗначениеРеквизита");
		$doc_7   = $doc_6->addChild("Наименование", "Объем");
		$doc_7   = $doc_6->addChild("Значение", 0);
		$doc_3   = $doc_2->addChild("Группы");
		$cat_def = Db::getInstance()->GetValue('
			SELECT cat.`xml`
			FROM `'._DB_PREFIX_.'category` cat
			LEFT JOIN `'._DB_PREFIX_.'product` p ON p.`id_product` = ' . $tovar['id_product'] .'
			WHERE p.`id_category_default` = cat.`id_category`
			');
		$list    = Db::getInstance()->ExecuteS('
			SELECT cat.`id_category`, cat.`xml`, cp.`id_category`, cp.`id_product`
			FROM `'._DB_PREFIX_.'category` cat
			LEFT JOIN `'._DB_PREFIX_.'category_product` cp ON cp.`id_category` = cat.`id_category`
			WHERE cp.`id_category` !=481 AND cp.`id_product`= ' . $tovar['id_product'] . '

			');
		$new_cats= array();

		$new_cats[] = $cat_def;

		foreach($list as $cat)
		{
			if($cat['xml'] <> $cat_def){
				$new_cats[] =($cat['id_category'] ==2)?'00000000-0000-0000-0000-000000000000':$cat['xml'];
				//$new_cats[] = $cat['xml'];
			}

		}

		foreach($new_cats as $cat_guid)
		{
			$doc_4 = $doc_3->addChild("Ид",$cat_guid);
		}

		$images= Image::getImages($lang, $tovar['id_product']);
		foreach($images as $id_image){
			$image     = new Image($id_image['id_image']);
			$im_xml = Db::getInstance()->GetValue('
			SELECT `xml`
			FROM `'._DB_PREFIX_.'image`
			WHERE `id_image` = '.(int)$id_image['id_image']
			);
			// get image full URL
			$image_url = _PS_BASE_URL_._THEME_PROD_DIR_.$image->getExistingImgPath().".jpg";
			if (file_exists(_PS_PROD_IMG_DIR_.$image->getExistingImgPath().".jpg")){
			$doc_3     = $doc_2->addChild("Картинка" );
			$doc_4     = $doc_3->addChild("URL", $image_url );
			$doc_4     = $doc_3->addChild("ИдКартинки", $im_xml );
		}
		}


		$doc_3 = $doc_2->addChild("ЗначенияСвойств");
		$feat  = Db::getInstance()->ExecuteS('
			SELECT
			fp.`id_feature`,
			fp.`id_feature_value`,
			f.`id_feature`,
			f.`xml`,
			fvl.`id_feature_value`,
			fvl.`value`,
			fvl.`xml` as xmlv,
			fp.`id_product`

			FROM `'._DB_PREFIX_.'feature_product` fp
			LEFT JOIN `'._DB_PREFIX_.'feature` f ON f.`id_feature` = fp.`id_feature`
			LEFT JOIN `'._DB_PREFIX_.'feature_value_lang` fvl ON fvl.`id_feature_value` = fp.`id_feature_value`
			WHERE  fp.`id_product` = '.$tovar['id_product'].' AND fvl.`id_lang` = "'.Configuration::get('_ecm_csync_lang_').'"

			');
		if(!empty($feat))
		{
			foreach($feat as $featl)
			{
				if(!empty($featl))
				{
					$doc_4 = $doc_3->addChild("ЗначениеСвойства");
					$doc_5 = $doc_4->addChild("Ид", xmlentities($featl['xml']));
					$doc_5 = $doc_4->addChild("Значение", xmlentities($featl['xmlv']));
				}
			}
		}
		$doc_3 = $doc_2->addChild("СтавкиНалогов");
		$doc_4 = $doc_3->addChild("СтавкаНалога");
		$doc_5 = $doc_4->addChild("Наименование", "НДС");
		$doc_5 = $doc_4->addChild("Ставка", "Без НДС");
		$doc_3 = $doc_2->addChild("ЗначенияРеквизитов");
		$doc_4 = $doc_3->addChild("ЗначениеРеквизита");
		$doc_5 = $doc_4->addChild("Наименование", "ВидНоменклатуры");
		$doc_5 = $doc_4->addChild("Значение", "товар");
		$doc_4 = $doc_3->addChild("ЗначениеРеквизита");
		$doc_5 = $doc_4->addChild("Наименование", "ТипНоменклатуры");
		$doc_5 = $doc_4->addChild("Значение", "товар");

		if(!empty($tovar['name']))
		{
			$doc_4 = $doc_3->addChild("ЗначениеРеквизита");
			$doc_5 = $doc_4->addChild("Наименование", "НаименованиеПолное");
			$doc_5 = $doc_4->addChild("Значение", xmlentities($tovar['name']));
		}
		if(!empty($tovar['description_short']))
		{
			$doc_4 = $doc_3->addChild("ЗначениеРеквизита");
			$doc_5 = $doc_4->addChild("Наименование", "Описание");
			$doc_5 = $doc_4->addChild("Значение", xmlentities($tovar['description_short']));
		}
		if(!empty($tovar['description']))
		{
			$doc_4 = $doc_3->addChild("ЗначениеРеквизита");
			$doc_5 = $doc_4->addChild("Наименование", "ОписаниеВФорматеHTML");
			$doc_5 = $doc_4->addChild("Значение", xmlentities($tovar['description']));
		}
		if(!empty($tovar['meta_description']))
		{
			$doc_4 = $doc_3->addChild("ЗначениеРеквизита");
			$doc_5 = $doc_4->addChild("Наименование", "МетаОписание");
			$doc_5 = $doc_4->addChild("Значение", xmlentities($tovar['meta_description']));
		}
		if(!empty($tovar['meta_keywords']))
		{
			$doc_4 = $doc_3->addChild("ЗначениеРеквизита");
			$doc_5 = $doc_4->addChild("Наименование", "МетаКлючевыеСлова");
			$doc_5 = $doc_4->addChild("Значение", xmlentities($tovar['meta_keywords']));
		}
		if(!empty($tovar['meta_title']))
		{
			$doc_4 = $doc_3->addChild("ЗначениеРеквизита");
			$doc_5 = $doc_4->addChild("Наименование", "МетаЗаголовок");
			$doc_5 = $doc_4->addChild("Значение", xmlentities($tovar['meta_title']));
		}
		if($tovar['active'] != 1){

			$doc_3 = $doc_2->addChild("Статус", "Удален");
		}
	}
	$doc_1 = $doc->addChild("ДополнительныеСведения");
	foreach($products as $tovar)
	{
		$prod_acs = Db::getInstance()->ExecuteS('
			SELECT
			`id_product_2`
			FROM `'._DB_PREFIX_.'accessory`
			WHERE  `id_product_1` = '.$tovar['id_product']
		);
		$prod_tags= Db::getInstance()->ExecuteS('
			SELECT
			t.`name`
			FROM `'._DB_PREFIX_.'product_tag` pt
			LEFT JOIN `'._DB_PREFIX_.'tag` t ON t.`id_tag` = pt.`id_tag`
			WHERE  pt.`id_product` = '.$tovar['id_product'].' AND t.`id_lang` = "'.Configuration::get('_ecm_csync_lang_').'"

			');
		$cpt      = count($prod_tags);
		$cpa      = count($prod_acs);
		if($cpt > 0 || $cpa > 0)
		{
			$doc_2 = $doc_1->addChild("Товар");
			$doc_3 = $doc_2->addChild("Ид",$tovar['xml'] );
			if($cpt > 0)
			{
				$doc_3 = $doc_2->addChild("Тэги");
				foreach($prod_tags as $tag)
				{
					$doc_4 = $doc_3->addChild("Тэг",$tag['name']);
				}

			}
			if($cpa > 0)
			{
				$doc_3 = $doc_2->addChild("СопутствующиеТовары");
				foreach($prod_acs as $acs)
				{
					$id    = CsyncTools::get("xml", "product", "id_product", $acs['id_product_2']);
					$doc_4 = $doc_3->addChild("Ид",$id);
				}

			}
		}
	}
	echo "Product: $ii done";
	$filename = "export".Tools::getValue('part').".xml";
}
if(Tools::getValue('type') == 'offers'){
	$first = Configuration::get('_first_');
	$timechange      = time();
	$no_spaces       = '<?xml version="1.0" encoding="utf-8"?><КоммерческаяИнформация ВерсияСхемы="2.05" ДатаФормирования="' . date( 'Y-m-d', $timechange) . 'T' . date( 'H:m:s',$timechange) .  '"></КоммерческаяИнформация>';
	$xml             = new SimpleXMLElement($no_spaces );
	$doc             = $xml->addChild("ПакетПредложений");
	$id              = CmlUid::create_guid('ПакетПредложений');
	$doc_1           = $doc->addChild("Ид",$id );
	$doc_1           = $doc->addChild("Наименование","Пакет предложений (Основной каталог товаров)" );
	$doc_1           = $doc->addChild("ИдКаталога",Configuration::get('1C_CAT_ID'));
	$doc_1           = $doc->addChild("ИдКлассификатора",Configuration::get('1C_CAT_CLASS'));
	$doc_1           = $doc->addChild("Владелец");
	$doc_2           = $doc_1->addChild("Ид",Configuration::get('1C_CAT_OVNER') );
	$doc_2           = $doc_1->addChild("Наименование",Configuration::get('PS_SHOP_NAME') );
	$doc_2           = $doc_1->addChild("ОфициальноеНаименование",Configuration::get('PS_SHOP_NAME') );
	$doc_1           = $doc->addChild("ТипыЦен");

	//Cгенерить ид закупочной цены
	Configuration::updateValue('1C_CAT_PRICE_WHO', CmlUid::create_guid('Закупочная'));
	Configuration::updateValue('1C_CAT_PRICE', CmlUid::create_guid('Розничная'));
	Configuration::updateValue('1C_CAT_PRICE_SALE', CmlUid::create_guid('Акционная'));
	$id_currency_get = Currency::getCurrency( Configuration::get('PS_CURRENCY_DEFAULT'));
	//$cprice          = Db::getInstance()->ExecuteS("SELECT * FROM `"._DB_PREFIX_."cuser`");
	$price_uid['price'] = Configuration::get('_price_');
	//foreach($cprice as $price_uid){
		//switch($price_uid['iso']){
		switch(2){
			case 1:
			$id_currency = $id_currency_get['iso_code'];
			break;
			case 2:
			$id_currency = $id_currency_get['sign'];
			break;
		}
		$doc_2 = $doc_1->addChild("ТипЦены");
		$doc_3 = $doc_2->addChild("Ид",Configuration::get('1C_CAT_PRICE_WHO'));
		$doc_3 = $doc_2->addChild("Наименование","Закупочная");
		$doc_3 = $doc_2->addChild("Валюта",$id_currency);

		$doc_2 = $doc_1->addChild("ТипЦены");
		$doc_3 = $doc_2->addChild("Ид",Configuration::get('1C_CAT_PRICE'));
		$doc_3 = $doc_2->addChild("Наименование","Розничная");
		$doc_3 = $doc_2->addChild("Валюта",$id_currency);

		$doc_2 = $doc_1->addChild("ТипЦены");
		$doc_3 = $doc_2->addChild("Ид",Configuration::get('1C_CAT_PRICE_SALE'));
		$doc_3 = $doc_2->addChild("Наименование","Акционная");
		$doc_3 = $doc_2->addChild("Валюта",$id_currency);

	//}

	$doc_1    = $doc->addChild("Предложения");
	$products = Product::getProducts($lang, 0, 0, 'id_product', 'ASC', false, false);
	//d ($products);
	foreach($products as $tovar){
		$id_lang   = Configuration::get('_ecm_csync_lang_');
		$id_prod   = $tovar['id_product'];
		$sql       = 'SELECT id_product_attribute, xml, price, reference, weight, ean13  FROM '._DB_PREFIX_.'product_attribute WHERE id_product = '.$id_prod;
		$attributs = Db::getInstance()->executeS($sql);
		//if ($id_prod == 1225)d($attributs);
		//Проверка на комбинации
		//d ($attributs);
		if(sizeof($attributs))
		{
			foreach($attributs as $attr)
			{
				$attr_c    = $attr['id_product_attribute'];
				$sql       = 'SELECT * FROM '._DB_PREFIX_.'product_attribute_combination WHERE id_product_attribute = '.$attr_c;
				$attr_c    = Db::getInstance()->executeS($sql);
				$attr_g = array();
				$attr_gg = array();
				foreach($attr_c as $attrc){
					$attr_l    = $attrc['id_attribute'];
					$sql       = 'SELECT id_attribute_group FROM '._DB_PREFIX_.'attribute WHERE id_attribute = '.$attr_l;

					$attr_l_    = Db::getInstance()->getValue($sql);
					if(!$attr_l_) continue;
					//$attr_g_    = $attr_l[0]['id_attribute_group'];
					$sql       = 'SELECT name FROM '._DB_PREFIX_.'attribute_group_lang WHERE id_attribute_group = '.$attr_l_.' AND id_lang = '.$id_lang;
					$attr_g[]    = Db::getInstance()->getValue($sql);
					//$attr_gg_   = $attr_l[0]['id_attribute'];
					$sql       = 'SELECT name FROM '._DB_PREFIX_.'attribute_lang WHERE id_attribute = '.$attr_l.' AND id_lang = '.$id_lang;
					$attr_gg[]   = Db::getInstance()->getValue($sql);
				}
				//if ($id_prod == 10001949)p($attr_g);
				//if ($id_prod == 10001949)p($attr_gg);
				$cena = @$tovar['price'] + @$attr['price'];
				$doc_2= $doc_1->addChild("Предложение");
				$doc_3= $doc_2->addChild("Ид",$attr['xml']);
				$doc_3 = $doc_2->addChild("ИдМагазин",$tovar['id_product']);
				$doc_3 = $doc_2->addChild("ИдМагазинКомбинация",$attr['id_product_attribute']);
								$sql = '
				SELECT id_image
				FROM `'._DB_PREFIX_.'product_attribute_image`
				WHERE `id_product_attribute` = '.$attr['id_product_attribute'];
				@$images = Db::getInstance()->ExecuteS($sql);
				if(sizeof(@$images))
				foreach($images as $image){
					@$guid_image = Db::getInstance()->getValue('
						SELECT xml
						FROM `'._DB_PREFIX_.'image`
						WHERE `id_image` = '.$image['id_image']);
						if(@$guid_image)
				$doc_3 = $doc_2->addChild("ИдКартинки",@$guid_image);
				}
				if($tovar['reference'])
				$doc_3 = $doc_2->addChild("АртикулТовара",xmlentities($tovar['reference']));
				if($attr['reference'])
				$doc_3 = $doc_2->addChild("АртикулКомбинации",xmlentities($attr['reference']));
				if($attr['weight'])
				$doc_3 = $doc_2->addChild("ВлияниеНаВес",$attr['weight']);
				if($attr['ean13']){
					$doc_3 = $doc_2->addChild("Штрихкод", $attr['ean13']);
				}
				$doc_3 = $doc_2->addChild("Наименование", xmlentities($tovar['name']));

				$doc_3 = $doc_2->addChild("БазоваяЕдиница", "шт");
				$doc_3->addAttribute("Код", "796");
				$doc_3->addAttribute("НаименованиеПолное", "Штука");
				$doc_3->addAttribute("МеждународноеСокращение", "PCE");
				@$c     = count($attr_g);
				//d($c);
				if($c > 0){
					$doc_3 = $doc_2->addChild("ХарактеристикиТовара");

				for($k = 0; $k <= (count($attr_g)-1); $k++)
				{
					$doc_4 = $doc_3->addChild("ХарактеристикаТовара");

					$doc_5 = $doc_4->addChild("Наименование", xmlentities($attr_g[$k]));
					$doc_5 = $doc_4->addChild("Значение", xmlentities($attr_gg[$k]));
				}
				}

				$doc_3 = $doc_2->addChild("Цены");
				$doc_4 = $doc_3->addChild("Цена");
				$doc_5 = $doc_4->addChild("Представление",$cena. " " .  $id_currency . " за шт");
				$doc_5 = $doc_4->addChild("ИдТипаЦены",Configuration::get('1C_CAT_PRICE'));
				$doc_5 = $doc_4->addChild("ЦенаЗаЕдиницу",$cena);
				$doc_5 = $doc_4->addChild("Валюта",$id_currency);
				$doc_5 = $doc_4->addChild("Единица","шт");
				$doc_5 = $doc_4->addChild("Коэффициент",1);

				if($tovar['wholesale_price'] != 0){
					$doc_4 = $doc_3->addChild("Цена");
					$doc_5 = $doc_4->addChild("Представление",$tovar['wholesale_price'] . " " .  $id_currency . " за шт");
					$doc_5 = $doc_4->addChild("ИдТипаЦены",Configuration::get('1C_CAT_PRICE_WHO'));
					$doc_5 = $doc_4->addChild("ЦенаЗаЕдиницу",$tovar['wholesale_price']);
					$doc_5 = $doc_4->addChild("Валюта",$id_currency);
					$doc_5 = $doc_4->addChild("Единица","шт");
					$doc_5 = $doc_4->addChild("Коэффициент",1);
				}

				$sql       = 'SELECT * FROM '._DB_PREFIX_.'specific_price WHERE id_product = '.$tovar['id_product'].' AND id_product_attribute = 0';
				$sale    = Db::getInstance()->executeS($sql);
				//d($sale);
				if($sale){
					if ($sale[0]['reduction_type'] == 'amount')
					$price = $cena - $sale[0]['reduction'];
					else
					$price = $cena - $cena*$sale[0]['reduction'];
					$doc_4 = $doc_3->addChild("Цена");
					$doc_5 = $doc_4->addChild("Представление",$price. " " .  $id_currency . " за шт");
					$doc_5 = $doc_4->addChild("ИдТипаЦены",Configuration::get('1C_CAT_PRICE_SALE'));
					$doc_5 = $doc_4->addChild("ЦенаЗаЕдиницу",$price);
					$doc_5 = $doc_4->addChild("Валюта",$id_currency);
					$doc_5 = $doc_4->addChild("Единица","шт");
					$doc_5 = $doc_4->addChild("Коэффициент",1);
				}
				$qvantity = StockAvailable::getQuantityAvailableByProduct($tovar['id_product'], $attr['id_product_attribute']);
				//p($qvantity);
				$doc_3 = $doc_2->addChild("Количество", (int)$qvantity);

			}
		}else{

		$doc_2 = $doc_1->addChild("Предложение");
		//if ( empty($tovar['xml']) ) $id = create_guid($tovar['name']);
		//else
		$id    = $tovar['xml'];
		//if ($tovar['id_product'] == 1) echo " < br > ".$_SERVER['SERVER_NAME']." < br > ".$tovar['name']." < br > ".$id;

		$doc_3 = $doc_2->addChild("Ид",$id);
		$doc_3 = $doc_2->addChild("ИдМагазин",$tovar['id_product']);
		if(!empty($tovar['ean13'])){
			$doc_3 = $doc_2->addChild("Штрихкод", $tovar['ean13']);
		}
		$doc_3 = $doc_2->addChild("Наименование", xmlentities(trim($tovar['name'])));
		$doc_3 = $doc_2->addChild("БазоваяЕдиница", "шт");
		$doc_3->addAttribute("Код", "796");
		$doc_3->addAttribute("НаименованиеПолное", "Штука");
		$doc_3->addAttribute("МеждународноеСокращение", "PCE");
		$doc_3 = $doc_2->addChild("Цены");
		$doc_4 = $doc_3->addChild("Цена");

		$price = $tovar['price'];
		if ($tovar['rate'] > 0) $price = round($price * (100+$tovar['rate'])/100);

		$doc_5 = $doc_4->addChild("Представление",(float)$price. " " .  $id_currency . " за шт");
		$doc_5 = $doc_4->addChild("ИдТипаЦены",Configuration::get('1C_CAT_PRICE'));


		$doc_5 = $doc_4->addChild("ЦенаЗаЕдиницу",(float)$price);
		$doc_5 = $doc_4->addChild("Валюта",$id_currency);
		$doc_5 = $doc_4->addChild("Единица","шт");
		$doc_5 = $doc_4->addChild("Коэффициент",1);
		if($tovar['wholesale_price'] != 0){
			$doc_4 = $doc_3->addChild("Цена");
			$doc_5 = $doc_4->addChild("Представление",(int)$tovar['wholesale_price'] . " " .  $id_currency . " за шт");
			$doc_5 = $doc_4->addChild("ИдТипаЦены",Configuration::get('1C_CAT_PRICE_WHO'));
			$doc_5 = $doc_4->addChild("ЦенаЗаЕдиницу",(int)$tovar['wholesale_price']);
			$doc_5 = $doc_4->addChild("Валюта",$id_currency);
			$doc_5 = $doc_4->addChild("Единица","шт");
			$doc_5 = $doc_4->addChild("Коэффициент",1);
		}
		$sql       = 'SELECT * FROM '._DB_PREFIX_.'specific_price WHERE id_product = '.$tovar['id_product'].' AND id_product_attribute = 0 AND from_quantity = 1';
				$sale    = Db::getInstance()->executeS($sql);
				if($sale){
					if ($sale[0]['reduction_type'] == 'amount')
					$sprice = $price - $sale[0]['reduction'];
					else
					$sprice = $price - $price*$sale[0]['reduction'];
					$doc_4 = $doc_3->addChild("Цена");
					$doc_5 = $doc_4->addChild("Представление",$sprice. " " .  $id_currency . " за шт");
					$doc_5 = $doc_4->addChild("ИдТипаЦены",Configuration::get('1C_CAT_PRICE_SALE'));
					$doc_5 = $doc_4->addChild("ЦенаЗаЕдиницу",$sprice);
					$doc_5 = $doc_4->addChild("Валюта",$id_currency);
					$doc_5 = $doc_4->addChild("Единица","шт");
					$doc_5 = $doc_4->addChild("Коэффициент",1);
				}
		$qvantity = StockAvailable::getQuantityAvailableByProduct($tovar['id_product']);
		//	$doc_3 = $doc_2->addChild("Количество", (int)$qvantity);
		$doc_3 = $doc_2->addChild("Количество",(int)$qvantity);



		}
	}
	//if($first == 1) CsyncTools::massupdate ("product_attribute", "xml", "id_product_attribute", $sqlchar, $sqlchar_idx);
	$filename = "offers.xml";
}
if(Tools::getValue('type') == 'sale')
{
	$timechange = time();
	$no_spaces  = '<?xml version="1.0" encoding="utf-8"?><КоммерческаяИнформация ВерсияСхемы="2.05" ДатаФормирования="' . date( 'Y-m-d', $timechange) . 'T' . date( 'H:m:s',$timechange) .  '"></КоммерческаяИнформация>';
	$xml        = new SimpleXMLElement($no_spaces );
	$doc        = $xml->addChild("Документ");
	$id         = CmlUid::create_guid('Документ');
	$doc_1      = $doc->addChild("Ид",$id );
	$doc_1      = $doc->addChild("Номер",0001 );
	$doc_1      = $doc->addChild("Дата",date( 'Y-m-d', $timechange) );
	$doc_1      = $doc->addChild("ХозОперация","Установка скидок");
	$doc_1      = $doc->addChild("Роль","Продавец");
	$doc_1      = $doc->addChild("Валюта","грн");


	$doc_1      = $doc->addChild("Время", date( 'H:m:s',$timechange) );



	$doc_1      = $doc->addChild("Скидки");
	$sql = "
	SELECT DISTINCT `from_quantity` FROM `" . _DB_PREFIX_ . "specific_price`
	WHERE `from_quantity` > 1
	";
	$from   = Db::getInstance()->ExecuteS($sql);
	//p($from);
	foreach($from as $step)
	{


				$doc_2= $doc_1->addChild("Скидка");
				$doc_3= $doc_2->addChild("УсловиеСкидки ","Количество товара превышает");
				$doc_3= $doc_2->addChild("ЗначениеУсловия ",$step['from_quantity']);
				$sql = "
				SELECT `id_product`,`reduction`, `reduction_type` FROM `" . _DB_PREFIX_ . "specific_price`
				WHERE `from_quantity` = ".$step['from_quantity']."
				";
				$products   = Db::getInstance()->ExecuteS($sql);
				//p($products);
				$doc_3= $doc_2->addChild("Товары");
				foreach ($products as $product){
					$doc_4= $doc_3->addChild("Товар");
					if($product['reduction_type'] == 'amount'){
						$sql = "
						SELECT `xml`,`price`FROM `" . _DB_PREFIX_ . "product`
						WHERE `id_product` = ".$product['id_product']."
						";
						$arr = Db::getInstance()->ExecuteS($sql);
						//p($arr);
						foreach($arr as $row){
							$guid = $row['xml'];
							$delta = 100-(($row['price']-$product['reduction'])*100/$row['price']);
						}


					}elseif($product['reduction_type'] == 'percentage'){
							$guid = CsyncTools::get("xml","product","id_product",$product['id_product']);
							$delta = $product['reduction']*100;
				}

				$doc_5= $doc_4->addChild("Ид",$guid);
				$doc_5= $doc_4->addChild("ИдСайт",$product['id_product']);
				$doc_5= $doc_4->addChild("Процент",$delta);

			}


	$filename = "sale.xml";
}
}
$fp   = fopen($filename, 'w+');
fwrite ( $fp , $xml->asXML());
fclose($fp);
$href = "http://".htmlspecialchars($_SERVER['HTTP_HOST'], ENT_COMPAT, 'UTF-8').__PS_BASE_URI__."modules/ecm_csync/$filename";
echo "<br><br>
Открыть в браузере: <a href='$href' target='_blank'>Открыть</a>
<br><br>
Скачать: <a href='$href' download>Скачать</a>
";

CmlUid::noCML ();

?>
