<script type="text/javascript" src="{$module_template_dir}js/ecm_form.js">
</script>

<input id="module_dir" name="module_dir" type="hidden" value ="{$module_template_dir}"/>
<div class="panel col-lg-12">
    <div id="fieldset_0">
        <div class="panel-heading">
            <i class="icon-edit">
            </i> Настройка товаров
        </div>
<div class="row">
    <div class="col-lg-6">
			<div class="form-group" style = "clear: both;">
					<label class="control-label col-lg-3" style = "clear: both;">
						Поведение удаленных товаров в 1С
					</label>
				<div class="col-lg-6">
					{html_radios name="zero_del" id="zero_del" options=$zero_radios_del selected=$zero_radio_del separator="<br />"}
					<p class="help-block"> Выберите правило поведения для удаленных товаров в 1C.</p>
				</div>
			</div>

			<div class="form-group" id="redirect_block_del" style = "clear: both;">
				<label class="control-label col-lg-3" style = "clear: both;">
					Редирект
				</label>
				<div class="col-lg-9">
					<div style="width: 220px" >
					{html_options name="redirect_selected_del"  class="redirect_del" options=$redirect_radios_del selected=$redirect_radio_del}
					<p class="help-block"> Выберите правило редиректа для удаленных товаров в 1C</p>
					</div>
				</div>
			</div>

			<div class="form-group" id="visibility_block_del" style = "clear: both;">
				<label class="control-label col-lg-3" style = "clear: both;">
					Видимость
				</label>
				<div class="col-lg-9">
					<div style="width: 220px" >
					{html_options name="visibility_selected_del"  class="visibility_del" options=$visibility_radios_del selected=$visibility_radio_del}
					<p class="help-block"> Выберите правило видимости удаленных товаров в 1C</p>
					</div>
				</div>
			</div>
		<div class="form-group" style = "clear: both;">
			<label class="control-label col-lg-3" style = "clear: both;">
				Управление статусом товара "Новинка"
			</label>
			<div class="col-lg-6">
				<input type="checkbox" name="new1c" id="new1c" {$new1c}/>
				<p class="help-block"> Отметьте для активации режима.. Внимание!!! Для использования этой опции должна быть доработана надлежащим образом конфигурация 1С</p>
			</div>
		</div>

		<div class="form-group" style = "clear: both;">
			<label class="control-label col-lg-3" style = "clear: both;">
				Управление кодом,  артикулом в режиме "только изменения"
			</label>
			<div class="col-lg-6">
				<input type="checkbox" name="rme" id="rme" {$rme}/>
				<p class="help-block"> Выберите "да" для отключения управления с 1C  полями "артикул", "штрихкод"  товаров, а также привязкой к производителю в интернет магазине. При включенном режиме первый раз (при создании товара) вышеуказанная информация передастся на сайт, при последующих обменах(когда товар уже передан и существует)  - обновляться не будет. </p>
			</div>
		</div>


		<div class="form-group" style = "clear: both;">
			<label class="control-label col-lg-3" style = "clear: both;">
				Управление контентом в режиме "только изменения"
			</label>
			<div class="col-lg-6">
				<input type="checkbox" name="diff" id="diff" {$diff}/>
				<p class="help-block"> Выберите "да" для отключения управления с 1C  полями "наименование", "короткое описание" и "полное описание" товаров, картинками товаров в интернет магазине.При включенном режиме первый раз (при создании товара) вышеуказанная информация передастся на сайт, при последующих обменах(когда товар уже передан и существует)  - обновляться не будет. </p>
			</div>
		</div>

		<div class="form-group" style = "clear: both;">
			<label class="control-label col-lg-3" style = "clear: both;">
				не передавать наименование товара с 1С
			</label>
			<div class="col-lg-6">
				<input type="checkbox" name="n1c" id="n1c"{$n1c} />
				<p class="help-block"> Отметьте для отмены передачи наименования товара с 1С.</p>
			</div>
		</div>

		<div class="form-group" style = "clear: both;">
			<label class="control-label col-lg-3" style = "clear: both;">
				не передавать короткое описание товара с 1С
			</label>
			<div class="col-lg-6">
				<input type="checkbox" name="sd1c" id="sd1c"{$sd1c}/>
				<p class="help-block"> Отметьте для отмены передачи короткого описания товара с 1С.</p>
			</div>
		</div>

		<div class="form-group" id="shot_desk" style = "clear: both;">
			<label class="control-label col-lg-3">
				Выберите соответствие для короткоко описания
			</label>
			<div class="col-lg-6" >
			{html_options class=sdesk name=sdesk_selected  options=$sdesk selected=$sdesk_selected}
			</div>
		</div>

		<div class="form-group" style = "clear: both;">
			<label class="control-label col-lg-3" style = "clear: both;">
				не передавать описание товара с 1С
			</label>
			<div class="col-lg-6">
				<input type="checkbox" name="d1c" id="d1c" {$d1c} />
				<p class="help-block"> Отметьте для отмены передачи описания товара с 1С.</p>
			</div>
		</div>

		<div class="form-group" id="full_desk" style = "clear: both;">
			<label class="control-label col-lg-3">
				Выберите соответствие для полного описания
			</label>
			<div class="col-lg-6" >
			{html_options class=desk name=desk_selected  options=$desk selected=$desk_selected}
			</div>
		</div>

		<div class="form-group" style = "clear: both;">
			<label class="control-label col-lg-3" style = "clear: both;">
				Формирование ЧПУ
			</label>
			<div class="col-lg-6">
				{html_radios name="furl_product" id="furl_product" options=$furl_product_radios selected=$furl_product_radio separator="<br />"}
				<p class="help-block"> Выберите правило формирования ЧПУ</p>
			</div>
		</div>

		<div class="form-group" style = "clear: both;">
			<label class="control-label col-lg-3" style = "clear: both;">
				загружать SEO поля товаров с 1С
			</label>
			<div class="col-lg-6">
				<input type="checkbox" name="st" id="st" {$st} />
				<p class="help-block"> Отметьте для передачи SEO полей товаров с 1С. Внимание!!! Для использования этой опции должна быть доработана надлежащим образом конфигурация 1С</p>
			</div>
		</div>
    </div>

    <div class="col-lg-6">
    	<div class="form-group" style = "clear: both;">
			<label class="control-label col-lg-3" style = "clear: both;">
				Не управлять привязкой к производителю
			</label>
			<div class="col-lg-6">
				<input type="checkbox"  name="not_man" id="not_man" {$not_man} />
				<p class="help-block">Выберите "да" для включения режима. В данном режиме на сайте не будет осуществляться привязка товара к производителю.</p>
			</div>
		</div>

		<div class="form-group" id="feature_man_block" style = "clear: both;">
			<label class="control-label col-lg-3" style = "clear: both;">
				Произодитель из свойства
			</label>
			<div class="col-lg-6">
				<input type="checkbox"  name="feature_man" id="feature_man" {$feature_man} />
				<p class="help-block">Выберите "да" для включения режима. В данном режиме на сайте список производителей, а также привязка товара к производителю будет браться из свойства товара. Свойство в 1С должно называться "Производитель"</p>
			</div>
		</div>


		<div class="form-group" style = "clear: both;">
			<label class="control-label col-lg-3" style = "clear: both;">
				Загружать файлы и вложения с 1С
			</label>
			<div class="col-lg-6">
				<input type="checkbox" name="fa" id="fa" {$fa} />
				<p class="help-block"> Отметьте для передачи файлов и вложений с 1С. Внимание!!! Для использования этой опции должна быть доработана надлежащим образом конфигурация 1С</p>
			</div>
		</div>

		<div class="form-group" style = "clear: both;">
			<label class="control-label col-lg-3" style = "clear: both;">
				Управлять передачей габаритов
			</label>
			<div class="col-lg-6">
				<input type="checkbox" name="shvd" id="shvd" {$shvd} />
				<p class="help-block"> Отметьте для передачи габаритов (длина, ширина, высота) с 1С на сайт. Внимание!!! Для использования этой опции должна быть доработана надлежащим образом конфигурация 1С</p>
			</div>
		</div>




		<div class="form-group" style = "clear: both;">
			<label class="control-label col-lg-3" style = "clear: both;">
				Управление сопутствующими товарами  из 1С
			</label>
			<div class="col-lg-6">
				<input type="checkbox" name="cross" id="cross" {$cross} />
				<p class="help-block"> Выберите "да" для включения режима. Внимание!!! Для использования этой опции должна быть доработана надлежащим образом конфигурация 1С и устанолен модуль </p>
			</div>
		</div>

		<div class="form-group" style = "clear: both;">
			<label class="control-label col-lg-3" style = "clear: both;">
				Управление аналогами товаров  из 1С
			</label>
			<div class="col-lg-6">
				<input type="checkbox" name="analog" id="analog" {$analog} />
				<p class="help-block"> Выберите "да" для включения режима. Внимание!!! Для использования этой опции должна быть доработана надлежащим образом конфигурация 1С </p>
			</div>
		</div>

		<div class="form-group" style = "clear: both;">
			<label class="control-label col-lg-3" style = "clear: both;">
				Управление тегами товаров с 1С
			</label>
			<div class="col-lg-6">
				<input type="checkbox" name="tags" id="tags" {$tags}/>
				<p class="help-block"> Выберите "да" для включения режима. Внимание!!! Для использования этой опции должна быть доработана надлежащим образом конфигурация 1С</p>
			</div>
		</div>

		<div class="form-group" style = "clear: both;">
			<label class="control-label col-lg-3">
				Передавать видео-обзоры товаров
			</label>
			<div class="col-lg-6">
				<input type="checkbox" name="vid" id="vid" {$vid} />
				<p class="help-block"> Отметьте для выбора режима. Данный режим позволяет передавать видеообзоры товаров .Внимание!!! Для использования этой опции должна быть доработана надлежащим образом конфигурация 1С и установлен соответствующий модуль.</p>
			</div>
		</div>
		<div class="form-group" id="video_sel_block" style = "clear: both;">
			<label class="control-label col-lg-3">
			</label>
			<div class="col-lg-3">
				<div style="width: 300px" >
				{html_options name="video_selects"  class="videosel" options=$video_select selected=$video_selected}
				</div>
			</div>
		</div>

		<div class="form-group" style = "clear: both;">
			<label class="control-label col-lg-3" style = "clear: both;">
				Сопоставление каталога
			</label>
			<div class="col-lg-6">
				<input type="checkbox"  name="precat" id="precat" {$precat} />
				<p class="help-block">Выберите "да" для включения режима. В данном режиме на сайте деактивируются заданные в 1С товары. Внимание!!! Для использования этой опции должна быть доработана надлежащим образом конфигурация 1С</p>
			</div>
		</div>

    </div>
</div>



    </div>
</div>
