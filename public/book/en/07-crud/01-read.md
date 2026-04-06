Displaying a list
=================

Consider a fairly typical situation where you need to display a list of books at a given URL
and offer all the related operations — primarily showing a book's detail, creating a new record,
editing an existing record, and deleting a record.

Let's assume a simple table for storing books:

```sql
-- file: db/migrations/0002_books.sql
CREATE SEQUENCE seq_books;
CREATE TABLE books(
  id INT PRIMARY KEY DEFAULT NEXTVAL('seq_books'),
  title VARCHAR(255),
  code VARCHAR(255),
  shelfmark VARCHAR(255),
  author VARCHAR(255),
  year_of_publication INT
);
```

And a controller named _BooksController_.

You can see it all in action at <http://www.atk14.net/en/books/>.

List
----

The list goes in the _index_ action.

The simplest possible version of a list might look like this — no searching or column sorting.

	<?php
	// file: app/controllers/books_controller.php
	class BooksController extends ApplicationController{

		/**
		 * Provides the list of books.
		 */
		function index(){
			$this->page_title = "Listing books";

			$this->tpl_data["finder"] = Book::Finder(array(
				"order" => "UPPER(title)",
				"limit" => 10,
				"offset" => $this->params->getInt("offset"),
			));
		}

		// ... other actions...
	}

In practice, however, searching and column sorting are usually needed.

	<?php
	// file: app/controllers/books_controller.php
	class BooksController extends ApplicationController{

		/**
		 * Provides the list of books.
		 */
		function index(){
			$this->page_title = "Listing books";

			// initialize sorting
			$this->sorting->add("title",array("order_by" => "UPPER(title)"));
			$this->sorting->add("author",array(
				"ascending_ordering" => "UPPER(author), UPPER(title)",
				"descending_ordering" => "UPPER(author) DESC, UPPER(title) DESC"
			));
			$this->sorting->add("code");

			// validate input parameters
			if(!($d = $this->form->validate($this->params))){
				return;
			}

			// build conditions
			$conditions = array();
			$bind_ar = array();
			if($d["search"]){
				$conditions[] = "UPPER(title||author||code||shelfmark) LIKE UPPER(:search)";
				$bind_ar[":search"] = "%$d[search]%";
			}

			$this->tpl_data["finder"] = Book::Finder(array(
				"conditions" => $conditions,
				"bind_ar" => $bind_ar,
				"order" => $this->sorting->getOrder(),
				"limit" => 10,
				"offset" => $this->params->getInt("offset"),
			));
		}

		// ... other actions...
	}

The form has a single search field — simple as can be.

```php
<?php
// file: app/forms/books/index_form.php
class IndexForm extends ApplicationForm{
  function set_up(){
    $this->set_method("get");
    $this->add_field("search",new CharField(array(
      "required" => false,
      "max_length" => 100,
    )));
  }
}
```

Rendering is handled by the `index.tpl` template with one partial template...

```smarty
{* file: app/views/books/index.tpl *}
<h1 class="page-header">
  {$page_title}
  {a action=create_new _class="btn btn-primary pull-right"}Create new book entry{/a}
</h1>

{form _class="form-inline"}
  {render partial="shared/form_field" field=search}
  <div class="form-group">
    <button type="submit" class="btn btn-default">Search</button>
  </div>
{/form}

<hr>


{if $finder}
  {if $finder->isEmpty()}
    <p>Your search did not match any books.</p>
  {else}
    <table class="table">
      <thead>
        <tr>
          {sortable key=title}<th>Title</th>{/sortable}
          {sortable key=author}<th>Author</th>{/sortable}
          {sortable key=code}<th>Code</th>{/sortable}
          <th>Shelfmark</th>
          <th></th>
        </tr>
      </thead>
      <tbody>
        {render partial=book_item from=$finder->getRecords() item=book}
      </tbody>
    </table>

    {paginator}
  {/if}
{/if}
```

...which renders a single table row.

```smarty
{* file: app/views/books/_book_item.tpl *}
<tr>
  <td>{a action=detail id=$book}{$book->getTitle()}{/a}</td>
  <td>{$book->getAuthor()}</td>
  <td>{$book->getCode()}</td>
  <td>{$book->getShelfmark()}</td>
  <td>
    {a action=edit id=$book}Edit{/a} |
    {a_destroy id=$book}Destroy{/a_destroy}
  </td>
</tr>
```

The _paginator_ helper used in `index.tpl` handles pagination of the found books. It retrieves all the necessary information from the `$finder` template variable.

When paginating records, the paginator preserves all URL parameters and adds the _offset_ parameter (see the controller).
