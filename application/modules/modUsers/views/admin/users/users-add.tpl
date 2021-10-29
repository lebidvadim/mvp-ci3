<div class="row">
	<div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
		<div class="form-element-list">
			{form_open()}
			<div class="row">
				<div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
					<div class="form-group">
						<div class="nk-int-st">
							<input type="text" name="identity" class="form-control{if form_error('identity')} error{/if}" placeholder="{__('Логин')}" value="{set_value('identity')}">
							{form_error('identity', '<div class="help-inline error">', '</div>')}
						</div>
					</div>
				</div>
				<div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
					<div class="form-group">
						<div class="nk-int-st">
							<input type="text" name="email" class="form-control{if form_error('email')} error{/if}" placeholder="{__('Эмаил')}" value="{set_value('email')}">
							{form_error('email', '<div class="help-inline error">', '</div>')}
						</div>
					</div>
				</div>
				<div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
					<div class="form-group">
						<div class="nk-int-st">
							<input type="password" name="password" class="form-control{if form_error('password')} error{/if}" placeholder="{__('Пароль')}">
							{form_error('password', '<div class="help-inline error">', '</div>')}
						</div>
					</div>
				</div>
				<div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
					<div class="form-group">
						<div class="nk-int-st">
							<input type="password" name="password_confirm" class="form-control{if form_error('password_confirm')} error{/if}" placeholder="{__('Повторить пароль')}">
							{form_error('password_confirm', '<div class="help-inline error">', '</div>')}
						</div>
					</div>
				</div>
				<div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
					<button class="btn btn-success notika-btn-success waves-effect" style="margin-top: 10px" type="submit">{__('Создать')}</button>
				</div>
			</div>
			{form_close()}
		</div>
	</div>
</div>

