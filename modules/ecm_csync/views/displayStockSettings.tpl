<script type="text/javascript" src="{$module_template_dir}js/ecm_form.js">
</script>

<input id="module_dir" name="module_dir" type="hidden" value ="{$module_template_dir}"/>
<div class="panel col-lg-12">
    <div id="fieldset_0">
        <div class="panel-heading">
            <i class="icon-bar-chart">
            </i> Настройка остатков
        </div>

		<div class="form-group" style = "clear: both;">
		<label class="control-label col-lg-2" style = "clear: both;">Отключить управление количеством товаров из 1С</label>
		<div class="col-lg-6">
		<input type="checkbox" name="col" id="col" {$col}>
		<p class="help-block"> Выберите "да" для включения режима. Внимание!!! При первой выгрузке 1С выгрузит на сайт остатки товаров, при последующих обменах информация о цене передаваться не будет</p>
		</div>
		</div>
		<div id="stocks">
       <div class="form-group" style = "clear: both;">
		<label class="control-label col-lg-2" style = "clear: both;">Обнулять количество товаров на сайте, перед обновлением из 1С</label>
		<div class="col-lg-6">
		<input type="checkbox" name="cs" id="cs" {$cs} >
		<p class="help-block"> Выберите "да" для включения режима. В этом режиме будут принудительно обнулятся остатки на сайте, перед обновлемием их с 1С</p>
		</div>
		</div>

		<div class="form-group" style = "clear: both;">
		<label class="control-label col-lg-2" style = "clear: both;">Использовать систему расширеного управления запасами</label>
		<div class="margin-form">
		<input type="checkbox"  name="aqs" id="aqs" {$aqs}>
		<p class="help-block"> Использовать систему расширеного управления запасами. <a href="http://support.elcommerce.com.ua/kb/faq.php?id=16" target="_blank">Не забываем ее также включить в магазине  </a> </p>
		</div>
		</div>

		<div class="form-group" style = "clear: both;">
				<label class="control-label col-lg-2">0 остаток
				</label>
			<div class="col-lg-6">
				<input type="number" size="5" name="qvant0" id="qvant0" min="0" max="5000" value="{$qvant0}" />
				<p class="help-block">Введите количество товара, которое будет подразумеваться под 0 остатком.</p>
			</div>
		</div>

		<div class="form-group" style = "clear: both;">
		<label class="control-label col-lg-2" style = "clear: both;">Товары с нулевыми остатками</label>
		<div class="col-lg-9">
		{html_radios name="zero" id="zero" options=$zero_radios selected=$zero_radio separator="<br />"}
		<p class="help-block"> Выберите правило поведения для товаров с нулевыми остатками.</p>
		</div>
		</div>
		
		<div class="form-group" id="redirect_block" style = "clear: both;">
		<label class="control-label col-lg-2" style = "clear: both;">Редирект</label>
		<div class="col-lg-9">
		<div style="width: 270px" >
		{html_options name="redirect_selected"  class="redirect" options=$redirect_radios selected=$redirect_radio}
		<p class="help-block"> Выберите правило редиректа для деактивированных товаров с нулевыми остатками</p>
		</div>
		</div>
		</div>
		
		<div class="form-group" id="visibility_block" style = "clear: both;">
		<label class="control-label col-lg-2" style = "clear: both;">Видимость</label>
		<div class="col-lg-9">
		<div style="width: 270px" >
		{html_options name="visibility_selected"  class="visibility" options=$visibility_radios selected=$visibility_radio}
		<p class="help-block"> Выберите правило видимости для скрытых товаров с нулевыми остатками</p>
		</div>
		</div>
		</div>

		<div class="form-group" style = "clear: both;">
		<label class="control-label col-lg-2" style = "clear: both;">Правило для комбинаций по умолчанию</label>
		<div class="col-lg-9">
		{html_radios name="qtp" id="qtp" options=$qtp_radios selected=$qtp_radio separator="<br />"}
		<p class="help-block"> Выберите правило назначения комбинаций по умолчанию. Внимание!!! для работы опции "Управлять с 1С" должна быть доработана соответствующим образом конфигурация 1С</p>
		</div>
		</div>
		</div>
    </div>
</div>
