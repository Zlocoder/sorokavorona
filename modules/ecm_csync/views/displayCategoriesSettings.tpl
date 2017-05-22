<script type="text/javascript" src="{$module_template_dir}js/ecm_form.js">
</script>

<input id="module_dir" name="module_dir" type="hidden" value ="{$module_template_dir}"/>
<div class="panel col-lg-12">
    <div id="fieldset_0">
        <div class="panel-heading">
            <i class="icon-caret-right">
            </i> Настройка категорий
        </div>

        <div class="form-group" style = "clear: both;">
			<label class="control-label col-lg-2">Режим мультикатегорий</label>
				<div class="col-lg-6">
					<input type="checkbox" name="multicat" id="multicat" {$multicat}>
					<p class="help-block"> Отметьте для выбора режима. Данный режим позволяет размещать товар в нескольких родительских категориях.Внимание!!! Для использования этой опции должна быть доработана надлежащим образом конфигурация 1С.</p>
				</div>
		</div>

		<div class="form-group" style = "clear: both;">
			<label class="control-label col-lg-2" style = "clear: both;">загружать картинки, описания категорий с 1С</label>
				<div class="col-lg-6">
					<input type="checkbox" name="idsc" id="idsc" {$idsc}>
					<p class="help-block"> Отметьте для передачи картинок, описаний  категорий с 1С. Внимание!!! Для использования этой опции должна быть доработана надлежащим образом конфигурация 1С</p>
				</div>
		</div>


		<div class="form-group" style = "clear: both;">
			<label class="control-label col-lg-2" style = "clear: both;">загружать SEO поля категорий с 1С</label>
				<div class="col-lg-6">
					<input type="checkbox" name="sc" id="sc" {$sc}>
					<p class="help-block"> Отметьте для передачи SEO полей категорий с 1С. Внимание!!! Для использования этой опции должна быть доработана надлежащим образом конфигурация 1С</p>
				</div>
		</div>

		<div class="form-group" style = "clear: both;">
			<label class="control-label col-lg-2" style = "clear: both;">Управлять позициями категорий с 1С</label>
				<div class="col-lg-6">
					<input type="checkbox" name="cp1c" id="cp1c" {$cp1c}>
					<p class="help-block"> Отметьте для активации режима. Внимание!!! Для использования этой опции должна быть доработана надлежащим образом конфигурация 1С</p>
				</div>
		</div>

		<div class="form-group" style = "clear: both;">
			<label class="control-label col-lg-2" style = "clear: both;">Отключить управление связями товаров</label>
				<div class="col-lg-6">
					<input type="checkbox" name="cp" id="cp" {$cp}>
					<p class="help-block"> Выберите "да" для включения режима. Внимание!!! При первой выгрузке 1С выгрузит на сайт привязку товаров к категориям, при последующих обменах информация о связях обновляться не будет</p>
				</div>
		</div>

		<div class="form-group" style = "clear: both;">
			<label class="control-label col-lg-2" style = "clear: both;">Сортировка категорий</label>
				<div class="col-lg-6">
					{html_radios name="category_product" id="category_product" options=$category_product_radios selected=$category_product_radio separator="<br />"}
					<p class="help-block"> Выберите правило назначения связей "категория-товар" (аналогично как в 1с, как в 1С и род. категории до категории "Home",только в скрытую категорию для дальнейшей ручной сортировки).</p>
				</div>
		</div>


    </div>
</div>
