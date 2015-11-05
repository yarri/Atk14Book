{render partial="base_book/breadcrumbs"}

<div class="col-md-9" role="main">
	{$page_content nofilter}
	{render partial="base_book/subchapters"}
</div>

<div class="col-md-3" role="complementary">

	<h4>Obsah</h4>
	<ul class="nav nav-pills nav-stacked">
		{!$navigation}
	</ul>

</div>


