{*
* 2007-2015 PrestaShop
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
*  @copyright  2007-2015 PrestaShop SA
*  @license    http://opensource.org/licenses/afl-3.0.php  Academic Free License (AFL 3.0)
*  International Registered Trademark & Property of PrestaShop SA
*}

<div class="row">
    <label class="control-label col-lg-4">
        <i class="icon-smile"></i>
        {l s='name' mod='pleasecallme'}
    </label>
    <div class="col-lg-8">
        {if $feedback->name}
            {$feedback->name|escape:'quotes':'UTF-8'}
        {else}
            -
        {/if}
    </div>
</div>
<div class="row">
    <label class="control-label col-lg-4">
        <i class="icon-phone"></i>
        {l s='phone' mod='pleasecallme'}
    </label>
    <div class="col-lg-8">
        <b>{if $feedback->phone}
                {$feedback->phone|escape:'quotes':'UTF-8'}
            {else}
                -
            {/if}
        </b>
    </div>
</div>
<hr>
{if $customer->id}
    <div class="row">
        <label class="control-label col-lg-12">
            <a href="{$link->getAdminLink('AdminCustomers')|escape:'quotes':'UTF-8'}&id_customer={$customer->id|intval}&viewcustomer">{l s='customer' mod='pleasecallme'} № {$customer->id|intval}</a>
        </label>
    </div>
    <div class="row">
        <label class="control-label col-lg-4">
            {l s='email' mod='pleasecallme'}
        </label>
        <div class="col-lg-8">
            {$customer->email|escape:'quotes':'UTF-8'}
        </div>
    </div>
    <div class="row">
        <label class="control-label col-lg-4">
            {l s='firstname' mod='pleasecallme'}
        </label>
        <div class="col-lg-8">
            {$customer->firstname|escape:'quotes':'UTF-8'}
        </div>
    </div>
    <div class="row">
        <label class="control-label col-lg-4">
            {l s='lastname' mod='pleasecallme'}
        </label>
        <div class="col-lg-8">
            {$customer->lastname|escape:'quotes':'UTF-8'}
        </div>
    </div>
{else}
    <div class="row">
        <label class="control-label col-lg-4">
            {l s='guest id' mod='pleasecallme'} {$feedback->id_guest|escape:'quotes':'UTF-8'}
        </label>
    </div>
{/if}
<div class="row">
    <label class="control-label col-lg-4">
        {l s='IP' mod='pleasecallme'}
    </label>
    <div class="col-lg-8">
        {$feedback->ip|escape:'quotes':'UTF-8'}
    </div>
</div>