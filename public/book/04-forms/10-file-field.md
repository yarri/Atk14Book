Nahrávání souborů: FileField
============================

[include file=app/forms/fields/file_field_form.php]

Volání metody *enable\_file\_upload()* je klíčový moment. Bez něj se upload souborů nepodaří :)

V šabloně a ve validaci pak není žádný rozdíl oproti kterémukoli jinému formuláři. Zvalidovana hodnota políčka *FileField* je objekt třídy *HTTPUploadedFile* (popis třídy najdete adrese <http://api.atk14.net/Atk14/InternalLibraries/HTTPUploadedFile.html>)

Formulář z příkladu běží na adrese <http://www.atk14.net/en/fields/file_field/>
