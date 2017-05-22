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

<table class="table">
    <thead>
    <th>{l s='Status' mod='pleasecallme'}</th>
    <th>{l s='Employee' mod='pleasecallme'}</th>
    <th>{l s='Message' mod='pleasecallme'}</th>
    <th>{l s='Date' mod='pleasecallme'}</th>
    </thead>
    <tbody>
    {if is_array($history) && count($history)}
        {foreach from=$history item=item}
            <tr>
                <td>
                    <span class="label color_field" style="background: {$item.status_color|escape:'quotes':'UTF-8'}">{$item.status_name|escape:'quotes':'UTF-8'}</span>
                </td>
                <td>
                    {if $item.employee_name}
                        {$item.employee_name|escape:'quotes':'UTF-8'}
                    {else}
                        {l s='-' mod='pleasecallme'}
                    {/if}
                </td>
                <td>
                    {$item.message|escape:'quotes':'UTF-8'}
                </td>
                <td>
                    {date('H:i:s d-m-Y', strtotime($item.date_time))|escape:'quotes':'UTF-8'}
                </td>
            </tr>
        {/foreach}
    {else}
        <tr>
            <td colspan="4">{l s='No statuses' mod='pleasecallme'}</td>
        </tr>
    {/if}
    </tbody>
</table>