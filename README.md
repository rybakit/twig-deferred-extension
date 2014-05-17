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
$twig->addExtension(new Phive\Twig\Extensions\Deferred\DeferredExtension());
```

## "Hello world" Example

For example purposes, let's create a [global twig variable](http://twig.sensiolabs.org/doc/advanced.html#globals):

```php
$twig = new Twig_Environment($loader);
$twig->addGlobal('data', new ArrayObject());
```

Then assume that we have the following template:

```jinja
{{ data.append('Hello') }}

{% block foo %}
    {{ data|join(' ') }}
{% endblock %}

{{ data.append('world!') }}
```

The output of this template will be `Hello`, which is predictable because Twig tags are processed consecutively, one by one.

Now, let's mark the `foo` block with `deferred` keyword to inform Twig engine to defer the rendering of this block.
It has to be placed just after the block name:

```jinja
{% block foo deferred %}
```

And here it is, we get desired `Hello world!` output.


## Advanced Example

Consider the following set of templates:

```jinja
{# layout.html.twig #}
<!DOCTYPE html>
<html>
    <head>
        ...
        {{ data.append('/js/layout-header.js') }}

        {% block javascripts deferred %}
            {% for item in data %}
                <script src="{{ item }}"></script>
            {% endfor %}
        {% endblock %}
    </head>
    <body>
        {% block content '' %}
        {{ data.append('/js/layout-footer.js') }}
    </body>
</html>


{# page.html.twig #}
{% extends "layout.html.twig" %}

{% block content %}
    {{ data.append('/js/page-header.js') }}

    {% if foo is not defined %}
        {{ include("subpage1.html.twig") }}
    {% else %}
        {{ include("subpage2.html.twig") }}
    {% endif %}

    {{ data.append('/js/page-footer.js') }}
{% endblock %}


{# subpage1.html.twig #}
{{ data.append('/js/subpage1.js') }}


{# subpage2.html.twig #}
{{ data.append('/js/subpage2.js') }}
```

The resulting html will be the following:

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


## Block overriding

```jinja
{# index.twig #}
{% extends "base.twig" %}
{% block foo %}foo is not deferred anymore{% endblock %}
{% block bar deferred %}bar is deferred now{% endblock %}

{# base.twig #}
{% block foo deferred %}foo is deferred{% endblock %}
{% block bar %}bar is not deferred{% endblock %}
```


## License

Deferred Twig Extension is released under the MIT License. See the bundled LICENSE file for details.
