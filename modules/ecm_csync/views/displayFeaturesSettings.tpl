<script type="text/javascript" src="{$module_template_dir}js/ecm_form.js">
</script>

<input id="module_dir" name="module_dir" type="hidden" value ="{$module_template_dir}"/>
<div class="panel col-lg-12">
    <div id="fieldset_0">
        <div class="panel-heading">
            <i class="icon-file-text-o">
            </i> Настройка свойств
        </div>

		<div class="form-group" style = "clear: both;">
			<label class="control-label col-lg-2" style = "clear: both;">
				не передавать свойства с 1С
			</label>
			<div class="col-lg-6">
				<input type="checkbox" name="features"  id="features"{$features}>
				<p class="help-block"> Отметьте для отмены передачи свойств с 1С.</p>
			</div>
		</div>
		<div id ="show_feat">
		<div class="form-group" style = "clear: both;">
			<label class="control-label col-lg-2" style = "clear: both;">
				преобразовывать булевы значения в "да","нет"
			</label>
			<div class="col-lg-6">
				<input type="checkbox" name="fbools"  id="fbools"{$fbools}>
				<p class="help-block"> Отметьте для преобразования булевых значений свойств в "да","нет".</p>
			</div>
		</div>
		<div class="form-group" id="multifeatures" style = "clear: both;">
			<label class="control-label col-lg-2">
				Режим мультисвойств
			</label>
			<div class="col-lg-6">
				<input type="checkbox" name="multifeat" id="multifeat"{$multifeat}>
				<p class="help-block"> Отметьте для выбора режима. Данный режим позволяет назначать несколько значений к одному свойству товара .Внимание!!! Для использования этой опции должна быть доработана надлежащим образом конфигурация 1С и CMS Prestashop.</p>
			</div>
		</div>
		</div>




    </div>
</div>
