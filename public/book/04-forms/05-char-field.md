Textové políčko: CharField
==========================

Pravděpodobně nejvíce používané políčko je pro zadávání textové hodnoty. Podívejte se, jak se takové políčko vkládá do formuláře.

```php
<?php
// file: app/forms/fields/char_field_form.php
class CharFieldForm extends ApplicationForm{
  function set_up(){
    $this->set_method("get");

    $this->add_field("value",new CharField(array(
      "label" => "A character value",
      "help_text" => "Write here a character value",
      "hint" => "Lorem Ipsum",
      "min_length" => 2, // def. null
      "max_length" => 20, // def. null

      // "widget" => new TextInput(),
      // "initial" => "",
      // "null_empty_output" => false,
      // "required" => true,
      // "trim_value" => true,
      // "disabled" => false,
    )));
  }
}
```

Příklad si vyzkoušíte na adrese <http://www.atk14.net/en/fields/char_field/>
