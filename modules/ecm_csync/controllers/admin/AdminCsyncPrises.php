<?php
include_once(dirname(__FILE__).'/../../ecm_csync.php');
class AdminCsyncPrisesController extends AdminController
{
	public $log;
	public function __construct()
	{
		$this->bootstrap = true;
		parent::__construct();
	}
	public static function getGroups($id_lang, $id_shop = false)
    {
        $shop_criteria = '';
        if ($id_shop) {
            $shop_criteria = Shop::addSqlAssociation('group', 'g');
        }

        return Db::getInstance(_PS_USE_SQL_SLAVE_)->executeS('
		SELECT DISTINCT g.`id_group`, g.`reduction`, g.`price_display_method`,g.`xml`, gl.`name`
		FROM `'._DB_PREFIX_.'group` g
		LEFT JOIN `'._DB_PREFIX_.'group_lang` AS gl ON (g.`id_group` = gl.`id_group` AND gl.`id_lang` = '.(int)$id_lang.')
		'.$shop_criteria.'
		ORDER BY g.`id_group` ASC');
    }
    public static
    function drawselect()
    {
		$groups = self::getGroups(Configuration::get('_ecm_csync_lang_'));
		$prises = Db::getInstance()->executeS("SELECT `name` ,`guid`,`id` FROM `"._DB_PREFIX_."optprice` WHERE `main` != 2");
		$html ='
		<div id="groups">
		';
		foreach ($groups as $group){
			$id_group = $group['id_group'];
			$name = $group['name'];
			$html.="<div class ='group' id='group-name-$id_group' ><b>$name</b></div>
				<select name='prise'  class='$id_group' id='group-id-$id_group'>
				<option id ='$id_group-none' value='none'";
			if ($group['xml'] =='none')
			$html.= "selected='selected'";
			$html.= ">Не привязывать данную группу</option>";
			foreach ($prises as $prise) {
					$id = $prise['id'];
					$name_prise = $prise['name'];
					$guid = $prise['guid'];
				$html.= "<option id ='$id_group-$guid' value='$guid'";
				if($group['xml'] ==$guid)
				$html.= "selected='selected'";
				$html.= ">$name_prise</option>";
				}
				$html.= "</select>";
			}
		$html.= "</div>";
		return $html;
	}
	public static
    function drawtable($edid = 0)
    {

		$spec_off  = Configuration::get('_ecm_csync_spec_price_off_');
		$hidden = ($spec_off && $spec_off ==1)?'style="display: none"':'';
		$prises = "SELECT * FROM `"._DB_PREFIX_."optprice` WHERE 1=1 ORDER BY `id`";
        $prev = Db::getInstance()->getValue("SELECT `id` FROM `"._DB_PREFIX_."optprice` WHERE `main` = 1");
        $sale = Db::getInstance()->getValue("SELECT `id` FROM `"._DB_PREFIX_."optprice` WHERE `main` = 2");
        $prices = Db::getInstance()->ExecuteS($prises);
        $edname = '';
        $edguid = '';
        $id     = 0;
        $html   = "
        <table class='table configuration'>
        <thead>
        <tr><th></th><th>Наименование</th><th>GUID</th><th><span class='label-tooltip' data-toggle='tooltip' title='' data-original-title='Цена которая отображается на сайте для пользователей, которые не входят в группы пользователей со специальной ценовой политикой'>Основная цена</th><th $hidden><span class='label-tooltip' data-toggle='tooltip' title='' data-original-title='Цена которая отображается на сайте для всеx пользователей в качестве акционной цены'>Акционная цена</th><th></th></tr></thead><tbody>";
        foreach ($prices as $price) {
            $id = $price['id'];
            if ($edid == $id) {
                $edname = $price['name'];
                $edguid = $price['guid'];
            }


            $name = $price['name'];
            $guid = $price['guid'];
            $main = $price['main'];

			$disabled_main = $disabled_sale = $selected = $bb   = $be   = $selected_sale = "";
            if ($main == 1) {
                $bb = "<b>"; $be = "</b>";
                $selected = "checked='checked'";
                $disabled_sale = "disabled = 'disabled'";
            }
            if ($main == 2) {
                $bb = "<font color='#8fde86'><b>"; $be = "</b></font>";
                $selected_sale = "checked='checked'";
                $disabled_main = "disabled = 'disabled'";
            }
            $html .= "
            <tr ><td>$bb$id$be</td><td>$bb$name$be</td><td id='guid$id'>$bb$guid$be</td>
			<td align='center' vAlign='middle'><input type='radio' id='main$id' name='main' class='$name' value= '$guid' $selected $disabled_main onclick='setmain($prev)'></td>
            <td align='center' vAlign='middle' $hidden><input type='radio' id='sale$id' name='sale' class='$name' value= '$guid' $selected_sale $disabled_sale onclick='setsale($sale)'></td>
            <td>
            <button
            title='Удалить набор: $name'
            onclick='delgroup($id, $main);return false;'
            type='button' name='DelGroup'
            class='btn btn-default'>
            <i class='icon-trash'></i>
            </button>
            <button
            title='Редактировать набор: $name'
            onclick='editgroup($id);return false;'
            type='button' name='EditGroup'
            class='btn btn-default'>
            <i class='icon-edit'></i>
            </button>
            </td></tr>";
        }
        if ($edid == 0) $edid = ++$id;
        $html .= "<tr><td>$edid</td><td>
        <input type='text' name='price_name' id='price_name' placeholder='название нового набора' value='$edname'/>
        </td><td>
        <input type='text' name='price_guid' id='price_guid' style='width: 250px' placeholder='GUID нового набора' value='$edguid'/>
        </td>
        <td>
        <button
        title='Сохранить изменения (добавить/редактировать)'
        onclick='addgroup($edid);return false;'
        type='button' name='EditGroup'
        class='btn btn-default'>
        <i class='icon-save'></i>
        </button>
        <button
        title='Очистить'
        onclick='clearedit();return false;'
        type='button' name='Clear'
        class='btn btn-default'>
        <i class='icon-eraser'></i>
        </button>
        </td></tr>
        <tbody>
        </table>
        ";

        return $html;
    }

	public function renderList()
	{

		$this->module = new Ecm_csync();
		$path = _PS_MODULE_DIR_."ecm_csync";
		include($path. '/init/defines_inc.php');
		$this->context->smarty->assign('drawtable',self::drawtable());
		$prises = Db::getInstance()->getValue("SELECT COUNT(DISTINCT `guid`) FROM `"._DB_PREFIX_."optprice`");
		$groups = self::getGroups($id_lang);
		$off_ = $this->module->checked($off);
		$set_curr_ = $this->module->checked($set_curr);
		$spec_price_off = $this->module->checked($spec_off);
		$this->context->smarty->assign(
		array(
		'price_off' => $off_,
		'set_curr' => $set_curr_,
		'spec_price_off' => $spec_price_off,
		'prises' => $prises,
		'groups' => $groups,
		'drawtable' => self::drawtable(),
		'drawselect' => self::drawselect(),
		)
		);
		$more = $this->module->display($path, 'views/displayPrises.tpl');
		return $more.parent::renderList();
	}

}

