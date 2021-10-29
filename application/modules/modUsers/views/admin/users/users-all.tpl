<div class="row">
	<div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
		{if count($users) > 0}
			<div class="normal-table-list">
				<div class="bsc-tbl">

					<table class="table table-bordered table-sc-ex">
						<thead>
						<tr>
							<th>{__('ID')}</th>
							<th>{__('Логин')}</th>
							<th>{__('Группа')}</th>
							<th>{__('Эмаил')}</th>
							<th width="115"></th>
						</tr>
						</thead>
						<tbody>
						{foreach $users as $us}
							<tr>
								<td>{$us['id']}</td>
								<td>{$us['username']}</td>
								<td>{$us['groups_description']}</td>
								<td>{$us['email']}</td>
								<td>
									{if $user->user_id != $us['id']}
									<a href="{site_url('admin/users/edit/')}{$us['id']}" class="btn btn-primary notika-btn-primary waves-effect"><i class="fas fa-edit"></i></a>
									<a href="{site_url('modUsers/ajax/del/')}{$us['id']}" class="btn btn-danger notika-btn-danger waves-effect" data-toggle="modal" data-target="#myModalnine{$us['id']}"><i class="fas fa-trash"></i></a>
									{else}
										-
									{/if}
								</td>
							</tr>
						{/foreach}
						</tbody>
					</table>
				</div>
			</div>
		{else}
			<div class="alert alert-warning">{__('Пользователей нет.')}</div>
		{/if}
	</div>

	{if $pagination}
		<div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
			{$pagination}
		</div>
	{/if}
</div>
{foreach $users as $us}
	<div class="modal fade" id="myModalnine{$us['id']}" tabindex="-1" role="dialog" style="display: none;">
		<div class="modal-dialog modal-sm">
			<div class="modal-content"></div>
		</div>
	</div>
{/foreach}

