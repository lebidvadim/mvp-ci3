{form_open()}
<div class="login-content">
	<div class="nk-block toggled">
		{if $message}{$message}{/if}
		<div class="nk-form">

			<div class="input-group">
				<span class="input-group-addon nk-ic-st-pro"><i class="fas fa-at"></i></span>
				<div class="nk-int-st">
					<input type="text" name="email" class="form-control{if form_error('email')} error{/if}" placeholder="{__('Эмаил')}">
				</div>
			</div>
			{form_error('telegram', '<div class="help-inline error">', '</div>')}
			<button type="submit" name="submit" data-ma-action="nk-login-switch" data-ma-block="#l-login" class="btn btn-login btn-success btn-float"><i class="notika-icon notika-right-arrow"></i></button>
		</div>

		<div class="nk-navigation nk-lg-ic rg-ic-stl">
			<a href="{site_url('login')}" data-ma-action="nk-login-switch" data-ma-block="#l-login"><i class="notika-icon notika-right-arrow"></i> <span>{__('Войти')}</span></a>
			<a href="{site_url('register')}" data-ma-action="nk-login-switch" data-ma-block="#l-register"><i class="notika-icon notika-plus-symbol"></i> <span>{__('Регистрация')}</span></a>
		</div>
	</div>
</div>
{form_close()}

