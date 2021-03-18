<p align="center">
    <a href="https://packagist.org/packages/devlop/fontawesome-blade"><img src="https://img.shields.io/packagist/v/devlop/fontawesome-blade" alt="Latest Stable Version"></a>
    <a href="https://github.com/devlop/fontawesome-blade/blob/master/LICENSE.md"><img src="https://img.shields.io/packagist/l/devlop/fontawesome-blade" alt="License"></a>
</p>

# Font Awesome Blade

Laravel [Blade Components](https://laravel.com/docs/8.x/blade#components) to make it dead simple to use Font Awesome SVG's in Laravel.

# Prerequisites

This package assumes that you are using [Font Awesome via a package manager](https://fontawesome.com/how-to-use/on-the-web/setup/using-package-managers).

# Installation

```
composer require devlop/fontawesome-blade
```

# Configuration

By default this package will assume you are using the Pro versions, if you are using the free versions you can change this in the configuration.

```
php artisan vendor:publish --provider="Devlop\FontAwesome\FontAwesomeBladeServiceProvider"
```

# Usage

Each of Font Awesome's different styles are available as separate components, they all accept the same `name` argument for specifying which icon to display.

```html
<x-fa.brands name="fa-laravel" />

<x-fa.solid name="fa-0" />

<x-fa.regular name="fa-2" />

<x-fa.light name="fa-5" />

<x-fa.thin name="fa-0" />

<x-fa.duotone name="fa-elephant" />
```

You also need to import the Font Awesome styles into your SASS to get the icons to display identical to using the JS method.

```scss
@import '@fortawesome/fontawesome-pro/css/svg-with-js.css';
```
