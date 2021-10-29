{form_open()}
<div class="login-content">
	<div class="nk-block toggled">
		{if $error}{$error}{/if}
		{if $message}{$message}{/if}
		<div class="nk-form">

			<div class="input-group">
				<span class="input-group-addon nk-ic-st-pro"><i class="notika-icon notika-edit"></i></span>
				<div class="nk-int-st">
					{form_password($new_password)}
				</div>
			</div>
			{form_error('new', '<div class="help-inline error">', '</div>')}
			<div class="input-group">
				<span class="input-group-addon nk-ic-st-pro"><i class="notika-icon notika-edit"></i></span>
				<div class="nk-int-st">
					{form_password($new_password_confirm)}
				</div>
			</div>
			{form_error('new_confirm', '<div class="help-inline error">', '</div>')}
			{form_input($user_id)}
			{form_hidden($csrf)}
			<button type="submit" name="form_submit" data-ma-action="nk-login-switch" data-ma-block="#l-login" class="btn btn-login btn-success btn-float"><i class="notika-icon notika-right-arrow"></i></button>
		</div>

		<div class="nk-navigation nk-lg-ic rg-ic-stl">
			<a href="{site_url('login')}" data-ma-action="nk-login-switch" data-ma-block="#l-login"><i class="notika-icon notika-right-arrow"></i> <span>{__('Войти')}</span></a>
			<a href="{site_url('register')}" data-ma-action="nk-login-switch" data-ma-block="#l-register"><i class="notika-icon notika-plus-symbol"></i> <span>{__('Регистрация')}</span></a>
		</div>
	</div>
</div>
