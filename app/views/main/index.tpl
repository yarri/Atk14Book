<div class="md_book_body">
	<div class="row">

		<div class="col col-md-9 md_book_content" role="main">
			{$book->getContent() nofilter}
		</div>

		<div class="col col-md-3 md_book_sidebar" role="complementary">
			{render partial="md_book_base/sidebar/table_of_contents"}
		</div>

	</div>
</div>
