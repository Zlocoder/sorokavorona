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

<div class="cart-item" id="product_{$product.id_product}_{$product.id_product_attribute}_{if $quantityDisplayed > 0}nocustom{else}0{/if}_{$product.id_address_delivery|intval}{if !empty($product.gift)}_gift{/if}" class="cart_item{if isset($productLast) && $productLast && (!isset($ignoreProductLast) || !$ignoreProductLast)} last_item{/if}{if isset($productFirst) && $productFirst} first_item{/if}{if isset($customizedDatas.$productId.$productAttributeId) AND $quantityDisplayed == 0} alternate_item{/if} address_{$product.id_address_delivery|intval} {if $odd}odd{else}even{/if}">
	<div class="row">
		<div class="col-md-6 col-sm-6">
			<div class="row">
				<div class="col-md-4 cart_product">
					<a href="{$link->getProductLink($product.id_product, $product.link_rewrite, $product.category, null, null, $product.id_shop, $product.id_product_attribute, false, false, true)|escape:'html':'UTF-8'}"><img src="{$link->getImageLink($product.link_rewrite, $product.id_image, 'small_default')|escape:'html':'UTF-8'}" alt="{$product.name|escape:'html':'UTF-8'}" {if isset($smallSize)}width="{$smallSize.width}" height="{$smallSize.height}" {/if} /></a>
				</div>
				<div class="col-md-8 cart_description">
                    {capture name=sep} : {/capture}
                    {capture}{l s=' : '}{/capture}
					<h2 class="product-name">{$product.name|escape:'html':'UTF-8'}</h2>
					<p>{if $product.reference}<p class="cart_ref">{l s='Артикул'}<span>{$smarty.capture.default}{$product.reference|escape:'html':'UTF-8'}</span></p>{/if}</p>
                    {if isset($product.attributes) && $product.attributes}<small><a href="{$link->getProductLink($product.id_product, $product.link_rewrite, $product.category, null, null, $product.id_shop, $product.id_product_attribute, false, false, true)|escape:'html':'UTF-8'}">{$product.attributes|@replace: $smarty.capture.sep:$smarty.capture.default|escape:'html':'UTF-8'}</a></small>{/if}
				</div>
			</div>
		</div>

		<div class="col-md-6 col-sm-6">
			<div class="row">
				<div class="col-md-3 cart_quantity" data-title="{l s='Quantity'}">
					<div class="product-how-quantity">
						<div class="cart_quantity_button clearfix" style="float: left;">
                            {if $product.minimal_quantity < ($product.cart_quantity-$quantityDisplayed) OR $product.minimal_quantity <= 1}
								<a rel="nofollow" style="text-decoration: none;" class="cart_quantity_down" id="cart_quantity_down_{$product.id_product}_{$product.id_product_attribute}_{if $quantityDisplayed > 0}nocustom{else}0{/if}_{$product.id_address_delivery|intval}" href="{$link->getPageLink('cart', true, NULL, "add=1&amp;id_product={$product.id_product|intval}&amp;ipa={$product.id_product_attribute|intval}&amp;id_address_delivery={$product.id_address_delivery|intval}&amp;op=down&amp;token={$token_cart}")|escape:'html':'UTF-8'}" title="{l s='Subtract'}">
									<span class="quantity-minus">-</span>
								</a>
                            {else}
								<a style="text-decoration: none;" class="cart_quantity_down disabled" href="#" id="cart_quantity_down_{$product.id_product}_{$product.id_product_attribute}_{if $quantityDisplayed > 0}nocustom{else}0{/if}_{$product.id_address_delivery|intval}" title="{l s='You must purchase a minimum of %d of this product.' sprintf=$product.minimal_quantity}">
									<span class="quantity-minus">-</span>
								</a>
                            {/if}
						</div>
                        {if (isset($cannotModify) && $cannotModify == 1)}
							<span>
				{if $quantityDisplayed == 0 AND isset($customizedDatas.$productId.$productAttributeId)}
                    {$product.customizationQuantityTotal}
                {else}
                    {$product.cart_quantity-$quantityDisplayed}
                {/if}
			</span>
                        {else}
                            {if isset($customizedDatas.$productId.$productAttributeId) AND $quantityDisplayed == 0}
								<span id="cart_quantity_custom_{$product.id_product}_{$product.id_product_attribute}_{$product.id_address_delivery|intval}" >{$product.customizationQuantityTotal}</span>
                            {/if}
                            {if !isset($customizedDatas.$productId.$productAttributeId) OR $quantityDisplayed > 0}

								<input type="hidden" value="{if $quantityDisplayed == 0 AND isset($customizedDatas.$productId.$productAttributeId)}{$customizedDatas.$productId.$productAttributeId|@count}{else}{$product.cart_quantity-$quantityDisplayed}{/if}" name="quantity_{$product.id_product}_{$product.id_product_attribute}_{if $quantityDisplayed > 0}nocustom{else}0{/if}_{$product.id_address_delivery|intval}_hidden" />
								<input  style="float: left;" size="2" type="text" autocomplete="off" class="cart_quantity_input form-control grey" value="{if $quantityDisplayed == 0 AND isset($customizedDatas.$productId.$productAttributeId)}{$customizedDatas.$productId.$productAttributeId|@count}{else}{$product.cart_quantity-$quantityDisplayed}{/if}"  name="quantity_{$product.id_product}_{$product.id_product_attribute}_{if $quantityDisplayed > 0}nocustom{else}0{/if}_{$product.id_address_delivery|intval}" />
								<div class="cart_quantity_button clearfix">
									<a rel="nofollow" style="text-decoration: none;" class="cart_quantity_up" id="cart_quantity_up_{$product.id_product}_{$product.id_product_attribute}_{if $quantityDisplayed > 0}nocustom{else}0{/if}_{$product.id_address_delivery|intval}" href="{$link->getPageLink('cart', true, NULL, "add=1&amp;id_product={$product.id_product|intval}&amp;ipa={$product.id_product_attribute|intval}&amp;id_address_delivery={$product.id_address_delivery|intval}&amp;token={$token_cart}")|escape:'html':'UTF-8'}" title="{l s='Add'}"><span class="quantity-plus">+</span></a>
								</div>
                            {/if}
                        {/if}
					</div>
				</div>
                {if $PS_STOCK_MANAGEMENT}
					<div class="col-md-3 cart_avail"><span class="label{if $product.quantity_available <= 0 && isset($product.allow_oosp) && !$product.allow_oosp} label-danger{elseif $product.quantity_available <= 0} label-warning{else} label-success{/if}">{if $product.quantity_available <= 0}{if isset($product.allow_oosp) && $product.allow_oosp}{if isset($product.available_later) && $product.available_later}{$product.available_later}{else}{l s='In Stock'}{/if}{else}{l s='Out of stock'}{/if}{else}{if isset($product.available_now) && $product.available_now}{$product.available_now}{else}{l s='In Stock'}{/if}{/if}</span>{if !$product.is_virtual}{hook h="displayProductDeliveryTime" product=$product}{/if}</div>
                {/if}
				<div class="col-md-3 col-xs-5 cart_unit" data-title="{l s='Unit price'}">
					<div class="product-info-price">
						<h3>
							<ul style="list-style-type: none; padding: 0 0 0 0; margin: 0 0 0 0;" class="price" id="product_price_{$product.id_product}_{$product.id_product_attribute}{if $quantityDisplayed > 0}_nocustom{/if}_{$product.id_address_delivery|intval}{if !empty($product.gift)}_gift{/if}">
                                {if !empty($product.gift)}
									<li class="gift-icon">{l s='Gift!'}</li>
                                {else}
                                    {if !$priceDisplay}
										<li class="price{if isset($product.is_discounted) && $product.is_discounted && isset($product.reduction_applies) && $product.reduction_applies} special-price{/if}">{convertPrice price=$product.price_wt}</li>
                                    {else}
										<li class="price{if isset($product.is_discounted) && $product.is_discounted && isset($product.reduction_applies) && $product.reduction_applies} special-price{/if}">{convertPrice price=$product.price}</li>
                                    {/if}
                                    {if isset($product.is_discounted) && $product.is_discounted && isset($product.reduction_applies) && $product.reduction_applies}
										<li class="price-percent-reduction small">
                                            {if !$priceDisplay}
                                                {if isset($product.reduction_type) && $product.reduction_type == 'amount'}
                                                    {assign var='priceReduction' value=($product.price_wt - $product.price_without_specific_price)}
                                                    {assign var='symbol' value=$currency->sign}
                                                {else}
                                                    {assign var='priceReduction' value=(($product.price_without_specific_price - $product.price_wt)/$product.price_without_specific_price) * 100 * -1}
                                                    {assign var='symbol' value='%'}
                                                {/if}
                                            {else}
                                                {if isset($product.reduction_type) && $product.reduction_type == 'amount'}
                                                    {assign var='priceReduction' value=($product.price - $product.price_without_specific_price)}
                                                    {assign var='symbol' value=$currency->sign}
                                                {else}
                                                    {assign var='priceReduction' value=(($product.price_without_specific_price - $product.price)/$product.price_without_specific_price) * -100}
                                                    {assign var='symbol' value='%'}
                                                {/if}
                                            {/if}
                                            {if $symbol == '%'}
												&nbsp;{$priceReduction|string_format:"%.2f"|regex_replace:"/[^\d]0+$/":""}{$symbol}&nbsp;
                                            {else}
												&nbsp;{convertPrice price=$priceReduction}&nbsp;
                                            {/if}
										</li>
										<li class="old-price">{convertPrice price=$product.price_without_specific_price}</li>
                                    {/if}
                                {/if}
							</ul>
						</h3>
					</div>
				</div>

				<div class="col-md-3 col-xs-5 cart_total" data-title="{l s='Total'}">
					<div class="product-info-price all">
						<h3 class="price" id="total_product_price_{$product.id_product}_{$product.id_product_attribute}{if $quantityDisplayed > 0}_nocustom{/if}_{$product.id_address_delivery|intval}{if !empty($product.gift)}_gift{/if}">
                            {if !empty($product.gift)}
								<span class="gift-icon">{l s='Gift!'}</span>
                            {else}
                                {if $quantityDisplayed == 0 AND isset($customizedDatas.$productId.$productAttributeId)}
                                    {if !$priceDisplay}{displayPrice price=$product.total_customization_wt}{else}{displayPrice price=$product.total_customization}{/if}
                                {else}
                                    {if !$priceDisplay}{displayPrice price=$product.total_wt}{else}{displayPrice price=$product.total}{/if}
                                {/if}
                            {/if}
						</h3>
                        {hook h='displayCartExtraProductActions' product=$product}
					</div>
				</div>

                {if !isset($noDeleteButton) || !$noDeleteButton}
					<div class="col-md-3 col-xs-2 remove-box cart_delete text-center" data-title="{l s='Delete'}">
                        {if (!isset($customizedDatas.$productId.$productAttributeId) OR $quantityDisplayed > 0) && empty($product.gift)}

							<a rel="nofollow" title="{l s='Delete'}" class="cart_quantity_delete" id="{$product.id_product}_{$product.id_product_attribute}_{if $quantityDisplayed > 0}nocustom{else}0{/if}_{$product.id_address_delivery|intval}" href="{$link->getPageLink('cart', true, NULL, "delete=1&amp;id_product={$product.id_product|intval}&amp;ipa={$product.id_product_attribute|intval}&amp;id_address_delivery={$product.id_address_delivery|intval}&amp;token={$token_cart}")|escape:'html':'UTF-8'}">
								<button class="remove-item"> <img src="/themes/sorokavorona/media/icons/delete.svg" alt=""></button>
							</a>

                        {else}

                        {/if}
					</div>
                {/if}

			</div>
		</div>
	</div>
</div>
