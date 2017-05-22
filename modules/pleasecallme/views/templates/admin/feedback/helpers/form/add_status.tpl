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

<div class="row form-group">
    <div class="col-lg-12">
        <select class="=" name="id_feedback_status">
            {if is_array($statuses) && count($statuses)}
                {foreach from=$statuses item=status}
                    {if is_array($history) && count($history) && $history[0].id_feedback_status == $status.id_feedback_status}{continue}{/if}
                    <option value="{$status.id_feedback_status|intval}">{$status.name|escape:'quotes':'UTF-8'}</option>
                {/foreach}
            {/if}
        </select>
    </div>
</div>
<div class="row form-group">
    <label class="control-label col-lg-12">{l s='Write problem or message about dialog with client' mod='pleasecallme'}</label>
    <div class="col-lg-12">
        <textarea name="message"></textarea>
    </div>
</div>
<div class="row form-group">
    <div class="col-lg-3">
        <button class="btn btn-default" name="submitStatus" type="submit">
            {l s='Add' mod='pleasecallme'}
        </button>
    </div>
</div>