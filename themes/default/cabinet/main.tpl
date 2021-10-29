<!doctype html>
<html lang="en">
<head>
	<meta charset="UTF-8">
	<meta name="viewport"
		  content="width=device-width, user-scalable=no, initial-scale=1.0, maximum-scale=1.0, minimum-scale=1.0">
	<meta http-equiv="X-UA-Compatible" content="ie=edge">
	<title>{$title}</title>
	<link href="https://fonts.googleapis.com/css?family=Roboto:100,300,400,700,900" rel="stylesheet">
	<!-- Bootstrap CSS
		============================================ -->
	{$theme->css('assets/css/bootstrap.min.css')}
	{$theme->css('assets/css/all.min.css')}
	{$theme->css('assets/css/bootstrap-select/bootstrap-select.css')}
	<!-- Bootstrap CSS
		============================================ -->
	{$theme->css('assets/css/font-awesome.min.css')}
	<!-- owl.carousel CSS
		============================================ -->
	{$theme->css('assets/css/owl.carousel.css')}
	{$theme->css('assets/css/owl.theme.css')}
	{$theme->css('assets/css/owl.transitions.css')}
	<!-- meanmenu CSS
		============================================ -->
	{$theme->css('assets/css/meanmenu/meanmenu.min.css')}
	<!-- dropzone CSS
		============================================ -->
	{$theme->css('assets/css/dropzone/dropzone.css')}
	<!-- animate CSS
		============================================ -->
	{$theme->css('assets/css/animate.css')}
	<!-- normalize CSS
		============================================ -->
	{$theme->css('assets/css/normalize.css')}
	<!-- mCustomScrollbar CSS
		============================================ -->
	{$theme->css('assets/css/scrollbar/jquery.mCustomScrollbar.min.css')}
	<!-- jvectormap CSS
		============================================ -->
	{$theme->css('assets/css/jvectormap/jquery-jvectormap-2.0.3.css')}
	<!-- notika icon CSS
		============================================ -->
	{$theme->css('assets/css/notika-custom-icon.css')}
	<!-- wave CSS
		============================================ -->
	{$theme->css('assets/css/wave/waves.min.css')}
	<!-- main CSS
		============================================ -->
	{$theme->css('assets/css/main.css')}
	<!-- style CSS
		============================================ -->
	{$theme->css('assets/css/style.css?v=2')}
	<!-- responsive CSS
		============================================ -->
	{$theme->css('assets/css/responsive.css')}
	<!-- modernizr JS
		============================================ -->
	{$theme->js('assets/js/vendor/modernizr-2.8.3.min.js')}
</head>
<body>



<!--[if lt IE 8]>
<p class="browserupgrade">You are using an <strong>outdated</strong> browser. Please <a href="http://browsehappy.com/">upgrade your browser</a> to improve your experience.</p>
<![endif]-->
<!-- Start Header Top Area -->
<div class="header-top-area">
	<div class="container">
		<div class="row">
			<div class="col-lg-4 col-md-4 col-sm-12 col-xs-12">
				<div class="logo-area">
					<a href="{site_url('cabinet')}"><img src="{theme_url()}assets/img/logo/logo.png" alt="" /><sup>{$user->group->name}</sup></a>
				</div>
			</div>
			<div class="col-lg-8 col-md-8 col-sm-12 col-xs-12">
				<div class="header-top-menu">
					<ul class="nav navbar-nav notika-top-nav">
						<li>
							<div class="dropdown-trig-sgn">
								<a href="#" data-toggle="dropdown" aria-expanded="true"><i class="fas fa-user-tie"></i></a>
								<ul class="dropdown-menu triger-bounceIn-dp">
									<li><a href="{site_url('cabinet/profile/edit-profile')}">{__('Ред. профиль')}</a></li>
									<li class="divider"></li>
									<li><a href="{site_url('logout')}">{__('Выход')}</a></li>
								</ul>
							</div>
						</li>
					</ul>
				</div>
			</div>
		</div>
	</div>
</div>
<!-- End Header Top Area -->
<!-- Mobile Menu start -->
<div class="mobile-menu-area">
	<div class="container">
		<div class="row">
			<div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
				<div class="mobile-menu">
					<nav id="dropdown">
						<ul class="mobile-menu-nav">
							{foreach $menu as $item }

									{if $item['disable'] == 0}
									<li>
										<a data-toggle="collapse"{if !empty($item['submenu'])} data-target="#{$item['active']}"{/if} href="{$item['url']}">{$item['title']}</a>
										{if !empty($item['submenu'])}
											<ul id="{$item['active']}" class="collapse dropdown-header-top">
												{foreach $item['submenu'] as $submenu}
													<li>
														<a href="{$submenu['url']}">{$submenu['title']}</a>
													</li>
												{/foreach}
											</ul>
										{/if}
									</li>
									{/if}

							{/foreach}
						</ul>
					</nav>
				</div>
			</div>
		</div>
	</div>
</div>
<!-- Mobile Menu end -->
<!-- Main Menu area start-->
<div class="main-menu-area mg-tb-40">
	<div class="container">
		<div class="row">
			<div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
				<ul class="nav nav-tabs notika-menu-wrap menu-it-icon-pro">
					{foreach $menu as $item }

							{if $item['disable'] == 0}
								<li{if $active == $item['active']} class="active"{/if}><a href="{$item['url']}">{$item['icon']} {$item['title']}</a></li>
							{/if}

					{/foreach}
				</ul>
				<div class="tab-content custom-menu-content">
					<div class="notika-tab-menu-bg">
						{foreach $menu as $item }

								{if $item['disable'] == 0}
									{if $active == $item['active']}
										{if !empty($item['submenu'])}
											<ul class="notika-main-menu-dropdown">
												{foreach $item['submenu'] as $submenu}
													<li>
														<a href="{$submenu['url']}">{$submenu['title']}</a>
													</li>
												{/foreach}
											</ul>
										{/if}
									{/if}
								{/if}

						{/foreach}
					</div>

				</div>
			</div>
		</div>
	</div>
</div>
<div class="wrap{if !empty($menu_act['submenu'])} submenu{/if}">
	{if $breadcrumb}
		<div class="breadcomb-area">
			<div class="container">
				<div class="row">
					<div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
						<div class="breadcomb-list">
							<div class="row">
								<div class="col-lg-6 col-md-6 col-sm-6 col-xs-12">
									<div class="breadcomb-wp">
										<div class="breadcomb-icon">
											{$menu_act['icon']}
										</div>
										<div class="breadcomb-ctn">
											<h2>{$breadcrumb['title']}</h2>
											<p>{$breadcrumb['desc']}</p>
										</div>
									</div>
								</div>
								{if $menu_act['active'] == 'wallet' and $user->group->id == 3}
								<div class="col-lg-6 col-md-6 col-sm-6 col-xs-3">
									<div class="breadcomb-report">
										<a href="{site_url('cabinet/wallet/replenish')}" data-toggle="tooltip" data-placement="left" title="" class="btn waves-effect" data-original-title="{__('Пополнить баланс')}"><i class="fab fa-bitcoin"></i></a>
									</div>
								</div>
								{/if}
							</div>
						</div>
					</div>
				</div>
			</div>
		</div>
	{/if}

	<div class="notika-email-post-area">
		<div class="container">
			{$message}
			{$content}
		</div>
	</div>
</div>
<div class="footer-copyright-area">
	<div class="container">
		<div class="row">
			<div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
				<div class="footer-copy-right">
					<p>Copyright © {date('Y',time())}</p>
				</div>
			</div>
		</div>
	</div>
</div>

{$theme->js('assets/js/vendor/jquery-1.12.4.min.js')}
<!-- bootstrap JS
	============================================ -->
{$theme->js('assets/js/bootstrap.min.js')}
<!-- wow JS
	============================================ -->
{$theme->js('assets/js/wow.min.js')}
<!-- price-slider JS
	============================================ -->
{$theme->js('assets/js/jquery-price-slider.js')}
<!-- owl.carousel JS
	============================================ -->
{$theme->js('assets/js/owl.carousel.min.js')}
<!-- scrollUp JS
	============================================ -->
{$theme->js('assets/js/jquery.scrollUp.min.js')}
<!-- meanmenu JS
	============================================ -->
{$theme->js('assets/js/meanmenu/jquery.meanmenu.js')}
<!-- counterup JS
	============================================ -->
{$theme->js('assets/js/counterup/jquery.counterup.min.js')}
{$theme->js('assets/js/counterup/waypoints.min.js')}
{$theme->js('assets/js/counterup/counterup-active.js')}
<!-- mCustomScrollbar JS
	============================================ -->
{$theme->js('assets/js/scrollbar/jquery.mCustomScrollbar.concat.min.js')}
<!-- bootstrap select JS
		============================================ -->
{$theme->js('assets/js/bootstrap-select/bootstrap-select.js')}
<!-- jvectormap JS
	============================================ -->
{$theme->js('assets/js/jvectormap/jquery-jvectormap-2.0.2.min.js')}
{$theme->js('assets/js/jvectormap/jquery-jvectormap-world-mill-en.js')}
{$theme->js('assets/js/jvectormap/jvectormap-active.js')}
<!-- sparkline JS
	============================================ -->
{$theme->js('assets/js/sparkline/jquery.sparkline.min.js')}
{$theme->js('assets/js/sparkline/sparkline-active.js')}
<!-- sparkline JS
	============================================ -->
{$theme->js('assets/js/flot/jquery.flot.js')}
{$theme->js('assets/js/flot/jquery.flot.resize.js')}
{$theme->js('assets/js/flot/curvedLines.js')}
{$theme->js('assets/js/flot/flot-active.js')}
<!-- knob JS
	============================================ -->
{$theme->js('assets/js/knob/jquery.knob.js')}
{$theme->js('assets/js/knob/jquery.appear.js')}
{$theme->js('assets/js/knob/knob-active.js')}
<!--  wave JS
	============================================ -->
{$theme->js('assets/js/wave/waves.min.js')}
{$theme->js('assets/js/wave/wave-active.js')}
<!--  todo JS
	============================================ -->
{$theme->js('assets/js/todo/jquery.todo.js')}
<!-- plugins JS
	============================================ -->
{$theme->js('assets/js/plugins.js')}
<!--  Chat JS
	============================================ -->
{$theme->js('assets/js/chat/moment.min.js')}
{$theme->js('assets/js/chat/jquery.chat.js')}
{$theme->js('assets/js/jasny-bootstrap.min.js')}
<!-- dropzone JS
		============================================ -->
{$theme->js('assets/js/dropzone/dropzone.js')}
<!-- main JS
	============================================ -->
{$theme->js('assets/js/main.js?v=155')}



</body>
</html>
