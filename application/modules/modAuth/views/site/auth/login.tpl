{form_open()}
<div class="login-content">
	<!-- Login -->
	<div class="nk-block toggled">
		{if $error}{$error}{/if}
		{if $message}{$message}{/if}
		<div class="nk-form">
			<div class="input-group">
				<span class="input-group-addon nk-ic-st-pro"><i class="fas fa-user-tie"></i></span>
				<div class="nk-int-st">
					<input type="text" name="identity" class="form-control{if form_error('identity')} error{/if}" placeholder="{__('Логин')}">
				</div>
			</div>
			{form_error('identity', '<div class="help-inline error">', '</div>')}
			<div class="input-group mg-t-15">
				<span class="input-group-addon nk-ic-st-pro"><i class="fas fa-lock"></i></span>
				<div class="nk-int-st">
					<input type="password" name="password" class="form-control{if form_error('password')} error{/if}" placeholder="{__('Пароль')}">
				</div>
			</div>
			{form_error('password', '<div class="help-inline error">', '</div>')}
			{if $remember_users == true}
			<div class="fm-checkbox">
				<label><input type="checkbox" name="remember" value="yes" class="i-checks"> <i></i> {__('Держите меня в системе')}</label>
			</div>
			{/if}
			<button type="submit" name="submit" class="btn btn-login btn-success btn-float"><i class="notika-icon notika-right-arrow right-arrow-ant"></i></button>
		</div>

		<div class="nk-navigation nk-lg-ic">
			<a href="{site_url('register')}" data-ma-action="nk-login-switch" data-ma-block="#l-register"><i class="notika-icon notika-plus-symbol"></i> <span>{__('Регистрация')}</span></a>
			<a href="{site_url('recover')}" data-ma-action="nk-login-switch" data-ma-block="#l-forget-password"><i>?</i> <span>{__('Забыл пароль')}</span></a>
		</div>
	</div>
</div>
{form_close()}
