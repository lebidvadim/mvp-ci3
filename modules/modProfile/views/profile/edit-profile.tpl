<div class="row">
	<div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
		<div class="row">
			<div class="col-lg-6 col-md-6 col-sm-12 col-xs-12">
				<div class="form-element-list">
					<div class="cmp-tb-hd">
						<h2>{__('Редактировать данные профиля')}</h2>
					</div>
					{form_open()}
					<div class="row">
						<input type="hidden" name="type_form" value="0">
						<div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
							<div class="form-group">
								<div class="nk-int-st">
									<label for="">{__('Имя')}</label>
									<input type="text" name="first_name" class="form-control{if form_error('first_name')} error{/if}" placeholder="{__('Имя')}" value="{if set_value('first_name')}{set_value('first_name')}{else}{$user->first_name}{/if}">
									{form_error('first_name', '<div class="help-inline error">', '</div>')}
								</div>
							</div>
						</div>

						<div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
							<div class="form-group">
								<div class="nk-int-st">
									<label for="">{__('Фамилия')}</label>
									<input type="text" name="last_name" class="form-control{if form_error('last_name')} error{/if}" placeholder="{__('Фамилия')}" value="{if set_value('last_name')}{set_value('last_name')}{else}{$user->last_name}{/if}">
									{form_error('last_name', '<div class="help-inline error">', '</div>')}
								</div>
							</div>
						</div>

						<div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
							<div class="form-group">
								<div class="nk-int-st">
									<label for="">{__('Компания')}</label>
									<input type="text" name="company" class="form-control{if form_error('company')} error{/if}" placeholder="{__('Компания')}" value="{if set_value('company')}{set_value('company')}{else}{$user->company}{/if}">
									{form_error('company', '<div class="help-inline error">', '</div>')}
								</div>
							</div>
						</div>

						<div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
							<div class="form-group">
								<div class="nk-int-st">
									<label for="">{__('Телефон')}</label>
									<input type="text" name="phone" class="form-control{if form_error('phone')} error{/if}" placeholder="{__('Телефон')}" value="{if set_value('phone')}{set_value('phone')}{else}{$user->phone}{/if}">
									{form_error('phone', '<div class="help-inline error">', '</div>')}
								</div>
							</div>
						</div>

						<div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
							<button class="btn btn-success notika-btn-success waves-effect" style="margin-top: 10px" type="submit">{__('Сохранить')}</button>
						</div>
					</div>
					{form_close()}
				</div>
			</div>
			<div class="col-lg-6 col-md-6 col-sm-12 col-xs-12">
				<div class="form-element-list">
					<div class="cmp-tb-hd">
						<h2>{__('Изменить пароль')}</h2>
					</div>
					{form_open()}
					<div class="row">
						<input type="hidden" name="type_form" value="1">
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
							<button class="btn btn-success notika-btn-success waves-effect" style="margin-top: 10px" type="submit">{__('Изменить')}</button>
						</div>
					</div>
					{form_close()}
				</div>
			</div>
		</div>
	</div>


</div>

