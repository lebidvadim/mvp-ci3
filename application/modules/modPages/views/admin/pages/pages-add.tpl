{form_open()}
	<div class="form-group">
		<input type="text" value="{set_value('title')}" name="title" class="form-control{if form_error('title')} is-invalid{/if}" placeholder="{__('Заголовок')}">
		{form_error('title', '<div class="invalid-feedback">', '</div>')}
	</div>
	<div class="form-group">
		<input type="text" value="{set_value('description')}" name="description" class="form-control{if form_error('description')} is-invalid{/if}" placeholder="{__('Описание')}">
		{form_error('description', '<div class="invalid-feedback">', '</div>')}
	</div>
	<div class="form-group">
		<textarea name="text" class="form-control{if form_error('text')} is-invalid{/if}" placeholder="{__('Текст страницы')}">{set_value('text')}</textarea>
		{form_error('text', '<div class="invalid-feedback">', '</div>')}
	</div>
	<button type="submit" class="btn btn-success">{__('Добавить')}</button>
{form_close()}
