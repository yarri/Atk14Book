<?php
class TcDrinkMarkdown extends TcBase{

	function test(){
		$dm = new DrinkMarkdown();
		
		$this->assertEquals('<p>Hello World!</p>',$dm->transform('Hello World!'));

		// Links
		
		$this->assertEquals('<p>Welcome at <a href="http://www.earth.net">www.earth.net</a>!</p>',$dm->transform('Welcome at www.earth.net!'));

		$this->assertEquals('<p>Contact as on <a href="http://www.earth.net">www.earth.net</a><br />
or <a href="mailto:we@earth.net">we@earth.net</a></p>',$dm->transform("Contact as on www.earth.net  \nor we@earth.net"));

		// Text centering

		$this->assertEquals('<h1><center>Title</center></h1>',$dm->transform('# <center>Title</center>'));

		$this->assertEquals("<center>\n\n<p>Centered text block</p>\n\n</center>",$dm->transform("<center>\n\nCentered text block\n\n</center>"));

		// HTML table

		$src = '
Paragraph #1

<table>
  <tr>
    <th>key</th>
    <td>val</td>
  </tr>
</table>

Paragraph #2';
		$result = trim('
<p>Paragraph #1</p>

<table class="table table-bordered table-hover">
  <tr>
    <th>key</th>
    <td>val</td>
  </tr>
</table>

<p>Paragraph #2</p>');
		$this->assertEquals($result,$dm->transform($src));

		// Code

		$src = '
Paragraph #1

```
function helloWorld(){
  alert("Hello World!");
}
```

Paragraph #2
';
		$result = trim('
<p>Paragraph #1</p>

<pre><code>function helloWorld(){
  alert(&quot;Hello World!&quot;);
}</code></pre>

<p>Paragraph #2</p>');
		$this->assertEquals($result,$dm->transform($src));


		// Code with highlighted syntax

		$src = '
Paragraph #1

```javascript
function helloWorld(){
  alert("Hello World!");
}
```

Paragraph #2
';

		$result = trim('
<p>Paragraph #1</p>

<pre><span style="color: #000066; font-weight: bold;">function</span> helloWorld<span style="color: #009900;">&#40;</span><span style="color: #009900;">&#41;</span><span style="color: #009900;">&#123;</span>
  alert<span style="color: #009900;">&#40;</span><span style="color: #3366CC;">&quot;Hello World!&quot;</span><span style="color: #009900;">&#41;</span><span style="color: #339933;">;</span>
<span style="color: #009900;">&#125;</span></pre>

<p>Paragraph #2</p>');

		$this->assertEquals($result,$dm->transform($src));
	}
}
