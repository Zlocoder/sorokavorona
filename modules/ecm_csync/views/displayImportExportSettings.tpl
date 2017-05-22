<script type="text/javascript" src="{$module_template_dir}js/ecm_form.js">
</script>
{if isset($success) && $success}{$success}{/if}
{if isset($error) && $error}{$error}{/if}
<input id="module_dir" name="module_dir" type="hidden" value ="{$module_template_dir}"/>
<div class="panel col-lg-12">

        <div class="panel-heading">
            <i class="icon-bars"></i>
             Импорт , экспорт каталога
        </div>
        <div class="panel">
			<div class="panel-heading">
				<i class="icon-upload"></i>
				Экспорт каталога в файлы обмена
			</div>
			<ol>
				<li>Сгенерировать уникальные идентификаторы: <a href={$genuid} target="_blank"><strong>test</strong> </a></li>
				<li>Получить файл каталога: <a href={$catalog} target="_blank"><strong>test</strong> </a></li>
				<li>Получить файл с  остатками и ценами: <a href={$stocks} target="_blank"><strong>test</strong></a></li>
				<li>Получить файл со скидками: <a href={$sale} target="_blank"><strong>test</strong></a></li>
			</ol>
		</div>
					<div class="row">
				<div class="col-lg-12">
					<div id="fieldset_0" class="panel">
						<div class="panel-heading">
						<i class="icon-upload-alt"></i> Загрузить файлы обмена
						</div>
			
							<label>Выбериате файл:</label>
							<div class="margin-form">
							<form action="{$link->getAdminLink('AdminCsyncImportExportSettings')|escape:'html'}" method="post" enctype="multipart/form-data">
								<input name="upfile" type="file"  enctype="multipart/form-data" /><br />
								<p class="clear">Выберите файл обмена</p>
								<button name="addFile" type="submit" class="btn btn-default">
									<i class="icon-upload-alt"></i>
									Загрузить этот файл
								</button>
							</form>	
							</div>

					</div>
			</div>
</div>
		<div class="panel">
			<div class="panel-heading">
				<i class="icon-download"></i> Импорт каталога из файлов обмена
			</div>


				<li>Импорт каталога товаров: <a href={$import} target="_blank"><strong>test</strong> </a></li>
				<li>Импорт остатков и цен : <a href={$offers} target="_blank"><strong>test</strong></a></li>

		</div>
		<div class="panel">
			<div class="panel-heading">
				<i class="icon-exchange"></i> Обмен заказами
			</div>
			<div class="form-group" style = "clear: both;">
				<li>Проверка экспорта заказов в 1С: <a href={$orders} target="_blank"><strong>test</strong></a></li>
				<li>Проверка экспорта статусов заказов с 1С: <a id="exp" href={$ansfile} target="_blank"><strong>test</strong></a></li>

				<p class="help-block">
                   Для проверки эксорта статусов введите наименование файла обмена, выгруженного с 1С в поле ниже и нажмите test
                </p>
             </div>
            <div class="form-group" style="height:34px;">
                <label class="control-label col-lg-2" style = "clear: both;">
                   Удалять файл ответа со статусами, пришедший с 1С
                </label>
                <div class="col-lg-6" >
                    <input type="checkbox" name="status_export" id="status_export" {$status_export}/>
                    <p class="help-block">
                        Выберите "да" для включения режима. В этом режиме будут удалятся файлы ответа, пришедшие с 1С.
                    </p>
                </div>
            </div>
            <div class="form-group" style = "height:34px;clear: both;" id="select_status_state">
                <label class="control-label col-lg-2 ">
                    Наименование файла ответа из 1С
                </label>
                <div style="width: 300px" class="control-label col-lg-6">
					{html_options id=ansfile name=ansfile class=ansfile options=$files}
				</div>

            </div>
 		</div>

</div>
