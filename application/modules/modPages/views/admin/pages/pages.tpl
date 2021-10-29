<div class="card">
	{if !empty($menu['submenu'])}
	<div class="card-header">
		<ul class="nav nav-tabs card-header-tabs">
			{foreach $menu['submenu'] as $submenu}
				<li class="nav-item">
					<a class="nav-link{if $active == $submenu['active'] or (is_array($submenu['active']) and in_array($active,$submenu['active']))} active{/if}" href="{$submenu['url']}">{$submenu['title']}</a>
				</li>
			{/foreach}
		</ul>
	</div>
	{/if}
	<div class="card-body">
		{$content}
	</div>
</div>
