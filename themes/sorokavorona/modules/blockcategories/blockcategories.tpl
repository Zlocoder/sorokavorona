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
{if $blockCategTree && $blockCategTree.children|@count}
<!-- Block categories module -->
	<section class="category-main">
		<div class="container-fluid">
			<div class="row">
				<div class="col-md-12 text-center">
					<h6>
                        {if isset($currentCategory)}
                            {$currentCategory->name|escape}
                        {else}
                            {l s='Categories' mod='blockcategories'}
                        {/if}
					</h6>
				</div>
			</div>

			<div class="row">
				<div class="col-md-12">
					<ul class="catalog-menu">
                        {foreach from=$blockCategTree.children item=node name=blockCategTree}
							<li>
								<a href="{$node.link|escape:'html':'UTF-8'}" title="{$node.desc|strip_tags|trim|escape:'html':'UTF-8'}">
									<span class="icon">
										<img src="{$link->getCatImageLink('0', $node.id, 'medium_default')}" alt="">
									</span>

									<h2 class="title">{$node.name|escape:'html':'UTF-8'}</h2>
								</a>
							</li>
                        {/foreach}
					</ul>
				</div>
			</div>
		</div>
	</section>
<!-- /Block categories module -->
{/if}
