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
{if $errors|@count == 0}
    {if !isset($priceDisplayPrecision)}
        {assign var='priceDisplayPrecision' value=2}
    {/if}
    {if !$priceDisplay || $priceDisplay == 2}
        {assign var='productPrice' value=$product->getPrice(true, $smarty.const.NULL, 6)}
        {assign var='productPriceWithoutReduction' value=$product->getPriceWithoutReduct(false, $smarty.const.NULL)}
    {elseif $priceDisplay == 1}
        {assign var='productPrice' value=$product->getPrice(false, $smarty.const.NULL, 6)}
        {assign var='productPriceWithoutReduction' value=$product->getPriceWithoutReduct(true, $smarty.const.NULL)}
    {/if}

	<div itemscope itemtype="https://schema.org/Product">
		<meta itemprop="url" content="{$link->getProductLink($product)}">

		<section class="product-page-hero">
			<ul class="slider-product">
				<li>
					<div style="background-image: url({$link->getImageLink($product->link_rewrite, $cover.id_image, 'large_default')|escape:'html':'UTF-8'})" class="pic"></div>
				</li>
                {if isset($images)}
                    {foreach from=$images item=image name=thumbnails}
                        {assign var=imageIds value="`$product->id`-`$image.id_image`"}
                        {if !empty($image.legend)}
                            {assign var=imageTitle value=$image.legend|escape:'html':'UTF-8'}
                        {else}
                            {assign var=imageTitle value=$product->name|escape:'html':'UTF-8'}
                        {/if}
						<li>
							<div style="background-image: url({$link->getImageLink($product->link_rewrite, $imageIds, 'large_default')|escape:'html':'UTF-8'}" class="pic"></div>
						</li>
                    {/foreach}
                {/if}

				<!--
                <li>
                    <iframe type="text/html" src="https://www.youtube.com/embed/uxpDa-c-4Mc?controls=0&amp;fs=0&amp;rel=0&amp;showinfo=0&amp;color=white&amp;enablejsapi=1" frameborder="0" allowfullscreen=""></iframe>
                </li>
                -->
			</ul>
			<div class="product-page-controls">
				<div class="container-fluid">
					<div class="row">
						<div class="col-md-6"><a href="" class="btn b-sec">← Назад</a></div>
						<div class="col-md-6 text-right"></div>
					</div>
				</div>
			</div>
			<div class="container-fluid product-page-content">
				<div class="row product-page-title">
					<div class="col-md-6 col-sm-8 col-xs-12">
						<div class="row">
							<div class="col-md-12">
								<div class="product-info-title">
									<h1 itemprop="name">{$product->name|escape:'html':'UTF-8'}</h1>
								</div>
							</div>
						</div>
						<div class="row">
							<div class="col-md-6">
								<div class="product-page-description">
									<h6>Описание</h6>
                                    {if isset($product) && $product->description}
										<p>{$product->description}</p>
                                    {/if}
								</div>
							</div>
							<div class="col-md-6">
								<div class="product-plus-and-minus">
									<h6><i class="fa fa-plus-circle"> </i> Плюсы</h6>
									<ul>
										<li>Хорошее качество</li>
									</ul>
									<h6><i class="fa fa-minus-circle"></i> Минусы</h6>
									<ul>
										<li>не обнаружено</li>
									</ul>
								</div>
							</div>
						</div>
					</div>
					<div class="col-md-3 col-sm-4">
						<div class="product-info-price">
							<h3>{convertPrice price=$productPrice|floatval}
								<span></span>
							</h3>
						</div>
						<div class="product-how-quantity">
							<a href="#" data-field-qty="qty" class="product_quantity_down">
								<span class="quantity-minus">-</span>
							</a>
							<input type="number" min="1" name="qty" id="quantity_wanted" class="quantity-amount" value="{if isset($quantityBackup)}{$quantityBackup|intval}{else}{if $product->minimal_quantity > 1}{$product->minimal_quantity}{else}1{/if}{/if}" />
							<a href="#" data-field-qty="qty" class="product_quantity_up">
								<span class="quantity-plus">+</span>
							</a>
						</div>

						<div id="add_to_cart" class="product-info-buy" data-product_id="{$product->id}">
							<button type="submit" name="Submit" class="btn exclusive added"><i class="fa fa-cart-plus"></i> Купить</button>
						</div>

						<!-- end box-cart-bottom -->
					</div>
					<div class="col-md-3">
						<div class="row">
							<div class="col-md-12">
								<div class="product-page-features">
									<ul>
										<li>
                                            {if isset($features) && $features}
                                                {foreach from=$features item=feature}
                                                    {if $feature.id_feature == '8'}
														<span>{$feature.name|escape:'html':'UTF-8'}:</span>
                                                        {$feature.value|escape:'html':'UTF-8'}
                                                    {/if}
                                                {/foreach}
                                            {/if}
										</li>
										<li id="product_reference"{if empty($product->reference) || !$product->reference} style="display: none;"{/if} style="color: #777; text-transform: uppercase; font-size: 12px; letter-spacing: 1px;">
											<label>{l s='Артикул:'} </label>
											<span class="editable" itemprop="sku" style="text-transform: none; color: #000; margin-bottom: 5px; font-size: 16px;" {if !empty($product->reference) && $product->reference} content="{$product->reference}"{/if}>{if !isset($groups)}{$product->reference|escape:'html':'UTF-8'}{/if}</span>
										</li>

                                        {foreach from=$features item=feature}
											<li>
                                                {if $feature.id_feature == '8'}
													<p style="display: none;">{$feature.name|escape:'html':'UTF-8'}:
                                                        {$feature.value|escape:'html':'UTF-8'}</p>
                                                {else}

													<span>{$feature.name|escape:'html':'UTF-8'}:</span>
                                                    {$feature.value|escape:'html':'UTF-8'}

                                                {/if}
											</li>

                                        {/foreach}
									</ul>
								</div>
							</div>
						</div>

					</div>
				</div>
				<div class="row comments-and-other-row">
					<div class="col-md-3 col-sm-6">
						<div class="comments-for-login">
							<h6>ОСТАВИТЬ КОММЕНТАРИЙ</h6>
							<input type="text" placeholder="Заголовок">
							<textarea id="comment-body" name="" placeholder="Комментарий" maxlength="240"></textarea>

                            {if $allow_guests == true && !$is_logged}
								<input id="commentCustomerName" name="customer_name" type="text" value=""/>
                            {/if}
							<input type="submit" value="Публиковать" class="btn">


						</div>

					</div>
					<div class="col-md-4 col-md-push-5 col-sm-6">
						<div class="product-page-rating">
							<h6>Оценки</h6>
							<div class="rating-item design-rating">
								<div class="row">
									<div class="col-xs-4"><span>Сорока</span></div>
									<div class="col-xs-4">
										<h4>Дизайн</h4>
									</div>
									<div class="col-xs-4">
										<h5 class="rating-count-soroka">8.7/10</h5>
									</div>
								</div>
								<div class="row">
									<div class="col-xs-12">
										<div class="rating-bar soroka">
											<div style="width: 85%" class="bar"></div>
										</div>
										<div class="rating-bar user-soroka">
											<div class="user-rating-controls">
												<ul>
													<li>
														<input type="radio" name="user-design-rating" value="1"><span>1</span>
													</li>
													<li>
														<input type="radio" name="user-design-rating" value="2"><span>2</span>
													</li>
													<li>
														<input type="radio" name="user-design-rating" value="3"><span>3</span>
													</li>
													<li>
														<input type="radio" name="user-design-rating" value="4"><span>4</span>
													</li>
													<li>
														<input type="radio" name="user-design-rating" value="5"><span>5</span>
													</li>
													<li>
														<input type="radio" name="user-design-rating" value="6"><span>6</span>
													</li>
													<li>
														<input type="radio" name="user-design-rating" value="7"><span>7</span>
													</li>
													<li>
														<input type="radio" name="user-design-rating" value="8"><span>8</span>
													</li>
													<li>
														<input type="radio" name="user-design-rating" value="9"><span>9</span>
													</li>
													<li>
														<input type="radio" name="user-design-rating" value="10"><span>10</span>
													</li>
												</ul>
											</div>
											<div style="width: 87%" class="bar"></div>
										</div>
									</div>
								</div>
								<div class="row">
									<div class="col-xs-3"><span>Клиенты 3</span></div>
									<div class="col-xs-6">
										<div class="control-buttons-box"><span class="rating-text">Поставить</span>
											<button class="btn open-rating-design">Оценить</button>
											<div class="count-rating">

												Ваша <span></span>
											</div>
										</div>
									</div>
									<div class="col-xs-3">
										<h5 class="rating-count-user">8.7/10</h5>
									</div>
								</div>
							</div>
							<div class="rating-item functional-rating">
								<div class="row">
									<div class="col-xs-4"><span>Сорока</span></div>
									<div class="col-xs-4">
										<h4>Функционал</h4>
									</div>
									<div class="col-xs-4">
										<h5 class="rating-count-soroka">8.7/10</h5>
									</div>
								</div>
								<div class="row">
									<div class="col-xs-12">
										<div class="rating-bar soroka">
											<div style="width: 85%" class="bar"></div>
										</div>
										<div class="rating-bar user-soroka">
											<div class="user-rating-controls">
												<ul>
													<li>
														<input type="radio" name="user-design-rating" value="1"><span>1</span>
													</li>
													<li>
														<input type="radio" name="user-design-rating" value="2"><span>2</span>
													</li>
													<li>
														<input type="radio" name="user-design-rating" value="3"><span>3</span>
													</li>
													<li>
														<input type="radio" name="user-design-rating" value="4"><span>4</span>
													</li>
													<li>
														<input type="radio" name="user-design-rating" value="5"><span>5</span>
													</li>
													<li>
														<input type="radio" name="user-design-rating" value="6"><span>6</span>
													</li>
													<li>
														<input type="radio" name="user-design-rating" value="7"><span>7</span>
													</li>
													<li>
														<input type="radio" name="user-design-rating" value="8"><span>8</span>
													</li>
													<li>
														<input type="radio" name="user-design-rating" value="9"><span>9</span>
													</li>
													<li>
														<input type="radio" name="user-design-rating" value="10"><span>10</span>
													</li>
												</ul>
											</div>
											<div style="width: 87%" class="bar"></div>
										</div>
									</div>
								</div>
								<div class="row">
									<div class="col-xs-3"><span>Клиенты 3</span></div>
									<div class="col-xs-6">
										<div class="control-buttons-box"><span class="rating-text">Поставить</span>
											<button class="btn open-rating-functional">Оценить</button>
											<div class="count-rating">

												Ваша <span></span>
											</div>
										</div>
									</div>
									<div class="col-xs-3">
										<h5 class="rating-count-user">8.7/10</h5>
									</div>
								</div>
							</div>
							<div class="rating-item quality-rating">
								<div class="row">
									<div class="col-xs-4"><span>Сорока</span></div>
									<div class="col-xs-4">
										<h4>Качество</h4>
									</div>
									<div class="col-xs-4">
										<h5 class="rating-count-soroka">8.7/10</h5>
									</div>
								</div>
								<div class="row">
									<div class="col-xs-12">
										<div class="rating-bar soroka">
											<div style="width: 85%" class="bar"></div>
										</div>
										<div class="rating-bar user-soroka">
											<div class="user-rating-controls">
												<ul>
													<li>
														<input type="radio" name="user-rading" value="1"><span>1</span>
													</li>
													<li>
														<input type="radio" name="user-rading" value="2"><span>2</span>
													</li>
													<li>
														<input type="radio" name="user-rading" value="3"><span>3</span>
													</li>
													<li>
														<input type="radio" name="user-rading" value="4"><span>4</span>
													</li>
													<li>
														<input type="radio" name="user-rading" value="5"><span>5</span>
													</li>
													<li>
														<input type="radio" name="user-rading" value="6"><span>6</span>
													</li>
													<li>
														<input type="radio" name="user-rading" value="7"><span>7</span>
													</li>
													<li>
														<input type="radio" name="user-rading" value="8"><span>8</span>
													</li>
													<li>
														<input type="radio" name="user-rading" value="9"><span>9</span>
													</li>
													<li>
														<input type="radio" name="user-rading" value="10"><span>10</span>
													</li>
												</ul>
											</div>
											<div style="width: 57%" class="bar"></div>
										</div>
									</div>
								</div>
								<div class="row">
									<div class="col-xs-4"><span>Клиенты 3</span></div>
									<div class="col-xs-4">
										<div class="control-buttons-box"><span class="rating-text">Поставить</span>
											<button class="btn open-rating-quality">Оценить</button>
											<div class="count-rating">

												Ваша <span></span>
											</div>
										</div>
									</div>
									<div class="col-xs-4">
										<h5 class="rating-count-user">8.7/10</h5>
									</div>
								</div>
							</div>
						</div>
					</div>
					<div class="col-md-5 col-md-pull-4 col-sm-12 col-xs-12">
						<div class="user-comments">
							<h6>Комментарии:</h6>
							<div id="idTab5">
								<div id="product_comments_block_tab">
                                    {if $comments}
                                        {foreach from=$comments item=comment}
                                            {if $comment.content}
												<div class="comment row" itemprop="review" itemscope itemtype="https://schema.org/Review">
													<div class="comment_author col-sm-2">
														<span>{l s='Grade' mod='productcomments'}&nbsp;</span>
														<div class="star_content clearfix"  itemprop="reviewRating" itemscope itemtype="https://schema.org/Rating">
                                                            {section name="i" start=0 loop=5 step=1}
                                                                {if $comment.grade le $smarty.section.i.index}
																	<div class="star"></div>
                                                                {else}
																	<div class="star star_on"></div>
                                                                {/if}
                                                            {/section}
															<meta itemprop="worstRating" content = "0" />
															<meta itemprop="ratingValue" content = "{$comment.grade|escape:'html':'UTF-8'}" />
															<meta itemprop="bestRating" content = "10" />
														</div>
														<div class="comment_author_infos">
															<strong itemprop="author">{$comment.customer_name|escape:'html':'UTF-8'}</strong>
															<meta itemprop="datePublished" content="{$comment.date_add|escape:'html':'UTF-8'|substr:0:10}" />
															<em>{dateFormat date=$comment.date_add|escape:'html':'UTF-8' full=0}</em>
														</div>
													</div> <!-- .comment_author -->

													<div class="comment_details col-sm-10">
														<p itemprop="name" class="title_block">
															<strong>{$comment.title}</strong>
														</p>
														<p itemprop="reviewBody">{$comment.content|escape:'html':'UTF-8'|nl2br}</p>
														<ul>
                                                            {if $comment.total_advice > 0}
																<li>
                                                                    {l s='%1$d out of %2$d people found this review useful.' sprintf=[$comment.total_useful,$comment.total_advice] mod='productcomments'}
																</li>
                                                            {/if}
                                                            {if $is_logged}
                                                                {if !$comment.customer_advice}
																	<li>
                                                                        {l s='Was this comment useful to you?' mod='productcomments'}
																		<button class="usefulness_btn btn btn-default button button-small" data-is-usefull="1" data-id-product-comment="{$comment.id_product_comment}">
																			<span>{l s='Yes' mod='productcomments'}</span>
																		</button>
																		<button class="usefulness_btn btn btn-default button button-small" data-is-usefull="0" data-id-product-comment="{$comment.id_product_comment}">
																			<span>{l s='No' mod='productcomments'}</span>
																		</button>
																	</li>
                                                                {/if}
                                                                {if !$comment.customer_report}
																	<li>
									<span class="report_btn" data-id-product-comment="{$comment.id_product_comment}">
										{l s='Report abuse' mod='productcomments'}
									</span>
																	</li>
                                                                {/if}
                                                            {/if}
														</ul>
													</div><!-- .comment_details -->

												</div> <!-- .comment -->
                                            {/if}
                                        {/foreach}
                                        {if (!$too_early AND ($is_logged OR $allow_guests))}
											<p class="align_center">
												<a id="new_comment_tab_btn" class="btn btn-default button button-small open-comment-form" href="#new_comment_form">
													<span>{l s='Write your review!' mod='productcomments'}</span>
												</a>
											</p>
                                        {/if}
                                    {else}
                                        {if (!$too_early AND ($is_logged OR $allow_guests))}
											<p class="align_center">
												<a id="new_comment_tab_btn" class="btn btn-default button button-small open-comment-form" href="#new_comment_form">
													<span>{l s='Be the first to write your review!' mod='productcomments'}</span>
												</a>
											</p>
                                        {else}
											<!-- no comments on page-->
											<div class="comment-item no-comment"><img src="media/icons/15.png" alt="">
												<h4 class="align_center">{l s='Нет комментариев' mod='productcomments'}</h4>
												<p>Будь первым</p>
											</div>
                                        {/if}
                                    {/if}
								</div> <!-- #product_comments_block_tab -->
							</div>


						</div>
					</div>
				</div>
			</div>
		</section>

		<!--Accessories -->
        {if isset($accessories) && $accessories}
			<!--Accessories -->
			<ul>
                {foreach from=$accessories item=accessory name=accessories_list}
                    {if ($accessory.allow_oosp || $accessory.quantity_all_versions > 0 || $accessory.quantity > 0) && $accessory.available_for_order && !isset($restricted_country_mode)}
                        {assign var='accessoryLink' value=$link->getProductLink($accessory.id_product, $accessory.link_rewrite, $accessory.category)}

						<li style="list-style-type: none;">
							<section class="product-page-hero">
								<ul class="slider-product">
                                    {if isset($images)}
                                        {foreach from=$images item=image}
											<li>
												<div style="background-image: url({$link->getImageLink($accessory.link_rewrite, $accessory.id_image, 'large_default')|escape:'html':'UTF-8'})" class="pic"></div>
											</li>
                                        {/foreach}
                                    {/if}
								</ul>
								<div class="container-fluid product-page-content">
									<div class="row product-page-title">
										<div class="col-md-6">
											<div class="row">
												<div class="col-md-12">
													<div class="product-info-title">
														<h1>{$accessory.name|truncate:50:'...':true|escape:'html':'UTF-8'}</h1>
													</div>
												</div>
											</div>
										</div>
										<div class="col-md-3">
											<div class="product-info-price">
												<h3>{if $accessory.show_price && !isset($restricted_country_mode) && !$PS_CATALOG_MODE}

                                                        {if $priceDisplay != 1}
                                                            {displayWtPrice p=$accessory.price}
                                                        {else}
                                                            {displayWtPrice p=$accessory.price_tax_exc}
                                                        {/if}
                                                        {hook h="displayProductPriceBlock" product=$accessory type="price"}

                                                    {/if}
													<span></span>
												</h3>
											</div>
										</div>
										<div class="col-md-3 text-right">
											<a href="{$accessoryLink|escape:'html':'UTF-8'}"
											   title="{$accessory.legend|escape:'html':'UTF-8'}"
											   class="btn b-big">Подробней</a>
										</div>
									</div>
								</div>
							</section>
						</li>
                    {/if}
                {/foreach}
			</ul>

			<!--end Accessories -->
        {/if}

	</div>
	<!-- itemscope product wrapper -->
    {strip}
        {if isset($smarty.get.ad) && $smarty.get.ad}
            {addJsDefL name=ad}{$base_dir|cat:$smarty.get.ad|escape:'html':'UTF-8'}{/addJsDefL}
        {/if}
        {if isset($smarty.get.adtoken) && $smarty.get.adtoken}
            {addJsDefL name=adtoken}{$smarty.get.adtoken|escape:'html':'UTF-8'}{/addJsDefL}
        {/if}
        {addJsDef allowBuyWhenOutOfStock=$allow_oosp|boolval}
        {addJsDef availableNowValue=$product->available_now|escape:'quotes':'UTF-8'}
        {addJsDef availableLaterValue=$product->available_later|escape:'quotes':'UTF-8'}
        {addJsDef attribute_anchor_separator=$attribute_anchor_separator|escape:'quotes':'UTF-8'}
        {addJsDef attributesCombinations=$attributesCombinations}
        {addJsDef currentDate=$smarty.now|date_format:'%Y-%m-%d %H:%M:%S'}
        {if isset($combinations) && $combinations}
            {addJsDef combinations=$combinations}
            {addJsDef combinationsFromController=$combinations}
            {addJsDef displayDiscountPrice=$display_discount_price}
            {addJsDefL name='upToTxt'}{l s='Up to' js=1}{/addJsDefL}
        {/if}
        {if isset($combinationImages) && $combinationImages}
            {addJsDef combinationImages=$combinationImages}
        {/if}
        {addJsDef customizationId=$id_customization}
        {addJsDef customizationFields=$customizationFields}
        {addJsDef default_eco_tax=$product->ecotax|floatval}
        {addJsDef displayPrice=$priceDisplay|intval}
        {addJsDef ecotaxTax_rate=$ecotaxTax_rate|floatval}
        {if isset($cover.id_image_only)}
            {addJsDef idDefaultImage=$cover.id_image_only|intval}
        {else}
            {addJsDef idDefaultImage=0}
        {/if}
        {addJsDef img_ps_dir=$img_ps_dir}
        {addJsDef img_prod_dir=$img_prod_dir}
        {addJsDef id_product=$product->id|intval}
        {addJsDef jqZoomEnabled=$jqZoomEnabled|boolval}
        {addJsDef maxQuantityToAllowDisplayOfLastQuantityMessage=$last_qties|intval}
        {addJsDef minimalQuantity=$product->minimal_quantity|intval}
        {addJsDef noTaxForThisProduct=$no_tax|boolval}
        {if isset($customer_group_without_tax)}
            {addJsDef customerGroupWithoutTax=$customer_group_without_tax|boolval}
        {else}
            {addJsDef customerGroupWithoutTax=false}
        {/if}
        {if isset($group_reduction)}
            {addJsDef groupReduction=$group_reduction|floatval}
        {else}
            {addJsDef groupReduction=false}
        {/if}
        {addJsDef oosHookJsCodeFunctions=Array()}
        {addJsDef productHasAttributes=isset($groups)|boolval}
        {addJsDef productPriceTaxExcluded=($product->getPriceWithoutReduct(true)|default:'null' - $product->ecotax)|floatval}
        {addJsDef productPriceTaxIncluded=($product->getPriceWithoutReduct(false)|default:'null' - $product->ecotax * (1 + $ecotaxTax_rate / 100))|floatval}
        {addJsDef productBasePriceTaxExcluded=($product->getPrice(false, null, 6, null, false, false) - $product->ecotax)|floatval}
        {addJsDef productBasePriceTaxExcl=($product->getPrice(false, null, 6, null, false, false)|floatval)}
        {addJsDef productBasePriceTaxIncl=($product->getPrice(true, null, 6, null, false, false)|floatval)}
        {addJsDef productReference=$product->reference|escape:'html':'UTF-8'}
        {addJsDef productAvailableForOrder=$product->available_for_order|boolval}
        {addJsDef productPriceWithoutReduction=$productPriceWithoutReduction|floatval}
        {addJsDef productPrice=$productPrice|floatval}
        {addJsDef productUnitPriceRatio=$product->unit_price_ratio|floatval}
        {addJsDef productShowPrice=(!$PS_CATALOG_MODE && $product->show_price)|boolval}
        {addJsDef PS_CATALOG_MODE=$PS_CATALOG_MODE}
        {if $product->specificPrice && $product->specificPrice|@count}
            {addJsDef product_specific_price=$product->specificPrice}
        {else}
            {addJsDef product_specific_price=array()}
        {/if}
        {if $display_qties == 1 && $product->quantity}
            {addJsDef quantityAvailable=$product->quantity}
        {else}
            {addJsDef quantityAvailable=0}
        {/if}
        {addJsDef quantitiesDisplayAllowed=$display_qties|boolval}
        {if $product->specificPrice && $product->specificPrice.reduction && $product->specificPrice.reduction_type == 'percentage'}
            {addJsDef reduction_percent=$product->specificPrice.reduction*100|floatval}
        {else}
            {addJsDef reduction_percent=0}
        {/if}
        {if $product->specificPrice && $product->specificPrice.reduction && $product->specificPrice.reduction_type == 'amount'}
            {addJsDef reduction_price=$product->specificPrice.reduction|floatval}
        {else}
            {addJsDef reduction_price=0}
        {/if}
        {if $product->specificPrice && $product->specificPrice.price}
            {addJsDef specific_price=$product->specificPrice.price|floatval}
        {else}
            {addJsDef specific_price=0}
        {/if}
        {addJsDef specific_currency=($product->specificPrice && $product->specificPrice.id_currency)|boolval} {* TODO: remove if always false *}
        {addJsDef stock_management=$PS_STOCK_MANAGEMENT|intval}
        {addJsDef taxRate=$tax_rate|floatval}
        {addJsDefL name=doesntExist}{l s='This combination does not exist for this product. Please select another combination.' js=1}{/addJsDefL}
        {addJsDefL name=doesntExistNoMore}{l s='This product is no longer in stock' js=1}{/addJsDefL}
        {addJsDefL name=doesntExistNoMoreBut}{l s='with those attributes but is available with others.' js=1}{/addJsDefL}
        {addJsDefL name=fieldRequired}{l s='Please fill in all the required fields before saving your customization.' js=1}{/addJsDefL}
        {addJsDefL name=uploading_in_progress}{l s='Uploading in progress, please be patient.' js=1}{/addJsDefL}
        {addJsDefL name='product_fileDefaultHtml'}{l s='No file selected' js=1}{/addJsDefL}
        {addJsDefL name='product_fileButtonHtml'}{l s='Choose File' js=1}{/addJsDefL}
    {/strip}
{/if}
