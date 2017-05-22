<?php
include(dirname(__FILE__).'/../../config/config.inc.php');
require_once(dirname(__FILE__).'/../../init.php');
require_once(_PS_MODULE_DIR_.'ecm_csync/classes/CsyncTools.php');
function outputCSV($data)
{

	$outstream = fopen("php://output", 'w');

	function __outputCSV( & $vals, $key, $filehandler)
	{
		fputcsv($filehandler, $vals, ';', '"');
	}
	array_walk($data, '__outputCSV', $outstream);

	fclose($outstream);
}
$export = array();
$export[] = array("id товара Prestashop","Артикул","Наименование");
   $xml = simplexml_load_file(_PS_UPLOAD_DIR_."articles.xml");
	$i = 0;
   $prod = array();
   foreach($xml->Товар as $prod_xml)
   {
	//d($prod_xml);
   	$id = CsyncTools::get("id_product","product","reference",trim((string)$prod_xml->Артикул));
   	if(!$id){
		echo $prod_xml->Артикул."- нет на сайте </br>";
		++$i;}
	else{
	Db::getInstance()->Execute("
			UPDATE `"._DB_PREFIX_."product`
			SET `xml`='".$prod_xml->Ид."'
			WHERE `id_product`= '".$id."'
		");
	Db::getInstance()->Execute("
			UPDATE `"._DB_PREFIX_."product_shop`
			SET `xml`='".$prod_xml->Ид."'
			WHERE `id_product`= '".$id."'
		");
	}
   }
$sql = "
SELECT
p.`id_product`,
p.`reference`,
pl.`name`
FROM `ps_product` p
LEFT JOIN  `ps_product_lang` pl
ON p.`id_product` = pl.`id_product`
WHERE p.`xml` = '' AND p.`active` = 0
AND pl. `id_lang` = 1
";
//d($sql);
$arr = Db::getInstance()->ExecuteS($sql);

foreach($arr as $item){
$ref = trim($item['reference']);
$export[] = array($item['id_product'],"$ref",$item['name']);
	}

/*$filename = "not_active";

header("Content-type: text/csv");
header("Content-Disposition: attachment; filename={$filename}.csv");
header("Pragma: no-cache");
header("Expires: 0");
outputCSV($export);*/

echo "done!!";
?>
