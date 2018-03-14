# StaTwig
[![Build Status](https://travis-ci.org/statwig/statwig.svg?branch=master)](https://travis-ci.org/statwig/statwig)
[![License: MIT](https://img.shields.io/badge/License-MIT-yellow.svg)](https://opensource.org/licenses/MIT)
[![Latest Stable Version](https://poser.pugx.org/statwig/statwig/v/stable)](https://packagist.org/packages/statwig/statwig)

StaTwig is a PHP console tool to compile Twig templates into HTML views.
You can also chech [the demo app](https://github.com/statwig/demo).

It will take every file with `.html.twig` extension **FROM THE ROOT** of the templates directory (excluding subdirectories) and compile them into output directory with `.html` extension.

### Installation

You can get StaTwig via composer:

a) from your project:

``` 
composer require --dev statwig/statwig
``` 

b) globally on your system:

```
composer global require statwig/statwig
```

### Usage

By default, StaTwig expects to directories to be present- `/templates` with your templates and `/output` for compiled html.
To run Statwig from your project use:

``` 
vendor/bin/statwig
```

or if you've installed it globally just:

```
statwig
```

You can use all Twig functionality like including files, extending layouts etc. Just put every file to be included/extended into subdirectory to be omitted by the compiler.

Given you have the following directory structure:

``` 
templates/
-- layouts/
---- base.html.twig
-- file.html.twig
output/
vendor/
composer.json
```

When you run the tool, you will get a new file:

``` 
templates/
-- layouts/
---- base.html.twig
-- file.html.twig
output/
-- file.html
vendor/
composer.json
```

Given the base layout looks like this:

```twig
<div>{% block body %}{% endblock %}</div>
```

and `file.html.twig`:

```twig
{% extends 'layouts/base.html.twig' %}

{% block body %}Hello world{% endblock %}
```

the output of the `output/file.html` will be:

```html
<div>Hello world</div>
```

### Customization

You can override default paths by providing arguments: first one is the templates path and second output folder:

```
vendor/bin/statwig my-templates-directory my-output-directory
```