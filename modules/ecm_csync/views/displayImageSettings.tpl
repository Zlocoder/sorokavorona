<script type="text/javascript" src="{$module_template_dir}js/ecm_form.js">
</script>

<input id="module_dir" name="module_dir" type="hidden" value ="{$module_template_dir}"/>
<div class="panel col-lg-12">
    <div id="fieldset_0">
        <div class="panel-heading">
            <i class="icon-picture-o">
            </i> Настройка изображений
        </div>

        <div class="form-group" style = "clear: both;">
            <label class="control-label col-lg-2">
                Генерирование изображений
            </label>
            <div class="col-lg-6">
                <input type="checkbox" name="gen" id="gen" {$gen}>
                <p class="help-block">
                    Выберите "да" для автоматического генерирования изображений при выгрузке, выберите "нет" для перегенерирования изображений вручную через админ-панель
                </p>
            </div>
        </div>

		<div class="form-group" style = "clear: both;">
			<label class="control-label col-lg-2">
				Принудительная перезапись изображений
			</label>
			<div class="col-lg-6">
				<input type="checkbox" name="gen_pr" id="gen_pr" {$gen_pr}>
				<p class="help-block"> Выберите "да" для включения режима. В этом режиме изображение на сайте принудутильено перезапишется по данным с 1С, даже если картинка уже существует на сайте</p>
			</div>
		</div>

        <div class="form-group" style = "clear: both;">
            <label class="control-label col-lg-2">
                Сделать невидимыми товары без изображений
            </label>
            <div class="col-lg-6">
                <input type="checkbox" name="dwim" id="dwim" {$dwim}>
                <p class="help-block">
                    Выберите "да" для включения режима. В этом режиме видимость у товаров у которых нет изображений установится "Только поиск""
                </p>
            </div>
        </div>

		<div class="form-group" style = "clear: both;">
            <label class="control-label col-lg-2">
                Генерирование водяного знака
            </label>
            <div class="col-lg-6">
                <input type="checkbox" name="wm" id="wm" {$wm}>
                <p class="help-block">
                    Выберите "да" для автоматического генерирования водяного знака на изображениях при выгрузке
                </p>
            </div>
        </div>
    </div>
</div>
