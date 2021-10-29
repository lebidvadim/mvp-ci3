<div class="row">
	<div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
		<div class="form-element-list">
			{form_open()}
			<div class="row">
				<div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
					<div class="form-group">
						<div class="nk-int-st">
							<label for="">{__('Заголовок сайта')}</label>
							<input type="text" name="title" class="form-control{if form_error('title')} error{/if}" placeholder="{__('Заголовок сайта')}" value="{$setting->title}">
							{form_error('title', '<div class="help-inline error">', '</div>')}
						</div>
					</div>
				</div>
				<div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
					<div class="form-group">
						<div class="nk-int-st">
							<label for="">{__('Описание проекта')}</label>
							<textarea class="form-control{if form_error('desc')} error{/if}" name="desc" rows="5" placeholder="{__('Описание проекта')}">{$setting->desc}</textarea>
							{form_error('desc', '<div class="help-inline error">', '</div>')}
						</div>
					</div>
				</div>
				<div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
					<div class="toggle-select-act fm-cmp-mg">
						<div class="nk-toggle-switch" data-ts-color="green">
							<input id="ts2" type="checkbox" name="status" hidden="hidden" value="{$setting->status}"{if $setting->status == 1} checked{/if}>
							<label for="ts2" class="ts-helper"></label>
							<label for="ts2" class="ts-label" style="margin-left: 10px">{__('Включить ли сайт?')}</label>
						</div>
					</div>
				</div>
				<div class="col-lg-12 col-md-12 col-sm-12 col-xs-12 mg-t-15">
					<button type="submit" class="btn btn-success">Сохранить</button>
				</div>
			</div>
			{form_close()}
		</div>
	</div>
</div>
