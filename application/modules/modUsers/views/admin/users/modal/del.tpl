<div class="modal-body">
	<h2>{__('Удалить пользователя <b>[name]</b>',[$user->username])}</h2>
	<p>{__('Вы уверены что хотите удалить пользователя.')}</p>
</div>
<div class="modal-footer">
	{form_open()}
	<button type="submit" name="del" value="1" class="btn btn-danger waves-effect">{__('Удалить')}</button>
	<button type="button" class="btn waves-effect" data-dismiss="modal">{__('Нет')}</button>
	{form_close()}
</div>
