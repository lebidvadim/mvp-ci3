{form_open()}
<div class="login-content">
	<div class="nk-block toggled">
		{if $error}{$error}{/if}
		{if $message}{$message}{/if}
		<div class="nk-form">
			<div class="input-group mg-t-15">
				<span class="input-group-addon nk-ic-st-pro"><i class="fas fa-user"></i></span>
				<div class="nk-int-st">
					<input type="text" name="identity" class="form-control{if form_error('identity')} error{/if}" value="{set_value('identity')}" placeholder="{__('Логин')}">
				</div>
			</div>
			{form_error('identity', '<div class="help-inline error">', '</div>')}
			<div class="input-group mg-t-15">
				<span class="input-group-addon nk-ic-st-pro"><i class="fas fa-at"></i></span>
				<div class="nk-int-st">
					<input type="text" name="email" class="form-control{if form_error('email')} error{/if}" value="{set_value('email')}" placeholder="{__('Эмаил')}">
				</div>
			</div>
			{form_error('email', '<div class="help-inline error">', '</div>')}
			<div class="input-group mg-t-15">
				<span class="input-group-addon nk-ic-st-pro"><i class="fas fa-lock"></i></span>
				<div class="nk-int-st">
					<input type="password" name="password" class="form-control{if form_error('password')} error{/if}" placeholder="{__('Пароль')}">
				</div>
			</div>
			{form_error('password', '<div class="help-inline error">', '</div>')}
			<div class="input-group mg-t-15">
				<span class="input-group-addon nk-ic-st-pro"><i class="fas fa-lock"></i></span>
				<div class="nk-int-st">
					<input type="password" name="password_confirm" class="form-control{if form_error('password_confirm')} error{/if}" placeholder="{__('Повторить пароль')}">
				</div>
			</div>
			{form_error('password_confirm', '<div class="help-inline error">', '</div>')}

			<button type="submit" name="submit" data-ma-action="nk-login-switch" data-ma-block="#l-login" class="btn btn-login btn-success btn-float"><i class="notika-icon notika-right-arrow"></i></button>
		</div>

		<div class="nk-navigation rg-ic-stl">
			<a href="{site_url('login')}" data-ma-action="nk-login-switch" data-ma-block="#l-login"><i class="notika-icon notika-right-arrow"></i> <span>{__('Войти')}</span></a>
			<a href="{site_url('recover')}" data-ma-action="nk-login-switch" data-ma-block="#l-forget-password"><i>?</i> <span>{__('Забыл пароль')}</span></a>
		</div>
	</div>
</div>
{form_close()}
