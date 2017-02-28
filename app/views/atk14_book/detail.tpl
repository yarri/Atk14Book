<div class="col-md-9" role="main">
	{render partial="base_book/breadcrumbs"}
	{$page_content nofilter}
	{render partial="base_book/subchapters"}
</div>

<div class="col-md-3" role="complementary">

	<h3>{t}Obsah{/t}</h3>
	<ul class="nav nav-pills nav-stacked">
		{!$navigation}
	</ul>

</div>
