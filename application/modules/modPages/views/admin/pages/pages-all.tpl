<div class="row">
	<div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
		{if count($pages) > 0}
			<div class="normal-table-list">
				<div class="bsc-tbl">

					<table class="table table-bordered table-sc-ex">
						<thead>
						<tr>
							<th>{__('Заголовок')}</th>
							<th>{__('Ссылка')}</th>
							<th>{__('Дата создания')}</th>
							<th width="200"></th>
						</tr>
						</thead>
						<tbody>
						{foreach $pages as $page}
							<tr>
								<td><a href="{site_url('admin/pages/edit/')}{$page['id']}">{$page['title']}</a></td>
								{if $page['home'] == 1}
									<td><a href="{site_url()}" target="_blank">/</a></td>
								{else}
									<td><a href="{site_url($page['name'])}" target="_blank">/{$page['name']}</a></td>
								{/if}
								<td>{$page['date_creat']|date_format:"%d-%m-%Y %H:%I"}</td>
								<td>
									<div class="d-flex">
										{form_open(site_url("admin/pages/view/{$page['id']}"))}
										<button type="submit" title="{if $page['status'] == 1}{__('Скрыть')}{else}{__('Показать')}{/if}" class="btn btn-sm {if $page['status'] == 1}btn-light{else}btn-dark{/if}">{if $page['status'] == 1}<i class="far fa-eye-slash"></i>{else}<i class="far fa-eye"></i>{/if}</button>
										{form_close()}
										<a href="{site_url('admin/pages/edit/')}{$page['id']}" title="{__('Редактировать')}" class="btn btn-sm btn-primary ml-2"><i class="fas fa-pen"></i></a>
										<a href="{site_url('admin/pages/del/')}{$page['id']}" title="{__('Удалить')}" class="btn btn-sm btn-danger ml-2"><i class="fas fa-trash"></i></a>
									</div>
								</td>
							</tr>
						{/foreach}
						</tbody>
					</table>
				</div>
			</div>
		{else}
			<div class="alert alert-warning">{__('Страниц нет.')}</div>
		{/if}
	</div>

	{if $pagination}
		<div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
			{$pagination}
		</div>
	{/if}
</div>

<div class="modal fade" id="myModalnineDel" tabindex="-1" role="dialog" style="display: none;">
	<div class="modal-dialog modal-large">
		<div class="modal-content"></div>
	</div>
</div>

