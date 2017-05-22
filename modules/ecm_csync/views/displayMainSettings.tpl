<script type="text/javascript" src="{$module_template_dir}js/ecm_form.js">
</script>

<input id="module_dir" name="module_dir" type="hidden" value ="{$module_template_dir}"/>
<div class="panel col-lg-12">
    <div id="fieldset_0">
        <div class="panel-heading">
           <i class="icon-cog"></i>Основные Настройки
        </div>

		<div class="form-group" style = "clear: both;">
			<label class="control-label col-lg-2 ">
			Логин
			</label>
			<div class="col-lg-3">
				<input type="text"
					name="login_1c" id="login_1c"
					placeholder="Логин" required
					value="{$login_1c}"/>
				<p class="help-block">
					Введите логин (Лантинскими буквами, цифрами, исключая спецсимволы)
				</p>
			</div>
		</div>

		<div class="form-group" style = "clear: both;">
			<label class="control-label col-lg-2">
			Пароль
			</label>
			<div class="col-lg-3">
				<input type="password" name="pass_1c" id="pass_1c" placeholder="Пароль" required value="{$pass_1c}"/>
				<p class="help-block">Введите пароль (Лантинскими буквами, цифрами, исключая спецсимволы)</p>
			</div>
		</div>

		<div class="form-group" style = "clear: both;">
				<label class="control-label col-lg-2" style = "clear: both;">
                    Налоговое правило
                </label>
					<div class="col-lg-6">
					<div style="width: 270px" >
					{html_options class=tax_rule name=tax_rule_selected  options=$tax_rule selected=$tax_rule_selected}
					</div>
					<p class="help-block"> Выберите налоговое правило</p>
					</div>
			</div>
		<div class="form-group" style = "clear: both;">
				<label class="control-label col-lg-2" style = "clear: both;">
                    Язык
                </label>
					<div class="col-lg-6">
					<div style="width: 270px" >
					{html_options class=lang name=lang_selected  options=$lang selected=$lang_selected}
					</div>
					<p class="help-block"> Выберите язык, c которым будет проходить обмен с 1С</p>
					</div>
		</div>

		<div class="form-group" style = "clear: both;">
				<label class="control-label col-lg-2">Количество товара за запрос
				</label>
			<div class="col-lg-6">
				<input type="number" size="5" name="qvant" min="2" max="5000" id="qvant" value="{$qvant}"/>
				<p class="help-block">Введите количество товара, передаваемое с 1C за один запрос. Параметр зависит от характеристик хостинга. Значение может быть в диапазоне от 2 до 5000. Чем слабее хостинг, тем меньше значение. Значение параметра больше 500 рекомендуется устанавливать для очень мощных серверов.</p>
			</div>
		</div>

		<div class="form-group" style = "clear: both;">
			<label class="control-label col-lg-2">
				Мультиязычность
			</label>
				<div class="col-lg-6">
				<input type="checkbox"  name="multilang" id="multilang" {$multilang} />
				<p class="help-block"> Отметьте для выбора режима. Данный режим позволяет передавать из 1С несколько языков(для нименований категорий, товаров, свойств и аттрибутов, и их значений, а также котортких и полных описаний товаров). Внимание!!! Для использования этой опции должна быть доработана надлежащим образом конфигурация 1С.</p>
				</div>
		</div>
		
		<div class="form-group" style = "clear: both;">
			<label class="control-label col-lg-2">
				Чистить кеш при добавлении новых товаров
			</label>
				<div class="col-lg-6">
				<input type="checkbox"  name="clear_cache" id="clear_cache" {$clear_cache} />
				<p class="help-block"> Отметьте для выбора режима. В данном режиме происходит очистка кеша смарти магазина, при добавлении новых товаров.</p>
				</div>
		</div>

		<div class="form-group" style = "clear: both;">
			<label class="control-label col-lg-2">
				Режим обмена с 1C
			</label>
		<div class="col-lg-6">
		<input type="checkbox"  name="fix" id="fix" {$fix} >
		<p class="help-block"> Использовать <strong>zip</strong> сжатие для обмена данными с 1C</p>
		</div>
		</div>
	<div class="form-group" style = "clear: both;">
			<ol>
				<h3>Настройки 1C</h3>
				<div class="alert alert-warning">
					<li style =" margin-left: 20px;">Указать путь к скрипту <strong>{$connector}</strong></li>
					<li style =" margin-left: 20px;">Для корректной авторизации приведите строки в файле .htaccess<br><br>
					<ul style ="list-style: square outside;" >
					<li>RewriteRule . - [E=REWRITEBASE:/]</li>
					<li>RewriteRule ^api$ api/ [L]</li>
					<br>
					<strong>к виду:</strong><br><br>
					<li>RewriteRule . - [E=REWRITEBASE:/]</li>
					{$rule}
					<li>RewriteRule ^api$ api/ [L]</li>
					</ul>
					<br><strong>
					При изменении файла .htaccess не забудте проверить внесенные правки
					</strong></li>
					<li style =" margin-left: 20px;">Ввести логин и пароль, указанный Вами выше в настройках </li>
					<li style =" margin-left: 20px;">Настроить 1C на выполнение  синхронизации</li>
				</div>
			</ol>
		<br />
			<ol>
				<h3>Особенности синхронизации</h3>
				<div class="alert alert-warning">
					<li style =" margin-left: 20px;">При  синхронизации производится обновление/добавление названий категорий, обновление/добавление названий товаров,  изменяется родитель, rewrite_rule остается прежним. Если внешние коды(guid) у товаров на сайте и в справочнике "Товары и услуги" в 1C не будут совпадать, на сайте появятся дубли </li>
					<li style =" margin-left: 20px;">При  синхронизации проводится обновление цен и количества товара</li>
				</div>
			</ol>

	</div>
    </div>
</div>
