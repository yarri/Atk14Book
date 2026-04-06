File upload: FileField
======================

```php
<?php
// file: app/forms/fields/file_field_form.php
class FileFieldForm extends ApplicationForm{
  function set_up(){
    $this->add_field("file",new FileField(array(
      // "required" => true
    )));
  }
}
```

Calling the *enable\_file\_upload()* method is the key step. Without it, file uploads will not work :)

In the template and during validation there is no difference from any other form. The validated value of a *FileField* field is an instance of *HTTPUploadedFile* (see the class description at <http://api.atk14.net/Atk14/InternalLibraries/HTTPUploadedFile.html>).

The example form is running at <http://www.atk14.net/en/fields/file_field/>
