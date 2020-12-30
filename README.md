Twig Deferred Extension
=======================

[![Quality Assurance](https://github.com/rybakit/twig-deferred-extension/workflows/QA/badge.svg)](https://github.com/rybakit/twig-deferred-extension/actions?query=workflow%3AQA)
[![Scrutinizer Code Quality](https://scrutinizer-ci.com/g/rybakit/twig-deferred-extension/badges/quality-score.png?b=master)](https://scrutinizer-ci.com/g/rybakit/twig-deferred-extension/?branch=master)
[![Mentioned in Awesome Twig](https://awesome.re/mentioned-badge.svg)](https://github.com/JulienRAVIA/awesome-twig#extensions)

An extension for [Twig](https://twig.symfony.com/) that allows to defer block rendering.


## Installation

```bash
composer require rybakit/twig-deferred-extension
```

> *Note that this extension requires Twig 3 or above. If you need support for older versions of Twig,
> please refer to the [legacy repository](https://github.com/rybakit/twig-extensions-deferred-legacy).* 


## Initialization

```php
use Twig\DeferredExtension\DeferredExtension;
use Twig\Environment;

...

$twig = new Environment($loader);
$twig->addExtension(new DeferredExtension());
```

## Simple example

```jinja
{% block foo deferred %}
    {{ bar }}
{% endblock %}

{% set bar = 'bar' %}
```

The `foo` block will output "bar".


## Advanced example

Just for example purposes, first create a [global twig variable](http://twig.sensiolabs.org/doc/advanced.html#globals):

```php
use Twig\Environment;

...

$twig = new Environment($loader);
$twig->addGlobal('assets', new \ArrayObject());
```

Then build the following set of templates:

```jinja
{# layout.html.twig #}
<!DOCTYPE html>
<html>
    <head>
        ...
    </head>
    <body>
        {% block content '' %}

        {{ assets.append('/js/layout-header.js') }}

        {% block javascripts deferred %}
            {% for asset in assets %}
                <script src="{{ asset }}"></script>
            {% endfor %}
        {% endblock %}

        {{ assets.append('/js/layout-footer.js') }}
    </body>
</html>


{# page.html.twig #}
{% extends "layout.html.twig" %}

{% block content %}
    {{ assets.append('/js/page-header.js') }}

    {% if foo is defined %}
        {{ include("subpage1.html.twig") }}
    {% else %}
        {{ include("subpage2.html.twig") }}
    {% endif %}

    {{ assets.append('/js/page-footer.js') }}
{% endblock %}


{# subpage1.html.twig #}
{{ assets.append('/js/subpage1.js') }}


{# subpage2.html.twig #}
{{ assets.append('/js/subpage2.js') }}
```

The resulting html will be the following:

```html
<!DOCTYPE html>
<html>
    <head>
        ...
    </head>
    <body>
        <script src="/js/layout-header.js"></script>
        <script src="/js/page-header.js"></script>
        <script src="/js/subpage2.js"></script>
        <script src="/js/page-footer.js"></script>
        <script src="/js/layout-footer.js"></script>
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

The library is released under the MIT License. See the bundled [LICENSE](LICENSE) file for details.
