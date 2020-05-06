<div class="col-md-9" role="main">
	{render partial="base_book/breadcrumbs"}
	{$page_content nofilter}
	{render partial="base_book/subchapters"}
</div>

<div class="col-md-3" role="complementary">
	<div> {* Acts like .container in SM and XS, see styles *}
		<h3>{t}Obsah{/t}</h3>
		<ul class="nav nav-pills nav-stacked">
			{foreach $navigation->getItems() as $item}
				<li{if $item->isActive()} class="active"{/if}><a href="{$item->getUrl()}">{!$item->getTitle()}</a></li>
			{/foreach}
		</ul>
	</div>
</div>
