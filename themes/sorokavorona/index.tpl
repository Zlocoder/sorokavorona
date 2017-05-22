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
<section class="alert-box-main blue">
	<div class="container-fluid">
		<div class="row">
			<div class="col-xs-12">
				<h3>Объявление которое нужно заметить</h3>
				<p>Краткий текст, описывающий это изменение</p>
			</div>
		</div>
	</div>
</section>

{if isset($HOOK_HOME_TAB_CONTENT) && $HOOK_HOME_TAB_CONTENT|trim}
	<section class="slider-product-main-box">
		<div class="container-fluid">
			<div class="row">
				<div class="col-md-12">
					<ul id="home-page-tabs" class="nav nav-tabs tabs-for-products">
						<div class="slider-main-product-nav">
                            {$HOOK_HOME_TAB}
						</div>
						<div class="slider-main-product">
                            {$HOOK_HOME_TAB_CONTENT}
						</div>
					</ul>
				</div>
			</div>
		</div>
	</section>
{/if}

<section class="main-collection"><span class="icon icon29"></span><span class="icon icon28"></span><span class="icon icon27"></span><span class="icon icon26"></span><span class="icon icon25"></span><span class="icon icon24"></span><span class="icon icon23"></span>
	<div class="container-fluid">
		<div class="row">
			<div class="col-md-12 text-center">
				<h6>коллекции</h6><br>
			</div>
			<div class="col-md-3">
				<div class="collection-item">
					<div style="background-image: url(media/images/03.jpg)" class="circle-pic"></div><a href="">

						Игрушки на радиоуправлении</a>
				</div>
			</div>
			<div class="col-md-3">
				<div class="collection-item">
					<div style="background-image: url(media/images/00.jpg)" class="circle-pic"></div><a href="">

						Куклы для наших девочек</a>
				</div>
			</div>
			<div class="col-md-3">
				<div class="collection-item">
					<div style="background-image: url(media/images/04.jpg)" class="circle-pic"></div><a href="">

						Мальчишке на 10 лет</a>
				</div>
			</div>
			<div class="col-md-3">
				<div class="collection-item">
					<div style="background-image: url(media/images/01.jpg)" class="circle-pic"></div><a href="">

						Играем на природе</a>
				</div>
			</div>
		</div>
	</div>
</section>

{if isset($HOOK_HOME) && $HOOK_HOME|trim}
	<div class="clearfix">{$HOOK_HOME}</div>
{/if}