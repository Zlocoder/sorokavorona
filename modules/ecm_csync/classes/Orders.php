<?php
class Orders
{
    public static
    function create_guid($namespace)
    {
        static $guid = '';
        $uid  = "";
        $data = $namespace;
        $data .= $_SERVER['SERVER_NAME'];
        $hash = strtolower(hash('md5', $uid . $guid . md5($data)));
        $guid = substr($hash,  0,  8).
        '-'.substr($hash,  8,  4).
        '-'.substr($hash, 12,  4).
        '-'.substr($hash, 16,  4).
        '-'.substr($hash, 20, 12);
        return $guid;
    }
    public static
    function list_attr ($id_product_attribute)
    {
        global $id_lang;
        return  Db::getInstance()->ExecuteS('
            SELECT al.`name` as `a_name`,agl.`name` as `ag_name` FROM
            `'._DB_PREFIX_.'product_attribute_combination` pac,
            `'._DB_PREFIX_.'attribute` a,
            `'._DB_PREFIX_.'attribute_lang` al,
            `'._DB_PREFIX_.'attribute_group_lang` agl
            WHERE
            pac.`id_attribute`=a.`id_attribute`
            AND a.`id_attribute`=al.`id_attribute` AND al.`id_lang`= "'.$id_lang.'"
            AND a.`id_attribute_group`=agl.`id_attribute_group` AND agl.`id_lang`= "'.$id_lang.'"
            AND pac.`id_product_attribute`='.$id_product_attribute
        );
    }
    public static
    function xmlentities($string)
    {
        return str_replace(array("&","<",">","\"","'"),
            array("&amp;","&lt;","&gt;","&quot;","&apos;"), $string);
    }

    public static
    function createzakaz()
    {
        global $id_lang,$phone_login, $delivery, $send_orders,$fo_statuses, $prefix,$curr,$order_date_min,$custom_curr,$p_data,$deliv;
        // p( $delivery);
        $timechange = time();
        $qwery      = '
        od.`id_order`,
        od.`id_carrier`,
        od.`date_add`,
        od.`total_paid`,
        od.`total_discounts`,
        od.`total_products`,
        od.`id_currency`,
        od.`total_shipping`,';
        if ( _PS_VERSION_ > "1.4.11.1")
        $qwery .= 'od. `current_state`,';
        $qwery .= '
        od.`payment`,
        ca.`name`,
        ad.`id_customer`,
        cu.`firstname`,
        cu.`lastname`,
        cu.`email`,
        oh.`id_order_state`,
        ad.`postcode`,
        ad.`city`,
        ';
        if ($phone_login)
        $qwery .= ('cu.`phone_login`,');
        else
        $qwery .= ('
            ad.`phone`,
            ad.`phone_mobile`,');
        if (sizeof($delivery))
        foreach ($delivery as $item => $name) {
            foreach ($name as $carrier => $installed) {
                if ($installed == 1) {
                    $qwery .= (
                        $item.'.`ware` AS ware'.$item.',
                        '.$item.'w.`number` AS number'.$item.',
                        '.$item.'w.`address` AS address'.$item.',
                        '.$item.'w.`city` AS city'.$item.',
                        '.$item.'w.`city_ref` AS city_ref'.$item.',
                        '.$item.'w.`area` AS area'.$item.',
                        '.$item.'w.`area_ref` AS area_ref'.$item.',');
                    if ($item == 'up')
                    $qwery .= (
                        $item.'w.`district` AS district'.$item.','
                    );
                }

            }
        }

        $qwery .= ('ad.`address1`');
        $sql = 'SELECT '.$qwery.'
        FROM `'._DB_PREFIX_.'orders` od
        LEFT JOIN `'._DB_PREFIX_.'address` ad ON ad.id_address = od.id_address_delivery
        LEFT JOIN `'._DB_PREFIX_.'customer` cu ON cu.id_customer = od.id_customer
        LEFT JOIN `'._DB_PREFIX_.'carrier` ca ON od.id_carrier = ca.id_carrier
        ';
        if (sizeof($delivery))
        foreach ($delivery as $item => $name) {
            foreach ($name as $carrier => $installed) {
                if ($installed == 1)
                $sql .= 'LEFT JOIN `'._DB_PREFIX_.$carrier.'_orders` '.$item.' ON '.$item.'.id_order = od.id_order
                LEFT JOIN `'._DB_PREFIX_.$carrier.'_warehouse` '.$item.'w ON '.$item.'w.ref = '.$item.'.ware
                ';
            }
        }
        $sql .= "JOIN `"._DB_PREFIX_."order_history` oh ON oh.id_order = od.id_order AND oh.id_order_history IN (SELECT MAX(`id_order_history`) FROM `"._DB_PREFIX_."order_history` GROUP BY `id_order`)";
        if ( _PS_VERSION_ > "1.4.11.1")
        $sql .= "WHERE od. current_state NOT IN (".$fo_statuses.")";
        else
        $sql .= "WHERE oh.id_order_state NOT IN (".$fo_statuses.")";
        $sql .= "AND DATE_FORMAT(od.date_add, '%Y-%m-%d') >= '".date('Y-m-d', strtotime($order_date_min))."'";

        if ($send_orders)
        $sql .= ' AND od. send !=1';
        $list      = Db::getInstance()->ExecuteS($sql);
        $no_spaces = '<?xml version="1.0" encoding="utf-8"?><КоммерческаяИнформация ВерсияСхемы="2.04" ДатаФормирования="' . date( 'Y-m-d', $timechange) . 'T' . date( 'H:m:s',$timechange) . '"></КоммерческаяИнформация>';
        $xml       = new SimpleXMLElement($no_spaces );
        foreach ($list as $zakazy) {
            if ($curr == 1)
            $val = Currency::getCurrency($zakazy['id_currency']);
            $iso_= ($curr == 1)?$val['iso_code']:$custom_curr;
            if (empty($iso_))CsyncTools::failure("Не установлено наименование валюты, для обмена заказами.\nПожалуйста почитайте базу знаний(http://support.elcommerce.com.ua/kb/faq.php?id=34)\nи установите в настройках модуля синхронизации наименование валюты");
            $doc = $xml->addChild("Документ");
            list($dt, $tm) = explode(" ", $zakazy['date_add']);
            $doc->addChild("Ид",             $prefix.$zakazy['id_order'] );
            $doc->addChild("Номер",             $prefix.$zakazy['id_order'] );
            $doc->addChild("Дата",             $dt);
            $doc->addChild("ХозОперация",         "Заказ товара");
            $doc->addChild("Роль",             "Продавец");
            $doc->addChild("Валюта",         $iso_);
            $doc->addChild("Курс",             "1");
            if (!empty($zakazy['total_discounts']) && $zakazy['total_discounts'] > 0) {
				if ( _PS_VERSION_ > "1.4.11.1") {

                    $order          = new Order($zakazy['id_order']);
                    $discounts      = $order->getCartRules();
                    $discounts_prod = array();
                    $discounts_value = array();
                    $id_products = array();
                    $discount_warning_p = array();
                    $discount_warning_d = array();
                    foreach ($discounts as $discount) {
                        $id_product_rule_group = Db::getInstance()->getValue('
                            SELECT id_product_rule_group
                            FROM '._DB_PREFIX_.'cart_rule_product_rule_group
                            WHERE id_cart_rule = '.(int)$discount['id_cart_rule'].'

                            ');
                        $id_product_rule = Db::getInstance()->getValue('
                            SELECT id_product_rule
                            FROM '._DB_PREFIX_.'cart_rule_product_rule
                            WHERE id_product_rule_group = '.(int)$id_product_rule_group.'

                            ');
                        $id_products = Db::getInstance()->Executes('
                            SELECT id_item
                            FROM '._DB_PREFIX_.'cart_rule_product_rule_value
                            WHERE id_product_rule = '.(int)$id_product_rule.'

                            ');
                        if (sizeof($id_products)) {
                            foreach ($id_products as $id_product) {
                                $discounts_prod[] = $id_product['id_item'];
                                $discounts_value[] = $discount ['value'];
                            }
                        }
                        else {

                            $doc1 = $doc->addChild("Скидки");
                            $doc2 = $doc1->addChild("Скидка");
                            $doc3 = $doc2->addChild("Сумма",$discount ['value']);
                            $doc3 = $doc2->addChild("УчтеноВСумме",'false');
                            $discount_warning_d[] = $discount ['value'];

                        }
                    }
                    if (sizeof(@$discounts_prod) && sizeof(@$discounts_value))
                    @$discounts_prod_value = array_combine($discounts_prod,$discounts_value);
                }else {
                    $doc1 = $doc->addChild("Скидки");
                    $doc2 = $doc1->addChild("Скидка");
                    $doc3 = $doc2->addChild("Сумма",$zakazy['total_discounts']);
                    $doc3 = $doc2->addChild("УчтеноВСумме",'false');
                }
            }
            $doc->addChild("Сумма",         $zakazy['total_products']);

            // Контрагенты
            $FIO      = self::xmlentities($zakazy['lastname'] . " " .     $zakazy['firstname']);
            $k1       = $doc->addChild('Контрагенты');
            //Покупатель
            $k1_1     = $k1->addChild('Контрагент');
            $customer = Configuration::get('_CUSTOMER_');
            if (empty($customer)) {
                $k1_2 = $k1_1->addChild("Ид"        ,    $prefix.$zakazy['id_customer']);
                $k1_2 = $k1_1->addChild("Наименование"        ,    $FIO);
                $k1_2 = $k1_1->addChild("ТелефонИд"        ,    $zakazy['phone_mobile']);
                $k1_2 = $k1_1->addChild("ПолноеНаименование",    $FIO);
                $k1_2 = $k1_1->addChild("Роль"                ,    "Покупатель");
                $k1_2 = $k1_1->addChild("Фамилия"            ,    self::xmlentities($zakazy['lastname']));
                $k1_2 = $k1_1->addChild("Имя"                ,    self::xmlentities($zakazy['firstname']));
                $k1_3 = $k1_1->addChild("АдресРегистрации");
                $k1_4 = $k1_3->addChild("Представление",$zakazy['postcode']." ".self::xmlentities($zakazy['city'])." ".self::xmlentities($zakazy['address1']) );
                if (!empty($zakazy['postcode'])) {
                    $k1_4 = $k1_3->addChild("АдресноеПоле");
                    $k1_5 = $k1_4->addChild("Тип","Почтовый индекс");
                    $k1_5 = $k1_4->addChild("Значение",$zakazy['postcode']);
                }
                if (!empty($zakazy['city'])) {
                    $k1_4 = $k1_3->addChild("АдресноеПоле");
                    $k1_5 = $k1_4->addChild("Тип","Город");
                    $k1_5 = $k1_4->addChild("Значение",self::xmlentities($zakazy['city']));
                }
                if (!empty($zakazy['address1'])) {
                    $k1_4 = $k1_3->addChild("АдресноеПоле");
                    $k1_5 = $k1_4->addChild("Тип","Улица");
                    $k1_5 = $k1_4->addChild("Значение",self::xmlentities($zakazy['address1']));
                }
                $k1_3 = $k1_1->addChild("АдресДоставки");
                $addr = $zakazy['address1'];
                $city = $zakazy['city'];
                $k1_4 = $k1_3->addChild("Перевозчик");
                $k1_5 = $k1_4->addChild("Наименование",$zakazy['name']);
                $k1_5 = $k1_4->addChild("ИдСайт",$zakazy['id_carrier']);
                if (sizeof($delivery)) {
                    foreach ($delivery as $item => $name) {
                        foreach ($name as $carrier => $installed) {
                            if ($installed == 1 && $zakazy['area'.$item] && $zakazy['city'.$item] && $zakazy['address'.$item]) {
                                $current_currier_ = CsyncTools::get("external_module_name","carrier","id_carrier", $zakazy['id_carrier']);
                                $current_currier  = ($current_currier_ == 'ecm_novaposhta')?'ecm_newpost':$current_currier_;
                                if ($current_currier == $carrier) {
                                    if (!empty($zakazy['ware'.$item]))
                                    $k1_5 = $k1_4->addChild("Ид",$carrier);
                                    $k1_5 = $k1_4->addChild("Область",self::xmlentities($zakazy['area'.$item])."   обл.");
                                    $k1_5 = $k1_4->addChild("ИдОбласти",$zakazy['area_ref'.$item]);
                                    $k1_5 = $k1_4->addChild("Город",self::xmlentities($zakazy['city'.$item]));
                                    $k1_5 = $k1_4->addChild("ИдГорода",$zakazy['city_ref'.$item]);
                                    if ($item == 'up')
                                    $k1_5 = $k1_4->addChild("Район",self::xmlentities($zakazy['district'.$item]));
                                    $k1_5 = $k1_4->addChild("Отделение",self::xmlentities("№".$zakazy['number'.$item]).", ".self::xmlentities($zakazy['address'.$item]));
                                    $k1_5 = $k1_4->addChild("ИдОтделения",$zakazy['ware'.$item]);
                                    $k1_5 = $k1_4->addChild("Представление", self::xmlentities($zakazy['area'.$item])." обл., ".self::xmlentities($zakazy['city'.$item]).", ".self::xmlentities($zakazy['address'.$item]));
                                    $addr = $zakazy['address'.$item];
                                    $city = $zakazy['city'.$item];
                                }

                            }
                        }
                    }
                }
                else {
                    $k1_4 = $k1_3->addChild("Представление",$zakazy['postcode']." ".self::xmlentities($zakazy['city'])." ".self::xmlentities($zakazy['address1']));
                    $addr = $zakazy['address1'];
                    $city = $zakazy['city'];
                }
                if (!empty($zakazy['postcode'])) {
                    $k1_4 = $k1_3->addChild("АдресноеПоле");
                    $k1_5 = $k1_4->addChild("Тип","Почтовый индекс");
                    $k1_5 = $k1_4->addChild("Значение",$zakazy['postcode']);
                }
                if (!empty($city)) {
                    $k1_4 = $k1_3->addChild("АдресноеПоле");
                    $k1_5 = $k1_4->addChild("Тип","Город");
                    $k1_5 = $k1_4->addChild("Значение",self::xmlentities($city));
                }
                if (!empty($addr)) {
                    $k1_4 = $k1_3->addChild("АдресноеПоле");
                    $k1_5 = $k1_4->addChild("Тип","Улица");
                    $k1_5 = $k1_4->addChild("Значение",self::xmlentities($addr));
                }
                $k1_3 = $k1_1->addChild("Контакты");
                $k1_4 = $k1_3->addChild("Контакт");
                $k1_5 = $k1_4->addChild("Тип","Почта");
                $k1_5 = $k1_4->addChild("Значение",$zakazy['email']);

                if (!empty($zakazy['phone']) || !empty($zakazy['phone_mobile']) || !empty($zakazy['phone_login'])) {
                    $k1_4 = $k1_3->addChild("Контакт");
                    $k1_5 = $k1_4->addChild("Тип","ТелефонРабочий");
                    if ($phone_login)
                    $k1_5 = $k1_4->addChild("Значение",$zakazy['phone_login']);
                    else
                    $k1_5 = $k1_4->addChild("Значение",$zakazy['phone']." ".$zakazy['phone_mobile']);
                }
            }
            else {
                $k1_2 = $k1_1->addChild("Ид"        ,    "Gdz18JHYiUWIY77EhXA582");
                $k1_2 = $k1_1->addChild("Наименование"        ,    self::xmlentities(Configuration::get('_CUSTOMER_')));
                $k1_2 = $k1_1->addChild("ПолноеНаименование",    self::xmlentities(Configuration::get('_CUSTOMER_')));
                $k1_2 = $k1_1->addChild("Роль"                ,    "Покупатель");
            }

            $doc->addChild("Время",         $tm);
            $id           = $zakazy['id_order'];
            $sqlm         = "
            SELECT
            ct.`id_order`,
            ct.`id_customer_thread`,
            cm.`id_customer_thread`,
            cm.id_employee,
            cm.`message`

            FROM `"._DB_PREFIX_."customer_thread` ct
            LEFT JOIN `"._DB_PREFIX_."customer_message` cm ON ct.id_customer_thread = cm.id_customer_thread
            WHERE ct.id_order = '$id'
            ";
            $messages_adm = array();
            $messages_cust = array();
            $mess = Db::getInstance()->ExecuteS($sqlm);

            foreach ($mess as $mesage) {
                switch ($mesage['id_employee']) {
                    case 1:
                    $messages_adm[] = $mesage['message'];
                    break;
                    case 0:
                    $messages_cust[] = $mesage['message'];
                    break;
                }
            }
            $messages_cust_s = implode(", ", $messages_cust);

            if ($p_data == 1)
            $doc->addChild ("Комментарий", "Адрес: ".self::xmlentities($zakazy['city'])." ".self::xmlentities($zakazy['address1']." \n"."Телефон: ".$zakazy['phone_mobile']." ".$zakazy['phone']." \n"."Почта: ".$zakazy['email'] ));
            elseif ($messages_cust_s)
            $doc->addChild ("Комментарий", self::xmlentities($messages_cust_s));


            $sql = '
            SELECT
            p.`id_product`,
            p.`reference` as preference,
            p.`xml`,
            pl.`name`,
            pa.`id_product_attribute`,
            pa.`reference`,
            pa.`xml` as xml_pa,
            o.`product_name`,
            o.`product_quantity`,';
            if ( _PS_VERSION_ > "1.4.11.1")
            $sql .= 'o.`original_product_price`,
            o.`reduction_percent`,
            o.`reduction_amount`,
            o.`reduction_amount_tax_incl`,
            o.`unit_price_tax_incl`,
            o.`product_quantity`*o.`original_product_price` as summ,
            o.`product_quantity`*o.`unit_price_tax_incl` as summ_u';
            else
            $sql .= 'o.`product_price`, o.`product_quantity`*o.`product_price` as summ';
            $sql .= ' FROM `'._DB_PREFIX_.'order_detail` o
            LEFT JOIN `'._DB_PREFIX_.'product` p ON(o.`product_id`=p.`id_product`)
            LEFT JOIN `'._DB_PREFIX_.'product_lang` pl ON p.`id_product`=pl.`id_product` AND pl.`id_lang`= "'.$id_lang.'"';
            if ( _PS_VERSION_ > "1.4.11.1")
            $sql .= ' AND pl.`id_shop`=1 ';

            $sql .= 'LEFT JOIN `'._DB_PREFIX_.'product_attribute` pa ON(pa.`id_product_attribute`=o.`product_attribute_id`)
            WHERE o.`id_order`='.$zakazy['id_order'].'
            ';
            $list_z = Db::getInstance()->ExecuteS($sql);
            // d($sql);
            //Товары
            $t1     = $doc->addChild('Товары');
            $cat_id = Configuration::get('1C_CAT_ID');
            foreach ($list_z as $razbor_zakaza_t) {
                //p($razbor_zakaza_t);
                $t1_1 = $t1->addChild('Товар');
                if (empty($razbor_zakaza_t['xml'])) CsyncTools::failure("Товар с наименованием ".$razbor_zakaza_t['product_name']."из заказа с Ид ".$zakazy['id_order']." отсуствует в БД магазина. Закройте этот заказ или удалите товар из заказа.");
                $t1_2 = $t1_1->addChild("Ид",($razbor_zakaza_t['xml_pa']?$razbor_zakaza_t['xml_pa']:$razbor_zakaza_t['xml']));
                $t1_2 = $t1_1->addChild("Наименование",            self::xmlentities($razbor_zakaza_t['name']));
                $t1_2 = $t1_1->addChild("Артикул",$razbor_zakaza_t['preference']);
                $t1_2 = $t1_1->addChild("БазоваяЕдиница", "шт");
                $t1_2->addAttribute("Код", "796");
                $t1_2->addAttribute("НаименованиеПолное", "Штука");
                $t1_2->addAttribute("МеждународноеСокращение", "PCE");
                $t1_2 = $t1_1->addChild("ЗначенияРеквизитов");
                $t1_3 = $t1_2->addChild("ЗначениеРеквизита");
                $t1_4 = $t1_3->addChild("Наименование","ВидНоменклатуры");
                $t1_4 = $t1_3->addChild("Значение","Товар");
                $t1_3 = $t1_2->addChild("ЗначениеРеквизита");
                $t1_4 = $t1_3->addChild("Наименование","ТипНоменклатуры");
                $t1_4 = $t1_3->addChild("Значение","Товар");
                if ($razbor_zakaza_t['id_product_attribute']) {
                    $ta_1   = $t1_1->addChild("ХарактеристикиТовара");

                    $list_a = self::list_attr($razbor_zakaza_t['id_product_attribute']);
                    if ($list_a) {
                        foreach ($list_a as $attributes) {
                            $ta_2 = $ta_1->addChild("ХарактеристикаТовара");
                            $ta_3 = $ta_2->addChild("Наименование",self::xmlentities($attributes['ag_name']));
                            $ta_3 = $ta_2->addChild("Значение",self::xmlentities($attributes['a_name']));
                        }
                    }
                }
                if ( _PS_VERSION_ > "1.4.11.1") {
                    //правило корзины

                    if (sizeof(@$discounts_prod_value) && array_key_exists($razbor_zakaza_t['id_product'] , $discounts_prod_value)) {
                        $t1_2 = $t1_1->addChild("Скидки");
                        $t1_3 = $t1_2->addChild("Скидка");
                        $t1_4 = $t1_3->addChild("Сумма",            $discounts_prod_value[$razbor_zakaza_t['id_product']] * $razbor_zakaza_t['product_quantity']);
                        $t1_4 = $t1_3->addChild("УчтеноВСумме","false");
                        $discount_warning_p[] = $discounts_prod_value[$razbor_zakaza_t['id_product']];
                    }

                    //Правило каталога и спец цена целое
                    if ((!empty($razbor_zakaza_t['reduction_amount']) && $razbor_zakaza_t['reduction_amount'] > 0) || (!empty($razbor_zakaza_t['reduction_percent']) && $razbor_zakaza_t['reduction_percent'] > 0)) {
						//p($razbor_zakaza_t['reduction_amount']);
						//d($razbor_zakaza_t['reduction_percent']);
                        $t1_2 = $t1_1->addChild("Скидки");
                        $t1_3 = $t1_2->addChild("Скидка");
                        if (!empty($razbor_zakaza_t['reduction_amount']) && $razbor_zakaza_t['reduction_amount'] > 0)
                        $t1_4 = $t1_3->addChild("Сумма",$razbor_zakaza_t['reduction_amount'] * $razbor_zakaza_t['product_quantity']);
                        if (!empty($razbor_zakaza_t['reduction_percent']) && $razbor_zakaza_t['reduction_percent'] > 0)
                        // d($razbor_zakaza_t['reduction_percent']);
                        $t1_4 = $t1_3->addChild("Сумма",            ($razbor_zakaza_t['original_product_price'] * $razbor_zakaza_t['reduction_percent'] / 100) * $razbor_zakaza_t['product_quantity']);
                        $t1_4 = $t1_3->addChild("УчтеноВСумме","false");
                    }
                }

                if ( _PS_VERSION_ > "1.4.11.1")
                $t1_2 = $t1_1->addChild("ЦенаЗаЕдиницу",        $razbor_zakaza_t['original_product_price']);
                else
                $t1_2 = $t1_1->addChild("ЦенаЗаЕдиницу",        $razbor_zakaza_t['product_price']);
                $t1_2 = $t1_1->addChild("Количество",            $razbor_zakaza_t['product_quantity']);
                $t1_2 = $t1_1->addChild("Сумма",                $razbor_zakaza_t['summ']);
            }
            // Доставка
            if ($zakazy['total_shipping'] > 0 && $deliv == 1) {
                $t1   = $t1->addChild ( 'Товар' );
                $t1->addChild ( "Ид", 'ORDER_DELIVERY');
                $t1->addChild ( "Наименование", 'Доставка');
                $t1_1 = $t1->addChild("БазоваяЕдиница", "шт");
                $t1_1->addAttribute("Код", "796");
                $t1_1->addAttribute("НаименованиеПолное", "Штука");
                $t1_1->addAttribute("МеждународноеСокращение", "PCE");
                $t1->addChild ( "ЦенаЗаЕдиницу", $zakazy['total_shipping']);
                $t1->addChild ( "Количество", 1 );
                $t1->addChild ( "Сумма", $zakazy['total_shipping']);
                $t1_2 = $t1->addChild ( "ЗначенияРеквизитов" );
                $t1_3 = $t1_2->addChild ( "ЗначениеРеквизита" );
                $t1_4 = $t1_3->addChild ( "Наименование", "ВидНоменклатуры" );
                $t1_4 = $t1_3->addChild ( "Значение", "Услуга" );
                $t1_3 = $t1_2->addChild ( "ЗначениеРеквизита" );
                $t1_4 = $t1_3->addChild ( "Наименование", "ТипНоменклатуры" );
                $t1_4 = $t1_3->addChild ( "Значение", "Услуга" );

            }

            $k1           = $doc->addChild('ЗначенияРеквизитов');
            $k1_1         = $k1->addChild('ЗначениеРеквизита');
            $k1_1_1       = $k1_1->addChild('Наименование','Метод оплаты');
            $k1_1_1       = $k1_1->addChild('Значение',$zakazy['payment']);
            $k1_1         = $k1->addChild("ЗначениеРеквизита");
            $k1_1_1       = $k1_1->addChild("Наименование","Метод доставки");
            $k1_1_1       = $k1_1->addChild("Значение",self::xmlentities($zakazy['name']));
            $k1_1         = $k1->addChild("ЗначениеРеквизита");
            $k1_1_1       = $k1_1->addChild("Наименование","Адрес");
            $k1_1_1       = $k1_1->addChild("Значение",$zakazy['postcode']." ".self::xmlentities($zakazy['city'])." ".self::xmlentities($zakazy['address1']));
            $id           = $zakazy['id_order'];
            $sql          = "
            SELECT
            ct.`id_order`,
            ct.`id_customer_thread`,
            cm.`id_customer_thread`,
            cm.id_employee,
            cm.`message`

            FROM `"._DB_PREFIX_."customer_thread` ct
            LEFT JOIN `"._DB_PREFIX_."customer_message` cm ON ct.id_customer_thread = cm.id_customer_thread
            WHERE ct.id_order = '$id'
            ";
            $messages_adm = array();
            $messages_cust = array();
            $mess = Db::getInstance()->ExecuteS($sql);
            foreach ($mess as $mesage) {
                switch ($mesage['id_employee']) {
                    case 1:
                    $messages_adm[] = $mesage['message'];
                    break;
                    case 0:
                    $messages_cust[] = $mesage['message'];
                    break;
                }
            }

            $messages_adm_s = implode(", ", $messages_adm);
            $messages_cust_s= implode(", ", $messages_cust);
            if (!empty($messages_adm_s)) {
                $k1_1   = $k1->addChild("ЗначениеРеквизита");
                $k1_1_1 = $k1_1->addChild("Наименование","Комментарий менеджера");
                $k1_1_1 = $k1_1->addChild("Значение",self::xmlentities($messages_adm_s));
            }
            if (!empty($zakazy['address1'])) {
                $k1_1   = $k1->addChild("ЗначениеРеквизита");
                $k1_1_1 = $k1_1->addChild("Наименование","Адрес доставки");
                $k1_1_1 = $k1_1->addChild("Значение", self::xmlentities($city)." ".self::xmlentities($addr));
            }
            if (!empty($messages_cust_s)) {
                $k1_1   = $k1->addChild("ЗначениеРеквизита");
                $k1_1_1 = $k1_1->addChild("Наименование","Комментарий клиента");
                $k1_1_1 = $k1_1->addChild("Значение",self::xmlentities($messages_cust_s));
            }
            $k1_1   = $k1->addChild("ЗначениеРеквизита");
            $k1_1_1 = $k1_1->addChild("Наименование","Покупатель, контакты");
            $k1_1_1 = $k1_1->addChild("Значение",self::xmlentities($zakazy['lastname'])." ".self::xmlentities($zakazy['firstname']).", ".$zakazy['phone']." ".$zakazy['phone_mobile']);
            $k1_1   = $k1->addChild('ЗначениеРеквизита');
            $k1_1_1 = $k1_1->addChild('Наименование','Статус заказа');
            if ( _PS_VERSION_ > "1.4.11.1")
            $status = Db::getInstance()->getValue("SELECT `name` FROM `"._DB_PREFIX_."order_state_lang` WHERE `id_order_state`= '".$zakazy['current_state']."' AND `id_lang`= ".$id_lang."  ");
            else
            $status = Db::getInstance()->getValue("SELECT `name` FROM `"._DB_PREFIX_."order_state_lang` WHERE `id_order_state`= '".$zakazy['id_order_state']."' AND `id_lang`= ".$id_lang."  ");

            $k1_1_1 = $k1_1->addChild('Значение', $status);
        }


        header("Content-type: text/xml; charset=windows-1251");
        print (iconv('UTF-8','WINDOWS-1251//IGNORE',$xml->asXML()));
        if (Configuration::get('_SEND_ORDERS_') == 1)
        foreach ($list as $orders) {
            Db::getInstance()->Execute("
                UPDATE `" . _DB_PREFIX_ . "orders`
                SET

                `send`= 1

                WHERE  `id_order`= " . $orders['id_order']);
        }

    }
    public static function updateStatus($file_name, $id_employee)
    {
        global $fod,$fods,$id_lang,$prefix,$status_export;
        $xml = simplexml_load_file($file_name);
        $doc = array();
        foreach ($xml->Документ as $docs) {
            if (!$docs->Номер) continue;

            if ($prefix) {
                $id = explode("-",$docs->Номер);
                if ($id[0] == substr($prefix, 0, - 1))
                $num = $id[1];
                else continue;
            }else {
                $num = (int)$docs->Номер;
            }

            //$num = (int)$docs->Номер;
            $order = Db::getInstance()->ExecuteS("
                SELECT `reference` FROM `"._DB_PREFIX_."orders` WHERE `id_order` = '".$num."'
                ");
            if (!empty($order)) {
                if ($fod == 1) {

                    Db::getInstance()->Execute("
                            DELETE FROM `"._DB_PREFIX_."order_detail` WHERE `id_order` = '".$num."'
                            ");
                    $skidka = 0;

                    foreach ($docs->Товары->Товар as $tovar) {
                        //shipping
                        $shipping = 0;
                        if ($tovar->Наименование == "Доставка") {
                            $shipping = $tovar->ЦенаЗаЕдиницу;
                            continue;

                        }
                        $prise = (float)$tovar->ЦенаЗаЕдиницу;
                        if (!empty($tovar->Скидки->Скидка->Сумма)) {
                            $skidka = $skidka + (float)$tovar->Скидки->Скидка->Сумма;
                           // d($skidka);
                            if ($tovar->Скидки->Скидка->УчтеноВСумме == "true")
                           $summa = @$summa + $skidka;
                            if ($skidka < 0) {
                                $prise = $tovar->ЦенаЗаЕдиницу - $skidka;
                                $skidka= 0;
                            }

                        }
                        $kol        = $tovar->Количество;
                        $prod_id_l            = explode("#", $tovar->Ид);
                        $prod_id              = CsyncTools::get("id_product","product","xml",$prod_id_l[0]);
                        $attr_id              = CsyncTools::get("id_product_attribute","product_attribute","xml",$tovar->Ид);
                        $product_attribute_id = $attr_id?$attr_id:0;
						$prod_name            = Db::getInstance()->getValue("
                            SELECT `name` FROM `"._DB_PREFIX_."product_lang` WHERE `id_product` = '".$prod_id."' AND `id_lang` = ".$id_lang);
                        if (!empty($prod_id)){
						if($product_attribute_id !=0)
                        $list_a = self::list_attr($product_attribute_id);
                   		if (@$list_a){
                   		//d($list_a)	;
                   		$attr_name = array();
                        foreach ($list_a as $attributes) {
                           $attr_name[] = $attributes['ag_name']." : ".$attributes['a_name'];
                        }
                        $prod_name = $prod_name." - ".implode(",",$attr_name);
                   		}
							$referense = ($product_attribute_id>0)?CsyncTools::get("reference","product_attribute","id_product_attribute",$product_attribute_id):$tovar->Артикул;
							 Db::getInstance()->Execute("
                            INSERT IGNORE INTO `"._DB_PREFIX_."order_detail` (
                            `id_order`,
                            ".(( _PS_VERSION_ < "1.4.11.1")?'':"
                                `id_shop`,
                                ")."
                            `product_id`,
                            `product_attribute_id`,
                            `product_name`,
                            `product_quantity`,
                            `product_reference`,
                            `product_supplier_reference`
                            ".(( _PS_VERSION_ < "1.4.11.1")?'':"
                                ,
                                `total_price_tax_incl`,
                                `total_price_tax_excl`,
                                `unit_price_tax_incl`,
                                `unit_price_tax_excl`,
                                `original_product_price`

                                ")."
                            )
                            VALUES (
                            '".$num."',
                            ".(( _PS_VERSION_ < "1.4.11.1")?'':"
                                1,
                                ")."
                            ".$prod_id.",
                            ".$product_attribute_id.",
                            '".pSQL($prod_name)."',
                            '".$tovar->Количество."',
                            '".pSQL($referense)."',
                            '".pSQL($referense)."'
                            ".(( _PS_VERSION_ < "1.4.11.1")?'':"
                                ,
                                '".(float)($tovar->Количество * (float)$prise)."',
                                '".(float)($tovar->Количество * (float)$prise)."',
                                '".(float)$prise."',
                                '".(float)$prise."',
                                '".(float)$prise."'
                                ")."
                            )
                            ");
						}

					$attr_name = array();
					$list_a = array();
					unset($referense);
                    }
                    //$summa = $docs->Сумма - $shipping;
                    $summa = (float)$docs->Сумма;
                    $total_products = $summa + $skidka - $shipping;
                    $sql            = "
                    UPDATE `"._DB_PREFIX_."orders`
                    SET     `total_discounts`='$skidka',
                    `total_paid`='$summa',
                    `total_paid_real`='$summa',
                    `total_products`='$total_products',
                    `total_products_wt`='$total_products',
                    `date_upd`= NOW()
                    ".(( _PS_VERSION_ < "1.4.11.1")?'':"
                        ,
                        `total_discounts_tax_excl`='$skidka',
                        `total_discounts_tax_incl`='$skidka',
                        `total_paid_tax_excl`='$summa',
                        `total_shipping`='$shipping',
                        `total_shipping_tax_incl`='$shipping',
                        `total_shipping_tax_excl`='$shipping',
                        `total_paid_tax_incl`='$summa'
                        ")."

                    WHERE `id_order`= '".$num."'";
                    Db::getInstance()->Execute($sql);
                    $sql            = "
                    UPDATE `"._DB_PREFIX_."order_carrier`
                    SET     `shipping_cost_tax_excl`='$shipping',
                    		`shipping_cost_tax_incl`='$shipping'
                    WHERE `id_order`= ".$num;
                    Db::getInstance()->Execute($sql);

                }
                foreach ($docs->ЗначенияРеквизитов->ЗначениеРеквизита as $rec)
                $doc[$num]["$rec->Наименование"] = (string)$rec->Значение;
                $id_order_history = Db::getInstance()->getValue('
                    SELECT `id_order_state`
                    FROM `'._DB_PREFIX_.'order_history`
                    WHERE `id_order` = '.(int)$num.'
                    ORDER BY `date_add` DESC, `id_order_history` DESC');
                $history = new OrderHistory();
                $history->id_order = (int)$num;
                $history->id_employee = (int)$id_employee;
                if ($fods != 1) {
                    if ((@$doc[$num]["ПометкаУдаления"] == 'true') || (@$doc[$num]["Проведен"] == 'true') || (!empty($doc[$num]["Дата отгрузки по 1С"])) || (!empty($doc[$num]["Номер оплаты по 1С"])) || (!empty($doc[$num]["Дата оплаты по 1С"]))) {
                        if (Validate::isLoadedObject(new Order($num))) {

                            if (@$doc[$num]["ПометкаУдаления"] == 'true') {
                                //Отменен
                                if ( $id_order_history != _PS_OS_CANCELED_) {
                                    $history->changeIdOrderState(_PS_OS_CANCELED_, $num);
                                    $history->addWithemail();
                                    continue;
                                }
                            }
                            elseif (@$doc[$num]["Отменен"] == 'true') {
                                //Отменен
                                if ( $id_order_history != _PS_OS_CANCELED_) {
                                    $history->changeIdOrderState(_PS_OS_CANCELED_, $num);
                                    $history->addWithemail();
                                    continue;
                                }
                            }
                            elseif (((!empty($doc[$num]["Номер оплаты по 1С"])) AND (!empty($doc[$num]["Дата отгрузки по 1С"]))) OR
                                ((!empty($doc[$num]["Дата оплаты по 1С"])) AND (!empty($doc[$num]["Дата отгрузки по 1С"])))) {
                                if ( $id_order_history != 5) {
                                    $history->changeIdOrderState(5, $num);
                                    $history->addWithemail();
                                }
                            }
                            elseif (!empty($doc[$num]["Дата отгрузки по 1С"])) {
                                //Отправлен
                                if ( $id_order_history != 4) {
                                    $history->changeIdOrderState(4, $num);
                                    $history->addWithemail();
                                }
                            }

                            elseif ((!empty($doc[$num]["Номер оплаты по 1С"])) OR (!empty($doc[$num]["Дата оплаты по 1С"]))) {
                                //Оплачен
                                if ( $id_order_history != 2) {
                                    $history->changeIdOrderState(2, $num);
                                    $history->addWithemail();
                                }
                            }
                            elseif ((!empty($doc[$num]["Проведен"]) AND (empty($doc[$num]["Дата отгрузки по 1С"])) AND (empty($doc[$num]["Номер оплаты по 1С"]))) OR (!empty($doc[$num]["Проведен"]) AND (empty($doc[$num]["Дата отгрузки по 1С"])) AND (empty($doc[$num]["Дата оплаты по 1С"])))) {
                                //Оплачен
                                if ( $id_order_history != 3) {
                                    $history->changeIdOrderState(3, $num);
                                    $history->addWithemail();
                                }
                            }
                        }
                    }
                }else {
                    if (isset($doc[$num]['ПометкаУдаления']) && $doc[$num]['ПометкаУдаления'] == 'true') {
                        //Отменен
                        if ( $id_order_history != _PS_OS_CANCELED_) {
                            $history->changeIdOrderState(_PS_OS_CANCELED_, $num);
                            $history->addWithemail();
                            continue;
                        }
                    }
                    elseif (isset($doc[$num]['Отменен']) && $doc[$num]['Отменен'] == 'true') {
                        //Отменен
                        if ( $id_order_history != _PS_OS_CANCELED_) {
                            $history->changeIdOrderState(_PS_OS_CANCELED_, $num);
                            $history->addWithemail();
                            continue;
                        }
                    }
                    else {

                        $status = CsyncTools::get("id_order_state","order_state_lang","name",pSQL($doc[$num]['Статус заказа']));

                        if (!empty($status)) {
                            if (Validate::isLoadedObject(new Order($num))) {

                                if ($status != $id_order_history) {
                                    $history->changeIdOrderState($status, $num);
                                    $history->addWithemail();
                                }



                            }
                        }
                    }
                }
            }


            unset($skidka);
            unset($summa);
            unset($shipping);
        }


        //Удаляем файл со статусами заказов

        if (file_exists($file_name) && $status_export == 1)
        unlink($file_name);


    }


    public static
    function exchange()
    {
        global $employee;
        $otvet = CsyncTools::loadfile();
        if ($otvet) {
            self::updateStatus(_PS_UPLOAD_DIR_ .  Tools::getValue('filename'),$employee);
            print "success\n";
        }
        else {
            print "error " . $otvet;
        }
    }



}

