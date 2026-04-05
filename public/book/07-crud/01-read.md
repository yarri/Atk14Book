Zobrazení seznamu
=================

Uvažujte celkem představitelnou situaci, kdy je třeba na nějakém URL zobrazit seznam knížek
a nabídnou všechny možné související operace - tj. především zobrazení podrobného detailu knihy, založení nového záznamu,
editaci již hotového záznamu a smazání záznamu.

Předpokládejme jednoduchou tabulku pro ukládání knih

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

Předpokládejme kontroler s názvem _BooksController_.

Koneckonců podívejte se na adresu <http://www.atk14.net/en/books/>, tam to všechno uvidíte.

Seznam
------

Seznam umístíme do akce _index_.

Nejmenší možná varianta seznamu by mohla vypadat takto. Nenajdete v ní vyhledávání ani třídění podle sloupců.

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

Běžně je však vyhledávání i třídění podle sloupců potřeba.

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

Ve formuláři máme jedno políčko pro vyhledávání. Jednoduché jako facka.

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

Vykresneí zajišťuje šablona index.tpl s jednou parciální šablonkou, ...

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

... která vykresluje jeden řádek v tabulce.

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

V šabloně _index.tpl_ je použit helper _paginator_, který zajišťuje stránkování nalezených knih. Všechny potřebné údaje si paginator zjistí z proměnné šablonky _$finder_.

Při stránkování záznamů paginátor zachovává v URL všechny parametry a přidává parametr _offset_ (viz kontroler).
