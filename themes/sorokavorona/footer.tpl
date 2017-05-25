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
</main>

{if !isset($content_only) || !$content_only}
	<footer><span class="icon icon21"></span><span class="icon icon16"></span><span class="icon icon6"></span>
		<div class="container-fluid">
			<div class="col-md-12">
				<div class="to-top">
					<button class="to-top"><img src="{$tpl_uri}media/icons/top.svg" alt=""><span>вверх</span></button>
				</div>
				<div class="clon-top-menu"><a href="">sales@sorokavorona.com.ua</a><a href="">+38 (096) 033-77-00</a><a href="">+38 (050) 711-44-15</a><a href="">+38 (063) 313-11-33</a></div>
			</div>
		</div>
	</footer>

	<div class="call-back-box">
		<button class="close-modal"></button>
		<div class="call-back-content">
			<h6>Перезвоните мне</h6>
			<input type="text" placeholder="Имя">
			<input type="number" placeholder="Ваш номер телефона">
			<input type="submit" vlaue="Отправить" class="btn">
		</div>
	</div>

	<div class="contact-form-box">
		<div class="container-fluid">
			<div class="row info-contact">
				<div class="col-md-3">
					<h6>Связаться с нами</h6>
				</div>
				<div class="col-md-3"><a href="">sales@sorokavorona.com.ua</a><a href="">+38 (096) 033-77-00</a></div>
				<div class="col-md-3"><a href="">+38 (050) 711-44-15</a><a href="">+38 (063) 313-11-33</a></div>
				<div class="col-md-3 text-right">
					<button class="btn close-contact"><span>Закрыть</span> ×</button>
				</div>
			</div>

			<div class="row steps-here">
				<div class="col-md-12">
					<form action="">
						<div class="first-step step">
							<textarea id="message" placeholder="Напишите ваше сообщение здесь"></textarea>
							<button class="btn to-second-step">Дальше ↝</button>
							<!-- if user login its button send form-->
							<!---->
						</div>

						<div class="second-step step">
							<input id="name" name="name" type="text" placeholder="Введите имя">
							<button class="btn to-third-step">Дальше ↬</button>
						</div>

						<div class="third-step step">
							<input name="email" type="text" placeholder="Номер телефона или email">
							<input type="submit" value="Отправить ⇉" class="btn send">
						</div>
					</form>
				</div>
			</div>
		</div>
	</div>
{/if}

{include file="$tpl_dir./global.tpl"}

<script src="https://use.fontawesome.com/2b87f5494f.js"></script>
<script src="{$tpl_uri}js/lib/slick.min.js"></script>
<script src="{$tpl_uri}js/scripts.js"></script>

</body>
</html>