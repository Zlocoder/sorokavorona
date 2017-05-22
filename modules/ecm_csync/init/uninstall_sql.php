<?php

$sql = array();

		$sql[] ='ALTER TABLE `'._DB_PREFIX_.'accessory` DROP `xml`';
		$sql[] ='ALTER TABLE `'._DB_PREFIX_.'attribute` DROP `xml`';
		$sql[] ='ALTER TABLE `'._DB_PREFIX_.'attribute_lang` DROP `xml`';
		$sql[] ='ALTER TABLE `'._DB_PREFIX_.'attribute_lang` DROP `namegroup` ';
		$sql[] ='ALTER TABLE `'._DB_PREFIX_.'attribute_group` DROP `xml`';
		$sql[] ='ALTER TABLE `'._DB_PREFIX_.'attribute_group` DROP `type`';
		$sql[] ='ALTER TABLE `'._DB_PREFIX_.'attribute_group_lang` DROP `xml`';
		$sql[] ='ALTER TABLE `'._DB_PREFIX_.'category` DROP `xml`';
		$sql[] ='ALTER TABLE `'._DB_PREFIX_.'category_product` DROP `xml_cp`';
		$sql[] ='ALTER TABLE `'._DB_PREFIX_.'category_product` DROP `xml_p`';
		if ( _PS_VERSION_ > "1.4.11.1")
		$sql[] ='ALTER TABLE `'._DB_PREFIX_.'cart_rule` DROP `id_pr`';
		$sql[] ='ALTER TABLE `'._DB_PREFIX_.'feature` DROP `numeric_`';
		$sql[] ='ALTER TABLE `'._DB_PREFIX_.'feature` DROP `xml`';
		$sql[] ='ALTER TABLE `'._DB_PREFIX_.'feature_value`  DROP `xml`';
		$sql[] ='ALTER TABLE `'._DB_PREFIX_.'feature_value_lang`  DROP `xml`';
		$sql[] ='ALTER TABLE `'._DB_PREFIX_.'group`  DROP `xml`';
		$sql[] ='ALTER TABLE `'._DB_PREFIX_.'image` DROP `xml`';
		if ( _PS_VERSION_ < "1.6.1") {
		$sql[] ='ALTER TABLE `'._DB_PREFIX_.'image_shop` DROP INDEX  `id_image_uniq`';
		}
		$sql[] ='ALTER TABLE `'._DB_PREFIX_.'manufacturer` DROP `xml`';
		$sql[] ='ALTER TABLE `'._DB_PREFIX_.'manufacturer` DROP INDEX  `UNIQUE`';
		$sql[] ='ALTER TABLE `'._DB_PREFIX_.'orders` DROP `send`';
		$sql[] ='DROP TABLE `'._DB_PREFIX_.'optprice`';
		$sql[] ='ALTER TABLE `'._DB_PREFIX_.'product` DROP `xml_cp`';
		$sql[] ='ALTER TABLE `'._DB_PREFIX_.'product` DROP `xml`';
		$sql[] ='ALTER TABLE `'._DB_PREFIX_.'product` DROP `status`';
		$sql[] ='ALTER TABLE `'._DB_PREFIX_.'product` DROP `on_stock`';
		$sql[] ='ALTER TABLE `'._DB_PREFIX_.'product` DROP `catalog_num`';
		if ( _PS_VERSION_ > "1.4.11.1"){
		$sql[] ='ALTER TABLE `'._DB_PREFIX_.'product_shop` DROP `xml`';
		$sql[] ='ALTER TABLE `'._DB_PREFIX_.'product_shop` DROP `catalog_num`';
		}
		$sql[] ='ALTER TABLE `'._DB_PREFIX_.'product_attribute` DROP `xml`';
		$sql[] ='ALTER TABLE `'._DB_PREFIX_.'product_attribute` DROP `xml_image`';
		if ( _PS_VERSION_ > "1.4.11.1")
		$sql[] ='ALTER TABLE `'._DB_PREFIX_.'product_attribute_shop` DROP `xml`';
		$sql[] ='ALTER TABLE `'._DB_PREFIX_.'product_tag` DROP `prod_tag`';
		$sql[] ='ALTER TABLE `'._DB_PREFIX_.'specific_price` DROP `xml`';
		$sql[] ='ALTER TABLE `'._DB_PREFIX_.'specific_price` DROP `from_admin`';
		$sql[] ='ALTER TABLE `'._DB_PREFIX_.'specific_price` DROP INDEX `id_prod`';
		$sql[] ='ALTER TABLE `'._DB_PREFIX_.'supplier` DROP `xml`';
		$sql[] ='ALTER TABLE `'._DB_PREFIX_.'tag` DROP `xml`';
		if ( _PS_VERSION_ > "1.4.11.1")
		$sql[] ='DROP TABLE `'._DB_PREFIX_.'ecm_warehouse_location`';
