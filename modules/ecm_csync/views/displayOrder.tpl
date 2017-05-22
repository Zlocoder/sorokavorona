<script type="text/javascript" src="{$module_template_dir}js/ecm_form.js">
</script>
<script type="text/javascript" src="{$module_template_dir}js/alertify.min.js">
</script>
<link rel="stylesheet" href="{$module_template_dir}css/alertify.core.css" />
<link rel="stylesheet" href="{$module_template_dir}css/alertify.default.css" />
<input id="module_dir" name="module_dir" type="hidden" value ="{$module_template_dir}"/>
<div class="panel col-lg-12">
         <div id="fieldset_0">
            <div class="panel-heading">
                <i class="icon-credit-card">
                </i> Настройка заказов
            </div>

		<div class="form-group" style = "clear: both;">
			<label class="control-label col-lg-2" style = "clear: both;">Передавать валюту заказа</label>
			<div class="col-lg-6">
				<input type="checkbox"  name="curr_1C" id="curr_1C" {$curr_1C}/>
				<p class="help-block"> Передавать валюту заказа с сайта в 1С. В 1С должны быть валюты такие как на сайте с одинаковыми международными классификаторами ISO (например USD,UAH,EUR и т.д)</p>
			</div>
		</div>

		<div class="form-group" style = "clear: both;" id="currensy">
				<label class="control-label col-lg-2">название валюты в 1С
				</label>
			<div class="col-lg-6">
				<input type="text" name="custom_curr" id="custom_curr" style="width: 70px" placeholder="валюта"   value="{$curr_custom}">
				<p class="help-block">Введите наименование валюты, для обмена заказами с 1С. <a href="http://support.elcommerce.com.ua/kb/faq.php?id=34" target="_blank">Где посмотреть наименование валюты, для обмена заказами с 1С</a></p>
			</div>
		</div>


            <div class="form-group" style = "clear: both;">
                <label class="control-label col-lg-2" style = "clear: both;">
                    Передавать персональные данные в комментарий
                </label>
                <div class="col-lg-6">
                    <input type="checkbox" name="p_data" id="p_data" {$p_data}/>
                    <p class="help-block">
                        Выберите "да" для включения режима. В этом режиме почта, телефон и адрес доставки будут передаваться в комментарий к заказу в 1С.
                    </p>
                </div>
            </div>

            
            <div class="form-group" style = "clear: both;">
                <label class="control-label col-lg-2" style = "clear: both;">
                    Синхронизировать заказы "разово"
                </label>
                <div class="col-lg-6">
                    <input type="checkbox" name="send" id="send_orders" {$send}/>
                    <p class="help-block">
                        Выберите "да" для включения режима. В этом режиме зхаказы передаются с сайта в 1С только один         раз, не зависимо от смены статуса и количественно-качественого состояния. С 1С на сайт будут приходить извещения об оплате и отгрузке товара по данному заказу.
                    </p>
                </div>
            </div>

            <div class="form-group" style = "clear: both;">
                <label class="control-label col-lg-2" style = "clear: both;">
                    Использовать полную версию схемы управления заказами
                </label>
                <div class="col-lg-6">
                    <input type="checkbox" name="fod" id="fod" class="checkbox_check" {$fod} />
                    <p class="help-block">
                        Выберите "да" для включения режима. В этом режиме количественно-качественные изменения в заказах, сделаные в 1С, передадутся на сайт и будут видны пользователю в личном кабинете.
                    </p>
                </div>
            </div>

            <div class="form-group" style = "clear: both;">
                <label class="control-label col-lg-2" style = "clear: both;">
                    Использовать полную версию схемы управления статусами заказов
                </label>
                <div class="col-lg-6">
                    <input type="checkbox" name="fods" id="fods" {$fods}/>
                    <p class="help-block">
                        Выберите "да" для включения режима. В этом режиме используются статусы не как в протоколе CommerceML2, а как в Prestashop.
                    </p>
                </div>
            </div>

            <div class="form-group" style = "clear: both;">
                <label class="control-label col-lg-2" style = "clear: both;">
                    Передавать заказы с учетом стоимости доставки
                </label>
                <div class="col-lg-6">
                    <input type="checkbox" name="deliv" id="deliv" {$deliv} />
                    <p class="help-block">
                        Выберите "да" для включения режима. В этом режиме заказы передаются с сайта в 1С с учетом стоимости доставки.
                    </p>
                </div>
            </div>

            <div class="form-group" style = "clear: both;">
                <label class="control-label col-lg-2" style = "clear: both;">
                    Синхронизировать заказы с сайта с даты
                </label>
                <div class="col-lg-6">
                	<input type="text" name="date" id="datepicker" style="width: 130px"  value="{$date}">
                    <p class="help-block">
                        Выберите дату, с которой можно будет синхронизировать заказы с сайта и 1C
                    </p>
                </div>
            </div>
			 <div class="form-group" style = "clear: both;">
				<label class="control-label col-lg-2" style = "clear: both;">
                    Финальные статусы
                </label>
					<div class="col-lg-6">
					<select multiple size="$size_s" name="final_orders[]" id="final_orders_states" class="final_orders_states" style="width: 270px" >
					{html_options options=$statuses selected=$status_selected}
					</select>
					<p class="help-block"> Выберите используя CTRL финальные статусы заказа.Заказы с этими статусами не будут передаваться  с сайта в 1С.</p>
					</div>
			</div>
			<div class="form-group" style = "clear: both;">
				<label class="control-label col-lg-2" style = "clear: both;">
                    Профиль администратора для управления статусами
                </label>
					<div class="col-lg-6">
					<div style="width: 270px" >
					{html_options class=employee name=employee_selected  options=$employees selected=$employee_selected}
					</div>
					<p class="help-block"> Выберите профиль администратора для управления статусами заказа. Статусы заказа на сайте будут меняться от имени выбранного профиля</p>
					</div>
			</div>
            <div class="form-group" style = "clear: both;">
                <label class="control-label col-lg-2 ">
                    Использовать префикс для идентификатора заказа
                </label>
                <div class="col-lg-6">
                    <input type="text" name="prefix" id="prefix" style="width: 130px"  placeholder="префикс" value="{$prefix}" />
                    <p class="help-block">
                        введите префикс для идентификатора заказа, все заказы будут передаваться в 1С с этим префиксом; оставьте пустым, если хотите, чтобы заказы передавались без префикса
                    </p>
                </div>
            </div>
        </div>
</div>
