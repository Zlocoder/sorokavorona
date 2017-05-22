<script type="text/javascript" src="{$module_template_dir}js/ecm_price.js">
</script>
<script type="text/javascript" src="{$module_template_dir}js/alertify.min.js">
</script>
<link rel="stylesheet" href="{$module_template_dir}css/alertify.core.css" />
<link rel="stylesheet" href="{$module_template_dir}css/alertify.default.css" />
<input id="module_dir" name="module_dir" type="hidden" value ="{$module_template_dir}"/>
<div class="panel">
	<div class="panel-heading">
		<i class="icon-list"></i> Управлять ценами с 1С
	</div>
	<div class="form-group" style = "clear: both; height:80px;">
			<label class="control-label col-lg-2" style = "clear: both;" >Отключить управление ценами товаров  из 1С</label>
			<div class="col-lg-6">
				<input type="checkbox" name="price_off" id="price_off" {$price_off}/>
				<p class="help-block"> Выберите "да" для включения режима. Внимание!!! При первой выгрузке 1С выгрузит на сайт цены товаров, при последующих обменах информация о цене передаваться не будет</p>
			</div>
	</div>
	<div id= "show_prises_settings">
	<div class="form-group" style = "clear: both; height:80px;">
			<label class="control-label col-lg-2">
				Передавать курсы валют  из 1С
			</label>
			<div class="col-lg-6">
				<input type="checkbox" name="set_curr" id="set_curr" {$set_curr}/>
				<p class="help-block"> Выберите "да" для включения режима.</p>
			</div>
	</div>
	<div class="form-group" style = "clear: both; height:80px;">
			<label class="control-label col-lg-2">
				Отключить управление специальными ценами товаров  из 1С
			</label>
			<div class="col-lg-6">
				<input type="checkbox" name="spec_price_off" id="spec_price_off" {$spec_price_off}/>
				<p class="help-block"> Выберите "да" для включения режима.</p>
			</div>
	</div>
	<div class="form-group" style = "clear: both; height:80px;">
			<label class="control-label col-lg-2">
				Сброс установок "Основной" и "Акционной" цен
			</label>
			<div class="col-lg-6">
				<button
        title='Очистить'
        onclick='reset();return false;'
        type='button' name='Clear'
        class='btn btn-default'>
        <i class='icon-eraser'></i> Сбросить
    </button>
				<p class="help-block">Кликните для сброса</p>
			</div>
	</div>
	</div>

</div>
<div id= "show_prises_settings_blocks">
<div class="panel" id ="prise_main" style = "clear: both;">
	<div class="panel-heading">
		<i class="icon-list"></i> Установка наборов цен
	</div>
	{if $prises >0}
	<div class="alert alert-info">
		<p style =" margin-left: 20px;">После изменения "Основной" и "Акционной" типов цен, а также после изменения привязки типов цен к группам пользователей не забывайте делать полную выгрузку без картинок с 1С, для того чтобы изменения отобразились на сайте!!!</p>
	</div>
	{else}
	<div class="alert alert-danger">
		<p style =" margin-left: 20px;">Данная таблица заполнится автоматически после первой выгрузки с 1С!!!</p>
	</div>
	{/if}
		<div id="prices_tabl">
		{$drawtable}
		</div>
</div>
<div class="panel" id ="prise_groups">
	<div class="panel-heading">
		<i class="icon-list"></i> Привязка цен к группам пользователей
	</div>
	<div class="alert alert-info">
		<p style =" margin-left: 20px;">Для групп пользователей "Посетитель", "Гость", "Клиент" рекомендуется не привязывать наборы цен с 1С. Клиентам этих групп будет доступна "Основная" цена с 1С.</p>
	</div>
	{$drawselect}

</div>
</div>