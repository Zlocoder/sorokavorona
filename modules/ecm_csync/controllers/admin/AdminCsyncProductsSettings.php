<?php
include_once(dirname(__FILE__).'/../../ecm_csync.php');
class AdminCsyncProductsSettingsController extends AdminController
{
	public $log;
	public function __construct()
	{

		$this->bootstrap = true;
		parent::__construct();


	}

	public function renderList()
	{

		$this->module = new Ecm_csync();
		$path = _PS_MODULE_DIR_."ecm_csync";
		include($path. '/init/defines_inc.php');
		$new1c_ =  $this->module->checked($new1c);
		$n1c_ = $this->module->checked($n1c);
		$sd1c_ = $this->module->checked($sd1c);
		$d1c_ = $this->module->checked($d1c);
		$st_ = $this->module->checked($st);
		$precat_ = $this->module->checked($precat);
		$fa_ = $this->module->checked($fa);
		$diff_ = $this->module->checked($diff);
		$cross_ = $this->module->checked($cross);
		$analog_ = $this->module->checked($analog);
		$tags = $this->module->checked($tags_);
		$vid_ = $this->module->checked($vid);
		$shvd_ = $this->module->checked($shvd);
		$feature_man_ = $this->module->checked($feature_man);
		$not_man_ = $this->module->checked($not_man);
		$rme_ = $this->module->checked($rme);
		$this->context->smarty->assign(
			array(
				'new1c'=> $new1c_,
				'n1c'=> $n1c_,
				'sd1c' => $sd1c_,
				'd1c' => $d1c_,
				'precat' => $precat_,
				'st' => $st_,
				'fa' => $fa_,
				'diff' => $diff_,
				'cross' => $cross_,
				'tags' => $tags,
				'vid' => $vid_,
				'shvd' => $shvd_,
				'analog' => $analog_,
				'furl_product_radio' => $f_url,
				'sdesk_selected' => $s_d,
				'desk_selected' => $f_d,
				'feature_man' => $feature_man_,
				'not_man' => $not_man_,
				'rme' => $rme_,
				'video_selected' => $video_selected,
				'zero_radio_del' => $zero_del,
				'redirect_radio_del' => $redirect_del,
				'visibility_radio_del' => $visibility_del,
				'furl_product_radios' => array(
                               1 => 'Кирилица в адресной строке',
                               2 => 'Латиница в адресной строке',
                               ),
                'sdesk' => array(
                               1 => 'Полное Наименование',
                               2 => 'Описание',
                               ),
                'desk' => array(
                               1 => 'Описание',
                               2 => 'ОписаниеВформатеHTML',
                               ),
                'video_select' => array(
                               1 => 'Модуль Video tab от IQIT-COMMERCE.COM',
                               2 => 'таблица video',
                               ),
                'zero_radios_del' => array(
                               1 => 'Деактивировать товары',
                               2 => 'Установить невидимыми',
                              ),
                'redirect_radios_del' => array(
                               301 => '301',
                               302 => '302',
                               404 => '404',
                              ),
                'visibility_radios_del' => array(
                               'search' => 'Только поиск',
                               'none' => 'Нигде',
                              ),
				)
		);
		//$this->context->smarty->assign('log',$this->readlog());
		$more = $this->module->display($path, 'views/displayProductsSettings.tpl');
		return $more.parent::renderList();
	}

}

