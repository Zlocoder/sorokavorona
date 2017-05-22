<script type="text/javascript" src="{$module_template_dir}js/ecm_checkcompability.js">
</script>

<input id="module_dir" name="module_dir" type="hidden" value ="{$module_template_dir}"/>
<div class="panel col-lg-12">
         <div id="fieldset_0">
            <div class="panel-heading">
                <i class="icon-check-square"></i>
                Совместимость с модулями
            </div>


		<div class="form-group" style = "clear: both;">
			<label class="control-label col-lg-2" style = "clear: both;">
				Номер телефона в качестве логина от forforse.com
			</label>
			<div class="col-lg-6">
				<input type="checkbox"  name="phonelogin" id="phonelogin" {$phonelogin} />
				<p class="help-block">Выберите "да" для включения режима. В данном режиме в заказ в 1С номер телефона контрагента будет передаваться из дополнительного поля в БД</p>
			</div>
		</div>
		<div class="form-group" style = "clear: both;">
			<label class="control-label col-lg-2" style = "clear: both;">
				сортировка по наличию от makebecool.com
			</label>
			<div class="col-lg-6">
				<input type="checkbox"  name="on_stock" id="on_stock" {$on_stock} />
				<p class="help-block">Выберите "да" для включения режима.</p>
			</div>
		</div>


		<div class="form-group" style = "clear: both;">
			<label class="control-label col-lg-2" style = "clear: both;">
				Совместимость с модулем доставки Новая Почта
			</label>
			<div class="col-lg-6">
				<input type="checkbox"  name="np" id="ecm_novaposhta" {$np} />
				<p class="help-block">Выберите "да" для включения режима. В данном режиме в заказ в 1С в адрес доставки будет передаваться данные с модуля доставки Новая Почта</p>
			</div>
		</div>

		<div class="form-group" style = "clear: both;">
			<label class="control-label col-lg-2" style = "clear: both;">
				Совместимость с модулем доставки УкрПочта
			</label>
			<div class="col-lg-6">
				<input type="checkbox"  name="up" id="ecm_ukrposhta" {$up} />
				<p class="help-block">Выберите "да" для включения режима. В данном режиме в заказ в 1С в адрес доставки будет передаваться данные с модуля доставки УкрПочта</p>
			</div>
		</div>

		<div class="form-group" style = "clear: both;">
			<label class="control-label col-lg-2" style = "clear: both;">
				Совместимость с модулем доставки Delivery Auto
			</label>
			<div class="col-lg-6">
				<input type="checkbox"  name="da"  id="ecm_delivery_auto" {$da} />
				<p class="help-block">Выберите "да" для включения режима. В данном режиме в заказ в 1С в адрес доставки будет передаваться данные с модуля доставки DeliveryAuto</p>
			</div>
		</div>

		<div class="form-group" style = "clear: both;">
			<label class="control-label col-lg-2" style = "clear: both;">
				Совместимость с модулем доставки InTime
			</label>
			<div class="col-lg-6">
				<input type="checkbox"  name="it" id="ecm_intime" {$it} />
				<p class="help-block">Выберите "да" для включения режима. В данном режиме в заказ в 1С в адрес доставки будет передаваться данные с модуля доставки InTime</p>
			</div>
		</div>


		<div class="form-group" style = "clear: both;">
			<label class="control-label col-lg-2" style = "clear: both;">
				Совместимость с модулем доставки AutoLux
			</label>
			<div class="col-lg-6">
				<input type="checkbox"  name="al" id="ecm_autolux" {$al}/>
				<p class="help-block">Выберите "да" для включения режима. В данном режиме в заказ в 1С в адрес доставки будет передаваться данные с модуля доставки AutoLux</p>
			</div>
		</div>

		<div class="form-group" style = "clear: both;">
			<label class="control-label col-lg-2" style = "clear: both;">
				Совместимость с модулем доставки MeestExpress
			</label>
			<div class="col-lg-6">
				<input type="checkbox"  name="me" id="ecm_meest_express" {$me} />
				<p class="help-block">Выберите "да" для включения режима. В данном режиме в заказ в 1С в адрес доставки будет передаваться данные с модуля доставки MeestExpress</p>
			</div>
		</div>

		<div class="form-group" style = "clear: both;">
			<label class="control-label col-lg-2" style = "clear: both;">
				Совместимость с модулем "Акционные Комплекты"
			</label>
			<div class="col-lg-6">
				<input type="checkbox"  name="pjs" id="ecm_pjs" {$pjs} />
				<p class="help-block">Выберите "да" для включения режима. В данном режиме с 1С будут приходить данные в модуль Акционных комплектов</p>
			</div>
		</div>

		<div class="form-group" style = "clear: both;">
			<label class="control-label col-lg-2" style = "clear: both;">
				Совместимость с модулем "Productrating"
			</label>
			<div class="col-lg-6">
				<input type="checkbox"  name="productrating" id="productrating" {$productrating} />
				<p class="help-block">Выберите "да" для включения режима. В данном режиме с 1С будут приходить данные в модуль Productrating</p>
			</div>
		</div>
        </div>
</div>
