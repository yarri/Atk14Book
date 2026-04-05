Nahrávání obrázků: ImageField
=============================

```php
<?php
// file: app/forms/fields/image_field_form.php
class ImageFieldForm extends ApplicationForm{
  function set_up(){
    $this->add_field("image",new ImageField(array(
      "label" => "Image",
      "help_text" => "Expecting jpeg, png or gif file",

      // "width" => null,
      // "height" => null,
      // "max_width" => null,
      // "max_height" => null,
      // "min_width" => null,
      // "min_height" => null,

      // "required" => true,
      // "hint" => "",
    )));
  }
}
```

*ImageField* je dědic třídy *FileField*, který vyžaduje, aby byl nahraný soubor obrázek.

Upload obrázku si vyzkoušejte na adrese <http://www.atk14.net/en/fields/image_field/>
