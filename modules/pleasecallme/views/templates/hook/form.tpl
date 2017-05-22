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

<!--pleasecallme-->
<script>
	var hourText = "{l s='h' mod='pleasecallme'}";
</script>
<div class="stage_please_call_me"></div>
<div class="please_call_me">
	<div class="form_title">
		<i class="icon-envelope"></i>
		{l s='Call' mod='pleasecallme'}
		<span>{l s='me' mod='pleasecallme'}</span>
		<a href="#" class="close_form">
			<i class="icon-remove"></i>
		</a>
	</div>
	<div class="form_body">
		<div class="field_row icon_row icon_smile">
			<input name="name" placeholder="{l s='name' mod='pleasecallme'}" type="text">
		</div>
		<div class="field_row icon_row icon_phone">
			<input name="phone" id="phone" placeholder="Ваш телефон" type="text">
		</div>
		{*<div class="field_row">*}
			{*<div class="time_slider"></div>*}
			{*<input type="hidden" name="time_from">*}
			{*<input type="hidden" name="time_to">*}
		{*</div>*}
	</div>
	<div class="form_footer">
		<button class="submitPleaseCallMe">
			{l s='Send' mod='pleasecallme'}
		</button>
	</div>
</div>
<!--/pleasecallme-->

<div class="link_show_please_call_me newton_callback_phone hidden-xs">
	<div class="show_message">Заказать обратный звонок</div>
	<div class="newton-track"></div>
	<div class="newton-wrapper">
		<div class="newton-ring"></div>
	</div>
	<div class="newton-back-circle"></div>
	<div class="newton-circle">
		<div class="newton-handset">

		</div>
	</div>
</div>
<script type="text/javascript" src="/js/jquery.maskedinput.min.js"></script>
{literal}
	<script>

		jQuery(function($) {
			$("#phone_mobile").mask("(999)999-99-99");
			$("#phone").mask("(+380)99-999-99-99");
		});
	</script>
{/literal}