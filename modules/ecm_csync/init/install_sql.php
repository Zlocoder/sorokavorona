<?php
$sql = array();

		$sql[] ='ALTER TABLE `'._DB_PREFIX_.'accessory` ADD `xml` VARCHAR( 73 ) NOT NULL';
		$sql[] ='ALTER TABLE `'._DB_PREFIX_.'attribute` ADD `xml` VARCHAR( 36 ) NOT NULL, ADD INDEX (  `xml` )';
		$sql[] ='ALTER TABLE `'._DB_PREFIX_.'attribute_lang` ADD `xml` VARCHAR( 36 ) NOT NULL, ADD INDEX (  `xml` )';
		$sql[] ='ALTER TABLE `'._DB_PREFIX_.'attribute_lang` ADD `namegroup` VARCHAR( 73 ) NOT NULL';
		$sql[] ='ALTER TABLE `'._DB_PREFIX_.'attribute_group` ADD `xml` VARCHAR( 36 ) NOT NULL, ADD INDEX (  `xml` )';
		$sql[] ='ALTER TABLE `'._DB_PREFIX_.'attribute_group` ADD `type` VARCHAR( 36 ) NOT NULL';
		$sql[] ='ALTER TABLE `'._DB_PREFIX_.'attribute_group_lang` ADD `xml` VARCHAR( 36 ) NOT NULL, ADD INDEX (  `xml` )';
		$sql[] ='ALTER TABLE `'._DB_PREFIX_.'category` ADD `xml` VARCHAR( 36 ) NOT NULL';
		$sql[] ='ALTER TABLE `'._DB_PREFIX_.'category_product` ADD `xml_cp` VARCHAR( 330 ) NOT NULL';
		$sql[] ='ALTER TABLE `'._DB_PREFIX_.'category_product` ADD `xml_p` VARCHAR( 110 ) NOT NULL';
		if ( _PS_VERSION_ > "1.4.11.1")
		$sql[] ='ALTER TABLE `'._DB_PREFIX_.'cart_rule` ADD `id_pr` INT( 10 ) NOT NULL';
		$sql[] ='ALTER TABLE `'._DB_PREFIX_.'feature` ADD `numeric_` INT( 2 )';
		$sql[] ='ALTER TABLE `'._DB_PREFIX_.'feature` ADD `xml` VARCHAR( 36 ) NOT NULL';
		$sql[] ='ALTER TABLE `'._DB_PREFIX_.'feature_value`  ADD `xml` VARCHAR( 36 ) NOT NULL';
		$sql[] ='ALTER TABLE `'._DB_PREFIX_.'feature_value_lang`  ADD `xml` VARCHAR( 36 ) NOT NULL';
		$sql[] ='ALTER TABLE `'._DB_PREFIX_.'group`  ADD `xml` VARCHAR( 36 ) NOT NULL';
		$sql[] ='ALTER TABLE `'._DB_PREFIX_.'image` ADD `xml` VARCHAR( 73 ) NOT NULL';
		if ( _PS_VERSION_ < "1.6.1" && _PS_VERSION_ > "1.4.11.1") {
		$sql[] ='ALTER TABLE `'._DB_PREFIX_.'image_shop` ADD UNIQUE  `id_image_uniq` (  `id_image` )';
		}
		$sql[] ='ALTER TABLE `'._DB_PREFIX_.'manufacturer` ADD `xml` VARCHAR( 36 ) NOT NULL';
		$sql[] ='ALTER TABLE `'._DB_PREFIX_.'manufacturer` ADD UNIQUE  `UNIQUE` (  `name` )';
		$sql[] ='ALTER TABLE `'._DB_PREFIX_.'orders` ADD `send` TINYINT( 2 ) NOT NULL';
		$sql[] ='CREATE TABLE `'._DB_PREFIX_.'optprice` (id INT NOT NULL AUTO_INCREMENT PRIMARY KEY,name varchar(32)NOT NULL, guid varchar(36) NOT NULL ,main INT (2) NOT NULL) CHARACTER SET utf8 COLLATE utf8_general_ci';
		$sql[] ='ALTER TABLE `'. _DB_PREFIX_ .'optprice` AUTO_INCREMENT = 1 ';
		$sql[] ='ALTER TABLE `'._DB_PREFIX_.'product` ADD `xml_cp` VARCHAR( 330 ) NOT NULL';
		$sql[] ='ALTER TABLE `'._DB_PREFIX_.'product` ADD `xml` VARCHAR( 110 ) NOT NULL';
		$sql[] ='ALTER TABLE `'._DB_PREFIX_.'product` ADD INDEX  `xml` (  `xml` )';
		$sql[] ='ALTER TABLE `'._DB_PREFIX_.'product` ADD `status` TINYINT( 2 ) NOT NULL';
		$sql[] ='ALTER TABLE `'._DB_PREFIX_.'product` ADD `on_stock` TINYINT( 1 ) NOT NULL';
		$sql[] ='ALTER TABLE `'._DB_PREFIX_.'product` ADD `catalog_num` VARCHAR( 36 ) NULL';
		if ( _PS_VERSION_ > "1.4.11.1"){
		$sql[] ='ALTER TABLE `'._DB_PREFIX_.'product_shop` ADD `xml` VARCHAR( 110 ) NOT NULL';
		$sql[] ='ALTER TABLE `'._DB_PREFIX_.'product_shop` ADD INDEX  `xml` (  `xml` )';
		$sql[] ='ALTER TABLE `'._DB_PREFIX_.'product_shop` ADD `catalog_num` VARCHAR( 110 ) NULL';
		}
		$sql[] ='ALTER TABLE `'._DB_PREFIX_.'product_attribute` ADD `xml` VARCHAR( 330 ) NOT NULL';
		$sql[] ='ALTER TABLE `'._DB_PREFIX_.'product_attribute` ADD `xml_image` text ( 2048 ) NOT NULL';
		if ( _PS_VERSION_ > "1.4.11.1")
		$sql[] ='ALTER TABLE `'._DB_PREFIX_.'product_attribute_shop` ADD `xml` VARCHAR( 330 ) NOT NULL';
		$sql[] ='ALTER TABLE `'._DB_PREFIX_.'product_attribute` ADD INDEX  `xml` (  `xml` )';
		if ( _PS_VERSION_ > "1.4.11.1")
		$sql[] ='ALTER TABLE `'._DB_PREFIX_.'product_attribute_shop` ADD INDEX  `xml` (  `xml` )';
		$sql[] ='ALTER TABLE `'._DB_PREFIX_.'product_tag` ADD `prod_tag` VARCHAR( 110 ) NOT NULL';
		$sql[] ='ALTER TABLE `'._DB_PREFIX_.'specific_price` ADD `xml` VARCHAR( 330 ) NOT NULL';
		$sql[] ='ALTER TABLE `'._DB_PREFIX_.'specific_price` ADD  `from_admin` INT( 1 ) NOT NULL DEFAULT  1';
		$sql[] ='ALTER TABLE `'._DB_PREFIX_.'specific_price` ADD INDEX  `id_prod` (  `id_product` )';
		$sql[] ='ALTER TABLE `'._DB_PREFIX_.'supplier` ADD `xml` VARCHAR( 110 ) NOT NULL';
		$sql[] ='ALTER TABLE `'._DB_PREFIX_.'tag` ADD `xml` VARCHAR( 110 ) NOT NULL';
		if ( _PS_VERSION_ > "1.4.11.1"){
		$sql[] ='ALTER TABLE `'._DB_PREFIX_.'warehouse` MODIFY `reference` varchar(36)';
		$sql[] ='CREATE TABLE `'._DB_PREFIX_.'ecm_warehouse_location` (id INT NOT NULL AUTO_INCREMENT PRIMARY KEY,warehouse varchar(128)NOT NULL, id_product int(11) NOT NULL ,id_product_attribute int(11) NOT NULL, quantity int(11) NOT NULL,xml_ware varchar(36)NOT NULL) CHARACTER SET utf8 COLLATE utf8_general_ci';}


