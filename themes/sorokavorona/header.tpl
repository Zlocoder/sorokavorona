<!DOCTYPE HTML>
<!--[if lt IE 7]> <html class="no-js lt-ie9 lt-ie8 lt-ie7"{if isset($language_code) && $language_code} lang="{$language_code|escape:'html':'UTF-8'}"{/if}><![endif]-->
<!--[if IE 7]><html class="no-js lt-ie9 lt-ie8 ie7"{if isset($language_code) && $language_code} lang="{$language_code|escape:'html':'UTF-8'}"{/if}><![endif]-->
<!--[if IE 8]><html class="no-js lt-ie9 ie8"{if isset($language_code) && $language_code} lang="{$language_code|escape:'html':'UTF-8'}"{/if}><![endif]-->
<!--[if gt IE 8]> <html class="no-js ie9"{if isset($language_code) && $language_code} lang="{$language_code|escape:'html':'UTF-8'}"{/if}><![endif]-->
<html{if isset($language_code) && $language_code} lang="{$language_code|escape:'html':'UTF-8'}"{/if}>
<head>
	<meta charset="UTF-8">
	<meta http-equiv="X-UA-Compatible" content="IE=edge">
	<meta name="viewport" content="width=device-width, initial-scale=1">

	<title>{$meta_title|escape:'html':'UTF-8'}</title>

    {if isset($meta_description) AND $meta_description}
		<meta name="description" content="{$meta_description|escape:'html':'UTF-8'}" />
    {/if}

    {if isset($meta_keywords) AND $meta_keywords}
		<meta name="keywords" content="{$meta_keywords|escape:'html':'UTF-8'}" />
    {/if}

	<meta name="generator" content="PrestaShop" />

	<meta name="robots" content="{if isset($nobots)}no{/if}index,{if isset($nofollow) && $nofollow}no{/if}follow" />

	<link rel="icon" type="image/vnd.microsoft.icon" href="{$favicon_url}?{$img_update_time}" />
	<link rel="shortcut icon" type="image/x-icon" href="{$favicon_url}?{$img_update_time}" />

	<link rel="stylesheet" href="{$tpl_uri}css/vendor/slick.css" />
    {if isset($css_files)}
        {foreach from=$css_files key=css_uri item=media}
            {if $css_uri == 'lteIE9'}
				<!--[if lte IE 9]>
				{foreach from=$css_files[$css_uri] key=css_uriie9 item=mediaie9}
				<link rel="stylesheet" href="{$css_uriie9|escape:'html':'UTF-8'}" type="text/css" media="{$mediaie9|escape:'html':'UTF-8'}" />
				{/foreach}
				<![endif]-->
            {else}
				<link rel="stylesheet" href="{$css_uri|escape:'html':'UTF-8'}" type="text/css" media="{$media|escape:'html':'UTF-8'}" />
            {/if}
        {/foreach}
    {/if}

    {if isset($js_defer) && !$js_defer && isset($js_files) && isset($js_def)}
        {$js_def}
        {foreach from=$js_files item=js_uri}
			<script type="text/javascript" src="{$js_uri|escape:'html':'UTF-8'}"></script>
        {/foreach}
    {/if}

	<script>document.documentElement.className = 'js';</script>

    {$HOOK_HEADER}
</head>

<body class="{if isset($page_name)}{$page_name|escape:'html':'UTF-8'}{/if}{if isset($body_classes) && $body_classes|@count} {implode value=$body_classes separator=' '}{/if}">
{*<body class="catalog-page loading">*}
	{if !isset($content_only) || !$content_only}
		<header>
			<div class="container-fluid">
				<div class="row">
					<div class="col-sm-4 col-xs-5">
						<button class="open-off-menu">
							<span class="lines"></span>
							<span class="text-menu">МЕНЮ</span>
						</button>

						<button class="open-search"><i class="fa fa-search"></i></button>
					</div>

					<div class="col-sm-4 col-xs-2">
						<a class="logo" href="{if isset($force_ssl) && $force_ssl}{$base_dir_ssl}{else}{$base_dir}{/if}" title="{$shop_name|escape:'html':'UTF-8'}">
							<div class="row">
								<div class="col-sm-6 col-sm-12 col-xs-12 logo-half-one">
									<img src="{$tpl_uri}media/logo-soroka.svg" alt="">
								</div>

								<div class="col-sm-6 logo-half-two">
									<span><img src="{$tpl_uri}media/logo-soroka-text.svg" alt=""></span>
									<span><img src="{$tpl_uri}media/logo-soroka-long.png" alt=""></span>
								</div>
							</div>
						</a>
					</div>

					<div class="col-sm-4 col-sx-5">
						<button class="open-call"><i class="fa fa-phone"></i></button>

                        {capture name='displayNav'}{hook h='displayNav'}{/capture}
                        {if $smarty.capture.displayNav}
							{$smarty.capture.displayNav}
                        {/if}

						<a href="" class="cart-box">
							<i class="fa fa-shopping-cart"></i>
							<p>0</p>
						</a>
						<div class="product-in-box"><img src="media/icons/5.png" alt=""><span>товар в корзине</span></div>
					</div>
				</div>
			</div>
		</header>

		<div class="off-canvas-header">
			<div class="first-menu">
				<ul class="top-menu">
					<li> <a href="">Популярное</a></li>
					<li><a href="">Новинки</a></li>
					<li><a href="">Видео</a></li>
				</ul>
				<ul class="lang-menu">
					<li><a href="">РУС</a></li>
					<li><a href="">УКР</a></li>
				</ul>
				<ul class="main-menu">
					<li><a href="index.html">Главная</a></li>
					<li><a href="about.html">Оплата</a></li>
					<li><a href="about.html">Доставка</a></li>
					<li><a href="about.html#link-why-us">Почему мы?</a></li>
					<li><a href="about.html#link-question">Вопросы и ответы</a></li>
					<li><a href="review.html">Отзывы</a></li>
					<li><a href="#" class="open-contact-form">Связаться</a></li>
					<li>
						<button class="btn open-login">Личный кабинет</button>
					</li>
				</ul>
				<div class="clon-top-menu"><a href="">sales@sorokavorona.com.ua</a><a href="">+38 (096) 033-77-00</a><a href="">+38 (050) 711-44-15</a><a href="">+38 (063) 313-11-33</a>
					<div class="work-time">
						<p>

							Будни <span>с 8 до 18</span>
						</p>
						<p>

							Суббота <span>с 8 до 15</span>
						</p>
						<p>

							воскрескенье <span>Выходной </span>
						</p>
					</div>
				</div>
			</div>

			<div class="second-menu">
				<ul class="category-menu-header">
					<li><a href="catalog.html"> <span class="icon"><img src="media/emoji/1.gif" alt=""></span>
							<h2 class="title">Для малышей</h2></a></li>
					<li><a href="catalog.html"> <span class="icon"><img src="media/emoji/3.png" alt=""></span>
							<h2 class="title">Девочке</h2></a></li>
					<li><a href="catalog.html"> <span class="icon"><img src="media/emoji/2.gif" alt=""></span>
							<h2 class="title">

								Мальчику
							</h2></a></li>
					<li><a href="catalog.html"> <span class="icon"><img src="media/emoji/6.png" alt=""></span>
							<h2 class="title">И тем и тем</h2></a></li>
					<li><a href="catalog.html"> <span class="icon"><img src="media/emoji/9.png" alt=""></span>
							<h2 class="title">Деревянные</h2></a></li>
					<li><a href="catalog.html"> <span class="icon"><img src="media/emoji/3.gif" alt=""></span>
							<h2 class="title">Конструкторы</h2></a></li>
					<li><a href=""> <span class="icon"><img src="media/emoji/222.png" alt=""></span>
							<h2 class="title">Детская комната</h2></a></li>
					<li><a href="catalog.html"> <span class="icon"><img src="media/emoji/4.gif" alt=""></span>
							<h2 class="title">Канцтовары</h2></a></li>
					<li><a href="catalog.html"> <span class="icon"><img src="media/emoji/5.gif" alt=""></span>
							<h2 class="title">Отдых</h2></a></li>
					<li><a href="catalog.html"> <span class="icon"><img src="media/emoji/88.png" alt=""></span>
							<h2 class="title">Развивающие</h2></a></li>
					<li><a href="catalog.html"> <span class="icon"><img src="media/emoji/6.gif" alt=""></span>
							<h2 class="title">Спорт</h2></a></li>
					<li><a href="catalog.html"> <span class="icon"><img src="media/emoji/11.png" alt=""></span>
							<h2 class="title">Уход за малышами</h2></a></li>
				</ul>
			</div>
		</div>

		<div class="search-box">
			<button class="close-modal"></button>

			<div class="search-content">
				<h6>поиск</h6>
				<input type="text" placeholder="Введите ваш запрос сюда" autofocus="true" class="search-input">
				<input type="submit" value="Искать" class="send-search">
			</div>
		</div>

		<div class="login-modal-box">
			<button class="close-modal"></button>
			<div class="login-content">
				<h6 class="text-center">войти в личный кабинет</h6>
				<div class="login-or-register-box">
					<div class="tabs-nav"><a href="#" class="register-link">

							Нет<br>личного кабинета</a>
						<div class="toggle to-right">
							<div class="toggle-box"><span></span></div>
						</div><a href="#" class="active login-link">

							Есть<br>личный кабинет</a>
					</div>
					<div class="tabs-content">
						<div class="register-box tabs-item">
							<form id="registration-form" action="">
								<div class="steps-box">
									<div class="step step-1">
										<p>шаг <span>1</span>/6</p>
										<input name="name" type="text" placeholder="напишите ваше имя здесь" autofocus="true">
										<button class="btn">Дальше →</button>
									</div>
									<div class="step step-2">
										<p>шаг <span>2</span>/6</p>
										<input name="otchestvo" type="text" placeholder="Отчество">
										<button class="btn">Дальше ↝</button>
									</div>
									<div class="step step-3">
										<p>шаг <span>3</span>/6</p>
										<input name="familia" type="text" placeholder="Фамилия">
										<button class="btn">Дальше ↛</button>
									</div>
									<div class="step step-4">
										<p>шаг <span>4</span>/6</p>
										<input name="email" type="email" placeholder="E-mail">
										<button class="btn">Дальше ↠</button>
									</div>
									<div class="step step-5">
										<p>шаг <span>5</span>/6</p>
										<input name="phone" type="number" placeholder="Телефон">
										<button class="btn">Дальше ↦</button>
									</div>
									<div class="step step-6">
										<p>шаг <span>6</span>/6</p>
										<input name="pass" type="password" placeholder="Пароль">
										<input type="submit" value="Отправить ⇵" class="btn">
									</div>
								</div>
							</form>
						</div>
						<div class="login-box tabs-item show">
							<form action="">
								<label for="#login" class="text-input">
									<input id="login" type="email" name="email" placeholder="E-mail">
								</label>
								<label for="#pass" class="text-input">
									<input id="pass" type="password" name="password" placeholder="Пароль">
								</label>
								<label>
									<input type="submit" value="Войти" class="btn">
								</label>
							</form>
						</div>
					</div>
				</div>
			</div>
		</div>
	{/if}

	<main>
		{capture name='displayTopColumn'}{hook h='displayTopColumn'}{/capture}
		{if $smarty.capture.displayTopColumn}
			{$smarty.capture.displayTopColumn}
		{/if}

