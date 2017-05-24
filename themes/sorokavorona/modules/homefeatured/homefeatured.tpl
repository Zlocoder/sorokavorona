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

<!-- MODULE Home Featured Products -->
{if isset($products) && $products}
	<div class="row">
        {include file="$tpl_dir./product-list.tpl" products=$products class='homefeatured tab-pane' id='homefeatured'}
	</div>

	<div class="row">
		<div class="col-md-4"><a href="">Новинки</a></div>
		<div class="col-md-4">
			<div class="contine-watch"><a href="catalog.html" class="btn">Показать больше</a></div>
		</div>
		<div class="col-md-4"><a href="">Популярные</a></div>
	</div>
{else}
	<ul id="blocknewproducts" class="blocknewproducts tab-pane">
		<li class="alert alert-info">{l s='No new products at this time.' mod='blocknewproducts'}</li>
	</ul>
{/if}