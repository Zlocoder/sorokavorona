{if isset($success) && $success}{$success}{/if}
<div class="panel">
    <div class="panel-heading">
        <i class="icon-cogs">
        </i> Дополнительные опции
    </div>
           <form action="{$link->getAdminLink('AdminCsyncAdditional')|escape:'html'}" method="post">
            <div class="panel">
            <label class="control-label col-lg-3">
                <span class="label-tooltip" data-toggle="tooltip" title="" data-original-title="Очистка категорий, товаров, свойств, атрибутов и комбинаций,остатков и цен в интернет магазине.">
                    Удалить  товары из каталога номенклатуры в магазине
                </span>
            </label>
            <input name="submitClear" class="btn btn-default btn btn-default button" type="submit" value="Удалить каталог" onclick="return confirm('Произойдет очистка категорий, товаров, свойств, атрибутов и комбинаций,остатков и цен в интернет магазине\nОперация очистки каталога номенклатуры необратима!!!\nТочно желаете удалить товары?')" />
        </div>

        <div class="panel">
            <label class="control-label col-lg-3">
                <span class="label-tooltip" data-toggle="tooltip" title="" data-original-title="Удалить все заказы из интернет магазина, и всю информацию о них.">
                    Удалить заказы товара из магазина
                </span>
            </label>
            <input name="submitClearZakaz" class="btn btn-default button" type="submit" id="submitClearZakaz" value="Удалить заказы" onclick="return confirm('Операция очистки заказов необратима!!!\nТочно желаете удалить все заказы?')" >
        </div>

        <div class="panel">
            <label class="control-label col-lg-3">
                <span class="label-tooltip" data-toggle="tooltip" title="" data-original-title="Очистка коротких описаний у всех товаров в интернет магазине.">
                    Удалить короткие описания
                </span>
            </label>
            <input name="submitClearShDesc" class="btn btn-default button" type="submit" value="Удалить короткие описания" onclick="return confirm('Очистка коротких описаний необратима!!!\nТочно хотите удалить короткие описания?')" />
        </div>


        <div class="panel">
            <label class="control-label col-lg-3">
                <span class="label-tooltip" data-toggle="tooltip" title="" data-original-title="Очистка описаний у всех товаров в интернет магазине.">
                    Удалить описания
                </span>
            </label>
            <input name="submitClearDesc" class="btn btn-default button" type="submit" value="Удалить описания"
			onclick="return confirm('Очистка описаний необратима!!!\nТочно хотите удалить описания?')">
        </div>


        <div class="panel">
            <label class="control-label col-lg-3">
                <span class="label-tooltip" data-toggle="tooltip" title="" data-original-title="Очистка категорий и значений свойств, привязки свойств к товарам в интернет магазине.">
                    Удалить все свойства
                </span>
            </label>
            <input name="submitClearFeatures" class="btn btn-default button" type="submit" value="Удалить свойства"
			onclick="return confirm('Очистка свойств необратима!!!\nТочно хотите удалить свойства?')">
        </div>

        <div class="panel">
            <label class="control-label col-lg-3">
                <span class="label-tooltip" data-toggle="tooltip" title="" data-original-title="Удалить изображения у товаров в интернет магазине (также происходит удаление папок с картинками на хостинге).">
                    Удалить все изображения
                </span>
            </label>
            <input name="submitClearImages" class="btn btn-default button" type="submit" value="Удалить изображения"
			onclick="return confirm('Очистка изображений необратима!!!\nТочно хотите удалить изображения?')">
        </div>

        <div class="panel">
            <label class="control-label col-lg-3">
                <span class="label-tooltip" data-toggle="tooltip" title="" data-original-title="Удалить атрибуты и значения атрибутов, комбинаций товаров, а также остатки и цены у товаров с комбинациями.">
                    Удалить атрибуты
                </span>
            </label>
            <input name="submitClearAttrib" class="btn btn-default button" type="submit" value="Удалить атрибуты"
			onclick="return confirm('Очистка комбинаций и атрибутов необратима!!!\nТочно хотите удалить?')">
        </div>

        <div class="panel">
            <label class="control-label col-lg-3">
                <span class="label-tooltip" data-toggle="tooltip" title="" data-original-title="Удалить все вложения , а также привязку вложений к товарам.">
                    Удалить вложения
                </span>
            </label>
            <input name="submitClearAttach" class="btn btn-default button" type="submit" value="Удалить вложения"
			onclick="return confirm('Очистка вложений необратима!!!\nТочно хотите удалить?')">
        </div>

        <div class="panel">
            <label class="control-label col-lg-3">
                <span class="label-tooltip" data-toggle="tooltip" title="" data-original-title=" Активировать все деактивированные товары">
                    Активировать все деактивированные товары
                </span>
            </label>
            <input name="submitActiv" class="btn btn-default button" type="submit" value="Активировать все деактивированные товары"
			onclick="return confirm('Продолжить?')">
        </div>


        <div class="panel">
            <label class="control-label col-lg-3">
                <span class="label-tooltip" data-toggle="tooltip" title="" data-original-title="Сделать все товары видимыми">
                    Сделать все товары видимыми
                </span>
            </label>
            <input name="submitBoth" class="btn btn-default button" type="submit" value="Сделать все товары видимыми"
			onclick="return confirm('Продолжить?')">
        </div>

        <div class="panel">
            <label class="control-label col-lg-3">
                <span class="label-tooltip" data-toggle="tooltip" title="" data-original-title="Удалить все типы цен">
                    Удалить все типы цен
                </span>
            </label>
            <input name="submitTypePrice" class="btn btn-default button" type="submit" value="Удалить все типы цен"
			onclick="return confirm('Продолжить?')">
        </div>


        <div class="panel">
            <label class="control-label col-lg-3">
                <span class="label-tooltip" data-toggle="tooltip" title="" data-original-title="Очистить папку Upload на хостинге.">
                    Очистить папку
                    <strong>
                        Upload
                    </strong>
                </span>
            </label>
            <input name="submitClearUpload" class="btn btn-default button" type="submit" value="Очистить Upload"
			onclick="return confirm('Продолжить?')">
        </div>


        <div class="panel" style="height:120px;">
        <label class="control-label col-lg-3">
            <span class="label-tooltip" data-toggle="tooltip" title="" data-original-title="Удалить товар с интернет магазина с указанным id.">
                Удаление дублей товаров
            </span>
        </label>

        <div class="col-lg-5">
            <label class="control-label col-lg-5">
                id дублирующегося товара
            </label>
            <input type="number" class="col-lg" min="1"  style="width: 50px" name="id_dubl" >
            <p class="help-block">
                Веедите id дублирующегося товара
            </p>

            <input name="submitClearDuplicate" class="btn btn-default button"  type="submit" value="Удалить товар"
			onclick="return confirm('Продолжить?')">
        </div>



</div>
		<div class="panel">
            <label class="control-label col-lg-3">
                <span class="label-tooltip" data-toggle="tooltip" title="" data-original-title="В режиме отладки на экран будут выводиться ошибки PHP и SQL. Глобально для всего магазина.">
                   Режим отладки
                </span>
            </label>
            <input name="submitDebugMod" class="btn btn-default button" type="submit" value={$value}
			onclick="return confirm('Продолжить?');window.location.reload();">
        </div>
</form>
</div>
