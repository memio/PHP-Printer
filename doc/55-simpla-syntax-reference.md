# Simpla Syntax Reference

With Simpla, templates are simple strings optionally containing placeholders:

```
Hello %name%!
```

## Placeholders

Placeholders are delimited by an opening and a closing `%` (e.g. `%name%`).

Those placeholders will be replaced by the value associated to the parameter with
the same name:

```php
// Displays "Hello world!"
echo $templatingEngine->render('hello_world', [
    'name' => 'world',
]);
```
