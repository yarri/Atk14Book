Image upload: ImageField
========================

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

*ImageField* is a subclass of *FileField* that requires the uploaded file to be an image.

Try uploading an image at <http://www.atk14.net/en/fields/image_field/>
