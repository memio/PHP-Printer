# Templating Engine

This documentation is dedicated to PHP Printer's Templating Engine:

* [Why a Templating Engine?](#why-a-templating-engine)
* [How to use Simpla](#how-to-use-simpla)

## Why a Templating Engine?

When it comes to coding style, everyone will have their own preferences and it's
very hard to find one that fits all.

Most Code Generator are written with their creator's coding style in mind, and
trying to change the style can be quite tricky (for example having to extend
a class and override its method).

Using a Templating Engine solves this issue: the coding style is described in
templates, in order to change the style we have to create a new template and use
it instead of the provided one.

But which Templating Engine should we use? [Twig](http://twig.sensiolabs.org/)
seems to be the most popular standalone one, and that's what Memio chose to use
in past past. Surprisingly though,
[some developers chose not to use Memio because of Twig](https://github.com/memio/memio/issues/51).
This might be because in some legacy systems another templating engine is used
([Smarty](http://www.smarty.net/) was a popular one at a time), or because some
framework use their own Templating Engine (e.g.
[Laravel with Blade](https://laravel.com/docs/5.3/blade)).

To solve this dilemna of choice, PHP Printer provides the following interface:

```php
<?php

namespace Memio\PhpPrinter;

interface TemplatingEngine
{
    /**
     * @throws TemplateNotFound
     */
    public function render(
        string $templateName,
        array $parameters = []
    ) : string;
}
```

Since it is an interface, we can create a Twig implementation, or a Smarty one,
or a Blade one, or even our own custom plain PHP one. Implementations are
required to work according to the following flow:

1. get a template using the given name
2. replace the template's placeholder by the values from the given parameters
3. return the resulting string

If no templates were found for the given name, a
`Memio\PhpPrinter\TemplateNotFound` exception should be thrown.

## How to use Simpla?

PHP Printer provides, out of the box, a plain PHP, custom made Templating Engine:
Simpla. It's by no mean intended to be a full power Templating Engine like Twig,
its purpose is to get you started quickly.

Let's imagine we have the following template file in `/tmp/hello_world.tpl`:

```
Hello %world%!
```

Then we can use Simpla as follow:

```php
<?php

require __DIR__.'/vendor/autoload.php';

use Memio\PhpPrinter\Build;

$build = new Build();
$simplaTemplateCollection = $build->simplaTemplateCollection();
$simplaTemplateCollection->add('hello_world', '/tmp/hello_world.tpl');

// Displays "Hello world!"
echo $build->simplaTemplatingEngine()->render('hello_world', [
    'name' => 'world'
]);
```

As specified by the `TemplatingEngine` interface, `SimplaTemplatingEngine` works
as follow:

1. find the template path associated to the given template name,
   using `SimplaTemplateCollection`
2. get the template content,
   using `SimplaFilesystem` (not seen in the above example)
3. replace the template's placeholders (e.g. `%name%`) by the values from the
   given parameters, using `SimplaRule` (not seen in the above example)

Let's have a closer look at those 3 components.

### SimplaTemplateCollection

Template paths can be registered in `SimplaTemplateCollection`, and can then be
found by their name:

```php
$simplaTemplateCollection->add('hello_world', '/tmp/hello_world.tpl');

// Displays "/tmp/hello_world.tpl"
echo $simplaTemplateCollection->get('hello_world');
```

If the given name hasn't been registered, a `TemplateNotFound` exception is
thrown:

```php
// Throws \Memio\PhpPrinter\TemplateNotFound
$simplaTemplateCollection->get('hello_kitty');
```

In order to be able to override an existing template, all we need to do is to
add the new path with the previously used name:

```php
$simplaTemplateCollection->add('hello_world', '/tmp/hello_world.tpl');
$simplaTemplateCollection->add('hello_world', '/dev/null');

// Displays "/dev/null"
echo $simplaTemplateCollection->get('hello_world');
```

With the above example the latest addition will be used, but if we want to have
more control then we can use the priority parameter:

```php
$simplaTemplateCollection->add('hello_world', '/tmp/hello_world.tpl', 42);
$simplaTemplateCollection->add('hello_world', '/dev/null', 23);

// Displays "/tmp/hello_world.tpl"
echo $simplaTemplateCollection->get('hello_world');
```

By default priority is set to `0`. The path with the highest priority will be
used (if two path are registered with the same name and the same priority, then
the latest addition will be used).

### SimplaFilesystem

`SimplaFilesystem` is an interface responsible to retrieve the content of a given
template. If it cannot do so (e.g. file doesn't exist, or permission error) then
a `TemplateNotFound` exception should be thrown.

When created with `Build`, `SimplaTemplatingEngine` will be using an
implementation of `SimplaFilesystem` that relies on `file_get_contents`.
If for some reason you need something else (e.g.
[Flysystem](https://flysystem.thephpleague.com/)), then you'll have to create
your own implementation (and either override `Build`, or create "manually"
`SimplaTemplatingEngine`).

### SimplaRule

The `SimpleRule` interface defines a method that accepts template and parameters,
and returns a modified template: 

```php
<?php

namespace Memio\PhpPrinter\Simpla;

interface SimplaRule
{
    public function apply(string $template, array $parameters = []) : string;
}
```

Out of the box, there are 2 implementations available:

* `ComposedSimplaRule`: aggregates `SimplaRule` and applies all of them
* `PlaceholderSimplaRule`: replaces `%placeholder%` in the given template with
  the value of the given paramaters named `placeholder`

With `ComposedSimplaRule`, `SimplaRule`s will be applied in the order they've
been added. `Build` gives you access to it, so you can register your own
`Simplarule`:

```php
$composedSimplaRule = $build->composedSimplaRule();
$composedSimplaRule->add($customSimplaRule);

// Will apply first PlaceholderSimplaRule and then your CustomSimplaRule
$simplaTemplatingEngine->render('hello_world', [
    'name' => 'world',
])
```

Just like in `SimplaTemplateCollection`, `ComposedSimplaRule` relies on
priorities to choose which `SimplaRule` will be executed first:

```php
$composedSimplaRule->add($willBeExecutedThird, 23);
$composedSimplaRule->add($willBeExecutedFirst, 42);
$composedSimplaRule->add($willBeExecutedSecond, 42);
```

By default the priority is `0`, if two `SimplaRule` have been added with the same
priority, they will be executed in the order they've been added.
