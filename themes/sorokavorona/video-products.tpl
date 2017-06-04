{*
* 2007-2016 PrestaShop
*
* NOTICE OF LICENSE
*
* This source file is subject to the Academic Free License (AFL 3.0)
* that is bundled with this package in the file LICENSE.txt.
* It is also available through the world-wide-web at this URL:
* http://opensource.org/licenses/afl-3.0.php
* If you did not receive a copy of the license and are unable to
* obtain it through the world-wide-web, please send an email
* to license@prestashop.com so we can send you a copy immediately.
*
* DISCLAIMER
*
* Do not edit or add to this file if you wish to upgrade PrestaShop to newer
* versions in the future. If you wish to customize PrestaShop for your
* needs please refer to http://www.prestashop.com for more information.
*
*  @author PrestaShop SA <contact@prestashop.com>
*  @copyright  2007-2016 PrestaShop SA
*  @license    http://opensource.org/licenses/afl-3.0.php  Academic Free License (AFL 3.0)
*  International Registered Trademark & Property of PrestaShop SA
*}
{include file="$tpl_dir./errors.tpl"}

	<section class="page-title">
		<div class="container-fluid">
			<div class="row">
				<div class="col-md-3 col-sm-3"><a href="" class="btn b-sec">← назад</a></div>

				<div class="col-md-6 col-sm-6 page-title-title"><h1>Товары с видео</h1></div>

				<div class="col-md-3 col-sm-3 text-right"><a href="" class="btn b-sec">следующая →</a></div>
			</div>
		</div>
	</section>

	<section class="catalog">
		<div class="container-fluid">
			<div class="row">
				{*}
				<div class="col-md-12">
					<div class="filter-box">
						<ul>
                            {foreach from=$subcategories item=subcategory}
								<li>
									<input id="{$subcategory->id}" type="checkbox" class="checkbox" />
									<label for="{$subcategory->id}">{$subcategory.name|truncate:25:'...'|escape:'html':'UTF-8'}</label>
								</li>
                            {/foreach}
						</ul>
					</div>
				</div>
				{*}

				<div class="col-md-12">
					<div class="filter-box">
						<div class="row">
							<div class="col-md-3 col-sm-4 price-filter">
								<h6>цена</h6>
								<p> <span id="layered_price_range">27,25грн. - 61,00грн.</span></p>
								<div class="layered_slider_container">
									<div class="layered_slider ui-slider ui-slider-horizontal ui-widget ui-widget-content ui-corner-all">
										<div style="left: 21.999999999999996%; width: 78%;" class="ui-slider-range ui-widget-header ui-corner-all"></div><a href="#" style="left: 21.999999999999996%;" class="ui-slider-handle ui-state-default ui-corner-all"></a><a href="#" style="left: 100%;" class="ui-slider-handle ui-state-default ui-corner-all"></a>
									</div>
								</div>
							</div>
							<div class="col-md-3 col-sm-4 age-filter">
								<h6>возраст</h6>
								<p> <span id="layered_price_range">27 - 61 год</span></p>
								<div class="layered_slider_container">
									<div class="layered_slider ui-slider ui-slider-horizontal ui-widget ui-widget-content ui-corner-all">
										<div style="left: 21.999999999999996%; width: 78%;" class="ui-slider-range ui-widget-header ui-corner-all"></div><a href="#" style="left: 21.999999999999996%;" class="ui-slider-handle ui-state-default ui-corner-all"></a><a href="#" style="left: 100%;" class="ui-slider-handle ui-state-default ui-corner-all"></a>
									</div>
								</div>
							</div>
							<div class="col-md-3 col-sm-4 sex-filter">
								<h6>Пол</h6>
								<ul>
									<li>
										<input id="1" type="radio" name="pol">
										<label for="1"><img src="media/icons/6.png" alt=""><span>для девочек</span></label>
									</li>
									<li>
										<input id="2" type="radio" name="pol">
										<label for="2"><img src="media/icons/7.png" alt=""><span>для мальчиков</span></label>
									</li>
									<li>
										<input id="3" type="radio" name="pol">
										<label for="3"><img src="media/icons/8.png" alt=""><span>для тех и тех</span></label>
									</li>
								</ul>
							</div>
							<div class="col-md-3 col-sm-4 type-box-filter">
								<h6>Тип упаковки</h6>
								<div class="select">
									<select>
										<option value="0" selected="selected">Выберите тип упаковки</option>
										<option value="05">коробка</option>
										<option value="07">пакет</option>
										<option value="12">без упаковки</option>
										<option value="14">блистер</option>
									</select><span class="select__arrow"></span>
								</div>
							</div>
						</div>
					</div>
				</div>

				{if $products}
					<div class="col-md-12">
						<div class="row">
							{include file="./product-list.tpl" products=$products}

							<div class="col-md-12">
								<div class="contine-watch"><a href="" class="btn">Показать больше</a></div>
							</div>
						</div>
					</div>
				{/if}
			</div>
		</div>
	</section>

