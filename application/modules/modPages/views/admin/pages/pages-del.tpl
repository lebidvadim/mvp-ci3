{form_open()}
<div class="alert alert-danger" role="alert">
	<h4 class="alert-heading">{__('Удалить страницу <b>[name]</b>!',[$page->title])}</h4>
	<p>{__('Вы уверены что хотите удалить страницу <b>[name]</b>.',[$page->title])}</p>
	<hr>
	<a href="{site_url('admin/pages')}" class="btn btn-success">{__('Нет')}</a>
	<button type="submit" name="del" value="1" class="btn btn-danger">{__('Да')}</button>
</div>
{form_close()}
