Deferred Twig Extension
=======================
[![Build Status](https://travis-ci.org/rybakit/twig-extensions-deferred.svg?branch=master)](https://travis-ci.org/rybakit/twig-extensions-deferred)

An extension for Twig that allows to defer block rendering.

## Installation

The recommended way to install the extension is through [Composer](http://getcomposer.org):

```sh
$ composer require phive/twig-extensions-deferred:dev-master
```


## Initialization

```php
$twig = new Twig_Environment($loader);
$twig->addExtension(new Phive\Twig\Extensions\Deferred());
```


## Usage example

Let's assume that we have the following set of templates:

*layout.html.twig*
```jinja
<!DOCTYPE html>
<html>
    <head>
        ...
        {{ storage.append('/js/layout-header.js') }}

        {% deferred javascripts %}
            {% for item in storage %}
                <script src="{{ item }}"></script>
            {% endfor %}
        {% enddeferred %}
    </head>
    <body>
        {% block content '' %}
        {{ storage.append('/js/layout-footer.js') }}
    </body>
</html>
```
<br>
*page.html.twig*
```jinja
{% extends "layout.html.twig" %}

{% block content %}
    {{ storage.append('/js/page-header.js') }}

    {% if foo is not defined %}
        {{ include("subpage1.html.twig") }}
    {% else %}
        {{ include("subpage2.html.twig") }}
    {% endif %}

    {{ storage.append('/js/page-footer.js') }}
{% endblock %}
```
<br>
*subpage1.html.twig*
```jinja
{{ storage.append('/js/subpage1.js') }}
```
<br>
*subpage2.html.twig*
```jinja
{{ storage.append('/js/subpage2.js') }}
```
<br>

> The `storage` is a [global twig variable](http://twig.sensiolabs.org/doc/advanced.html#globals)
> which can be created like this:
>
>     $twig = new Twig_Environment($loader);
>     $twig->addGlobal('storage', new ArrayObject());
>
> It's not provided by this extension and it's there just to show the order in which data are added.<br>
> It's up to you how to share data between templates.

<br>

Then the output will be:

```html
<!DOCTYPE html>
<html>
    <head>
        ...
        <script src="/js/layout-header.js"></script>
        <script src="/js/page-header.js"></script>
        <script src="/js/subpage1.js"></script>
        <script src="/js/page-footer.js"></script>
        <script src="/js/layout-footer.js"></script>
    </head>
    <body>
    </body>
</html>
```


## License

Deferred Twig Extension is released under the MIT License. See the bundled LICENSE file for details.
